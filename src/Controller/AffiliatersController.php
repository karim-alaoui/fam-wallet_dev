<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Form\BankForm;
use App\Form\UserForm;
use App\Model\Entity\ChildCoupon;
use App\Model\Entity\Coupon;
use App\Model\Entity\Myuser;
use App\Model\Entity\MyuserBank;
use App\Model\Table\AffiliaterCouponsTable;
use App\Model\Table\CouponsTable;
use App\Model\Table\MyuserBanksTable;
use App\Model\Table\MyusersTable;
use App\Services\AuthService;
use App\Services\StripeService;
use Cake\Collection\Collection;
use Cake\Core\Configure;
use Cake\Database\Connection;
use Cake\Datasource\ConnectionManager;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use CakeDC\Users\Controller\Component\UsersAuthComponent;
use CakeDC\Users\Controller\Traits\LoginTrait;
use CakeDC\Users\Controller\Traits\RegisterTrait;
use CakeDC\Users\Controller\Traits\U2fTrait;
use Exception;

/**
 * Affiliaters Controller
 *
 *
 * @method \App\Model\Entity\Affiliater[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AffiliatersController extends AppController
{
    use LoginTrait;
    use RegisterTrait;
    use U2fTrait;

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

    /**
     * @var MyuserBanksTable
     */
    private $MyuserBanks;

    public function initialize() {
        parent::initialize();

        $user = $this->Auth->user();
        $this->set('User', $user);

        $this->Myusers = TableRegistry::getTableLocator()->get('Myusers');
        $this->AffiliaterCoupons = TableRegistry::getTableLocator()->get('AffiliaterCoupons');
        $this->Coupons = TableRegistry::getTableLocator()->get('Coupons');
        $this->MyuserBanks = TableRegistry::getTableLocator()->get('MyuserBanks');
    }

    public function beforeFilter (Event $event)
    {
        parent::beforeFilter($event);

        $this->Auth->allow(['signUp']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index($id = null)
    {
        $coupon_ids = $this->AffiliaterCoupons->find()
            ->where(['myuser_id IN ' => $id])
            ->contain(['Coupons'])
            ->extract('coupon_id')
            ->toArray();

        $coupon_record = [];
        if($coupon_ids) {
            $coupon_record = $this->Coupons->find()
                ->contain(['ChildCoupons', 'CouponShops', 'CouponShops.Shops', 'AffiliaterChildCoupons', 'AffiliaterCoupons'])
                ->where(['id IN' => $coupon_ids])->toArray();
        }

        $coupon_list = (new Collection($coupon_record))
            ->filter(function(Coupon $x) {
                return isset($x->child_coupons);
            })
            ->map(function(Coupon $x) use(&$coupon_check){
                $uses_count = 0;
                $list = [];
                /**
                 * @var $child_coupon ChildCoupon
                 */
                foreach ($x->child_coupons as $child_coupon) {
                    if($child_coupon->limit_count > 0) {
                        $coupon_check++;
                        $uses_count += $child_coupon->limit_count;
                    }
                }

                foreach ($x->affiliater_child_coupons as $child_coupon) {
                    if($child_coupon->used_count > 0) {
                        $coupon_check++;
                        $uses_count += $child_coupon->used_count;
                        if($child_coupon === end($x->affiliater_child_coupons)) {
                            $x['count_total'] = $uses_count;
                            array_push($list, $x);
                        }
                    }
                }
                return $x;
            })
            ->filter(function($x) {
                return $x->count_total;
            })
            ->sortBy('count_total', SORT_DESC)
            ->toArray()
        ;

        if(!$coupon_list && $coupon_ids) {
            $coupon_list = $this->Coupons->find()
                ->contain(['ChildCoupons', 'CouponShops', 'CouponShops.Shops', 'AffiliaterChildCoupons', 'AffiliaterCoupons'])
                ->where(['id IN' => $coupon_ids])
                ->limit(10);
        }

        $point = $this->Myusers->get_point($id);

        $this->set(compact('coupon_list', 'point'));

    }

    /**
     * Detail View
     */
    public function detail() {
        if (!AuthService::authCheck($this)) {
          return null;
        }

        $user = $this->Auth->user();

        $point = $this->Myusers->find()
            ->where(['id' => $user['id']])
            ->select(['point'])
            ->first()->point;

        $myuser = $this->Myusers->get($user['id']);

        $userForm = new UserForm();

        $bank = $this->MyuserBanks->find()
            ->where(['myuser_id' => $user['id']])
            ->first();

        $this->set(compact('point', 'myuser', 'userForm', 'bank'));

//        if($myuser->customer) {
//            $stripeService = new StripeService();
//            try {
//                $account = $stripeService->getAccounts($myuser->customer);
//                $this->set(compact('account'));
//            } catch (Exception $e) {
//                $this->Flash->error('Stripeアカウント取得に失敗しました、再設定してください。');
//            }
//        }

        if($this->request->is('post')) {

            // バリデーションチェック
            $myuser_entity = $this->Myusers->patchEntity($myuser, $this->request->getData());
            $email_check = $myuser_entity->errors('email.email_unique');

            if($email_check) {
                $this->Flash->error(__($email_check));
                $this->set(compact('email_check'));
                return $this->redirect(['action' => 'detail' ]);
            }

            if ($myuser_entity->hasErrors() == false) {
                try {
                    $myuser->email = $this->request->getData('email');
                    $this->Myusers->save($myuser);
                    $this->Flash->success('メールアドレスの変更が完了しました。');
                } catch (\Exception $e) {
                    $this->Flash->error('メールアドレスの変更に失敗しました。');
                }
            } else {
                $this->Flash->error('入力データにエラーがあります');
            }

            return $this->redirect(['action' => 'detail' ]);
        }

    }

    public function accountEdit() {
        $client_id = env('CLIENT_ID');
        $uri = $this->request->getUri();
        $redirect_uri = 'https://'.$uri->getHost().'/affiliater/detail/account_confirm';

        $this->set(compact('client_id', 'redirect_uri'));
    }

    public function accountConfirm() {
        $token_request_body = array(
            'client_secret' => env('STRIPE_SECRET', 'sk_test_p8FZiwisBl1sctoLGyZtVWUW'),
            'grant_type' => 'authorization_code',
            'client_id' => env('CLIENT_ID','ca_CmUon45AueYQEKcsJ9XaBUl6NSohJMTM'),
            'code' => $this->request->query('code'),
        );

        $req = curl_init('https://connect.stripe.com/oauth/token');
        curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($req, CURLOPT_POST, true );
        curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));

        $respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);

        $res = curl_exec($req);
        $resp = json_decode($res, true);
        curl_close($req);

        try {
            $entity = $this->Myusers->get($this->Auth->user('id'));
            $entity = $this->Myusers->patchEntity($entity, ['customer' => $resp['stripe_user_id']]);
            $this->Myusers->saveOrFail($entity);
            $this->Flash->success('口座情報を登録しました');
        } catch (\Exception $exception) {
            $this->Flash->error('口座情報登録に失敗しました');
        }

        return $this->redirect(['action' => 'detail' ]);
    }

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
            $this->redirect(['controller' => 'Affiliaters', 'action' => $action, $id]);
        }
    }

    public function bankEdit() {
        $bankForm = new BankForm();
        $user = $this->Auth->user();

        $bank = $this->MyuserBanks->find('all')
            ->where(['myuser_id' => $user['id']])
            ->first();

        $depositTypeList = MyuserBank::DEPOSIT_TYPE_LIST;

        $this->set(compact('bankForm', 'bank', 'user', 'depositTypeList'));

        if(!$this->request->is('post')) {
            return null;
        }

        if (!$bank) {
            $bank = $this->MyuserBanks->newEntity();
        }

        $bank_entity = $this->MyuserBanks->patchEntity($bank, $this->request->getData());
        if(!$bankForm->execute($this->request->getData()) && $bank_entity->hasErrors()) {
            $this->Flash->error('入力データにエラーがあります');
            return null;
        }

        /**
         * @var $connection Connection
         */
        $connection = ConnectionManager::get('default');
        $connection->begin();

        try {
            if(!$this->MyuserBanks->save($bank_entity)) {
                $this->Flash->error('口座情報を登録出来ませんでした。');
                throw new Exception(Configure::read("M.ERROR.INVALID"));
            }

            $this->Flash->success('口座情報を登録致しました。');
            $connection->commit();
        } catch(\Exception $e) {
            $this->Flash->error('口座情報を登録出来ませんでした。');
            $connection->rollback();
        }
    }
}
