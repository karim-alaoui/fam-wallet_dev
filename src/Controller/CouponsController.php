<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\AffiliaterCoupon;
use App\Model\Entity\Coupon;
use App\Model\Entity\Myuser;
use App\Model\Table\AffiliaterCouponsTable;
use App\Model\Table\CouponsTable;
use App\Model\Table\MyusersTable;
use App\Services\AuthService;
use App\Services\CouponService;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;
use App\Controller\Component\PKpassOrg;
use App\Form\CouponForm;
use Cake\Datasource\ConnectionManager;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\Routing\Router;
use Cake\Event\Event;
use http\Header;
use JsonSchema\Exception\ValidationException;

/**
 * Class CouponsController
 * @package App\Controller
 */
class CouponsController extends AppController
{
  /**
  * @var MyusersTable
  */
  private $Myusers;

    /**
     * @var AffiliaterCouponsTable
     */
  private $AffiliaterCoupons;

  /**
   * @var CouponsTable
   */
  private $Coupons;

  public function beforeFilter(Event $event)
  {
    parent::beforeFilter($event);
//    $this->Auth->allow(['qrcode']);
  }

  public function initialize()
  {
    parent::initialize();
    // テーブル呼び出し
    $this->MyuserShops = TableRegistry::get('MyuserShops');
    $this->Shops = TableRegistry::get('Shops');
    $this->CouponShops = TableRegistry::get('CouponShops');
    $this->ChildCoupons = TableRegistry::get('ChildCoupons');
    $this->Releases = TableRegistry::get('ReleaseStates');
    $this->Myusers = TableRegistry::get('Myusers');
    $this->AffiliaterCoupons = TableRegistry::get('AffiliaterCoupons');
    $this->Coupons = TableRegistry::get('Coupons');
  }

  // 一覧
  public function index()
  {
    if (!AuthService::authCheck($this)) {
      return null;
    }

      // 配列で整形
      $shop_list = [];
      if ($this->Auth->User('role_id') == Myuser::ROLE_AFFILIATER) {
          $this->redirect('/affiliater-coupons');
            return;
      } else if ($this->Auth->User('role_id') == Myuser::ROLE_OWNER) {

          // 店舗一覧
          $shops = $this->Shops->shop_index($this->Auth->User('company_id'));

          $shop_list = ['0' => '全所属店舗'];
          foreach ($shops as $key) {
              $shop_list[$key->id] = $key->name;
          }
          // 公開,非公開,全てのレコード
          list($release_record, $private_record, $result_record) = $this->Coupons->total_release_record($this->Auth->User('company_id'));
      } else {
          $shops = $this->MyuserShops->find('all')->contain(['Shops'])->where(['myuser_id' => $this->Auth->User('id')]);

          if ($shops->count()) {

              foreach ($shops as $key) {
                  $shop_list[$key->shop_id] = $key->shop->name;
              }

              $tmp_shop_id = [];
              // 所属してる店舗IDを抽出
              foreach ($shops as $key) {
                  array_push($tmp_shop_id, $key->shop_id);
              }
              // 店舗IDからcoupon_idを抽出
              $tmp_coupon_shop_id = $this->CouponShops->find('all')->where(['shop_id IN' => $tmp_shop_id]);
              $tmp_coupon_id = [];
              // coupon_id抽出

              foreach ($tmp_coupon_shop_id as $key) {
                  array_push($tmp_coupon_id, $key->coupon_id);
              }
              $result_record = $this->Coupons->find('all')
                  ->where(['id IN' => $tmp_coupon_id])
                  ->contain(['ChildCoupons', 'CouponShops', 'CouponShops.Shops', 'AffiliaterChildCoupons']);
              $release_record = $this->Coupons->find('all')
                  ->where(['id IN' => $tmp_coupon_id, 'release_id' => 1])
                  ->contain(['ChildCoupons', 'CouponShops', 'CouponShops.Shops'])->count();
              $private_record = $this->Coupons->find('all')
                  ->where(['id IN' => $tmp_coupon_id, 'release_id' => 2])
                  ->contain(['ChildCoupons', 'CouponShops', 'CouponShops.Shops'])->count();
          }
      }

      // 店舗検索
      if ($this->request->is('post')) {
          // 全店舗
          if ($this->request->getData('shop') == 0) {
              list($release_record, $private_record, $result_record) = $this->Coupons->total_release_record($this->Auth->User('company_id'));
          } else {
              $shop_record = $this->CouponShops->find('all')->where(
                  ['shop_id' => $this->request->getData('shop')]
              )->select(['coupon_id']);

              list($release_record, $private_record, $result_record) = $this->Coupons->search_record($this->Auth->User('company_id'), $shop_record);
          }
      }
      $this->set(compact('shop_list', 'release_record', 'private_record', 'result_record', 'shops'));
  }

  // 新規作成
  public function new()
  {
    if (!AuthService::authCheck($this, [
      Myuser::ROLE_OWNER,
      Myuser::ROLE_LEADER,
      Myuser::ROLE_MEMBER
    ])) {
      return null;
    }

    // 利用回数
    $count_list = [];
    for ($i = 0; $i <= 10; $i++) {
      if ($i == 0) {
        $count_list['無制限'] = '無制限';
      } else {
        $count_list[$i] = $i;
      }
    }

    $user = $this->Auth->user();
    $roleId = $this->Auth->user('role_id');
    // 店舗リスト
    // オーナー
    if ($roleId == Myuser::ROLE_OWNER) {
      // ログインIDが所属している店舗レコードを抽出
      $shop_list = $this->Shops->shop_record_array($user['company_id']);
    }

    if($roleId != Myuser::ROLE_OWNER && $roleId == Myuser::ROLE_LEADER) {
        $myuser_shop_list = $this->MyuserShops
            ->find('all')
            ->contain(['Shops'])
            ->where(['myuser_id' => $this->Auth->User('id')]);
        $shop_list = [];
        foreach ($myuser_shop_list as $key) {
            $shop_list[$key->shop_id] = $key->shop->name;
        }
    }

    // 店舗登録0 リダイレクト
    if (empty($shop_list)) {
      $this->Flash->error('クーポン発行には店舗登録が必要です。');
      return $this->redirect(['controller' => 'Shops', 'action' => 'new']);
    }

    $coupon = $this->Coupons->newEntity();
    $coupon_shop = $this->CouponShops->newEntity();
    $coupon_validate = new CouponForm();
    $this->request = $this->request->withData('token', uniqid('', true));
    $coupon_entity = null;

    $affiliaters = $this->getAffiliaters();
    $this->set(compact('affiliaters'));

    if ($this->request->is('post')) {
      // バリデーションチェック
      /**
       * @var $coupon_entity EntityInterface
       */
      $coupon_entity = $this->Coupons->patchEntity($coupon, $this->request->getData());

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

      $data = $this->request->getData();
//      if (isset($data['back'])) {
//          $this->request->withoutData('back');
//          $this->render("new");
//          return;
//      }

      if ($coupon_validate->execute($this->request->getData())
          && $coupon_entity->hasErrors() == false
          && empty($shop_error)
          && empty($color_error)
          && empty($address_error)
      ) {
        // 確認画面
        if ($this->request->getData('mode') == 'confirm' ) {
          // 一時保存
//          $after_save_data = $this->request->getData('shop_id');
          // shop_modelから呼び出し
          $shop_result_array = $this->Shops->add_record_array($this->request->getData('shop_id'), 'id', 'name');
          $this->set(compact('shop_result_array'));

          if (!empty($this->request->getData('address'))) {
            list($lat, $lng) = $this->result_longitude_latitude($this->request->getData('address'));
            $this->set(compact('lat', 'lng'));
          }

            if(intval($this->request->getData('rate'))) {
                $is_affiliate = 1;
            } else {
                $is_affiliate = 0;
            }
            $this->set(compact('is_affiliate'));

          $this->render("confirm");

        } elseif (isset($data['add'])) { // 確認画面から保存
          $connection = ConnectionManager::get('default');
          // トランザクション開始
          $connection->begin();

          try {
            if ($this->Coupons->save($coupon_entity)) {
              $shop_save_data = [];
              $wallet_shops = [];

              foreach ($this->request->getData('after_save_data') as $shop_id) {
                $tmp_array = [];
                $tmp_array['shop_id'] = $shop_id;
                $tmp_array['coupon_id'] = $coupon_entity->id;

                array_push($shop_save_data, $tmp_array);
                $wallet_shops[] += $shop_id;
              }

              $coupon_shop_entity = $this->CouponShops->patchEntities($coupon_shop, $shop_save_data);
              $this->CouponShops->saveMany($coupon_shop_entity);
              $shop_list =$this->Shops->all_shop_record($wallet_shops);

//              $this->attachAffiliaterCoupons($coupon_entity);

              $connection->commit();

              $this->Flash->success('新規クーポンを作成しました。');
              return $this->redirect(['action' => 'index']);
            } else {
              $this->Flash->success('新規クーポンを作成できませんでした。');
              throw new \Exception(Configure::read("M.ERROR.INVALID"));
            }
          } catch(\Exception $e) {
            // ロールバック
            $this->Flash->success($e->getMessage());
          }
        }
      } else {
        $this->Flash->error('入力データにエラーがあります');
      }
    }
    if($coupon_entity) {
        $this->set(compact('coupon', 'shop_list', 'count_list', 'coupon_validate', 'coupon_entity'));
    } else {
        $this->set(compact('coupon', 'shop_list', 'count_list', 'coupon_validate'));
    }
  }

  // 編集
  public function edit($id = null)
  {
    if (!$this->accessCheck($id)) {
      $authUser = $this->Myusers->get($this->Auth->user('id'));
      AuthService::redirect($this, $authUser->toArray());
      return null;
    }

    $roleId = $this->Myusers->get($this->Auth->user('id'))->role_id;

    $coupon = $this->Coupons->get($id, [
      'contain' => ['ChildCoupons', 'CouponShops', 'CouponShops.Shops', 'AffiliaterCoupons.Myusers'],
    ]);

    if (!AuthService::sameAuthUserCompanyCheck($this, $coupon->company_id)) {
      return null;
    }

    $view_url = Router::url(['controller' => 'Coupons', 'action' => 'qrcode', $coupon->id, '?' => ['param' => $coupon->token, 'openExternalBrowser'=> 1]], true);

    if ($this->request->is(['patch', 'post', 'put'])) {
      $coupon = $this->Coupons->patchEntity($coupon, $this->request->getData());
      if ($this->Coupons->save($coupon)) {
        $this->Flash->success(__('クーポンを情報を更新しました。'));

        return $this->redirect(['action' => 'index']);
      }
      $this->Flash->error(__('クーポン情報を更新出来ませんでした。'));
    }
    $this->set(compact('coupon', 'view_url', 'roleId'));
  }

  // 削除
  public function delete($id = null)
  {
    if (!$this->accessCheck($id)) {
      $authUser = $this->Myusers->get($this->Auth->user('id'));
      AuthService::redirect($this, $authUser->toArray());
      return null;
    }

    $this->request->allowMethod(['post', 'delete']);
    $coupon = $this->Coupons->get($id);
    if ($this->Coupons->delete($coupon)) {
      $this->Flash->success(__('クーポンを削除しました。'));
    } else {
      $this->Flash->error(__('クーポンを削除出来ませんでした。'));
    }

    return $this->redirect(['action' => 'index']);
  }

  public function couponQrConfirm($id = null)
  {
      if ($this->Auth->User('role_id') == Myuser::ROLE_AFFILIATER) {
          $this->Flash->error('クーポン読み込み権限がありません。');
          $this->redirect('/affiliater-coupons');
          return;
      }


    $id = $this->request->getParam('pass');

    $find_id = $this->ChildCoupons->find('all')->where(['id IN' => $id])->firstOrFail();
    // IDが存在しない場合、リダイレクト
    if (!$find_id) {
      $this->Flash->error('クーポンが存在しないか、公開クーポンが削除済みの為、使用出来ません。');
      return $this->redirect(['controller' => 'Coupons', 'action' => 'index']);
    }

    $child_coupon = $this->ChildCoupons->get($id, [
      'contain' => [
        'Coupons',
        'Coupons.CouponShops',
        'Coupons.CouponShops.Shops'
      ],
    ]);

    // 非公開時、リダイレクト
    if ($child_coupon->coupon->release_id == 2) {
      $this->Flash->error(__('このクーポンは非公開の為、使用出来ません。'));
      return $this->redirect(['controller' => 'Coupons', 'action' => 'index']);
    }
    if (empty($this->request->getData()) && $child_coupon->token != $this->request->getQuery('param')) {
      $this->Flash->error(__('不正パラメータです。'));
      return $this->redirect(['controller' => 'Coupons', 'action' => 'index']);
    }

    $shops = $this->MyuserShops->my_user_list($this->Auth->user('id'));
    $user_shop = [];
    $coupon_shops = [];
    foreach ($shops as $shop) {
      array_push($user_shop, $shop->shop->name);
    }
    foreach ($child_coupon->coupon->coupon_shops as $coupon_shop) {
      array_push($coupon_shops, $coupon_shop->shop->name);
    }
    // 読み取りユーザーの所属店舗判定
//    $shop_check = array_intersect($user_shop, $coupon_shops);
//    if (empty($shop_check)) {
//      $this->Flash->error(__('このユーザーは対象店舗に所属していません。'));
//      return $this->redirect(['controller' => 'Myusers', 'action' => 'login']);
//    }

    if ($child_coupon->limit_count >= $child_coupon->coupon->limit && $child_coupon->coupon->limit != '無制限') {
      $state_flg = 1;
      $this->set(compact('state_flg'));
    }


    $list = [];
    foreach ($child_coupon->coupon->coupon_shops as $shop_data) {
      $list[] += $shop_data->shop_id;
    }

    $shop_list =$this->Shops->all_shop_record($list);

    if ($this->request->is(['patch', 'post', 'put'])) {
      if ($this->request->getData('limit_count') == true) {
        $count_up = $child_coupon->limit_count + 1;
        $this->request = $this->request->withData('limit_count', $count_up);
      }
      $child_coupon_entity = $this->ChildCoupons->patchEntity($child_coupon, $this->request->getData());
      $this->ChildCoupons->save($child_coupon_entity);

      #return $this->redirect(['action' => 'couponQrConfirm', $child_coupon->id]);

        $couponUser = $this->Myusers->get($this->request->getQuery('user_id'));
        if ($couponUser->role_id == Myuser::ROLE_AFFILIATER) {
            $res = (new CouponService())->useCoupon($couponUser->id, $child_coupon->coupon);

            if(!$res) {
                $this->Flash->error('クーポンの使用に失敗しました。');
                return;
            }
        }

      $this->Flash->success(__('クーポンを使用しました。'));
      return $this->redirect(['action' => 'index']);
    }

    $this->set(compact('child_coupon', 'coupon_shops', 'user_shop'));
  }

  // クーポンビュー
  public function qrcode($id = null)
  {
    if (!AuthService::authCheck($this)) {
      return null;
    }

    $authUser = $this->Myusers->get($this->Auth->user('id'));
    $child_coupon = $this->ChildCoupons->newEntity();

    $coupon = $this->Coupons->find()
      ->where(['id' => $id])
      ->first();

    if (!$coupon) {
      $this->Flash->error(__('クーポンが既に削除されているか存在しません。'));
      AuthService::redirect($this, $authUser->toArray());
      return;
    }

    $coupon = $this->Coupons->get($id, [
      'contain' => ['CouponShops', 'CouponShops.Shops'],
    ]);

    // 非公開時、リダイレクト
    if ($coupon->release_id == 2) {
      if ($authUser['role_id'] == Myuser::ROLE_AFFILIATER) {
        AuthService::redirect($this, $authUser->toArray());
        $this->Flash->error(__('非公開のクーポンです。'));
        return null;
      }

      if (!AuthService::sameAuthUserCompanyCheck($this, $coupon->company_id)) {
        $this->Flash->error(__('公開設定が非公開です。'));
        return null;
      }

      return $this->redirect(['action' => 'edit', $coupon->id]);
    }

    if ($coupon->token != $this->request->getQuery('param')) {
      $this->Flash->error(__('不正パラメータです。'));
      return $this->redirect(['action' => 'index']);
    }

    $view_url = Router::url(['controller' => 'Coupons', 'action' => 'qrcode', $coupon->id, '?' => ['param' => $coupon->token, 'openExternalBrowser'=> 1]], true);
    $view_url_download = Router::url(['controller' => 'Coupons', 'action' => 'downloadCoupon', $coupon->id, '?' => ['param' => $coupon->token, 'openExternalBrowser'=> 1]], true);

    $list = [];
    foreach ($coupon->coupon_shops as $shop_data) {
      $list[] += $shop_data->shop_id;
    }

    $shop_list =$this->Shops->all_shop_record($list);

    // QRcode読み込み時
    /*if ($this->request->isMobile()) {

      $serial_number = $this->make_rand_str(12);
      $authentication_token = $this->make_rand_str(33);

      $data = [
        'parent_id' => $coupon->id,
        'serial_number' => $serial_number,
        'authentication_token' => $authentication_token,
        'limit_count' => '0',
        'dir_path' => 'null',
        'token' => uniqid('', true),
      ];


      $child_coupon_entity = $this->ChildCoupons->patchEntity($child_coupon, $data);
      $this->ChildCoupons->save($child_coupon_entity);

      $url = Router::url(['controller' => 'Coupons', 'action' => 'couponQrConfirm', $child_coupon_entity->id, '?' => ['param' => $child_coupon_entity->token, 'user_id' => $this->Auth->user('id')]], true);

      $this->add_coupon(
        $serial_number,
        $authentication_token,
        $url,
        $coupon->longitude,
        $coupon->latitude,
        $coupon->relevant_text,
        $coupon->title,
        $coupon->foreground_color,
        $coupon->background_color,
        $coupon->reword,
        $coupon->content,
        $coupon->before_expiry_date,
        $coupon->after_expiry_date,
        $shop_list
      );

      $file =  file(TMP.'pkpass_path/path_file');
      $data = [
        'id' => $child_coupon_entity->id,
        'dir_path' => $file[0]
      ];

      $child_coupon_entity = $this->ChildCoupons->patchEntity($child_coupon, $data);
      $this->ChildCoupons->save($child_coupon_entity);

      if ($this->Auth->user('role_id') == Myuser::ROLE_AFFILIATER) {
          $this->attachAffiliaterCoupons($coupon);
      }

      // ディレクトリがなければ作成
      $check_dir = MNT.$serial_number;
      if (!is_dir($check_dir)) {
        mkdir($check_dir,0777, true);
      }

      // file copy
      $from = TMP.'pkpass_path/'.$data['dir_path']."/pass.pkpass";
//      $to = MNT.$serial_number.'_pass.pkpass';
//      copy($from, $to);

      // file view
      $mime_type = "application/vnd.apple.pkpass";
//      $file_path = MNT.$serial_number."_pass.pkpass";
      header("Content-Type: $mime_type");
      readfile($from);
      exit;
    }*/

    $this->set(compact('coupon', 'shop_list', 'view_url','view_url_download','authUser'));
  }

  public function downloadCoupon($id = null)
  {
    if (!AuthService::authCheck($this)) {
      return null;
    }

    $authUser = $this->Myusers->get($this->Auth->user('id'));
    $child_coupon = $this->ChildCoupons->newEntity();

    $coupon = $this->Coupons->find()
      ->where(['id' => $id])
      ->first();

    if (!$coupon) {
      $this->Flash->error(__('クーポンが既に削除されているか存在しません。'));
      AuthService::redirect($this, $authUser->toArray());
      return;
    }

    $coupon = $this->Coupons->get($id, [
      'contain' => ['CouponShops', 'CouponShops.Shops'],
    ]);

    // 非公開時、リダイレクト
    if ($coupon->release_id == 2) {
      if ($authUser['role_id'] == Myuser::ROLE_AFFILIATER) {
        AuthService::redirect($this, $authUser->toArray());
        $this->Flash->error(__('非公開のクーポンです。'));
        return null;
      }

      if (!AuthService::sameAuthUserCompanyCheck($this, $coupon->company_id)) {
        $this->Flash->error(__('公開設定が非公開です。'));
        return null;
      }

      return $this->redirect(['action' => 'edit', $coupon->id]);
    }

    if ($coupon->token != $this->request->getQuery('param')) {
      $this->Flash->error(__('不正パラメータです。'));
      return $this->redirect(['action' => 'index']);
    }

    $view_url = Router::url(['controller' => 'Coupons', 'action' => 'qrcode', $coupon->id, '?' => ['param' => $coupon->token, 'openExternalBrowser'=> 1]], true);
    $view_url_download = Router::url(['controller' => 'Coupons', 'action' => 'downloadCoupon', $coupon->id, '?' => ['param' => $coupon->token, 'openExternalBrowser'=> 1]], true);

    $list = [];
    foreach ($coupon->coupon_shops as $shop_data) {
      $list[] += $shop_data->shop_id;
    }

    $shop_list =$this->Shops->all_shop_record($list);

    // QRcode読み込み時

      $serial_number = $this->make_rand_str(12);
      $authentication_token = $this->make_rand_str(33);

      $data = [
        'parent_id' => $coupon->id,
        'serial_number' => $serial_number,
        'authentication_token' => $authentication_token,
        'limit_count' => '0',
        'dir_path' => 'null',
        'token' => uniqid('', true),
      ];


      $child_coupon_entity = $this->ChildCoupons->patchEntity($child_coupon, $data);
      $this->ChildCoupons->save($child_coupon_entity);

      $url = Router::url(['controller' => 'Coupons', 'action' => 'couponQrConfirm', $child_coupon_entity->id, '?' => ['param' => $child_coupon_entity->token, 'user_id' => $this->Auth->user('id')]], true);

      $this->add_coupon(
        $serial_number,
        $authentication_token,
        $url,
        $coupon->longitude,
        $coupon->latitude,
        $coupon->relevant_text,
        $coupon->title,
        $coupon->foreground_color,
        $coupon->background_color,
        $coupon->reword,
        $coupon->content,
        $coupon->before_expiry_date,
        $coupon->after_expiry_date,
        $shop_list
      );

      $file =  file(TMP.'pkpass_path/path_file');
      $data = [
        'id' => $child_coupon_entity->id,
        'dir_path' => $file[0]
      ];

      $child_coupon_entity = $this->ChildCoupons->patchEntity($child_coupon, $data);
      $this->ChildCoupons->save($child_coupon_entity);

      if ($this->Auth->user('role_id') == Myuser::ROLE_AFFILIATER) {
          $this->attachAffiliaterCoupons($coupon);
      }

      // ディレクトリがなければ作成
      $check_dir = MNT.$serial_number;
      if (!is_dir($check_dir)) {
        mkdir($check_dir,0777, true);
      }

      // file copy
      $from = TMP.'pkpass_path/'.$data['dir_path']."/pass.pkpass";
//      $to = MNT.$serial_number.'_pass.pkpass';
//      copy($from, $to);

      // file view
      $mime_type = "application/vnd.apple.pkpass";
//      $file_path = MNT.$serial_number."_pass.pkpass";
      header("Content-Type: $mime_type");
      readfile($from);
      exit;

    $this->set(compact('coupon', 'shop_list', 'view_url','view_url_download'));
  }

  public function analytics()
  {
    // 店舗一覧
    $shops = $this->Shops->shop_index($this->Auth->User('company_id'));

    // 配列で整形
    $shop_list = ['0' => '全所属店舗'];
    foreach ($shops as $key) {
      $shop_list[$key->id] = $key->name;
    }

//    $coupons = $this->paginate($release_record_all, ['limit' => 10]);
    $this->set(compact('shop_list'));
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
  private function add_coupon(
    // シリアル番号
    $serial_number,
    // 照合トークン
    $authentication_token,
    // 店舗読み取り時URL
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
    $before_expiry_date,
    $after_expiry_date,
    // 裏側に表示する店舗
    $shop_list
  )
  {
    $pass = new PKpassOrg(env('certificate_path'), env('keypasswd'));
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
        "message" => "$admin_view",
        "format" => "PKBarcodeFormatQR",
          "messageEncoding" => "utf-8"
      ],
      "locations" => [
        [
          "longitude" => (float)$longitude, // 経度
          "latitude" => (float)$latitude, // 緯度
          "relevantText" => $relevant_text,
        ],
      ],
      "organizationName" => "Paw Planet",
      "description" => "メゾンマークーポンタイトル",
      "logoText" => $title,
      "foregroundColor" => "rgb($foreground_color)",
      "backgroundColor" => "rgb($background_color)",
      "coupon" => [
        "primaryFields" => [
          [
            "key"  =>"content",
            "label" => $reword,
            "value" => $content
          ]
        ],
        "auxiliaryFields" => [
          [
            "key" => "expires",
            "label" => "有効期限",
            "value" => date("Y/m/d", strtotime($before_expiry_date)) . "〜". date("Y/m/d", strtotime($after_expiry_date)),
            #"isRelative" => true,
            #"dateStyle" => "PKDateStyleShort"
          ]
        ],
        "backFields" => [
          [
            'key' => 'content',
            'label' => '内容',
            'value' => $content,
          ],
        ]
      ]
    ];

    $i = 1;
    foreach ($shop_list as $shop_data) {
      $shopname = [];
      $shopname['key'] = 'shopname'.$i;
      $shopname['label'] = '店舗名';
      $shopname['value'] = $shop_data->name;

      array_push($data['coupon']['backFields'], $shopname);

      // HomePageが存在
      if (!empty($shop_data->homepage)) {
        $homepage = [];
        $homepage['key'] = 'homepage'.$i;
        $homepage['label'] = 'Homepage';
        $homepage['value'] = $shop_data->homepage;

        array_push($data['coupon']['backFields'], $homepage);
      }

      // LINEが存在
      if (!empty($shop_data->line)) {
        $line = [];
        $line['key'] = 'line'.$i;
        $line['label'] = 'LINE';
        $line['value'] = $shop_data->line;

        array_push($data['coupon']['backFields'], $line);
      }

      // twitterが存在
      if (!empty($shop_data->twitter)) {
        $twitter = [];
        $twitter['key'] = 'twitter'.$i;
        $twitter['label'] = 'Twitter';
        $twitter['value'] = $shop_data->twitter;

        array_push($data['coupon']['backFields'], $twitter);
      }

      if (!empty($shop_data->facebook)) {
        $facebook = [];
        $facebook['key'] = 'facebook'.$i;
        $facebook['label'] = 'FaceBook';
        $facebook['value'] = $shop_data->facebook;

        array_push($data['coupon']['backFields'], $facebook);
      }

      if (!empty($shop_data->instagram)) {
        $instagram = [];
        $instagram['key'] = 'instagram'.$i;
        $instagram['label'] = 'instagram';
        $instagram['value'] = $shop_data->instagram;

        array_push($data['coupon']['backFields'], $instagram);
      }
      $i++;
    }
    $pass->setData($data);

    // DocumentRoot:webroot
    if (file_exists(WWW_ROOT.'img/shops/'.$shop_list[0]['id'].'/icon.png')) {
      $pass->addFile(WWW_ROOT. 'img/shops/'.$shop_list[0]['id'].'/icon.png');
      $pass->addFile(WWW_ROOT. 'img/shops/'.$shop_list[0]['id'].'/logo.png');
    } else {
      $pass->addFile(ROOT. '/src/wallet_resource/images/test/icon.png');
      $pass->addFile(ROOT. '/src/wallet_resource/images/test/icon@2x.png');
      $pass->addFile(ROOT. '/src/wallet_resource/images/test/logo.png');
    }

    // debug
    if(!$pass->create(true)) {
      echo 'Error: ' . $pass->getError();
    }
  }

  /* google map api 緯度経度取得
  *  request_address: 検索住所
  */
  private function result_longitude_latitude($request_address)
  {
    mb_language("Japanese");//文字コードの設定
    mb_internal_encoding("UTF-8");

    //住所を入れて緯度経度を求める。
    $address = $request_address;
    $myKey = "AIzaSyAKVUIqxiVHeWhNGyKUIb2mwDgEYXNR4GQ";
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

  private function getAffiliaters() {
      /**
       * @var MyusersTable
       */
      $model = TableRegistry::getTableLocator()->get('Myusers');

      return $model->find('all')
          ->where(['company_id' => $this->Auth->user('company_id'), 'role_id' => 4])
          ->select(['id', 'username'])
          ->combine('id', 'username');
  }

  private function attachAffiliaterCoupons(Coupon $coupon) {
    if (!$coupon->rate) {
      return;
    }

      $data = [
          'coupon_id' => $coupon->id,
          'myuser_id' => $this->Auth->user('id'),
          'type' => AffiliaterCoupon::TYPE_RATE,
          'rate' => $coupon->rate

      ];

      $affiliaterCoupon = $this->AffiliaterCoupons->find()
          ->where(['coupon_id' => $data['coupon_id'], 'myuser_id' => $data['myuser_id']])
          ->first();

      if ($affiliaterCoupon) {
          return;
      }

      if($data['type'] == null) {
          return;
      }
      $entity = $this->AffiliaterCoupons->newEntity();
      $entity = $this->AffiliaterCoupons->patchEntity($entity, $data);
      $this->AffiliaterCoupons->save($entity);
  }

  private function accessCheck($couponId) {
    if (!AuthService::authCheck($this, [
      Myuser::ROLE_OWNER,
      Myuser::ROLE_LEADER,
      Myuser::ROLE_MEMBER
    ])) {
      return false;
    }

    $coupon = $this->Coupons->find()
      ->where(['id' => $couponId])
      ->first();

    if (!$coupon) {
      $authUser = $this->Myusers->get($this->Auth->user('id'));
      AuthService::redirect($this, $authUser->toArray());
      return false;
    }

    if (!AuthService::sameAuthUserCompanyCheck($this, $coupon->company_id)) {
      return false;
    }
    return true;
  }
}
