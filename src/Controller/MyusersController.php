<?php
namespace App\Controller;

use App\Model\Entity\AffiliaterApplication;
use App\Model\Entity\Myuser;
use App\Model\Table\AffiliaterApplicationsTable;
use App\Model\Table\AffiliaterPointsTable;
use App\Model\Table\MyuserBanksTable;
use App\Services\ApplicationService;
use App\Services\AuthService;
use App\Services\CouponService;
use Cake\Database\Connection;
use Cake\ORM\TableRegistry;
use App\Controller\AppController;
use App\Model\Table\MyUsersTable;
use Cake\Event\Event;
use CakeDC\Users\Controller\Component\UsersAuthComponent;
use CakeDC\Users\Controller\Traits\LoginTrait;
use CakeDC\Users\Controller\Traits\RegisterTrait;
use CakeDC\Users\Controller\Traits\U2fTrait;
use Cake\Utility\Inflector;
use Cake\Core\Configure;
use Cake\Validation\Validator;
use Cake\I18n\Time;
use Cake\Routing\Router;
use CakeDC\Users\Exception\TokenExpiredException;
use CakeDC\Users\Exception\UserAlreadyActiveException;
use CakeDC\Users\Exception\UserNotActiveException;
use CakeDC\Users\Exception\UserNotFoundException;
use CakeDC\Users\Exception\WrongPasswordException;
use Exception;
use Cake\Datasource\EntityInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use App\Form\UserForm;
use Cake\Datasource\ConnectionManager;
use Stripe;
use App\Services\StripeService;

/**
 * Myusers Controller
 *
 * @property \App\Model\Table\MyusersTable $Myusers
 *
 * @method \App\Model\Entity\Myuser[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MyusersController extends AppController
{
  use LoginTrait;
  use RegisterTrait;
  use U2fTrait;

    /**
     * @var AffiliaterApplicationsTable
     */
    private $AffiliaterApplications;

    /**
     * @var MyuserBanksTable
     */
    private $MyuserBanks;

  public function initialize()
  {
    parent::initialize();
    $user = $this->Auth->user();
    $this->set('User', $user);

    // テーブル呼び出し
    $this->Myusers = TableRegistry::get('Myusers');
    $this->Companies = TableRegistry::get('companies');
    $this->Shops = TableRegistry::get('Shops');
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

    $this->Auth->allow(['signUp', 'signUpRegistration', 'passwordResetDone', 'privacyPolicy', 'enabledDevice']);
  }

  public function index($id = null)
  {
    $this->mypage_filter();
    $roleId = $this->Auth->user()['role_id'];

    if($roleId == Myuser::ROLE_AFFILIATER) {
        return $this->redirect(['controller' => 'Affiliaters', 'action' => 'index', $id]);
    }

    if($roleId == Myuser::ROLE_ADMIN) {
        return $this->redirect(['controller' => 'Admins', 'action' => 'index', $id]);
    }

    // myusershopsに登録されているかチェック
    $shop_record_check = $this->MyuserShops->find('all')->where(['myuser_id' => $this->Auth->User('id')])->first();
    // child_couponsのレコードチェック
    $coupon_check = 0;
    // child_stampcardsチェック
    $stampcard_check = 0;

    // オーナーは全件
    if ($this->Auth->user(['role_id']) == 1) {
      $coupon_record = $this->Coupons->my_company_coupon($this->Auth->user(['company_id']));
      $stampcard_record = $this->Stampcards->my_company_stampcard($this->Auth->user(['company_id']));

    // リーダー、メンバーは所属店舗のクーポン/スタンプ
    } else {
      if (!empty($shop_record_check)) {
        // 所属してる店舗IDを抽出
        $shops = $this->MyuserShops->find('all')->where(['myuser_id' => $this->Auth->User('id')])->extract('shop_id')->toArray();
        // couponshopsに登録されているかチェック
        $coupon_record_check = $this->CouponShops->find('all')->where(['shop_id IN' => $shops])->first();
        // stampcardshopsに登録されいているかチェック
        $stampcard_record_check = $this->StampcardShops->find('all')->where(['shop_id IN' => $shops])->first();

        if (!empty($coupon_record_check)) {
          // 店舗IDからcoupon_idを抽出
          $tmp_coupon_id = $this->CouponShops->find('all')->where(['shop_id IN' => $shops])->extract('coupon_id')->toArray();
          // 抽出したcoupon_idからレコードを抽出
          $coupon_record = $this->Coupons->find('all')->contain(['ChildCoupons', 'CouponShops', 'CouponShops.Shops'])->where(['id IN' => $tmp_coupon_id]);
        }
        if (!empty($stampcard_record_check)) {
          $tmp_stampcard_id = $this->StampcardShops->find('all')->where(['shop_id IN' => $shops])->extract('stamp_id')->toArray();
          $stampcard_record = $this->Stampcards->find('all')->contain(['ChildStampcards', 'StampcardShops', 'StampcardShops.Shops'])->where(['id IN' => $tmp_stampcard_id]);
        }
      }
    }

    if (!empty($coupon_record)) {
      foreach ($coupon_record as $val) {
        if (!empty($val->child_coupons)) {
          foreach ($val->child_coupons as $child_coupon) {
            if ($child_coupon->limit_count > 0) {
              $coupon_check++;
            }
          }
        }
      }
    }
    if (!empty($stampcard_record)) {
      foreach ($stampcard_record as $val) {
        if (!empty($val->child_stampcards)) {
          foreach ($val->child_stampcards as $dl) {
            $stampcard_check++;
          }
        }
      }
    }

    // 1件も存在しなければ、レコードから降順で10件取り出す
    if ($coupon_check > 0) {
      // 検索結果
      $coupon_list = [];

      // 取り出したレコードを整形
      foreach ($coupon_record as $coupon) {
        $child_count = 0; //
        // child_couponsが存在
        if (!empty($coupon->child_coupons)) {
          // child_coupon配列を回す
          foreach ($coupon->child_coupons as $count) {
            // 0は計算除外
            if ($count->limit_count > 0) {
              // 使用回数を足す
              $child_count += $count->limit_count;
              // 最後にpush
              if ($count === end($coupon->child_coupons)) {
                $coupon['count_total'] = $child_count;
                array_push($coupon_list, $coupon);
              }
            }
          }
        }
      }
      $coupon_sort = [];
      // ソート用配列
      foreach ((array) $coupon_list as $key => $value) {
        $coupon_sort[$key] = $value['count_total'];
      }
      // 結果をソート
      array_multisort($coupon_sort, SORT_DESC, $coupon_list);
      $this->set(compact('coupon_list'));

    // 0件オーナー
    } elseif ($this->Auth->user(['role_id']) == 1) {
      $coupon_list = $this->Coupons->find('all')
        ->where(['company_id' => $this->Auth->user(['company_id'])])
        ->contain(['ChildCoupons', 'CouponShops', 'CouponShops.Shops'])
        ->limit(10);
      $this->set(compact('coupon_list'));

    // 0件リーダー、メンバー
    } elseif (!empty($coupon_record_check)) {
      $coupon_list = $this->Coupons->find('all')
        ->contain(['ChildCoupons', 'CouponShops', 'CouponShops.Shops'])
        ->where(['id IN' => $tmp_coupon_id])
        ->limit(10);
      $this->set(compact('coupon_list'));
    }

    // 1件も存在しなければ、レコードから10件降順で取り出す
    if ($stampcard_check > 0) {
      // 検索結果
      $stampcard_list = [];

      // 取り出したレコードを整形
      foreach ($stampcard_record as $stampcard){
        $dl_count = 0;
        // child_stampcardsが存在
        if (!empty($stampcard->child_stampcards)) {
          // child_stampcard配列を回す
          foreach ($stampcard->child_stampcards as $count) {
            // child_stampcardだけ足す
            $dl_count++;
            // 最後にpush
            if ($count === end($stampcard->child_stampcards)) {
              $stampcard['dl_total'] = $dl_count;
              array_push($stampcard_list, $stampcard);
            }
          }
        }
      }
      // ソート用配列
      foreach ((array) $stampcard_list as $key => $value) {
        $stampcard_sort[$key] = $value['dl_total'];
      }
      // 結果をソート
      array_multisort($stampcard_sort, SORT_DESC, $stampcard_list);
      $this->set(compact('stampcard_list'));

    // 0件オーナー
    } elseif ($this->Auth->user(['role_id']) == 1) {
      $stampcard_list = $this->Stampcards->find('all')
        ->contain(['ChildStampcards', 'StampcardShops', 'StampcardShops.Shops'])
        ->where(['company_id' => $this->Auth->user(['company_id'])])
        ->limit(10);
      $this->set(compact('stampcard_list'));
    // 0件リーダー、メンバー
    } elseif (!empty($stampcard_record_check) && $this->Auth->user(['role_id']) != 1) {
      $stampcard_list = $this->Stampcards->find('all')
        ->contain(['ChildStampcards', 'StampcardShops', 'StampcardShops.Shops'])
        ->where(['id IN' => $tmp_stampcard_id])
        ->limit(10);
      $this->set(compact('stampcard_list'));
    }
  }

  public function edit($id = null)
  {
    if (!AuthService::authCheck($this)) {
      return null;
    }

    // プライベートアクションから呼び出し
    $this->mypage_filter();

    $table = $this->loadModel();
    $tableAlias = $table->getAlias();

    $entity = $table->get($id, [
      'contain' => [
        'Roles',
        'MyuserShops'
      ]
    ]);

    $myuser = $this->Myusers->get($this->Auth->user()['id']);

    $roles = $this->Myusers->Roles->find('list', ['limit' => 200]);

    $roleId = $myuser->role_id;
    $this->set(compact('roleId'));

    if ($roleId == Myuser::ROLE_OWNER) {
      $shops = $this->Shops->find('all')->where(['company_id' => $myuser['company_id']]);
      $company = $this->Companies->find()
        ->where(['id' => $myuser['company_id']])
        ->first();
      $this->set(compact('company'));
    } else {
      $shops = $this->MyuserShops->shop_list($this->Auth->user(['id']));
    }

    // 口座情報
      $bank = $this->MyuserBanks->find('all')
          ->where(['myuser_id' => $this->Auth->user(['id'])])
          ->first();

    // 支払方法
    if($myuser->customer) {
        $stripeService = new StripeService();
        $card = $stripeService->getCard($myuser->customer);
        $this->set(compact('card'));
    }

    $this->set($tableAlias, $entity);
    $this->set('tableAlias', $tableAlias);
    $this->set('_serialize', [$tableAlias, 'tableAlias']);
    $this->set(compact('roles', 'shops', 'bank'));

    if (!$this->request->is(['patch', 'post', 'put'])) {
      return;
    }
    $target_name = $entity->username;
    $entity = $table->patchEntity($entity, $this->request->getData());
    $singular = Inflector::singularize(Inflector::humanize($tableAlias));

    if ($this->Auth->user(['role_id']) == Myuser::ROLE_OWNER) {
      $companyValid = $this->companyValid();

      if (!$companyValid) {
        return;
      }
      $data = $this->request->getData();
      $params = [
        'name' => $data['company_name'],
        'address' => $data['company_address'],
        'email' => $data['company_email'],
        'tel' => $data['company_tel'],
        'manager_name' => $data['company_manager_name']
      ];

      $company = $this->Companies->patchEntity($company, $params);
      $this->Companies->save($company);
      $this->set(compact('company'));
    }

    if ($table->save($entity)) {
      $this->Flash->success(__d('CakeDC/Users', "$target_name さんの情報を更新しました。", $singular));

      if ($entity->id != $this->Auth->user(['id']) ) {
        return $this->redirect(['action' => 'logout']);
      } else {
        return $this->redirect(['action' => 'index']);
      }
    }
    $this->Flash->error(__d('CakeDC/Users', "$target_name さんの情報を更新出来ませんでした。", $singular));
  }

  // 支払方法編集
  public function paymentEdit() {
      $service = new StripeService();

      $api_key =  Configure::read('Stripe.key');
      $this->set(compact('api_key'));

      if ($this->request->is('post')) {
          if($this->request->getData('stripeToken')) {
              return $this->redirect(['action' => 'paymentConfirm', 'card' => $this->request->getData()]);
          }
      }
  }

    // 支払方法確認
    public function paymentConfirm() {

        $service = new StripeService();

        $myuser = $this->Myusers->get($this->Auth->user('id'));

        if ($this->request->is('post')) {

            try {
                if($myuser->customer) {
                    try {
                        if($myuser->customer) {
                            $service->retrieve($myuser->customer);
                        }
                    } catch(\Exception $e) {
                        $card = $service->saveCard($this->request->getData('stripeToken'));
                        $myuser->customer = $card->id;
                        $this->Myusers->save($myuser);
                        $myuser = $this->Myusers->get($this->Auth->user('id'));
                    }

                    $customer = $service->retrieve($myuser->customer);
                    $res = $customer->sources->create([
                        'source' => $this->request->getData('stripeToken')
                    ]);

                    // 使用するカードを変更
                    $customer->default_source = $res->id;
                    $customer->save();

                    $this->redirect(['action' => 'edit', 'id' => $myuser->id]);
                    $this->Flash->success('支払方法の登録に成功しました');
                } else {
                    $card = $service->saveCard($this->request->getData('stripeToken'));
                    $myuser->customer = $card->id;
                    $this->Myusers->save($myuser);
                    $this->redirect(['action' => 'edit', 'id' => $myuser->id]);
                    $this->Flash->success('支払方法の変更に成功しました');
                }
            } catch(\Exception $e) {
                $this->redirect(['action' => 'edit', 'id' => $myuser->id]);
                $this->Flash->error('支払方法の登録に失敗しました');
            }
        }
    }

    public function cardDelete() {
        $this->request->allowMethod('delete');
        $user = $this->Myusers->get($this->Auth->user('id'));

        $service = new StripeService();
        try {
            $account = $service->retrieve($user->customer);
        } catch (Exception $exception) {
            $this->Flash->error('カード情報の取得に失敗しました');
            $this->redirect(['action' => 'edit', 'id' => $user->id]);
            return;
        }

        try {
            foreach ($account->sources as $source) {
                $service->deleteCard($user->customer, $source->id);
            }
        } catch (Exception $exception) {
            $this->Flash->error('カード情報の削除に失敗しました');
        }

        $user->customer = null;
        $this->Myusers->save($user);
        $this->Flash->success('カード情報を削除しました');

        $this->redirect(['action' => 'edit', 'id' => $user->id]);
    }

  public function delete($id = null)
  {
    $this->request->allowMethod(['post', 'delete']);
    $myuser = $this->Myusers->get($id);

    if ($this->Myusers->delete($myuser)) {
      $this->Flash->success(__('ユーザーを削除しました。'));
    } else {
      $this->Flash->error(__('このユーザーは削除出来ません。'));
    }

    $url = $this->referer(null, true);
    // 直前のURLがleadersなら、leader一覧へredirect
    if (preg_match('/leaders/',$url)) {
      return $this->redirect(['controller' => 'Leaders', 'action' => 'index']);
    } elseif(preg_match('/affiliater/', $url)) {
      return $this->redirect(['action' => 'affiliater_list']);
    } else {
      return $this->redirect(['controller' => 'Leaders', 'action' => 'index']);
    }
  }

    /*
    * ユーザ登録
    * sessionにリクエストデータを保存
    */
    public function signUp()
    {
      $myuser = $this->Myusers->newEntity();
      // require src/Form/UserForm.php
      $user = new UserForm();
      $role_id = $this->request->getQuery('role_id');
      $this->set(compact('user', 'role_id'));

      if ($this->request->is('post')) {
        // バリデーションチェック
        $myuser = $this->Myusers->patchEntity($myuser, $this->request->getData());
        $email_check = $myuser->errors('email.email_unique');
        $this->set(compact('email_check'));

        $data = $this->request->getData();

        if ($role_id == Myuser::ROLE_OWNER) {
          $companyValid = $this->companyValid();

          if (!$companyValid) {
            return;
          }
        }

        if ($user->execute($this->request->getData()) && $myuser->hasErrors() == null) {
          // 確認画面
          if ($this->request->getData('mode') == 'confirm') {
            $this->render('signUpConfirm');
          } else {
              if ($role_id == Myuser::ROLE_OWNER) {
                  $company = $this->Companies->newEntity();
                  $params = [
                    'name' => $data['company_name'],
                    'address' => $data['company_address'],
                    'email' => $data['company_email'],
                    'tel' => $data['company_tel'],
                    'manager_name' => $data['company_manager_name']
                  ];

                  $company = $this->Companies->patchEntity($company, $params);
                  $this->Companies->save($company);
                  $get_company_name = $this->Companies->find('all')
                      ->where(['name' => $this->request->getData('company_name')])
                      ->select(['id'])
                      ->first();
                  $this->request = $this->request->withData('company_id', $get_company_name->id);
              }

              return $this->setAction('register');
          }
        } else {
          $this->Flash->error('入力データにエラーがあります。');
        }
      }
    }
//
//  // あとでまとめる
//  public function memberList()
//  {  // leader_listと同じ
//     $myusers = $this->Myusers->my_company_leder_list_array(3, $this->Auth->user('company_id'));
//     $shops = $this->Shops->shop_record_array($this->Auth->user('company_id'));
//     $this->set(compact('myusers', 'shops'));
//  }
//
//  // あとでまとめる
//  public function memberInput()
//  {
//    // ログインIDが所属している店舗レコードを抽出
//    // modelから呼び出し
//    if ($this->Auth->user('role_id') == 1) {
//    $shop_list = $this->Shops->shop_record_array($this->Auth->user('company_id'));
//    } else {
//      $shop_list = $this->MyuserShops->shop_list($this->Auth->user('id'));
//    }
//
//    // Entity宣言
//    $myuser_entity = $this->Myusers->newEntity();
//    $myuser_shop_entity = $this->MyuserShops->newEntity();
//    // カスタムバリデーション宣言
//    $user = new UserForm();
//    $this->set(compact('user'));
//
//    if ($this->request->is('post')) {
//      // バリデーションチェック
//      $myuser_entity = $this->Myusers->patchEntity($myuser_entity, $this->request->getData());
//      $email_check = $myuser_entity->errors('email.email_unique');
//      $this->set(compact('email_check'));
//      if ($user->execute($this->request->getData()) && $myuser_entity->hasErrors() == false) {
//        // 確認画面
//        if ($this->request->getData('mode') == 'confirm') {
//          // 一時保存
//          $after_save_data = $this->request->getData('shop_id');
//          // modelから呼び出し
//          $shop_result_array = $this->Shops->add_record_array($this->request->getData('shop_id'), 'id', 'name');
//          $this->set(compact('after_save_data', 'shop_result_array'));
//          $this->render('member_confirm');
//        } else {
//          $connection = ConnectionManager::get('default');
//          // トランザクション開始
//          $connection->begin();
//          try {
//            if ($this->Myusers->save($myuser_entity)) {
//              $shop_save_data = [];
//              foreach ($this->request->getData('after_save_data') as $shop_id ) {
//                $tmp_array = [];
//                $tmp_array['shop_id'] = $shop_id;
//                $tmp_array['myuser_id'] = $myuser_entity->id;
//
//                array_push($shop_save_data, $tmp_array);
//              }
//              $myuser_shop_entity = $this->MyuserShops->patchEntities($myuser_shop_entity, $shop_save_data);
//              $this->MyuserShops->saveMany($myuser_shop_entity);
//              $this->Flash->success(__('メンバーユーザーを作成致しました。'));
//              $connection->commit();
//              return $this->redirect(['action' => 'member_list']);
//            } else {
//              $this->Flash->success(__('メンバーユーザーを作成出来ませんでした。'));
//              throw new Exception(Configure::read("M.ERROR.INVALID"));
//            }
//          } catch(\Exception $e) {
//            // ロールバック
//            $connection->rollback();
//          }
//        }
//      } else {
//        $this->Flash->error(__('入力データにエラーがあります'));
//      }
//    }
//    $this->set(compact('shop_list'));
//  }
//
//  public function memberEdit($id = null)
//  {
//    $myuser = $this->Myusers->get($id, [
//      'contain' => [
//      'MyuserShops',
//      ],
//    ]);
//
//    $myuser_shops = $this->MyuserShops->my_user_list($id);
//
//    if ($this->Auth->user('role_id') == 1) {
//    $shop_list = $this->Shops->shop_record_array($this->Auth->user('company_id'));
//    } else {
//      $shop_list = $this->MyuserShops->shop_list($this->Auth->user('id'));
//    }
//
//    // 所属しているユーザー情報のみ
//    $target_company_id = $myuser->company_id;
//    // private 関数
//    $this->my_company_filter($target_company_id);
//
//    if ($this->request->is(['patch', 'post', 'put'])) {
//      $connection = ConnectionManager::get('default');
//      // トランザクション開始
//      $myuser_entity = $this->Myusers->patchEntity($myuser, $this->request->getData());
//      #$edit_myuser_shop_id = $this->MyuserShops->edit_record($id);
//
//      $connection->begin();
//      // 例外処理
//      try {
//        if ($this->Myusers->save($myuser_entity)) {
//          if (!empty($this->request->getData('shop_id'))) {
//
//            $shop_save_data = [];
//            foreach ($this->request->getData('shop_id') as $shop_id) {
//              $tmp_array = [];
//              #$tmp_array['id'] = array_shift($edit_myuser_shop_id);
//              $tmp_array['myuser_id'] = $id;
//              $tmp_array['shop_id'] = $shop_id;
//
//              array_push($shop_save_data, $tmp_array);
//            }
//
//            // 対象ユーザのshopデータ削除
//            $delete_data = [
//              'myuser_id' => $myuser->id
//            ];
//
//            $this->MyuserShops->deleteAll($delete_data);
//            $myuser_shop_entity = $this->MyuserShops->patchEntities($myuser_shops, $shop_save_data);
//            $this->MyuserShops->saveMany($myuser_shop_entity);
//          }
//          $connection->commit();
//          $this->Flash->success(__('ユーザー情報を更新しました。'));
//          return $this->redirect(['action' => 'member_list']);
//
//        } else {
//          $this->Flash->error(__('ユーザー情報を更新出来ませんでした。'));
//          throw new Exception(Configure::read("M.ERROR.INVALID"));
//        }
//      } catch(\Exception $e) {
//        //ロールバック
//        $this->Flash->error(__('ユーザー情報を更新出来ませんでした。'));
//        $connection->rollback();
//      }
//    }
//
//    $companies = $this->Myusers->Companies->find('list', ['limit' => 200]);
//    // オーナー非表示
//    $roles = $this->Myusers->Roles->find('list', ['limit' => 200])->where(['id IN' => [2, 3]]);
//    $this->set(compact('myuser_shops', 'myuser', 'companies', 'roles', 'shop_list'));
//
//  }


    public function userManagement()
    {
      if (!AuthService::authCheck($this, [
        Myuser::ROLE_OWNER,
        Myuser::ROLE_LEADER,
        Myuser::ROLE_MEMBER])) {
        return null;
      }
    }

    public function privacyPolicy()
    {
    }

    public function enabledDevice()
    {
    }

    /* corefileから上書き
    *  corefire: vender/cakedc
    */
    public function register()
    {

        if (!Configure::read('Users.Registration.active')) {
            throw new NotFoundException();
        }

        $userId = $this->Auth->user('id');
        if (!empty($userId) && !Configure::read('Users.Registration.allowLoggedIn')) {
            $this->Flash->error(__d('CakeDC/Users', 'You must log out to register a new user account'));

            return $this->redirect(Configure::read('Users.Profile.route'));
        }

        $usersTable = $this->getUsersTable();
        $user = $usersTable->newEntity();
        $validateEmail = (bool)Configure::read('Users.Email.validate');
        $useTos = (bool)Configure::read('Users.Tos.required');
        $tokenExpiration = Configure::read('Users.Token.expiration');
        $options = [
            'token_expiration' => $tokenExpiration,
            'validate_email' => $validateEmail,
            'use_tos' => $useTos
        ];
        $requestData = $this->request->getData();
        $event = $this->dispatchEvent(UsersAuthComponent::EVENT_BEFORE_REGISTER, [
            'usersTable' => $usersTable,
            'options' => $options,
            'userEntity' => $user,
        ]);

        if ($event->result instanceof EntityInterface) {
            $data = $event->result->toArray();
            $data['password'] = $requestData['password']; //since password is a hidden property
            if ($userSaved = $usersTable->register($user, $data, $options)) {
                return $this->_afterRegister($userSaved);
            } else {
                $this->set(compact('user'));
                $this->Flash->error(__d('CakeDC/Users', 'The user could not be saved'));

                return;
            }
        }
        if ($event->isStopped()) {
            return $this->redirect($event->result);
        }

        $this->set(compact('user'));
        $this->set('_serialize', ['user']);

        if (!$this->request->is('post')) {
            return;
        }

        if (!$this->_validateRegisterPost()) {
            $this->Flash->error(__d('CakeDC/Users', 'Invalid reCaptcha'));

            return;
        }

        $userSaved = $usersTable->register($user, $requestData, $options);
        if (!$userSaved) {
            $this->Flash->error(__d('CakeDC/Users', 'The user could not be saved'));

            return;
        }

        return $this->_afterRegister($userSaved);
    }

    /**
     * Check the POST and validate it for registration, for now we check the reCaptcha
     *
     * @return bool
     */
    protected function _validateRegisterPost()
    {
        if (!Configure::read('Users.reCaptcha.registration')) {
            return true;
        }

        return $this->validateReCaptcha(
            $this->request->getData('g-recaptcha-response'),
            $this->request->clientIp()
        );
    }

    /**
     * Prepare flash messages after registration, and dispatch afterRegister event
     *
     * @param EntityInterface $userSaved User entity saved
     * @return Response
     */
    protected function _afterRegister(EntityInterface $userSaved)
    {
        $validateEmail = (bool)Configure::read('Users.Email.validate');
        $message = __d('CakeDC/Users', 'You have registered successfully, please log in');
        if ($validateEmail) {
            $message = __d('CakeDC/Users', 'Please validate your account before log in');
        }
        $event = $this->dispatchEvent(UsersAuthComponent::EVENT_AFTER_REGISTER, [
            'user' => $userSaved
        ]);
        if ($event->result instanceof Response) {
            return $event->result;
        }
        $this->Flash->success($message);

        return $this->redirect(['action' => 'register']);
    }

    /**
     * Validate an email
     *
     * @param string $token token
     * @return void
     */
    public function validateEmail($token = null)
    {
        $this->validate('email', $token);
    }

  /**
   * Validates email
   *
   * @param string $type 'email' or 'password' to validate the user
   * @param string $token token
   * @return Response
   */
    public function validate ($type = null, $token = null)
    {
      try {
        switch ($type) {
          case 'email':
            try {
              $result = $this->getUsersTable()->validate($token, 'activateUser');
              if ($result) {
                $this->Flash->success(__d('CakeDC/Users', 'User account validated successfully'));
              } else {
                $this->Flash->error(__d('CakeDC/Users', 'User account could not be validated'));
              }
            } catch (UserAlreadyActiveException $exception) {
              $this->Flash->error(__d('CakeDC/Users', 'User already active'));
            }
            break;
          case 'password':
            $result = $this->getUsersTable()->validate($token);
            if (!empty($result)) {
              $this->Flash->success(__d('CakeDC/Users', 'Reset password token was validated successfully'));
              $this->request->getSession()->write(
                Configure::read('Users.Key.Session.resetPasswordUserId'),
                $result->id
              );

              return $this->redirect(['action' => 'changePassword']);
            } else {
              $this->Flash->error(__d('CakeDC/Users', 'Reset password token could not be validated'));
            }
            break;
          default:
            $this->Flash->error(__d('CakeDC/Users', 'Invalid validation type'));
        }
      } catch (UserNotFoundException $ex) {
        $this->Flash->error(__d('CakeDC/Users', 'Invalid token or user account already validated'));
      } catch (TokenExpiredException $ex) {
        $event = $this->dispatchEvent(UsersAuthComponent::EVENT_ON_EXPIRED_TOKEN, ['type' => $type]);
        if (!empty($event) && is_array($event->result)) {
          return $this->redirect($event->result);
        }
        $this->Flash->error(__d('CakeDC/Users', 'Token already expired'));
      }

      return $this->redirect(['action' => 'signUpRegistration']);
    }

  /*
  * passwart reset
  */
    public function requestResetPassword()
    {
      $this->set('user', $this->getUsersTable()->newEntity());
      $this->set('_serialize', ['user']);
      if (!$this->request->is('post')) {
        return;
      }

      $reference = $this->request->getData('reference');
      try {
        $resetUser = $this->getUsersTable()->resetToken($reference, [
          'expiration' => Configure::read('Users.Token.expiration'),
          'checkActive' => false,
          'sendEmail' => true,
          'ensureActive' => Configure::read('Users.Registration.ensureActive'),
          'type' => 'password'
        ]);
        if ($resetUser) {
          $msg = __d('CakeDC/Users', 'Please check your email to continue with password reset process');
          $this->Flash->success($msg);
        } else {
          $msg = __d('CakeDC/Users', 'The password token could not be generated. Please try again');
          $this->Flash->error($msg);
        }

        return $this->render('passwordResetSend');
      //return $this->redirect(['controller' => 'Test', 'action' => 'password_reset_send']);
      } catch (UserNotFoundException $exception) {
        $this->Flash->error(__d('CakeDC/Users', 'User {0} was not found', $reference));
      } catch (UserNotActiveException $exception) {
        $this->Flash->error(__d('CakeDC/Users', 'The user is not active'));
      } catch (Exception $exception) {
        $this->Flash->error(__d('CakeDC/Users', 'Token could not be reset'));
        $this->log($exception->getMessage());
      }
    }

    public function signUpRegistration() {
    }

    public function passwordResetSend() {
    }

    public function passwordResetDone() {
    }

    public function resetPassword ($token = null)
    {
      $this->validate('password', $token);
    }

    public function changePassword ($id = null)
    {
      $user = $this->getUsersTable()->newEntity();
      if ($this->Auth->user('id')) {
        if ($id && $this->Auth->user('is_superuser') && Configure::read('Users.Superuser.allowedToChangePasswords')) {
          // superuser editing any account's password
          $user->id = $id;
          $validatePassword = false;
          $redirect = ['action' => 'index'];
        } elseif (!$id || $id === $this->Auth->user('id')) {
          // normal user editing own password
          $user->id = $this->Auth->user('id');
          $validatePassword = true;
          $redirect = Configure::read('Users.Profile.route');
        } else {
          $this->Flash->error(__d('CakeDC/Users', 'Changing another user\'s password is not allowed'));
          $this->redirect(Configure::read('Users.Profile.route'));

          return;
        }
      } else {
        // password reset
        $user->id = $this->request->getSession()->read(Configure::read('Users.Key.Session.resetPasswordUserId'));
        $validatePassword = false;
        $redirect = [
          'plugin' => false,
          'controller' => 'Myusers',
          'action' => 'passwordResetDone',
          'prefix' => false
        ];
        if (!$user->id) {
          $this->Flash->error(__d('CakeDC/Users', 'User was not found'));
          $this->redirect($this->Auth->getConfig('loginAction'));

          return;
        }
      }
      $this->set('validatePassword', $validatePassword);
      if ($this->request->is(['post', 'put'])) {
        try {
          $validator = $this->getUsersTable()->validationPasswordConfirm(new Validator());
          if ($validatePassword) {
            $validator = $this->getUsersTable()->validationCurrentPassword($validator);
          }
          $user = $this->getUsersTable()->patchEntity(
            $user,
            $this->request->getData(),
            [
              'validate' => $validator,
              'accessibleFields' => [
                'current_password' => true,
                'password' => true,
                'password_confirm' => true,
              ]
            ]
          );
          if ($user->getErrors()) {
            $this->Flash->error(__d('CakeDC/Users', 'Password could not be changed'));
          } else {
            $result = $this->getUsersTable()->changePassword($user);
            if ($result) {
              $event = $this->dispatchEvent(UsersAuthComponent::EVENT_AFTER_CHANGE_PASSWORD, ['user' => $result]);
              if (!empty($event) && is_array($event->result)) {
                return $this->redirect($event->result);
              }
              $this->Flash->success(__d('CakeDC/Users', 'Password has been changed successfully'));

              return $this->redirect($redirect);
            } else {
              $this->Flash->error(__d('CakeDC/Users', 'Password could not be changed'));
            }
          }
        } catch (UserNotFoundException $exception) {
          $this->Flash->error(__d('CakeDC/Users', 'User was not found'));
        } catch (WrongPasswordException $wpe) {
          $this->Flash->error($wpe->getMessage());
        } catch (Exception $exception) {
          $this->Flash->error(__d('CakeDC/Users', 'Password could not be changed'));
          $this->log($exception->getMessage());
        }
      }
      $this->set(compact('user'));
      $this->set('_serialize', ['user']);
    }

  /**
   * Resend Token validation
   *
   * @return mixed
   */
  public function resendTokenValidation()
  {
    $this->set('user', $this->getUsersTable()->newEntity());
    $this->set('_serialize', ['user']);
    if (!$this->request->is('post')) {
      return;
    }
    $reference = $this->request->getData('email');
    try {
      if ($this->getUsersTable()->resetToken($reference, [
        'expiration' => Configure::read('Users.Token.expiration'),
        'checkActive' => true,
        'sendEmail' => true,
        'type' => 'email'
      ])) {
        $event = $this->dispatchEvent(UsersAuthComponent::EVENT_AFTER_RESEND_TOKEN_VALIDATION);
        if (!empty($event) && is_array($event->result)) {
          return $this->redirect($event->result);
        }
        $this->Flash->success(__d(
          'CakeDC/Users',
          'Token has been reset successfully. Please check your email.'
        ));
      } else {
        $this->Flash->error(__d('CakeDC/Users', 'Token could not be reset'));
      }

      return $this->redirect(['action' => '']);
    } catch (UserNotFoundException $ex) {
      $this->Flash->error(__d('CakeDC/Users', 'User {0} was not found', $reference));
    } catch (UserAlreadyActiveException $ex) {
      $this->Flash->error(__d('CakeDC/Users', 'User {0} is already active', $reference));
    } catch (Exception $ex) {
      $this->Flash->error(__d('CakeDC/Users', 'Token could not be reset'));
    }
  }

  public function affiliaterList() {
      $companyId = $this->Auth->user('company_id');

      $affiliater = $this->Myusers->my_affiliater_list_array(Myuser::ROLE_AFFILIATER, $companyId);

      $appAffiliater = $this->AffiliaterApplications->find()
          ->where(['status_id In' => [
                AffiliaterApplication::STATUS_ID_APPLYING,
                AffiliaterApplication::STATUS_ID_APPROVED,
                AffiliaterApplication::STATUS_ID_ERROR
              ]
          ])
          ->where([
              'AffiliaterApplications.company_id' => $companyId
          ])
          ->contain(['Myusers'])
          ->toArray();

      // 全金額
      $allFee = 0;
      collection($appAffiliater)
          ->each(function ($x) use(&$allFee) {
              $allFee += $x->point;
          });

      $stripe = new StripeService();
      $commission = $stripe->getCommission($allFee);
      $totalAmount = $stripe->getTotalAmount($allFee);

      if ($this->request->is('post')) {
          $service = new ApplicationService();
          $service->transferApplication($this, $appAffiliater);
      }

      $this->set(compact('affiliater', 'appAffiliater', 'allFee', 'commission', 'totalAmount'));
  }

//  public function affiliaterNew() {
//    $user_entity = $this->Myusers->newEntity();
//    // カスタムバリデーション宣言
//    $user = new UserForm();
//    $this->set(compact('user'));
//
//    if(!$this->request->is('post')) {
//      return null;
//    }
//
//    $user_entity = $this->Myusers->patchEntity($user_entity, $this->request->getData());
//    $email_check = $user_entity->getError('email.email_unique');
//    $this->set(compact('email_check'));
//
//    if(!$user->execute($this->request->getData()) && $user_entity->hasErrors()) {
//      $this->Flash->error(__('入力データにエラーがあります'));
//      return null;
//    }
//
//    if($this->request->getData('mode') == 'confirm') {
//      return $this->render('affiliater_confirm');
//    }
//
//    /**
//     * @var $connection Connection
//     */
//    $connection = ConnectionManager::get('default');
//    $connection->begin();
//
//    try {
//      if(!$this->Myusers->save($user_entity)) {
//        $this->Flash->success(__('アフィリエイターを作成出来ませんでした。'));
//        throw new Exception(Configure::read("M.ERROR.INVALID"));
//      }
//
//      $this->Flash->success(__('アフィリエイターを作成致しました。'));
//      $connection->commit();
//    } catch(\Exception $e) {
//      $connection->rollback();
//    }
//  }

  public function affiliaterEdit($id = null) {
    if (!AuthService::authCheck($this, [
      Myuser::ROLE_OWNER,
      Myuser::ROLE_LEADER,
      Myuser::ROLE_MEMBER])) {
      return null;
    }

    $myuser = $this->Myusers->find()
      ->where(['Myusers.id' => $id])
      ->first();

    if (!$myuser) {
      $this->redirect('/affiliaters');
    }

    $myuserBank = $this->MyuserBanks->find()
      ->where(['id' => $myuser['id']])
      ->first();

    $this->set(compact('myuser', 'myuserBank'));
  }

    /**
     * @param null $id
     * @return Response|void|null
     */
  public function affiliaterPayment($id = null) {

      $appAffiliater = $this->AffiliaterApplications
          ->find()
          ->where(['id' => $id])
          ->first();

      if(!$appAffiliater) {
          return $this->redirect('/affiliaters');
      }

      $stripe = new StripeService();
      $commission = $stripe->getCommission($appAffiliater->point);
      $totalAmount = $stripe->getTotalAmount($appAffiliater->point);

      $this->set(compact('appAffiliater', 'commission', 'totalAmount'));

      if(!$this->request->is('post')) {
          return;
      }

      // 承認
      if ($appAffiliater->status_id == AffiliaterApplication::STATUS_ID_APPLYING) {
          $entity = $this->AffiliaterApplications->patchEntity($appAffiliater, ['status_id' => AffiliaterApplication::STATUS_ID_APPROVED]);
          $entity = $this->AffiliaterApplications->saveOrFail($entity);
          $appAffiliater = $entity;;
          $this->set(compact('appAffiliater'));
          $this->Flash->success('承認しました');
          return;
      }

      // 管理者へ振込
      if ($appAffiliater['status_id'] == AffiliaterApplication::STATUS_ID_APPROVED
          || $appAffiliater['status_id'] == AffiliaterApplication::STATUS_ID_ERROR) {
          $service = new ApplicationService();
          $service->transferApplication($this, $appAffiliater);
      }
  }

  ###############
  #  関数リスト #
  ###############
  /*
  * 自分以外に飛ぼうとした場合、リダイレクト
  */
  private function mypage_filter()
  {
     //ログイン者情報
     $id = $this->Auth->user('id');
     // 直前のaction
     $action = $this->request->action;
     if ($id != $this->request->getParam('pass.0')) {
       $this->redirect(['controller' => 'Myusers', 'action' => $action, $id]);
     }
  }

  private function my_company_filter($target_company_id)
  {
    $action = $this->request->action;
    $my_company_id = $this->Auth->user('company_id');
    if ($my_company_id != $target_company_id) {
      $this->redirect(['controller' => 'Myusers', 'action' => 'user_management']);
    }
  }

  private function companyValid() {
    $data = $this->request->getData();
    $res = true;
    if (!$data['company_name']) {
      $company_name_check = '会社名を入力してください。';
      $this->set(compact('company_name_check'));
      $res = false;
    }
    if (!$data['company_address']) {
      $company_address_check = '住所を入力してください。';
      $this->set(compact('company_address_check'));
      $res = false;
    }
    if (!$data['company_email']) {
      $company_email_check = 'メールアドレスを入力してください。';
      $this->set(compact('company_email_check'));
      $res = false;
    }
    if (!$data['company_tel']) {
      $company_tel_check = '電話番号を入力してください。';
      $this->set(compact('company_tel_check'));
      $res = false;
    }
    if ($data['company_tel']) {
      if (!preg_match( '/^[0-9]{9,11}\z/', $data['company_tel'] ) ) {
        $company_tel_check = '正しい電話番号を入力してください';
        $this->set(compact('company_tel_check'));
        $res = false;
      }
    }

    if (!$data['company_manager_name']) {
      $company_manager_name_check = 'ご担当者名を入力してください。';
      $this->set(compact('company_manager_name_check'));
      $res = false;
    }
    return $res;
  }
}
