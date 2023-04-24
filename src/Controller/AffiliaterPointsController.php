<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Form\AffiliaterApplicationForm;
use App\Model\Entity\AffiliaterCoupon;
use App\Model\Entity\AffiliaterPoint;
use App\Model\Table\AffiliaterApplicationsTable;
use App\Model\Table\AffiliaterCouponsTable;
use App\Model\Table\AffiliaterPointsTable;
use App\Model\Table\MyusersTable;
use App\Model\Table\MyuserBanksTable;
use App\Services\ApplicationService;
use App\Services\StripeService;
use Cake\Collection\Collection;
use Cake\Database\Connection;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;

/**
 * AffiliaterPoints Controller
 *
 *
 * @method \App\Model\Entity\AffiliaterPoint[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 * @property AffiliaterPointsTable $AffiliaterPoints
 */
class AffiliaterPointsController extends AppController
{

    /**
     * @var AffiliaterCouponsTable
     */
    private $AffiliaterCoupons;

    /**
     * @var AffiliaterApplicationsTable
     */
    private $AffiliaterApplications;

    /**
     * @var MyusersTable
     */
    private $Myusers;

    /**
     * @var MyuserBanksTable
     */
    private $MyuserBanks;

    /**
     * @var AffiliaterPointsTable
     */
    private $AffiliaterPoints;


    public function initialize() {
        parent::initialize();

        $this->AffiliaterCoupons = TableRegistry::getTableLocator()->get('AffiliaterCoupons');
        $this->AffiliaterApplications = TableRegistry::getTableLocator()->get('AffiliaterApplications');
        $this->Myusers = TableRegistry::getTableLocator()->get('Myusers');
        $this->MyuserBanks = TableRegistry::getTableLocator()->get('MyuserBanks');
        $this->AffiliaterPoints = TableRegistry::getTableLocator()->get('AffiliaterPoints');
        $this->ApplicationPoint = TableRegistry::getTableLocator()->get('ApplicationPoint');
        $this->loadComponent('Paginator');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
      $bank = $this->MyuserBanks->find()
        ->where(['myuser_id' => $this->Auth->user('id')])
        ->first();

      $hasBank = !!$bank;
      if (!$hasBank) {
        $this->Flash->error(__('換金申請をするには振込先口座を登録してください'));
        return $this->redirect(['controller' => 'Affiliaters', 'action' => 'detail']);
      }

        $query = $this->AffiliaterPoints->find()
          ->orderDesc('AffiliaterPoints.create_at')
            ->where(['AffiliaterPoints.myuser_id' => $this->Auth->user('id')])
            ->contain([
                'AffiliaterCoupons.Coupons.CouponShops' => function(Query $query) {
                    return $query->contain(['Shops' => function(Query $query) {
                        return $query->select(['id', 'name'])->limit(1);
                    }]);
                }
            ]);
        $points = $this->paginate($query);
        $point = $this->Myusers->get_point($this->Auth->user('id'));

        $affAppStatusId=[];
        $query = $this->AffiliaterPoints->find()
                ->where(['AffiliaterPoints.myuser_id' => $this->Auth->user('id')])
                ->where(['AffiliaterPoints.is_applied' => 1]);
                
        foreach ($query as $AffiliaterPoint) {
            $subQuery = $this->ApplicationPoint->find()
                ->where(['ApplicationPoint.affiliater_point_id' => $AffiliaterPoint->id]);
            foreach ($subQuery as $ApplicationPoint) {
                $affAppStatusId[$AffiliaterPoint->id]=$ApplicationPoint->affiliater_application_id;
            }
        }

        foreach ($affAppStatusId as $affiliater_point_id => $affiliater_application_id) {
            $query = $this->AffiliaterApplications->find()
                ->where(['AffiliaterApplications.id' => $affiliater_application_id]);
            foreach ($query as $AffiliaterApplications) {
                //$affAppStatusId[$affiliater_point_id]=$AffiliaterApplications->status_id;
                if($AffiliaterApplications->status_id==0){
                    $affAppStatusId[$affiliater_point_id]='Applying';
                }elseif($AffiliaterApplications->status_id==1){
                    $affAppStatusId[$affiliater_point_id]='Approved';
                }elseif($AffiliaterApplications->status_id==2){
                    $affAppStatusId[$affiliater_point_id]='Paid';
                }else{
                    $affAppStatusId[$affiliater_point_id]='Payment Error';    
                }
            }
        }



        $disabled = true;

        $myuserBank = $this->MyuserBanks
            ->find()
            ->where('myuser_id', $this->Auth->user('id'))
            ->first();

//        $user = $this->Myusers->get($this->Auth->user('id'));

//        if($user->customer) {
//            $service = new StripeService();
//            $c = $service->getAccounts($user->customer);
//            $disabled = !$c;
//        }

        if ($myuserBank) {
            $disabled = false;
        }
        
        $this->set(compact('points', 'point', 'disabled', 'affAppStatusId'));
    }

    /**
     * @return \Cake\Http\Response|void
     */
    public function application() {
        $application = $this->AffiliaterApplications->newEntity();
        $point = $this->Myusers->get_point($this->Auth->user('id'));
        $validate = new AffiliaterApplicationForm();

//        $affiliaterCoupons = $this->AffiliaterCoupons->find()
//            ->where([
//                'myuser_id' => $this->Auth->user('id'),
//                'is_use' => true,
//                'hide' => false
//            ])
//            ->map(function ($x) {
//                $q = $this->AffiliaterPoints->find()
//                    ->where([
//                        'myuser_id' => $this->Auth->user('id'),
//                        'affiliater_coupon_id' => $x->id
//                    ]);
//                $x['all_point'] = $q->sumOf('point');
//                $x['affliater_points'] = $q->select('id')->toArray();
//                return $x;
//            });

        $affiliaterCoupons = $this->AffiliaterCoupons->find()
            ->where([
                'myuser_id' => $this->Auth->user('id'),
                'is_use' => true,
                'hide' => false
            ])
            ->contain(['Coupons', 'AffiliaterPoints']);

        $coupon_list = [];
        $affiliater_points = [];

        $affiliaterCoupons->each(function ($affiliaterCoupon) use(&$coupon_list, &$affiliater_points) {
            foreach ($affiliaterCoupon->affiliater_points as $x) {
                if (!$x->is_applied) {
                    $label = $x->create_at.' '.$affiliaterCoupon->coupon->title.' '.$x->point.'P';
                    $coupon_list[$x->id] = $label;
                    array_push($affiliater_points, $x->toArray());
                }
            }
        });

        $affiliater_points = json_encode($affiliater_points);

        $this->set(compact('point', 'application', 'validate', 'coupon_list', 'affiliater_points'));

        if(!$this->request->is('post') || $this->request->getData('back')) {
            return;
        }

        if (!$validate->execute($this->request->getData())) {
            return;
        }

        if($this->request->getData('mode') === 'confirm') {
            return $this->render('application_confirm');
        }

//        $application = $this->AffiliaterApplications->patchEntity($application, $this->request->getData());
//        $connection = ConnectionManager::get('default');
//
//        $connection->rollback();
//        return;
        (new ApplicationService())->saveApplication($this);
    }

    public function success() {

    }

    public function insert($id = null) {
        $d = $this->AffiliaterCoupons->find()
            ->where(['myuser_id' => $id])
            ->contain(['Coupons'])->first();

        collection(range(1, 10))
            ->each(function() use($d){
                $p = [100, 200, 300, 350, 400];
                $arr = [
                    'myuser_id' => $d->myuser_id,
                    'affiliater_coupon_id' => $d->id,
                    'point' => $p[array_rand($p)]
                ];
                $this->AffiliaterPoints->save($this->AffiliaterPoints->newEntity($arr));
            });
    }

}
