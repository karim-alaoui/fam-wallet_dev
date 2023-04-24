<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\Myuser;
use App\Model\Table\MyusersTable;
use App\Services\AuthService;
use Cake\ORM\TableRegistry;
use App\Controller\Component\PKpassOrg;
use App\Form\StampcardForm;
use Cake\Datasource\ConnectionManager;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\Routing\Router;
use Cake\Event\Event;
use JsonSchema\Exception\ValidationException;

/**
 * Stampcards Controller
 *
 * @property \App\Model\Table\StampcardsTable $Stampcards
 *
 * @method \App\Model\Entity\Stampcard[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StampcardsController extends AppController
{

  /**
   * @var MyusersTable
   */
  private $Myusers;

  public function beforeFilter(Event $event)
  {
    parent::beforeFilter($event);
//    $this->Auth->allow(['qrcode']);
  }

  public function initialize()
  {
    parent::initialize();
    // テーブル呼び出し
    $this->Myusers = TableRegistry::getTableLocator()->get('Myusers');
    $this->MyuserShops = TableRegistry::get('MyuserShops');
    $this->Shops = TableRegistry::get('Shops');
    $this->StampcardShops = TableRegistry::get('StampcardShops');
    $this->StampcardRewords = TableRegistry::get('StampcardRewords');
    $this->Releases = TableRegistry::get('ReleaseStates');
    $this->Rewords = TableRegistry::get('RewordStates');
    $this->ChildStampcards = TableRegistry::get('ChildStampcards');
    $this->ChildStampcardRewords = TableRegistry::get('ChildStampcardRewords');
  }

  public function delete($id = null)
  {
    $this->request->allowMethod(['post', 'delete']);
    $stampcard = $this->Stampcards->get($id);
    if ($this->Stampcards->delete($stampcard)) {
      $this->Flash->success(__('スタンプカードを削除しました。'));
    } else {
      $this->Flash->error(__('スタンプカードを削除できませんでした。'));
    }

    return $this->redirect(['action' => 'index']);
  }

  public function index()
  {
    $result_record = [];
    $release_record = 0;
    $private_record = 0;

    // レコードを配列へ
    $shop_list = [];

    if ($this->Auth->User('role_id') == Myuser::ROLE_OWNER) {
      // ログインIDが所属している店舗レコードを抽出
      $shops = $this->Shops->shop_index($this->Auth->User('company_id'));
      $shop_list = ['0' => '全所属店舗'];
      foreach ($shops as $key => $val) {
        $shop_list[$val->id] = $val->name;
      }
      // 公開,非公開,全てのレコード
      list($release_record, $private_record, $result_record) = $this->Stampcards->total_release_record($this->Auth->User('company_id'));
    } else { // リーダー、メンバー
      $shops = $this->MyuserShops->find('all')->where(['myuser_id' => $this->Auth->User('id')])->contain(['Shops']);

      foreach ($shops as $key) {
        $shop_list[$key->shop_id] = $key->shop->name;
      }
      $tmp_shop_id = [];
      // 所属してる店舗IDを抽出
      foreach ($shops as $key) {
        array_push($tmp_shop_id, $key->shop_id);
      }
      // 店舗IDからstamp_idを抽出
      $tmp_stamp_shop_id = $this->StampcardShops->find('all')->where(['shop_id IN' => $tmp_shop_id]);

      if (count($tmp_stamp_shop_id->toArray())) {
        // stamp_idを抽出
        $tmp_stamp_id = [];
        foreach ($tmp_stamp_shop_id as $key) {
          array_push($tmp_stamp_id, $key->stamp_id);
        }
        $result_record = $this->Stampcards->find('all')->where(['id IN' => $tmp_stamp_id])->contain(['ChildStampcards', 'StampcardShops', 'StampcardShops.Shops']);
        $release_record = $this->Stampcards->find('all')->where(['id IN' => $tmp_stamp_id, 'release_id' => 1])->contain(['ChildStampcards', 'StampcardShops', 'StampcardShops.Shops'])->count();
        $private_record = $this->Stampcards->find('all')->where(['id IN' => $tmp_stamp_id, 'release_id' => 2])->contain(['ChildStampcards', 'StampcardShops', 'StampcardShops.Shops'])->count();
      }
    }

    if ($this->request->is('post')) {
      // 全店舗
      if ($this->request->getData('shop') == 0) {
        list($release_record, $private_record, $result_record) = $this->Stampcards->total_release_record($this->Auth->User('company_id'));
      } else {
        $shop_record = $this->StampcardShops->find('all')->where(['shop_id' => $this->request->getData('shop')])->select(['stamp_id']);
        list($release_record, $private_record, $result_record) = $this->Stampcards->search_record($this->Auth->User('company_id'), $shop_record);
      }
    }

    $this->set(compact('shop_list', 'release_record', 'private_record', 'result_record'));
  }

    // new
  public function new()
  {
    if ($this->Auth->user('role_id') == 1) {
      // ログインIDが所属している店舗レコードを抽出
      $shop_list = $this->Shops->shop_record_array($this->Auth->user('company_id'));
    } elseif ($this->Auth->user('role_id') == 2){
      $myuser_shop_list = $this->MyuserShops->find('all')->contain(['Shops'])->where(['myuser_id' => $this->Auth->User('id')]);
      $shop_list = [];
      foreach ($myuser_shop_list as $key) {
        $shop_list[$key->shop_id] = $key->shop->name;
      }
    }

    // 店舗登録0 リダイレクト
    if (empty($shop_list)) {
      $this->Flash->success(__('スタンプカード発行には店舗登録が必要です。'));
      return $this->redirect(['controller' => 'Shops', 'action' => 'new']);
    }

    // ゴール設定
    $end_stanp_value = [];
    $end_stamp_value = ['5' => '5', '10' => '10', '15' => '15', '20' => '20'];

    $stampcard = $this->Stampcards->newEntity();
    $stampcard_shop = $this->StampcardShops->newEntity();
    $stampcard_reword = $this->StampcardRewords->newEntity();
    $stampcard_validate = new StampcardForm();
    $this->request = $this->request->withData('token', uniqid('', true));

    if ($this->request->is('post')) {
      // バリデーションチェック
      $stampcard = $this->Stampcards->patchEntity($stampcard, $this->request->getData());

      if (empty($this->request->getData('shop_id'))) {
        $shop_error = '店舗を選択してください';
        $this->set(compact('shop_error'));
      }
      if ($this->add_background_value() == '0') {
        $color_error = '色を選択してください';
        $this->set(compact('color_error'));
      }

      if (!empty($this->request->getData('address'))) {
        try {
          $this->result_longitude_latitude($this->request->getData('address'));
        } catch (\Exception $e) {
          $address_error = '住所から位置情報を取得できませんでした';
          $this->set(compact('address_error'));
        }
      }

      if ($stampcard_validate->execute($this->request->getData())
        && $stampcard->hasErrors() == false
        && empty($color_error)
        && empty($address_error)) {
        // 確認画面に遷移
        if ($this->request->getData('mode') == "confirm" ){
          // 一時保存
          $after_save_data = $this->request->getData('shop_id');
          // shop_modelから呼び出し
          $shop_result_array = $this->Shops->add_record_array($this->request->getData('shop_id'), 'id', 'name');
          $this->set(compact('shop_result_array'));

          $this->render("confirm");
        } else {
          // 確認画面から保存
          $connection = ConnectionManager::get('default');
          // トランザクション開始
          $connection->begin();
          try {
            if ($this->Stampcards->save($stampcard)) {

              $shop_save_data = [];
              $wallet_shops = [];

              foreach ($this->request->getData('after_save_data') as $shop_id) {
                $tmp_array = [];
                $tmp_array['shop_id'] = $shop_id;
                $tmp_array['stamp_id'] = $stampcard->id;

                array_push($shop_save_data, $tmp_array);
                $wallet_shops[] += $shop_id;
              }

              $stamp_shop_entity = $this->StampcardShops->patchEntities($stampcard_shop, $shop_save_data);
              $this->StampcardShops->saveMany($stamp_shop_entity);

              $stampcard_reword_data = [
                'stamp_id' => $stampcard->id,
                'reword' => $this->request->getData('reword'),
                'reword_point' => $this->request->getData('max_limit'),
              ];

              $stamp_reword_entity = $this->StampcardRewords->patchEntity($stampcard_reword, $stampcard_reword_data);

              $this->StampcardRewords->save($stamp_reword_entity);

              $shop_list =$this->Shops->all_shop_record($wallet_shops);

              $connection->commit();
              $this->Flash->success(__('新規スタンプカードを作成しました。'));
              return $this->redirect(['action' => 'index']);
            } else {
              $this->Flash->success(__('新規スタンプカードを作成できませんでした。'));
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
    $this->set(compact('stampcard', 'shop_list', 'stampcard_validate', 'end_stamp_value'));
  }

  public function edit($id = null)
  {
    $roleId = $this->Myusers->get($this->Auth->user('id'))->role_id;

    $stampcard = $this->Stampcards->get($id, [
      'contain' => ['ChildStampcards', 'StampcardShops', 'StampcardShops.Shops'],
    ]);

    $view_url = Router::url(['controller' => 'Stampcards', 'action' => 'qrcode', $stampcard->id, '?' => ['param' => $stampcard->token, 'openExternalBrowser'=> 1]], true);

    if ($this->request->is(['patch', 'post', 'put'])) {
      $stampcard = $this->Stampcards->patchEntity($stampcard, $this->request->getData());
      if ($this->Stampcards->save($stampcard)) {
        $this->Flash->success(__('スタンプカード情報を更新しました。'));

        return $this->redirect(['action' => 'index']);
      }
      $this->Flash->error(__('スタンプカード情報を更新出来ませんでした。'));
    }
    $this->set(compact('stampcard', 'view_url','roleId'));
  }

    // 店舗QRcode読み取り
    public function stampQrConfirm($id = null)
    {
      $id = $this->request->getParam('pass');
      $find_id = $this->ChildStampcards->find('all')->where(['id IN' => $id])->firstOrFail();
      // IDが存在しない場合、リダイレクト
      if ($find_id) {
        $this->Flash->error(__('スタンプカードがが存在しないか、公開用スタンプカードが削除済みの為、使用出来ません。'));
        return $this->redirect(['controller' => 'Stampcards', 'action' => 'index']);
      }

      $child_stampcard = $this->ChildStampcards->get($id, [
        'contain' => [
          'ChildStampcardRewords',
          'Stampcards',
          'Stampcards.StampcardShops',
          'Stampcards.StampcardShops.Shops',
          'Stampcards.StampcardRewords'
        ],
      ]);

      if ($child_stampcard->stampcard->release_id == 2) {
        $this->Flash->error(__('このクーポンは非公開の為、使用出来ません。'));
        return $this->redirect(['controller' => 'Stampcards', 'action' => 'index']);
      }

      if (empty($this->request->getData()) && $child_stampcard->token != $this->request->getQuery('param')) {
        $this->Flash->error(__('不正パラメータです。'));
        return $this->redirect(['controller' => 'Stampcards', 'action' => 'index']);
      }

      $shops = $this->MyuserShops->my_user_list($this->Auth->user('id'));
      $user_shop = [];
      $stamp_shop = [];
      foreach ($shops as $shop) {
        array_push($user_shop, $shop->shop->name);
      }
      foreach ($child_stampcard->stampcard->stampcard_shops as $stampcard_shop) {
        array_push($stamp_shop, $stampcard_shop->shop->name);
      }

      // 読み取りユーザーの所属店舗判定
      $shop_check = array_intersect($user_shop, $stamp_shop);
      if (empty($shop_check)) {
        $this->Flash->error(__('このユーザーは対象店舗に所属していません。'));
        return $this->redirect(['controller' => 'Myusers', 'action' => 'login']);
      }

      // 使用済みflg
      if ($child_stampcard->child_stampcard_rewords[0]->state_id == 1) {
        $state_flg = 1;
        $this->set(compact('state_flg'));
      }

      // ゴールflg
      if ($child_stampcard->stampcard->max_limit == $child_stampcard->limit_count) {
        $change_flg = 1;
        $this->set(compact('change_flg'));
      }

      // post
      if ($this->request->is(['patch', 'post', 'put'])) {
        $count = $child_stampcard->stampcard->max_limit - $child_stampcard->limit_count;
        if ($count >= $this->request->getData('limit_count')) { //スタンプ数の上限判定

          $list = [];
          foreach ($child_stampcard->stampcard->stampcard_shops as $shop_data) {
            $list[] += $shop_data->shop_id;
          }

          $url = Router::url(['controller' => 'Stampcards', 'action' => 'stampQrConfirm', $child_stampcard->id, '?' => ['param' => $child_stampcard->token]], true);
          // スタンプカード裏面の店舗用
          $shop_list =$this->Shops->all_shop_record($list);
          // 現在のスタンプ数 + 付与するスタンプ数
          $total_count = $child_stampcard->limit_count + $this->request->getData('limit_count');
          // ゴール数 - 現在のスタンプ数
          $next_reword = $child_stampcard->stampcard->max_limit - $total_count;

          // 引き換え
          if ($this->request->getData('change') == 1) {
            $data = ['state_id' => 1];
            $child_stampcard_reword = $child_stampcard->child_stampcard_rewords[0];
            $child_stampcard_reword_entity = $this->ChildStampcardRewords->patchEntity($child_stampcard_reword, $data);
            $this->ChildStampcardRewords->save($child_stampcard_reword_entity);
          }

          $this->request = $this->request->withData('limit_count', $total_count);
          $child_stampcard = $this->ChildStampcards->patchEntity($child_stampcard, $this->request->getData());
          $this->ChildStampcards->save($child_stampcard);

          $this->add_stampcard(
            $child_stampcard->serial_number,
            $child_stampcard->authentication_token,
            $url,
            $child_stampcard->stampcard->longitude,
            $child_stampcard->stampcard->latitude,
            $child_stampcard->stampcard->relevant_text,
            $child_stampcard->stampcard->title,
            $child_stampcard->stampcard->foreground_color,
            $child_stampcard->stampcard->background_color,
            $child_stampcard->stampcard['stampcard_rewords']['0']['reword'],
            $child_stampcard->stampcard->content,
            $next_reword,
            $child_stampcard->stampcard->before_expiry_date,
            $child_stampcard->stampcard->after_expiry_date,
            $shop_list,
            $child_stampcard->stampcard->max_limit,
            $child_stampcard->limit_count,
            $child_stampcard->child_stampcard_rewords[0]->state_id
          );

          $file =  file(TMP.'pkpass_path/path_file');
          $this->request = $this->request->withData('dir_path', $file[0]);
          $child_stampcard = $this->ChildStampcards->patchEntity($child_stampcard, $this->request->getData());
            if ($this->ChildStampcards->save($child_stampcard)) {
              // file copy
              $from = TMP.'pkpass_path/'.$child_stampcard->dir_path."/pass.pkpass";
              $to = MNT.$child_stampcard->serial_number.'/pass.pkpass';
              copy($from, $to);
              $this->Flash->success(__('更新しました。'));

              if ($child_stampcard->limit_count == $child_stampcard->stampcard->max_limit) {
                return $this->redirect(['action' => 'stampQrConfirm', $child_stampcard->id, '?' => ['param' => $child_stampcard->token]]);
              } else {
                return $this->redirect(['action' => 'index']);
              }
            }
        } else {
          $this->Flash->error(__('付与するスタンプ数が上限を超えています。'));
        }
      }
      $this->set(compact('child_stampcard'));
    }

    /*
    * クーポンビュー
    */
    public function qrcode($id = null)
    {
      if (!AuthService::authCheck($this)) {
        return null;
      }

      $authUser = $this->Myusers->get($this->Auth->user('id'));

      $child_stampcard = $this->ChildStampcards->newEntity();
      $child_stampcard_reword = $this->ChildStampcardRewords->newEntity();

      $stampcard = $this->Stampcards->find()
        ->where(['id' => $id])
        ->first();

      if (!$stampcard) {
        $this->Flash->error(__('スタンプカードが既に削除されているか存在しません。'));
        AuthService::redirect($this, $authUser->toArray());
        return;
      }

      $stampcard = $this->Stampcards->get($id, [
        'contain' => [
          'StampcardShops',
          'StampcardShops.Shops',
          'StampcardRewords',
        ],
      ]);

      // 非公開時、リダイレクト
      if ($stampcard->release_id == 2) {
        if ($authUser['role_id'] == Myuser::ROLE_AFFILIATER) {
          AuthService::redirect($this, $authUser->toArray());
          $this->Flash->error(__('非公開のスタンプカードです。'));
          return null;
        }

        if (!AuthService::sameAuthUserCompanyCheck($this, $stampcard->company_id)) {
          $this->Flash->error(__('公開設定が非公開です。'));
          return null;
        }

        return $this->redirect(['action' => 'edit', $stampcard->id]);
      }

      if ($stampcard->token != $this->request->getQuery('param')) {
        $this->Flash->error(__('不正パラメータです。'));
        return $this->redirect(['action' => 'index']);
      }

        $view_url = Router::url(['controller' => 'Stampcards', 'action' => 'qrcode', $stampcard->id, '?' => ['param' => $stampcard->token, 'openExternalBrowser'=> 1]], true);
        $view_url_download = Router::url(['controller' => 'Stampcards', 'action' => 'downloadStamp', $stampcard->id, '?' => ['param' => $stampcard->token, 'openExternalBrowser'=> 1]], true);
        
        $list = [];
        foreach ($stampcard->stampcard_shops as $shop_data) {
          $list[] += $shop_data->shop_id;
        }

        $shop_list =$this->Shops->all_shop_record($list);

       // QRcode読み込み時
       /*if ($this->request->isMobile()) {

        $serial_number = $this->make_rand_str(12);
        $authentication_token = $this->make_rand_str(33);
        $next_reword = $stampcard->max_limit -1;

        $child_stamp_data = [
          'parent_id' => $stampcard->id,
          'serial_number' => $serial_number,
          'authentication_token' => $authentication_token,
          'limit_count' => '1',
          'dir_path' => 'null',
          'token' => uniqid('', true),
        ];

        $child_stampcard_entity = $this->ChildStampcards->patchEntity($child_stampcard, $child_stamp_data);
        $this->ChildStampcards->save($child_stampcard_entity);

        $url = Router::url(['controller' => 'Stampcards', 'action' => 'stampQrConfirm', $child_stampcard_entity->id, '?' => ['param' => $child_stampcard_entity->token, 'openExternalBrowser'=> 1]], true);

        // スタンプカード作成
        $this->add_stampcard(
          $child_stampcard_entity->serial_number,
          $child_stampcard_entity->authentication_token,
          $url,
          $stampcard->longitude,
          $stampcard->latitude,
          $stampcard->relevant_text,
          $stampcard->title,
          $stampcard->foreground_color,
          $stampcard->background_color,
          $stampcard['stampcard_rewords']['0']['reword'],
          $stampcard->content,
          $next_reword,
          $stampcard->before_expiry_date,
          $stampcard->after_expiry_date,
          $shop_list,
          $stampcard->max_limit,
          '1', // count 1
          '2'  // state_id 2 未使用
        );

        $file =  file(TMP.'pkpass_path/path_file');
        // スタンプ発行後、ファイルパスを保存
        $child_stamp_data = [
          'parent_id' => $stampcard->id,
          'dir_path' => $file[0]
        ];

        $child_stampcard_entity = $this->ChildStampcards->patchEntity($child_stampcard, $child_stamp_data);
        $this->ChildStampcards->save($child_stampcard_entity);

        $child_stampcard_reword_data = [
          'parent_id' => $stampcard->id,
          'child_id' => $child_stampcard_entity->id,
          'reword_point' => $stampcard->max_limit,
          'state_id' => '2' // 未使用
        ];


        $child_stampcard_reword_entity = $this->ChildStampcardRewords->patchEntity($child_stampcard_reword, $child_stampcard_reword_data);
        $this->ChildStampcardRewords->save($child_stampcard_reword_entity);

        // ディレクトリがなければ作成
        $check_dir = MNT.$child_stampcard_entity->serial_number;
        if (!is_dir($check_dir)) {
          mkdir($check_dir, true);
          chmod($check_dir, 0777);
        }

        // file copy
        $from = TMP.'pkpass_path/'.$child_stampcard_entity->dir_path."/pass.pkpass";
        $to = MNT.$child_stampcard_entity->serial_number.'/pass.pkpass';
        copy($from, $to);

        // file view
        $mime_type = "application/vnd.apple.pkpass";
        $file_path = MNT.$child_stampcard_entity->serial_number."/pass.pkpass";
        Header("Content-Type: $mime_type");
        readfile($file_path);
        exit;
      }*/

    $this->set(compact('stampcard', 'shop_list', 'view_url', 'view_url_download','authUser'));
    }

    public function downloadStamp($id = null)
    {
      if (!AuthService::authCheck($this)) {
        return null;
      }

      $authUser = $this->Myusers->get($this->Auth->user('id'));

      $child_stampcard = $this->ChildStampcards->newEntity();
      $child_stampcard_reword = $this->ChildStampcardRewords->newEntity();

      $stampcard = $this->Stampcards->find()
        ->where(['id' => $id])
        ->first();

      if (!$stampcard) {
        $this->Flash->error(__('スタンプカードが既に削除されているか存在しません。'));
        AuthService::redirect($this, $authUser->toArray());
        return;
      }

      $stampcard = $this->Stampcards->get($id, [
        'contain' => [
          'StampcardShops',
          'StampcardShops.Shops',
          'StampcardRewords',
        ],
      ]);

      // 非公開時、リダイレクト
      if ($stampcard->release_id == 2) {
        if ($authUser['role_id'] == Myuser::ROLE_AFFILIATER) {
          AuthService::redirect($this, $authUser->toArray());
          $this->Flash->error(__('非公開のスタンプカードです。'));
          return null;
        }

        if (!AuthService::sameAuthUserCompanyCheck($this, $stampcard->company_id)) {
          $this->Flash->error(__('公開設定が非公開です。'));
          return null;
        }

        return $this->redirect(['action' => 'edit', $stampcard->id]);
      }

      if ($stampcard->token != $this->request->getQuery('param')) {
        $this->Flash->error(__('不正パラメータです。'));
        return $this->redirect(['action' => 'index']);
      }

        $view_url = Router::url(['controller' => 'Stampcards', 'action' => 'qrcode', $stampcard->id, '?' => ['param' => $stampcard->token, 'openExternalBrowser'=> 1]], true);
        $view_url_download = Router::url(['controller' => 'Stampcards', 'action' => 'downloadStamp', $stampcard->id, '?' => ['param' => $stampcard->token, 'openExternalBrowser'=> 1]], true);
        
        $list = [];
        foreach ($stampcard->stampcard_shops as $shop_data) {
          $list[] += $shop_data->shop_id;
        }

        $shop_list =$this->Shops->all_shop_record($list);

       // QRcode読み込み時

        $serial_number = $this->make_rand_str(12);
        $authentication_token = $this->make_rand_str(33);
        $next_reword = $stampcard->max_limit -1;

        $child_stamp_data = [
          'parent_id' => $stampcard->id,
          'serial_number' => $serial_number,
          'authentication_token' => $authentication_token,
          'limit_count' => '1',
          'dir_path' => 'null',
          'token' => uniqid('', true),
        ];

        $child_stampcard_entity = $this->ChildStampcards->patchEntity($child_stampcard, $child_stamp_data);
        $this->ChildStampcards->save($child_stampcard_entity);

        $url = Router::url(['controller' => 'Stampcards', 'action' => 'stampQrConfirm', $child_stampcard_entity->id, '?' => ['param' => $child_stampcard_entity->token, 'openExternalBrowser'=> 1]], true);

        // スタンプカード作成
        $this->add_stampcard(
          $child_stampcard_entity->serial_number,
          $child_stampcard_entity->authentication_token,
          $url,
          $stampcard->longitude,
          $stampcard->latitude,
          $stampcard->relevant_text,
          $stampcard->title,
          $stampcard->foreground_color,
          $stampcard->background_color,
          $stampcard['stampcard_rewords']['0']['reword'],
          $stampcard->content,
          $next_reword,
          $stampcard->before_expiry_date,
          $stampcard->after_expiry_date,
          $shop_list,
          $stampcard->max_limit,
          '1', // count 1
          '2'  // state_id 2 未使用
        );

        $file =  file(TMP.'pkpass_path/path_file');
        // スタンプ発行後、ファイルパスを保存
        $child_stamp_data = [
          'parent_id' => $stampcard->id,
          'dir_path' => $file[0]
        ];

        $child_stampcard_entity = $this->ChildStampcards->patchEntity($child_stampcard, $child_stamp_data);
        $this->ChildStampcards->save($child_stampcard_entity);

        $child_stampcard_reword_data = [
          'parent_id' => $stampcard->id,
          'child_id' => $child_stampcard_entity->id,
          'reword_point' => $stampcard->max_limit,
          'state_id' => '2' // 未使用
        ];


        $child_stampcard_reword_entity = $this->ChildStampcardRewords->patchEntity($child_stampcard_reword, $child_stampcard_reword_data);
        $this->ChildStampcardRewords->save($child_stampcard_reword_entity);

        // ディレクトリがなければ作成
        $check_dir = MNT.$child_stampcard_entity->serial_number;
        if (!is_dir($check_dir)) {
          mkdir($check_dir, true);
          chmod($check_dir, 0777);
        }

        // file copy
        $from = TMP.'pkpass_path/'.$child_stampcard_entity->dir_path."/pass.pkpass";
        $to = MNT.$child_stampcard_entity->serial_number.'/pass.pkpass';
        copy($from, $to);

        // file view
        $mime_type = "application/vnd.apple.pkpass";
        $file_path = MNT.$child_stampcard_entity->serial_number."/pass.pkpass";
        Header("Content-Type: $mime_type");
        readfile($file_path);
        exit;

    $this->set(compact('stampcard', 'shop_list', 'view_url', 'view_url_download'));
    }

    ###############
    #  関数リスト #
    ###############

    /* 擬似バリデーション
    *
    */
    private function add_background_value()
    {
      $i = 1;
      $length = count($this->request->getData());
      $color_array = ['white', 'black', 'red', 'blue', 'green', 'yellow', 'orange', 'purple', 'pink', 'brown'];
      if ($this->request->getData('foreground_color') == 'null' && $this->request->getData('background_color') == 'null') {
        foreach ($this->request->getData() as $key => $value) {
          if ($value == '0' && in_array($key, $color_array)) {
            if ($key == 'white') {
              $foreground_color = '51,51,51';
              $background_color = '224,224,224';
              $this->set(compact('foreground_color', 'background_color'));
              break;
            } elseif ($key == 'black') {
              $foreground_color = '255,255,255';
              $background_color = '51,51,51';
              $this->set(compact('foreground_color', 'background_color'));
              break;
            } elseif ($key == 'red') {
              $foreground_color = '255,255,255';
              $background_color = '213,80,80';
              $this->set(compact('foreground_color', 'background_color'));
              break;
            } elseif ($key == 'blue') {
              $foreground_color = '255,255,255';
              $background_color = '62,122,211';
              $this->set(compact('foreground_color', 'background_color'));
              break;
            } elseif ($key == 'green') {
              $foreground_color = '255,255,255';
              $background_color = '14,198,116';
              $this->set(compact('foreground_color', 'background_color'));
              break;
            } elseif ($key == 'yellow') {
              $foreground_color = '51,51,51';
              $background_color = '245,210,28';
              $this->set(compact('foreground_color', 'background_color'));
              break;
            } elseif ($key == 'orange') {
              $foreground_color = '255,255,255';
              $background_color = '236,161,37';
              $this->set(compact('foreground_color', 'background_color'));
              break;
            } elseif ($key == 'purple') {
              $foreground_color = '255,255,255';
              $background_color = '105,58,202';
              $this->set(compact('foreground_color', 'background_color'));
              break;
            } elseif ($key == 'pink') {
              $foreground_color = '255,255,255';
              $background_color = '233,156,157';
              $this->set(compact('foreground_color', 'background_color'));
              break;
            } elseif ($key == 'brown') {
              $foreground_color = '255,255,255';
              $background_color = '112,82,63';
              $this->set(compact('foreground_color', 'background_color'));
              break;
            }
          }
          if($i == $length){
            // false
            return '0';
          }
          $i++;
        }
      }
    }

    /**
     * ランダム文字列生成 (英数字,記号)
     * $length: 生成する文字数
     */
    private function make_rand_str($length) {
      $str = array_merge(range('0', '9'));
      $r_str = null;
      for ($i = 0; $i < $length; $i++) {
        $r_str .= $str[rand(0, count($str) - 1)];
      }
      return $r_str;
    }

    /* コアファイル:vendor/pkpass/pkpass/src/PKPass.php
     * pkpass作成
     */
    private function add_stampcard(
      // シリアル番号
      $serial_number,
      // 照合トークン
      $authentication_token,
      // 舗読み取り時URL
      $admin_view,
      // 緯度
      $longitude,
      // 経度
      $latitude,
      // 通知メッセージ
      $relevant_text,
      $title,
      $foreground_color,
      $background_color,
      $reword,
      $content,
      $next_reword,
      $before_expiry_date,
      $after_expiry_date,
      // 裏側に表示する店舗
      $shop_list,
      $max_limit,
      $value_count,
      $state_id
    )
    {
      $pass = new PKPassOrg(env('certificate_path'), env('keypasswd'));

      // test code
      // strcode utf-8
      $data = [
        "formatVersion" => 1,
        "passTypeIdentifier" => env('passTypeID'), // passTypeID
        "serialNumber" => $serial_number, // use update number
        "teamIdentifier" => env('teamID'), // teamID
        "webServiceURL" => env('updateURL'), // updateURLPath
        "authenticationToken" => $authentication_token, // auth number
        "barcode" => [
          "message" => $admin_view,
          "format" => "PKBarcodeFormatQR",
          "messageEncoding" => "utf-8"
        ],
        "locations" => [
          [
            "longitude" => (float)$longitude, // 経度
            "latitude" => (float)$latitude, // 緯度
            "relevantText" => $relevant_text,
          ]
        ],
        "organizationName" => "Paw Planet",
        "description" => "メゾンマークーポンタイトル",
        "logoText" => $title,
        "foregroundColor" => "rgb($foreground_color)",
        "backgroundColor" => "rgb($background_color)",
        "storeCard" => [
          "headerFields" => [
            [
              "key"  =>"content",
              "label" => $reword,
              "value" => $content
            ]
          ],
          "secondaryFields" => [
            [
              "key" => "benefits",
              "label" => "次の特典まで",
              "value" => $next_reword
            ]
          ],
          "auxiliaryFields" => [
            [
              "key" => "expires",
              "label" => "有効期限",
              "value" => date("Y/m/d", strtotime($before_expiry_date)) . "~" . date("Y/m/d", strtotime($after_expiry_date)),
            ]
          ],
          "backFields" => [
          ]
        ]
      ];

      $i = 1;
      foreach ($shop_list as $shop_data) {
        $shopname = [];
        $shopname['key'] = 'shopname'.$i;
        $shopname['label'] = '店舗名';
        $shopname['value'] = $shop_data->name;

        array_push($data['storeCard']['backFields'], $shopname);

        $homepage = [];
        $homepage['key'] = 'homepage'.$i;
        $homepage['label'] = 'Homepage';
        $homepage['value'] = $shop_data->homepage;

        array_push($data['storeCard']['backFields'], $homepage);

        $line = [];
        $line['key'] = 'line'.$i;
        $line['label'] = 'LINE';
        $line['value'] = $shop_data->line;

        array_push($data['storeCard']['backFields'], $line);

        $twitter = [];
        $twitter['key'] = 'twitter'.$i;
        $twitter['label'] = 'Twitter';
        $twitter['value'] = $shop_data->twitter;

        array_push($data['storeCard']['backFields'], $twitter);

        $facebook = [];
        $facebook['key'] = 'facebook'.$i;
        $facebook['label'] = 'FaceBook';
        $facebook['value'] = $shop_data->facebook;

        array_push($data['storeCard']['backFields'], $facebook);

        $instagram = [];
        $instagram['key'] = 'instagram'.$i;
        $instagram['label'] = 'instagram';
        $instagram['value'] = $shop_data->instagram;

        array_push($data['storeCard']['backFields'], $instagram);
        $i++;
      }

      // DocumentRoot:webroot

      if (file_exists('/var/www/html/fam-wallet/webroot/img/shop_images/'.$shop_list[0]['id'].'/icon.png')) {
        $pass->addFile('/var/www/html/fam-wallet/webroot/img/shop_images/'.$shop_list[0]['id'].'/icon.png');
        $pass->addFile('/var/www/html/fam-wallet/webroot/img/shop_images/'.$shop_list[0]['id'].'/logo.png');
      } else {
        $pass->addFile('/var/www/html/fam-wallet/src/wallet_resource/StoreCard/icon.png');
        $pass->addFile('/var/www/html/fam-wallet/src/wallet_resource/StoreCard/icon@2x.png');
        $pass->addFile('/var/www/html/fam-wallet/src/wallet_resource/StoreCard/logo.png');
      }
      if ($state_id == 1) {
        $pass->addFile('/var/www/html/fam-wallet/src/wallet_resource/StoreCard/strip/strip-used/strip.png');
      } else {
        $pass->addFile('/var/www/html/fam-wallet/src/wallet_resource/StoreCard/strip/strip'.$max_limit.'/'.$value_count.'/strip.png');
      }

      $pass->setData($data);

      // debug
      if(!$pass->create(true)) {
        echo 'Error: ' . $pass->getError();
      }
    }

  private function result_longitude_latitude($request_address)
  {
    mb_language("Japanese");//文字コードの設定
    mb_internal_encoding("UTF-8");

    //住所を入れて緯度経度を求める。
    $address = $request_address;
    $myKey = 'AIzaSyAKVUIqxiVHeWhNGyKUIb2mwDgEYXNR4GQ';
    $address = urlencode($address);

    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $address . "&language=ja&region=JP" . "+CA&key=" . $myKey ;

    $contents= file_get_contents($url);
    $jsonData = json_decode($contents,true);

    if (!$jsonData["results"]) {
      throw new ValidationException('住所から位置情報を取得できませんでした');
    }

    $lat = $jsonData["results"][0]["geometry"]["location"]["lat"];
    $lng = $jsonData["results"][0]["geometry"]["location"]["lng"];

    return [$lat, $lng];
  }

}
