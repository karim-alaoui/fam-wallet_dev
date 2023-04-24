<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Form\AffiliaterCouponsForm;
use App\Model\Entity\AffiliaterChildCoupon;
use App\Model\Entity\AffiliaterCoupon;
use App\Model\Entity\Coupon;
use App\Model\Table\AffiliaterChildCouponsTable;
use App\Model\Table\AffiliaterCouponsTable;
use App\Model\Table\CouponsTable;
use App\Model\Table\MyuserShopsTable;
use App\Model\Table\MyusersTable;
use App\Services\AuthService;
use App\Services\CouponEmitter;
use App\Services\CouponService;
use App\Traits\LogTrail;
use Cake\Event\Event;
use Cake\Filesystem\Folder;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Utility\Text;

/**
 * AffiliaterCoupons Controller
 *
 * @property \App\Model\Table\AffiliaterCouponsTable $AffiliaterCoupons
 *
 * @method \App\Model\Entity\AffiliaterCoupon[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AffiliaterCouponsController extends AppController
{

    /**
     * @var AffiliaterChildCouponsTable
     */
    private $AffiliaterChildCoupons;

    /**
     * @var CouponsTable
     */
    private $Coupons;

    /**
     * @var MyuserShopsTable
     */
    private $MyuserShops;

    /**
     * @var AffiliaterCouponsTable
     */
    private $AffiliaterCoupons;

  /**
   * @var MyusersTable
   */
  private $Myusers;

    use LogTrail;

    public function initialize() {
        parent::initialize();
        $this->loadComponent('Paginator');
        $this->AffiliaterChildCoupons = TableRegistry::getTableLocator()->get('AffiliaterChildCoupons');
        $this->Coupons = TableRegistry::getTableLocator()->get('Coupons');
        $this->MyuserShops = TableRegistry::getTableLocator()->get('MyuserShops');
        $this->AffiliaterCoupons = TableRegistry::getTableLocator()->get('AffiliaterCoupons');
        $this->Myusers = TableRegistry::getTableLocator()->get('Myusers');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
//        $this->Auth->allow(['qrcode']);

        if ($this->request->getParam('action') == "qrcode") {
            if (!$this->Auth->user()) {
                $this->Auth->setConfig('authError', 'クーポンを取得するにはログインをしてください');
            }
        }

    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $tmpAlias = $this->AffiliaterCoupons->getAlias();

        $affiliaterCoupons = $this->Paginator
            ->paginate($this->getAffiliaterCoupons('Enable'), ['scope' => 'Enable'])->toArray();

        $affiliaterCouponsHide = $this->Paginator
            ->paginate($this->getAffiliaterCoupons('Disable',1), ['scope' => 'Disable'])->toArray();

        $this->set(compact( 'affiliaterCoupons', 'affiliaterCouponsHide'));
        $this->AffiliaterCoupons->setAlias($tmpAlias);
    }


    /**
     * Edit method
     *
     * @param string|null $id Affiliater Coupon id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $affiliaterCoupon = $this->AffiliaterCoupons->get($id, [
            'contain' => [
                'Coupons' => function(Query $query) {
                    return $query->contain(['CouponShops' => function(Query $query) {
                        return $query->contain(['Shops']);
                    }]);
                },
                'Myusers',
                'AffiliaterChildCoupons' => function(Query $query) {
                    return $query->select([
                        'download_sum' => $query->sumOf('download_count'),
                        'used_sum' => $query->sumOf('used_count'),
                    ])->limit(1);
                }
            ],
        ]);
        $coupon = $this->Coupons->get($affiliaterCoupon->coupon->id, [
            'contain' => ['ChildCoupons', 'CouponShops', 'CouponShops.Shops', 'AffiliaterCoupons.Myusers'],
          ]);
        $coupon = $this->Coupons->patchEntity($coupon, $this->request->getData());
        $qrUrl = Router::url([
            '_ssl' => true,
            'controller' => 'AffiliaterCoupons',
            'action' => 'qrcode',
            $affiliaterCoupon->coupon->id,
            $affiliaterCoupon->id,
            '?' => [
                'serial_number' => Text::uuid(),
                'user_id'=> $this->Auth->user('id'),
                'openExternalBrowser'=> 1
            ]], true);

//        if ($this->request->is(['patch'])) {
//        }
        $this->set(compact('affiliaterCoupon', 'qrUrl','coupon'));
    }

    /**
     * @param $parentId
     * @param $id
     * @return \Cake\Http\Response|void
     */
    public function qrcode($parentId, $id) {

        $affiliaterCoupon = $this->AffiliaterCoupons->get($id, [
            'contain' => [
                'Coupons' => function(Query $query) {
                    return $query->contain(['CouponShops' => function(Query $query) {
                        return $query->contain(['Shops']);
                    }]);
                },
                'Myusers',
                'AffiliaterChildCoupons' => function(Query $query) {
                    return $query->select([
                        'download_sum' => $query->sumOf('download_count'),
                        'used_sum' => $query->sumOf('used_count'),
                    ])->limit(1);
                }
            ],
        ]);
        $coupon = $this->Coupons->get($affiliaterCoupon->coupon->id, [
            'contain' => ['ChildCoupons', 'CouponShops', 'CouponShops.Shops', 'AffiliaterCoupons.Myusers'],
          ]);
        $coupon = $this->Coupons->patchEntity($coupon, $this->request->getData());
        $qrUrl = Router::url([
            '_ssl' => true,
            'controller' => 'AffiliaterCoupons',
            'action' => 'downloadCoupon',
            $affiliaterCoupon->coupon->id,
            $affiliaterCoupon->id,
            '?' => [
                'serial_number' => Text::uuid(),
                'user_id'=> $this->Auth->user('id'),
                'openExternalBrowser'=> 1
            ]], true);

//        if ($this->request->is(['patch'])) {
//        }
        $this->set(compact('affiliaterCoupon', 'qrUrl','coupon'));
    }

    public function downloadCoupon($parentId, $id) {

        $serialNumber = $this->request->getQuery('serial_number');
        $userId = $this->request->getQuery('user_id');
        $entity = $this->AffiliaterChildCoupons->find('all')->where(['serial_number' => $serialNumber])->first();
        if(!$entity) {
            $entity = $this->AffiliaterChildCoupons->newEntity(
                [
                    'serial_number' => $this->request->getQuery('serial_number'),
                    'authentication_token' => Text::uuid(),
                    'token' => Text::uuid(),
                    'parent_id' => $parentId,
                    'affiliater_coupon_id' => $id
                ]
            );
        }

        $service = new CouponEmitter();

        $st = $service->emit($entity, $userId);

        if(!$st) {
            $this->Flash->error('クーポン取得に失敗しました。');
            return;
        }

        return $this->response
            ->withHeader('Content-Description', 'File Transfer')
            ->withHeader('Content-Transfer-Encoding', 'binary')
            ->withType('pkpass')
            ->withDownload('coupon.pkpass')
            ->withStringBody($st);
    }

    public function qrDetail($id = null) {

        $f = function() use($id){
            return $this->AffiliaterChildCoupons->get($id, [
                'contain' => [
                    'AffiliaterCoupons.Coupons' => function(Query $query) {
                        return $query->contain([
                            'CouponShops.Shops',
                            'AffiliaterChildCoupons'
                        ]);
                    },
                ],
            ]);
        };

        try {
            $childCoupon = $f();
            $this->set(compact('childCoupon'));
        } catch (\Exception $e) {
        }

        if(!$this->request->is('post')) {
            return;
        }

        $validate = new AffiliaterCouponsForm();
        if($this->request->getData() && !$validate->execute($this->request->getData())) {
            $this->Flash->error('入力データにエラーがあります');
//            $childCoupon = $f();
//            $this->set(compact('childCoupon'));
            return;
        }

        $price = $this->request->getData('price');
        $useCouponUserId = $this->request->getQuery('user_id');

        $this->qrConfirm($id, $useCouponUserId, $price);
        $this->redirect("/coupons");

    }

    private function qrConfirm($id, $useCouponUserId, $price = null) {
        try {
            $coupon = $this->AffiliaterChildCoupons->get($id, [
                'contain' => [
                    'AffiliaterCoupons' => function(Query $query) {
                        return $query->contain(['Coupons.CouponShops.Shops', 'Myusers']);
                    }
                ]
            ]);

//            $shops = $this->MyuserShops->my_user_list($this->Auth->user('id'));
//            $userShops = [];
//            $couponShops = [];

//            foreach ($shops as $shop) {
//                array_push($userShops, $shop->shop->id);
//            }
//
//            foreach ($coupon->affiliater_coupon->coupon->coupon_shops as $shop) {
//                array_push($couponShops, $shop->shop->id);
//            }

//            if(!array_intersect($userShops, $couponShops)) {
//                $this->Flash->error('このユーザーは対象店舗に所属していません。');
//                return;
//            }

            if($coupon->affiliater_coupon->coupon->limit != '無制限' &&
                $coupon->used_count >= $coupon->affiliater_coupon->coupon->limit) {
                $this->Flash->error('クーポン利用制限が超過しています');
                return;
            }

            if(Time::now()->gt($coupon->affiliater_coupon->coupon->after_expiry_date)) {
                $this->Flash->error('クーポンの有効期限が切れています');
                return;
            }

            if(Time::now()->lt($coupon->affiliater_coupon->coupon->before_expiry_date)) {
                $date = date('Y-m-d', strtotime($coupon->affiliater_coupon->coupon->before_expiry_date));
                $this->Flash->error('このクーポンは'. $date. 'から使用可能です');
                return;
            }

            if($coupon->affiliater_coupon->coupon->release_id === Coupon::RELEASE_HIDE) {
                $this->Flash->error('このクーポンは非公開の為、使用出来ません。');
                return;
            }

            if($coupon->token !== $this->request->getQuery('token')) {
                $this->Flash->error('不正なパラメータです。');
                return;
            }
            $res = (new CouponService())->useAffiliaterCoupon($useCouponUserId, $coupon, $price);

            if(!$res) {
                $this->Flash->error('クーポンの使用に失敗しました。');
                return;
            }

            $this->Flash->success('クーポンを使用しました。');

        } catch (\Exception $e) {
            $this->Flash->error('クーポン取得に失敗しました。');
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Affiliater Coupon id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    /*public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $affiliaterCoupon = $this->AffiliaterCoupons->get($id);

        $affiliaterCoupon->hide = true;
        $this->AffiliaterCoupons->save($affiliaterCoupon);

        return $this->redirect(['action' => 'index']);
    }*/

    /**
     * @param int $hide
     * @return Query
     */
    private function getAffiliaterCoupons($alias, $hide = 0) {
        return $this->AffiliaterCoupons
            ->setAlias($alias)
            ->find()
            ->where(
                [
                    'myuser_id' => $this->Auth->user('id'),
                    'is_use' => true,
                    'hide' => $hide
                ])
            ->contain([
                'Myusers',
                'Coupons' => function(Query $query) {
                    return $query
                        ->where(['release_id' => 1])
                        ->where(['after_expiry_date >' => (new \DateTime())->format('Y-m-d H:i:s')])
                        ->where(['before_expiry_date <' => (new \DateTime())->format('Y-m-d H:i:s')])
                        ->contain(
                        [
                            'CouponShops' => function(Query $query) {
                                return $query->contain(['Shops']);
                            },
                            'ChildCoupons'
                        ]
                    );
                },
                'AffiliaterChildCoupons'
            ]);
    }

    private function attachAffiliaterCoupons($coupon) {
      $data = [
          'coupon_id' => $coupon->id,
          'myuser_id' => $this->Auth->user('id'),
          'type' => AffiliaterCoupon::TYPE_RATE,
          'rate' => $coupon->rate,
      ];

      $entity = $this->AffiliaterCoupons->newEntity();
      $entity = $this->AffiliaterCoupons->patchEntity($entity, $data);
      $this->AffiliaterCoupons->save($entity);
  }
}
