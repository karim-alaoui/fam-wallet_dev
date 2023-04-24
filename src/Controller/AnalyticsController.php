<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class AnalyticsController extends AppController
{

  public function initialize()
  {
    parent::initialize();
    // テーブル呼び出し
    $this->Shops = TableRegistry::get('Shops');
    $this->Coupons = TableRegistry::get('Coupons');
    $this->Stampcards = TableRegistry::get('Stampcards');
    $this->MyuserShops = TableRegistry::get('MyuserShops');
    $this->CouponShops = TableRegistry::get('CouponShops');
    $this->StampcardShops = TableRegistry::get('StampcardShops');
  }

  public function coupons()
  {
    // 店舗一覧
    $shops = $this->Shops->shop_index($this->Auth->User('company_id'));
    $session = $this->getRequest()->getSession();
    // 配列で整形
    $shop_list = [];

    if ($this->request->getQuery('reset') == 1) {
      $session->delete('result_coupon_id_list');
      $session->delete('search_shop_list');
      $session->delete('start_date');
      $session->delete('end_date');
    }

    if ($this->Auth->User('role_id') == 1) {
      $shop_list = ['0' => '全所属店舗'];
      foreach ($shops as $key) {
        $shop_list[$key->id] = $key->name;
      }
    } else {
      $shops = $this->MyuserShops->find('all')->where(['myuser_id' => $this->Auth->User('id')])->contain(['Shops']);
      $shop_list = ['0' => '全所属店舗'];
      foreach ($shops as $key) {
        $shop_list[$key->shop_id] = $key->shop->name;
      }
    }

    // オーナーでの検索
    if (!empty($session->read('result_coupon_id_list')) && $this->Auth->User('role_id') == 1) {
      // 全所属店舗以外のPOST
      if ($this->request->getData('coupon_list') != 0) {
        $tmp_coupons = $this->CouponShops->find('all')->where(['shop_id IN' => $this->request->getData('coupon_list')])->extract('coupon_id')->toArray();
        $search_id = array_intersect($session->read('result_coupon_id_list'), $tmp_coupons);
        if (!empty($search_id)) {
          $coupons = $this->Coupons->find('all')->contain(['ChildCoupons'])->where(['id IN' => $search_id]);
          $session->write(['search_shop_list' => $search_id]);
        } else {
          $this->Flash->success(__('検索条件に一致するクーポンがありません。'));
        }
      } else {
        $session->delete('search_shop_list');
      }
      $start_date = $session->read('start_date');
      $end_date = $session->read('end_date');
      $this->set(compact('start_date', 'end_date'));

    // オーナーでの検索なし
    } elseif (empty($session->read('result_coupon_id_list')) && $this->Auth->User('role_id') == 1) {

      // session削除
      $this->session_delete();
      // 全所属店舗以外のPOST
      if ($this->request->getData('coupon_list') != 0) {
        // 選択店舗のIDのみ
        $tmp_coupons = $this->CouponShops->find('all')->where(['shop_id IN' => $this->request->getData('coupon_list')])->extract('coupon_id')->toArray();
        if (!empty($tmp_coupons)) {
          $coupons = $this->Coupons->find('all')->contain(['ChildCoupons'])->where(['id IN' => $tmp_coupons])->order(['id' => 'DESC']);
          $session->write(['search_shop_list' => $tmp_coupons]);
        } else {
          $this->Flash->success(__('検索条件に一致するクーポンがありません。'));
        }
      } else {
        if (!empty($session->read('search_shop_list'))) {
          $coupons = $this->Coupons->find('all')->contain(['ChildCoupons'])->where(['id IN' => $session->read('search_shop_list')])->order(['id' => 'DESC']);
        }
        $session->delete('search_shop_list');
      }

    // リーダー、メンバーでの検索
    } elseif (!empty($session->read('result_coupon_id_list')) && $this->Auth->User('role_id') != 1) {

      //店舗検索
      if ($this->request->getData('coupon_list') != 0) {
        $tmp_coupons = $this->CouponShops->find('all')->where(['shop_id IN' => $this->request->getData('coupon_list')])->extract('coupon_id')->toArray();
        $search_id = array_intersect($session->read('result_coupon_id_list'), $tmp_coupons);
        if (!empty($search_id)) {
          $coupons = $this->Coupons->find('all')->contain(['ChildCoupons', 'CouponShops', 'CouponShops.Shops'])->where(['id IN' => $search_id]);
          $session->write(['search_shop_list' => $search_id]);
        } else {
          $this->Flash->success(__('検索条件に一致するクーポンがありません。'));
        }
      } else {
        $session->delete('search_shop_list');
      }

      $start_date = $session->read('start_date');
      $end_date = $session->read('end_date');
      $this->set(compact('start_date', 'end_date'));

    // リーダー、メンバーでの検索なし
    } else {
       // session削除
      $this->session_delete();
      $shops = $this->MyuserShops->find('all')->where(['myuser_id' => $this->Auth->User('id')])->extract('shop_id')->toArray();
      // 店舗IDからcoupon_idを抽出
      $tmp_coupon_shop_id = $this->CouponShops->find('all')->where(['shop_id IN' => $shops])->extract('coupon_id')->toArray();
      $session->write(['leader_coupons' => $tmp_coupon_shop_id]);

      //店舗検索
      if ($this->request->getData('coupon_list') != 0) {
        $tmp_coupons = $this->CouponShops->find('all')->where(['shop_id IN' => $this->request->getData('coupon_list')])->extract('coupon_id')->toArray();
        if (!empty($tmp_coupons)) {
          $session->write(['search_shop_list' => $tmp_coupons]);
        } else {
          $this->Flash->success(__('検索条件に一致するクーポンがありません。'));
        }
      } else {
        $session->delete('search_shop_list');
      }
    }

    $this->set(compact('shop_list'));
  }

  public function stampcards()
  {
    // 店舗一覧
    $shops = $this->Shops->shop_index($this->Auth->User('company_id'));
    $session = $this->getRequest()->getSession();
    // 配列で整形
    $shop_list = [];

    if ($this->request->getQuery('reset') == 1) {
      $session->delete('result_stamp_id_list');
      $session->delete('search_stamp_shop_list');
      $session->delete('stamp_start_date');
      $session->delete('stamp_end_date');
    }

    if ($this->Auth->User('role_id') == 1) {
      $shop_list = ['0' => '全所属店舗'];
      foreach ($shops as $key) {
        $shop_list[$key->id] = $key->name;
      }
    } else {
      $shops = $this->MyuserShops->find('all')->where(['myuser_id' => $this->Auth->User('id')])->contain(['Shops']);
      foreach ($shops as $key) {
        $shop_list[$key->shop_id] = $key->shop->name;
      }
    }

    // オーナーでの検索
    if (!empty($session->read('result_stamp_id_list')) && $this->Auth->User('role_id') == 1) {
      // 全店舗
      if ($this->request->getData('stamp_list') != 0) {
        $tmp_stamps = $this->StampcardShops->find('all')->where(['shop_id IN' => $this->request->getData('stamp_list')])->extract('stamp_id')->toArray();
        $search_id = array_intersect($session->read('result_stamp_id_list'), $tmp_stamps);
        if (!empty($search_id)) {
          $session->write(['search_stamp_shop_list' => $search_id]);
        } else {
          $this->Flash->success(__('検索条件に一致するスタンプカードがありません。'));
        }
      } else {
        $session->delete('search_stamp_shop_list');
      }
      $start_date = $session->read('start_date');
      $end_date = $session->read('end_date');
      $this->set(compact('start_date', 'end_date'));

    // オーナーで検索なし
    } elseif (empty($session->read('result_stamp_id_list')) && $this->Auth->User('role_id') == 1) {
      // session削除
      $this->session_delete();
      // 全店舗
      if ($this->request->getData('stamp_list') != 0) {
        $tmp_stamps = $this->StampcardShops->find('all')->where(['shop_id IN' => $this->request->getData('stamp_list')])->extract('stamp_id')->toArray();
        if (!empty($tmp_stamps)) {
          $stampcards = $this->Stampcards->find('all')->contain(['ChildStampcards'])->where(['id IN' => $tmp_stamps])->order(['id' => 'DESC']);
          $session->write(['search_stamp_shop_list' => $tmp_stamps]);
        } else {
          $this->Flash->success(__('検索条件に一致するスタンプカードががありません。'));
        }
      } else {
        $session->delete('stamp_search_shop_list');
      }

    // リーダー、メンバーでの検索
    } elseif (!empty($session->read('result_stamp_id_list')) && $this->Auth->User('role_id') != 1) {
      $stampcards = $this->Stampcards->find('all')->contain(['ChildStampcards'])->where(['id IN' => $session->read('result_stamp_id_list')])->order(['id' => 'DESC']);;

      if($this->request->is('post')){
        // 全店舗
        if ($this->request->getData('stamp_list') == 0) {
          $stampcards = $this->Stampcards->find('all')->contain(['ChildStampcards'])->where(['id IN' => $session->read('result_stamp_id_list')])->order(['id' => 'DESC']);
        } else {
          $tmp_stamps = $this->StampcardShops->find('all')->where(['shop_id IN' => $this->request->getData('stamp_list')]);
          $tmp_stamp_id = [];
          // coupon_id抽出
          foreach ($tmp_stamps as $key) {
            array_push($tmp_stamp_id, $key->stamp_id);
          }
          $stampcards = $this->Stampcards->find('all')->where(['id IN' => $tmp_stamp_id])->contain(['ChildStampcards', 'StampcardShops', 'StampcardShops.Shops']);
        }
      }

      $start_date = $session->read('start_date');
      $end_date = $session->read('end_date');
      $this->set(compact('start_date', 'end_date'));

    // リーダー、メンバーでの検索なし
    } else {
      $this->session_delete();
      $shops = $this->MyuserShops->find('all')->where(['myuser_id' => $this->Auth->User('id')])->extract('shop_id')->toArray();
      // 店舗IDからcoupon_idを抽出
      $tmp_stamp_shop_id = $this->StampcardShops->find('all')->where(['shop_id IN' => $shops])->extract('coupon_id')->toArray();
      $session->write(['leader_coupons' => $tmp_stamp_shop_id]);

      // 全店舗
      if ($this->request->getData('stamp_list') == 0) {
        $tmp_stampcards = $this->Stampcards->find('all')->where(['shop_id IN' => $this->request->getData('stamp_list')])->extract('coupon_id')->toArray();
        if (!empty($tmp_stampcards)) {
          $session->write(['stamp_search_shop_list' => $tmp_stampcards]);
        } else {
          $this->Flash->success(__('検索条件に一致するクーポンがありません。'));
        }
      } else {
        $session->delete('stamp_search_shop_list');
      }
    }

    $this->set(compact('shop_list'));
  }


  public function couponNarrowDown()
  {
    $requestData = $this->request->getData();
    $my_company_id = $this->Auth->User('company_id');
    $session = $this->getRequest()->getSession();

    if ($this->request->is('post')) {
      if ($this->Auth->User('role_id') == 1) {
        if (!empty($requestData['before_expiry_date']) && empty($requestData['after_expiry_date'])) {
          $result_coupons = $this->Coupons->analytics_coupon_record($my_company_id, $requestData['before_expiry_date'], null);
        } elseif (!empty($requestData['after_expiry_date']) && empty($requestData['before_expiry_date'])) {
          $result_coupons = $this->Coupons->analytics_coupon_record($my_company_id, null, $requestData['after_expiry_date']);
        } else {
          $result_coupons = $this->Coupons->analytics_coupon_record($my_company_id, $requestData['before_expiry_date'], $requestData['after_expiry_date']);
        }
      } else {
        $shops = $this->MyuserShops->find('all')->where(['myuser_id' => $this->Auth->User('id')])->extract('shop_id')->toArray();
        // 店舗IDからcoupon_idを抽出
        $tmp_coupon_id = $this->CouponShops->find('all')->where(['shop_id IN' => $shops])->extract('coupon_id')->toArray();
        if (!empty($requestData['before_expiry_date']) && empty($requestData['after_expiry_date'])) {
          $result_coupons = $this->Coupons->analytics_coupon_leader_record($my_company_id, $tmp_coupon_id, $requestData['before_expiry_date'], null);
        } elseif (!empty($requestData['after_expiry_date']) && empty($requestData['before_expiry_date'])) {
          $result_coupons = $this->Coupons->analytics_coupon_leader_record($my_company_id, $tmp_coupon_id, null, $requestData['after_expiry_date']);
        } else {
          $result_coupons = $this->Coupons->analytics_coupon_leader_record($my_company_id, $tmp_coupon_id, $requestData['before_expiry_date'], $requestData['after_expiry_date']);
        }
      }

      // 開始時刻のみ
      if (!empty($requestData['before_expiry_date']) && empty($requestData['after_expiry_date'])) {
        $start_date = $requestData['before_expiry_date'];
        $end_date = '';
      // 終了時刻のみ
      } elseif (!empty($requestData['after_expiry_date']) && empty($requestData['before_expiry_date'])) {
        $start_date = '';
        $end_date = $requestData['after_expiry_date'];
      // 両方存在
      } else {
        $start_date = $requestData['before_expiry_date'];
        $end_date = $requestData['after_expiry_date'];
      }
      $session->write(['start_date' => $start_date]);
      $session->write(['end_date' => $end_date]);
      $session->write(['result_coupon_id_list' => $result_coupons]);
      $this->redirect(['action' => 'coupons']);
    }
  }

  public function stampcardNarrowDown()
  {
    $requestData = $this->request->getData();
    $my_company_id = $this->Auth->User('company_id');
    $session = $this->getRequest()->getSession();

    if ($this->request->is('post')) {
      if ($this->Auth->User('role_id') == 1) {
        if (!empty($requestData['before_expiry_date']) && empty($requestData['after_expiry_date'])) {
          $result_stamps = $this->Stampcards->analytics_stamp_record($my_company_id, $requestData['before_expiry_date'], null);
        } elseif (!empty($requestData['after_expiry_date']) && empty($requestData['before_expiry_date'])) {
          $result_stamps = $this->Stampcards->analytics_stamp_record($my_company_id, null, $requestData['after_expiry_date']);
        } else {
          $result_stamps = $this->Stampcards->analytics_stamp_record($this->Auth->User('company_id'), $requestData['before_expiry_date'], $requestData['after_expiry_date']);
        }
      } else {
        $shops = $this->MyuserShops->find('all')->where(['myuser_id' => $this->Auth->User('id')])->extract('shop_id')->toArray();
        // 店舗IDからcoupon_idを抽出
        $tmp_stamp_id = $this->StampcardShops->find('all')->where(['stamp_id IN' => $shops])->extract('stamp_id')->toArray();
        if (!empty($requestData['before_expiry_date']) && empty($requestData['after_expiry_date'])) {
          $result_stamps = $this->Stampcards->analytics_stamp_leader_record($my_company_id, $tmp_stamp_id, $requestData['before_expiry_date'], null);
        } elseif (!empty($requestData['after_expiry_date']) && empty($requestData['before_expiry_date'])) {
          $result_stamps = $this->Stampcards->analytics_stamp_leader_record($my_company_id, $tmp_stamp_id, null, $requestData['after_expiry_date']);
        } else {
          $result_stamps = $this->Stampcards->analytics_stamp_leader_record($my_company_id, $tmp_stamp_id, $requestData['before_expiry_date'], $requestData['after_expiry_date']);
        }
      }

      // 開始時刻のみ
      if (!empty($requestData['before_expiry_date']) && empty($requestData['after_expiry_date'])) {
        $start_date = $requestData['before_expiry_date'];
        $end_date = '';
      // 終了時刻のみ
      } elseif (!empty($requestData['after_expiry_date']) && empty($requestData['before_expiry_date'])) {
        $start_date = '';
        $end_date = $requestData['after_expiry_date'];
      // 両方存在
      } else {
        $start_date = $requestData['before_expiry_date'];
        $end_date = $requestData['after_expiry_date'];
      }
      $session->write(['start_date' => $start_date]);
      $session->write(['end_date' => $end_date]);
      $session->write(['result_stamp_id_list' => $result_stamps]);
      $this->redirect(['action' => 'stampcards']);
    }
  }

  /*
  * 分析coupon Ajax API
  */
  public function couponApi()
  {
    // 店舗一覧
    $shops = $this->Shops->shop_index($this->Auth->User('company_id'));
    $session = $this->getRequest()->getSession();

    if ($this->request->is('ajax')) {
      // オーナー検索あり
      if (!empty($session->read('result_coupon_id_list')) && $this->Auth->User('role_id') == 1) {
        $coupons = $this->Coupons->find('all')->contain(['ChildCoupons'])->where(['id IN' => $session->read('result_coupon_id_list')])->order(['id' => 'DESC']);
      // オーナー検索なし
      } elseif (empty($session->read('result_coupon_id_list')) && $this->Auth->User('role_id') == 1) {
        $coupons = $this->Coupons->find('all')->contain(['ChildCoupons'])->where(['company_id' => $this->Auth->User('company_id')])->order(['id' => 'DESC']);
      // リーダー検索あり
      } elseif (!empty($session->read('result_coupon_id_list')) && $this->Auth->User('role_id') == 2) {
        $coupons = $this->Coupons->find('all')->contain(['ChildCoupons'])->where(['id IN' => $session->read('result_coupon_id_list')])->order(['id' => 'DESC']);
      // リーダー検索なし
      } elseif (!empty($session->read('leader_coupons')) && $this->Auth->User('role_id') == 2) {
        $coupons = $this->Coupons->find('all')->contain(['ChildCoupons'])->where(['id IN' => $session->read('leader_coupons')])->order(['id' => 'DESC']);
      }

      // 店舗検索
      if (!empty($session->read('search_shop_list'))) {
        $coupons = $this->Coupons->find('all')->contain(['ChildCoupons'])->where(['id IN' => $session->read('search_shop_list')])->order(['id' => 'DESC']);
      }
      // 利用回数ソート
      if ($this->request->getQuery('param') == 'usecount') {
        $coupons = $this->coupon_value_count_sort($coupons); 
      }
      // ダウンロードソート
      if ($this->request->getQuery('param') == 'download') {
        $coupons = $this->coupon_download_sort($coupons);
      }
      // 最終更新日ソート
      if ($this->request->getQuery('param') == 'last_updated') {
        // オーナー検索あり
        if (!empty($session->read('result_coupon_id_list')) && $this->Auth->User('role_id') == 1) {
          $coupons = $this->Coupons->find('all')->contain(['ChildCoupons'])->where(['company_id' => $this->Auth->User('company_id')])->order(['update_at' => 'DESC', 'id' => 'DESC']);
        // オーナー検索なし
        } elseif (empty($session->read('result_coupon_id_list')) && $this->Auth->User('role_id') == 1) {
          $coupons = $this->Coupons->find('all')->contain(['ChildCoupons'])->where(['company_id' => $this->Auth->User('company_id')])->order(['update_at' => 'DESC', 'id' => 'DESC']);
        // リーダー検索あり
        } elseif (!empty($session->read('result_coupon_id_list')) && $this->Auth->User('role_id') == 2) {
          $coupons = $this->Coupons->find('all')->contain(['ChildCoupons'])->where(['id IN' => $session->read('result_coupon_id_list')])->order(['update_at' => 'DESC', 'id' => 'DESC']);
        // リーダー検索なし
        } elseif (!empty($session->read('leader_coupons')) && $this->Auth->User('role_id') == 2) {
          $coupons = $this->Coupons->find('all')->contain(['ChildCoupons'])->where(['id IN' => $session->read('leader_coupons')])->order(['update_at' => 'DESC', 'id' => 'DESC']);
        }
        if (!empty($session->read('search_shop_list'))) {
          $coupons = $this->Coupons->find('all')->contain(['ChildCoupons'])->where(['id IN' => $session->read('search_shop_list')])->order(['update_at' => 'DESC', 'id' => 'DESC']);
        }
      }

      // 作成日ソート
      if ($this->request->getQuery('param') == 'create_date') {
        // オーナー検索あり
        if (!empty($session->read('result_coupon_id_list')) && $this->Auth->User('role_id') == 1) {
          $coupons = $this->Coupons->find('all')->contain(['ChildCoupons'])->where(['company_id' => $this->Auth->User('company_id')])->order(['create_at' => 'DESC', 'id' => 'DESC']);
        // オーナー検索なし
        } elseif (empty($session->read('result_coupon_id_list')) && $this->Auth->User('role_id') == 1) {
          $coupons = $this->Coupons->find('all')->contain(['ChildCoupons'])->where(['company_id' => $this->Auth->User('company_id')])->order(['create_at' => 'DESC', 'id' => 'DESC']);
        // リーダー検索あり
        } elseif (!empty($session->read('result_coupon_id_list')) && $this->Auth->User('role_id') == 2) {
          $coupons = $this->Coupons->find('all')->contain(['ChildCoupons'])->where(['id IN' => $session->read('result_coupon_id_list')])->order(['create_at' => 'DESC', 'id' => 'DESC']);
        // リーダー検索なし
        } elseif (!empty($session->read('leader_coupons')) && $this->Auth->User('role_id') == 2) {
          $coupons = $this->Coupons->find('all')->contain(['ChildCoupons'])->where(['id IN' => $session->read('leader_coupons')])->order(['create_at' => 'DESC', 'id' => 'DESC']);
        }
        // 店舗検索
        if (!empty($session->read('search_shop_list'))) {
          $coupons = $this->Coupons->find('all')->contain(['ChildCoupons'])->where(['id IN' => $session->read('search_shop_list')])->order(['create_at' => 'DESC', 'id' => 'DESC']);
        }
      }
      // viewの表示OFF
      $this->autoRender = false;
      // JSONでreturn
      $this->response->body(json_encode($coupons));
      return;
    }
  }

  /*
  * 分析 stampcards Ajax API
  */
  public function stampcardApi()
  {
    // 店舗一覧
    $shops = $this->Shops->shop_index($this->Auth->User('company_id'));
    $session = $this->getRequest()->getSession();

    if ($this->request->is('ajax')) {
      // オーナー検索あり
      if (!empty($session->read('result_stamp_id_list')) && $this->Auth->User('role_id') == 1) {
        $stamps = $this->Stampcards->find('all')->contain(['ChildStampcards'])->where(['id IN' => $session->read('result_stamp_id_list')])->order(['id' => 'DESC']);
      // オーナー検索なし
      } elseif (empty($session->read('result_stamp_id_list')) && $this->Auth->User('role_id') == 1) {
        $stamps = $this->Stampcards->find('all')->contain(['ChildStampcards'])->where(['company_id' => $this->Auth->User('company_id')])->order(['id' => 'DESC']);
      // リーダー検索あり
      } elseif (!empty($session->read('result_stamp_id_list')) && $this->Auth->User('role_id') == 2) {
        $stamps = $this->Stampcards->find('all')->contain(['ChildStampcards'])->where(['id IN' => $session->read('result_stamp_id_list')])->order(['id' => 'DESC']);
      // リーダー検索なし
      } elseif (!empty($session->read('leader_stamps')) && $this->Auth->User('role_id') == 2) {
        $stamps = $this->Stampcards->find('all')->contain(['ChildStampcards'])->where(['id IN' => $session->read('leader_stamps')])->order(['id' => 'DESC']);
      }

      // 店舗検索
      if (!empty($session->read('search_stamp_shop_list'))) {
        $stamps = $this->Stampcards->find('all')->contain(['ChildStampcards'])->where(['id IN' => $session->read('search_stamp_shop_list')])->order(['id' => 'DESC']);
      }
      // ダウンロードソート
      if ($this->request->getQuery('param') == 'download') {
        $stamps = $this->stamp_download_sort($stamps);
      }
      // 最終更新日ソート
      if ($this->request->getQuery('param') == 'last_updated') {
        // オーナー検索あり
        if (!empty($session->read('result_stamp_id_list')) && $this->Auth->User('role_id') == 1) {
          $stamps = $this->Stampcards->find('all')->contain(['ChildStampcards'])->where(['id IN' => $session->read('result_coupon_id_list')])->order(['update_at' => 'DESC', 'id' => 'DESC']);
        // オーナー検索なし
        } elseif (empty($session->read('result_stamp_id_list')) && $this->Auth->User('role_id') == 1) {
          $stamps = $this->Stampcards->find('all')->contain(['ChildStampcards'])->where(['company_id' => $this->Auth->User('company_id')])->order(['update_at' => 'DESC', 'id' => 'DESC']);
        // リーダー検索あり
        } elseif (!empty($session->read('result_stamp_id_list')) && $this->Auth->User('role_id') == 2) {
          $stamps = $this->Stampcards->find('all')->contain(['ChildStampcards'])->where(['id IN' => $session->read('result_stamp_id_list')])->order(['update_at' => 'DESC', 'id' => 'DESC']);
        // リーダー検索なし
        } elseif (!empty($session->read('leader_stamps')) && $this->Auth->User('role_id') == 2) {
          $stampcards = $this->Stampcards->find('all')->contain(['ChildStampcards'])->where(['id IN' => $session->read('leader_stamps')])->order(['update_at' => 'DESC', 'id' => 'DESC']);
        }
        // 店舗検索
        if (!empty($session->read('search_stamp_shop_list'))) {
          $stamps = $this->Stampcards->find('all')->contain(['ChildStampcards'])->where(['id IN' => $session->read('search_stamp_shop_list')])->order(['update_at' => 'DESC', 'id' => 'DESC']);
        }
      }

      // 作成日ソート
      if ($this->request->getQuery('param') == 'create_date') {
        // オーナー検索あり
        if (!empty($session->read('result_stamp_id_list')) && $this->Auth->User('role_id') == 1) {
          $stamps = $this->Stampcards->find('all')->contain(['ChildStampcards'])->where(['id IN' => $session->read('result_coupon_id_list')])->order(['create_at' => 'DESC', 'id' => 'DESC']);
        // オーナー検索なし
        } elseif (empty($session->read('result_stamp_id_list')) && $this->Auth->User('role_id') == 1) {
          $stamps = $this->Stampcards->find('all')->contain(['ChildStampcards'])->where(['company_id' => $this->Auth->User('company_id')])->order(['create_at' => 'DESC', 'id' => 'DESC']);
        // リーダー検索あり
        } elseif (!empty($session->read('result_stamp_id_list')) && $this->Auth->User('role_id') == 2) {
          $stamps = $this->Stampcards->find('all')->contain(['ChildStampcards'])->where(['id IN' => $session->read('result_stamp_id_list')])->order(['create_at' => 'DESC', 'id' => 'DESC']);
        // リーダー検索なし
        } elseif (!empty($session->read('leader_stamps')) && $this->Auth->User('role_id') == 2) {
          $stampcards = $this->Stampcards->find('all')->contain(['ChildStampcards'])->where(['id IN' => $session->read('leader_stamps')])->order(['create_at' => 'DESC', 'id' => 'DESC']);
        }
        // 店舗検索
        if (!empty($session->read('search_stamp_shop_list'))) {
          $stamps = $this->Stampcards->find('all')->contain(['ChildStampcards'])->where(['id IN' => $session->read('search_stamp_shop_list')])->order(['create_at' => 'DESC', 'id' => 'DESC']);
        }
      }

      // viewの表示OFF
      $this->autoRender = false;
      // JSONでreturn
      $this->response->body(json_encode($stamps));
      return;
    }
  }


  private function session_delete()
  {
    $session = $this->getRequest()->getSession();
    if ($this->request->action == 'coupons') {
      if ($session->check('result_coupon_id_list')) {
        $session->delete('search_shop_list');
        $session->delete('start_date');
        $session->delete('end_date');
        $this->Flash->success(__('検索条件に一致するクーポンがありません。'));
      }
      if ($session->check('leader_coupons')) {
        $session->delete('leader_coupons');
      }
    } else {
      if ($session->check('result_stamp_id_list')) {
        $session->delete('search_stamp_shop_list');
        $session->delete('start_date');
        $session->delete('end_date');
        $this->Flash->success(__('検索条件に一致するクーポンがありません。'));
      }
      if ($session->check('leader_stamps')) {
        $session->delete('leader_stamps');
      }
    }
  }

  private function coupon_value_count_sort($coupons)
  {
    // 検索結果
    $coupon_list = [];
    // 取り出したレコードを整形
    foreach ($coupons as $coupon) {
      $child_count = 0; 
      // child_couponsが存在
      // child_coupon配列を回す
      if (!empty($coupon->child_coupons[0])) {
        foreach ($coupon->child_coupons as $count) {
          // 0は計算除外
          // 使用回数を足す
          $child_count += $count->limit_count;
          // 最後にpush
          if ($count === end($coupon->child_coupons)) {
            $coupon['count_total'] = $child_count;
            array_push($coupon_list, $coupon);
          }
        }
      } else {
        array_push($coupon_list, $coupon);
      }
    }
    // ソート用配列
    foreach ((array) $coupon_list as $key => $value) {
      $coupon_sort[$key] = $value['count_total'];
      $coupon_id_sort[$key] = $value['id'];
    }
    // 結果をソート
    array_multisort($coupon_sort, SORT_DESC, $coupon_id_sort, SORT_DESC, $coupon_list);
    return $coupon_list;
  }

  private function coupon_download_sort($coupons)
  {
    $coupon_list = [];
    foreach ($coupons as $coupon) {
      $dl_count = 0;
      if (!empty($coupon->child_coupons[0])) {
        foreach ($coupon->child_coupons as $count) {
          $dl_count++;
          if ($count === end($coupon->child_coupons)) {
            $coupon['download_total'] = $dl_count;
            array_push($coupon_list, $coupon);
          }
        }
      } else {
      array_push($coupon_list, $coupon);
      }
    }
    // ソート用配列
    foreach ((array) $coupon_list as $key => $value) {
      $coupon_sort[$key] = $value['download_total'];
      $coupon_id_sort[$key] = $value['id'];
    }
    // 結果をソート
    array_multisort($coupon_sort, SORT_DESC, $coupon_id_sort, SORT_DESC, $coupon_list );
    return $coupon_list;
  }

  private function stamp_download_sort($stamps)
  {
    $stamp_list = [];
    foreach ($stamps as $stamp) {
      $dl_count = 0;
      if (!empty($stamp->child_stampcards[0])) {
        foreach ($stamp->child_stampcards as $count) {
          $dl_count++;
          if ($count === end($stamp->child_stampcards)) {
            $stamp['download_total'] = $dl_count;
            array_push($stamp_list, $stamp);
          }
        }
      } else {
        array_push($stamp_list, $stamp);
      }
    }
    // ソート用配列
    foreach ((array) $stamp_list as $key => $value) {
      $stamp_sort[$key] = $value['download_total'];
      $stamp_id_sort[$key] = $value['id'];
    }
    // 結果をソート
    array_multisort($stamp_sort, SORT_DESC, $stamp_id_sort, SORT_DESC, $stamp_list );
    return $stamp_list;
  }
}
