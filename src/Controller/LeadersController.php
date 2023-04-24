<?php
namespace App\Controller;

use App\Model\Entity\Myuser;
use App\Model\Table\ShopsTable;
use App\Services\AuthService;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use Cake\Core\Configure;
use Exception;
use App\Form\UserForm;
use Cake\Datasource\ConnectionManager;

/**
 * Myusers Controller
 *
 * @property \App\Model\Table\MyusersTable $Myusers
 *
 * @method \App\Model\Entity\Myuser[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LeadersController extends AppController
{

  /**
   * @var ShopsTable
   */
  private $Shops;

  public function initialize()
  {
    parent::initialize();
    $user = $this->Auth->user();
    $this->set('User', $user);

    // テーブル呼び出し
    $this->Myusers = TableRegistry::get('Myusers');
    $this->Companies = TableRegistry::get('companies');
    $this->Shops = TableRegistry::getTableLocator()->get('Shops');
    $this->MyuserShops = TableRegistry::get('MyuserShops');
    $this->Coupons = TableRegistry::get('Coupons');
    $this->Stampcards = TableRegistry::get('Stampcards');
    $this->ChildCoupons = TableRegistry::get('ChildCoupons');
    $this->CouponShops = TableRegistry::get('CouponShops');
    $this->StampcardShops = TableRegistry::get('StampcardShops');
    $this->AffiliaterApplications = TableRegistry::getTableLocator()->get('AffiliaterApplications');
    $this->MyuserBanks = TableRegistry::getTableLocator()->get('MyuserBanks');
  }

  public function beforeFilter (Event $event)
  {
    parent::beforeFilter($event);

    $this->Auth->allow([]);
  }

  public function index()
  {
    if (!AuthService::authCheck($this, [Myuser::ROLE_OWNER])) {
      return null;
    }

    /* emptyは、オブジェクト判定はしないため、配列化する。
    * modelから呼び出し
    */
    $user = $this->Myusers->get($this->Auth->user('id'));
    $myusers = $this->Myusers->my_company_leder_list_array(Myuser::ROLE_LEADER, $user['company_id']);

    $shops = $this->Shops->shop_record_array($user['company_id']);
    $this->set(compact('myusers', 'shops'));
  }

  public function input()
  {
    if (!AuthService::authCheck($this, [Myuser::ROLE_OWNER])) {
      return null;
    }

    $authUser = $this->Myusers->get($this->Auth->user('id'));

    // ログインIDが所属している店舗レコードを抽出
    // modelから呼び出し
    $shop_list = $this->Shops->shop_record_array($authUser['company_id']);

    // Entity宣言
    $myuser_entity = $this->Myusers->newEntity();
    $myuser_shop_entity = $this->MyuserShops->newEntity();
    // カスタムバリデーション宣言
    $user = new UserForm();
    $this->set(compact('user'));

    if ($this->request->is('post')) {
      // バリデーションチェック
      $params = $this->request->getData();
      $params['company_id'] = $authUser['company_id'];
      $myuser_entity = $this->Myusers->patchEntity($myuser_entity, $params);
      $email_check = $myuser_entity->errors('email.email_unique');
      $this->set(compact('email_check'));

      if (!$params['shop_id']) {
        $shop_check = '店舗を選択してください';
        $this->set(compact('shop_check'));
      }

      if ($user->execute($params) && $myuser_entity->hasErrors() == false && !isset($shop_check)) {
        // 確認画面
        if ($this->request->getData('mode') == 'confirm') {
          // 一時保存
          $after_save_data = $this->request->getData('shop_id');
          // modelから呼び出し
          $shop_result_array = $this->Shops->add_record_array($this->request->getData('shop_id'), 'id', 'name');
          $this->set(compact('after_save_data', 'shop_result_array'));
          $this->render('confirm');
        } else {
          $connection = ConnectionManager::get('default');
          // トランザクション開始
          $connection->begin();
          try {
            if ($this->Myusers->save($myuser_entity)) {
              $shop_save_data = [];
              foreach ($this->request->getData('after_save_data') as $shop_id ) {
                $tmp_array = [];
                $tmp_array['shop_id'] = $shop_id;
                $tmp_array['myuser_id'] = $myuser_entity->id;

                array_push($shop_save_data, $tmp_array);
              }
              $myuser_shop_entity = $this->MyuserShops->patchEntities($myuser_shop_entity, $shop_save_data);
              $this->MyuserShops->saveMany($myuser_shop_entity);
              $this->Flash->success(__('リーダーユーザーを作成致しました。'));
              $connection->commit();
              return $this->redirect(['action' => 'index']);
            } else {
              $this->Flash->success(__('リーダーユーザーを作成出来ませんでした。'));
              throw new Exception(Configure::read("M.ERROR.INVALID"));
            }
          } catch(\Exception $e) {
            // ロールバック
            $connection->rollback();
          }
        }
      } else {
        $this->Flash->error(__('入力データにエラーがあります'));
      }
    }
    $this->set(compact('shop_list'));
  }

  public function edit($id)
  {
    if (!AuthService::authCheck($this, [Myuser::ROLE_OWNER])) {
      return null;
    }

    $authUser = $this->Myusers->get($this->Auth->user('id'));

    $leader = $this->Myusers->find()->where(['id' => $id])->first();
    if (!$leader) {
      return $this->redirect(['controller' => 'Myusers', 'action' => 'user_management']);
    }

    $leader = $this->Myusers->get($id, [
      'contain' => [
        'MyuserShops',
      ],
    ]);

    $myuserShops = $this->MyuserShops->my_user_list($id);
    $myuser_shops = [];

      foreach ($myuserShops as $myuserShop) {
        $myuser_shops[] = $myuserShop->shop_id;
      }

    $shop_list = $this->Shops->shop_record_array($authUser['company_id']);

    // 所属しているユーザー情報のみ
    if ($leader->company_id != $authUser['company_id']) {
      $this->redirect(['controller' => 'Myusers', 'action' => 'user_management']);
    }

    if ($this->request->is(['patch', 'post', 'put'])) {
      $connection = ConnectionManager::get('default');
      // トランザクション開始
      $myuser_entity = $this->Myusers->patchEntity($leader, $this->request->getData());
      #$edit_myuser_shop_id = $this->MyuserShops->edit_record($id);

      $connection->begin();
      // 例外処理
      try {
        if ($this->Myusers->save($myuser_entity)) {
          if (!empty($this->request->getData('shop_id'))) {
            $shop_save_data = [];
            foreach ($this->request->getData('shop_id') as $shop_id) {
              $tmp_array = [];
              #$tmp_array['id'] = array_shift($edit_myuser_shop_id);
              $tmp_array['myuser_id'] = $id;
              $tmp_array['shop_id'] = $shop_id;

              array_push($shop_save_data, $tmp_array);
            }

            // 対象ユーザのshopデータ削除
            $delete_data = [
              'myuser_id' => $leader->id
            ];

            $this->MyuserShops->deleteAll($delete_data);
            $myuser_shop_entity = $this->MyuserShops->patchEntities($myuser_shops, $shop_save_data);
            $this->MyuserShops->saveMany($myuser_shop_entity);
          }
          $connection->commit();
          $this->Flash->success(__('ユーザー情報を更新しました。'));
          return $this->redirect(['action' => 'index']);
        } else {
          $this->Flash->error(__('ユーザー情報を更新出来ませんでした。'));
          throw new Exception(Configure::read("M.ERROR.INVALID"));
        }
      } catch(\Exception $e) {
        //ロールバック
        $this->Flash->error(__('ユーザー情報を更新出来ませんでした。'));
        $connection->rollback();
      }
    }
    $companies = $this->Myusers->Companies->find('list', ['limit' => 200]);
    // オーナー非表示
    $roles = $this->Myusers->Roles->find('list', ['limit' => 200])->where(['id IN' => [2, 3]]);
    $myuser = $leader;
    $this->set(compact('myuser_shops', 'myuser', 'companies', 'roles', 'shop_list'));
  }
}
