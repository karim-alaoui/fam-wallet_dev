<?php

namespace App\Services;

use App\Controller\AffiliaterPointsController;
use App\Model\Entity\AffiliaterApplication;
use App\Model\Entity\Application;
use App\Model\Entity\Myuser;
use App\Model\Table\AffiliaterApplicationsTable;
use App\Model\Table\AffiliaterPointsTable;
use App\Model\Table\AffiliaterCouponsTable;
use App\Model\Table\ApplicationPointTable;
use App\Model\Table\ApplicationsTable;
use App\Model\Table\MyusersTable;
use App\Model\Table\CouponsTable;
use App\Traits\Transactionable;
use Cake\Collection\Collection;
use Cake\Controller\Controller;
use Cake\Database\Connection;
use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;
use Cake\ORM\Exception\PersistenceFailedException;
use Cake\ORM\TableRegistry;
use phpDocumentor\Reflection\Types\String_;
use Stripe\Exception\CardException;
use Stripe\Exception\InvalidRequestException;

class ApplicationService {

    use Transactionable;

    /**
     * @var MyusersTable
     */
    private $Myusers;

    /**
     * @var AffiliaterApplicationsTable
     */
    private $AffiliaterApplications;

    /**
     * @var ApplicationsTable
     */
    private $Applications;

    /**
     * @var AffiliaterPointsTable
     */
    private $AffliaterPoints;

    /**
     * @var AffiliaterCouponsTable
     */
    private $AffliaterCoupons;

    /**
     * @var CouponsTable
     */
    private $Coupons;

    /**
     * @var ApplicationPointTable
     */
    private $ApplicationPoint;

    public function __construct()
    {
        $this->AffiliaterApplications = TableRegistry::getTableLocator()->get('AffiliaterApplications');
        $this->Myusers = TableRegistry::getTableLocator()->get('Myusers');
        $this->AffliaterPoints = TableRegistry::getTableLocator()->get('AffiliaterPoints');
        $this->AffliaterCoupons = TableRegistry::getTableLocator()->get('AffiliaterCoupons');
        $this->Coupons = TableRegistry::getTableLocator()->get('Coupons');
        $this->ApplicationPoint = TableRegistry::getTableLocator()->get('ApplicationPoint');
        $this->Applications = TableRegistry::getTableLocator()->get('Applications');
    }

    /**
     * ポイント換金申請
     * @param Controller $controller
     * @param AffiliaterApplication $entity
     */
    public function saveApplication(Controller $controller) {
        $this->callTransaction(function() use($controller){
//            $this->AffiliaterApplications->saveOrFail($entity);
            $myusersEntity = $this->Myusers->get($controller->Auth->user('id'));
            $point = $myusersEntity->point - $controller->request->getData('point');
            $myusersEntity = $this->Myusers->patchEntity($myusersEntity, ['point' => $point]);
            $this->Myusers->saveOrFail($myusersEntity);

            $application = $this->addApplication($controller);

            $this->addAffliaterApplications($controller, $application);

            $this->appliedAffiliaterPoints($controller);

            $controller->Flash->success('換金申請が完了しました。');
            return $controller->redirect('/affiliater/application/success');
        }, function($e) use($controller){
            $controller->Flash->error('換金申請に失敗しました。');
        });

    }

    /**
     * 個別で店舗が支払うボタンを押した時
     */
    public function transferApplication(Controller $controller, $entity, $redirectPath='affiliaters') {
        $res = null;
        $this->callTransaction(function() use($entity ,$controller, &$res, $redirectPath) {

            $user = $this->Myusers->get($entity->myuser_id);
            $owner = $this->Myusers->find()
                ->where(['company_id' => $entity->company_id])
                ->where(['role_id' => Myuser::ROLE_OWNER])
                ->first();

            if(!$owner) {
                $controller->Flash->error('該当するオーナーの取得に失敗しました。[EO100]');
                return;
            }

            if(!$owner->customer) {
                $controller->Flash->error('オーナーのクレジット情報がありません');
                return;
            }

            $service = new StripeService();

            $res = $service->charge($owner, $user, [
                'amount' => $entity->point,
                'application_fee' => AffiliaterApplication::TRANSFER_FEE,
                'description' => 'userID: '.$user->id.' name: '.$user->username.' email: '.$user->email
            ]);

            $entity = $this->AffiliaterApplications->patchEntity($entity, [
                'status_id' => AffiliaterApplication::STATUS_ID_PAID,
                'charge_id' => $res->id
            ]);
            if(!$this->AffiliaterApplications->save($entity)) {
                $service->refund($res->id);
                throw new \Exception();
            }

            $res->confirm();

            $controller->Flash->success('支払いが完了しました');
            $controller->redirect($redirectPath);

        }, function(\Exception $exception) use($controller, $entity){
            if(get_class($exception) === InvalidRequestException::class ||
                get_class($exception) === CardException::class) {
                $code = $exception->getError()->code;

                $entity = $this->AffiliaterApplications->patchEntity($entity, [
                    'status_id' => AffiliaterApplication::STATUS_ID_ERROR,
                ]);
                if(!$this->AffiliaterApplications->save($entity)) {
                    throw new \Exception();
                }

                if(get_class($exception) === InvalidRequestException::class) {
                    $controller->Flash->error('不正なパラメーターです [EO103]');
                } else {
                    $controller->Flash->error('クレジットカードが使用できません [EO102]');
                }
                $msg = $exception->getMessage();
                Log::error("EO102 $msg/ [StripeCode]: $code");
                return;
            }

            $controller->Flash->error('送金処理に失敗しました。[EO101]');
        });

        return $res;
    }

    /**
     * 店舗が管理者に支払うボタンを押した時
     */
    public function transferApplicationToAdmin(Controller $controller, AffiliaterApplication $entity, $redirectPath='affiliaters') {
        $res = null;
        $this->callTransaction(function() use($entity ,$controller, &$res, $redirectPath) {

            $user = $this->Myusers->get($entity->myuser_id);
            $owner = $this->Myusers->find('all')
                ->where(['company_id' => $user->company_id])
                ->where(['role_id' => Myuser::ROLE_OWNER])
                ->first();

            if(!$owner) {
                $controller->Flash->error('該当するオーナーの取得に失敗しました。[EO100]');
                return;
            }

            if(!$owner->customer) {
                $controller->Flash->error('クレジット情報を設定してください');
                return;
            }

            $service = new StripeService();

            $res = $service->charge($owner, $user, [
                'amount' => $entity->point,
                'application_fee' => AffiliaterApplication::TRANSFER_FEE,
            ]);

            $entity = $this->AffiliaterApplications->patchEntity($entity, [
                'charge_id' => $res->id, 'is_transferred' => true
            ]);

            if(!$this->AffiliaterApplications->save($entity)) {
                $service->refund($res->id);
                throw new \Exception();
            }

            $res->confirm();

            $controller->Flash->success('支払いが完了しました');
            $controller->redirect($redirectPath);

        }, function(\Exception $exception) use($controller){
            if(get_class($exception) === InvalidRequestException::class ||
                get_class($exception) === CardException::class) {
                $code = $exception->getError()->code;

                if(get_class($exception) === InvalidRequestException::class) {
                    $controller->Flash->error('不正なパラメーターです [EO103]');
                } else {
                    $controller->Flash->error('クレジットカードが使用できません [EO102]');
                }
                $msg = $exception->getMessage();
                Log::error("EO102 $msg/ [StripeCode]: $code");
                return;
            }

            $controller->Flash->error('送金処理に失敗しました。[EO101]');
        });

        return $res;
    }


    private function getActualAmount(AffiliaterApplication $entity) {
        return $entity->point - AffiliaterApplication::TRANSFER_FEE;
    }

    private function addApplication(Controller $controller) {
        $affliaterPointsIds = json_decode($controller->request->getData('coupon_id_list'));
        if (!count($affliaterPointsIds)) {
            throw new \Exception();
        }

        $point = 0;
        foreach ($affliaterPointsIds as $id) {
            $affliaterPoint = $this->AffliaterPoints->get($id);
            $point += $affliaterPoint->point;
        }

        $saveModel = [
            'myuser_id' => $controller->Auth->user('id'),
            'status_id' => Application::STATUS_ID_UNPAID,
            'point' => $point
        ];

        $application = $this->Applications->newEntity();
        $entity = $this->Applications->patchEntity($application, $saveModel);
        return $this->Applications->saveOrFail($entity);
    }

    /**
     * 店舗ごとの申請を作成
     * @param Controller $controller
     */
    private function addAffliaterApplications(Controller $controller, $application) {
        $affliaterPointsIds = json_decode($controller->request->getData('coupon_id_list'));
        if (!count($affliaterPointsIds)) {
            throw new \Exception();
        }

        $affliaterApplications = [];
        $applicationPoints = [];
        foreach ($affliaterPointsIds as $id) {
            $affliaterPoint = $this->AffliaterPoints->get($id);
            $affiliaterCoupon = $this->AffliaterCoupons->get($affliaterPoint->affiliater_coupon_id);
            $coupon =  $this->Coupons->get($affiliaterCoupon->coupon_id);

            $saveModel = [
                'application_id' => $application->id,
                'myuser_id' => $affiliaterCoupon->myuser_id,
                'company_id' => $coupon->company_id,
                'point' => $affliaterPoint->point
            ];

            if (!count($affliaterApplications)) {
                array_push($affliaterApplications, $saveModel);
                array_push($applicationPoints, [$id]);
            } else {
                foreach ($affliaterApplications as $key => $affliaterApplication) {
                    if ($affliaterApplication['myuser_id'] == $saveModel['myuser_id']
                        && $affliaterApplication['company_id'] == $saveModel['company_id']) {
                        $affliaterApplications[$key]['point'] = $saveModel['point'] + $affliaterApplication['point'];

                        array_push($applicationPoints[$key], $id);
                    } else {
                        array_push($affliaterApplications, $saveModel);
                        array_push($applicationPoints, [$id]);
                    }
                }
            }
        }

        foreach ($affliaterApplications as $key => $affliaterApplication) {
            $application = $this->AffiliaterApplications->newEntity();
            $entity = $this->AffiliaterApplications->patchEntity($application, $affliaterApplication);
            $model = $this->AffiliaterApplications->saveOrFail($entity);

            // 申請とポイント取得履歴の中間テーブル作成
            foreach ($applicationPoints[$key] as $applicationPoint) {
                $data = [
                    'affiliater_application_id' => $model->id,
                    'affiliater_point_id' => intval($applicationPoint)
                ];

                $ent = $this->ApplicationPoint->newEntity();
                try {
                    $ent = $this->ApplicationPoint->patchEntity($ent, $data);
                    $this->ApplicationPoint->saveOrFail($ent);
                } catch (\Exception $e) {
                    throw $e;
                }
            }
        }
    }

    private function appliedAffiliaterPoints(Controller $controller) {
        $affliaterPointsIds = json_decode($controller->request->getData('coupon_id_list'));

        foreach ($affliaterPointsIds as $id) {
            $affliaterPointsEntity = $this->AffliaterPoints->get($id);
            $affliaterPointsEntity = $this->AffliaterPoints->patchEntity($affliaterPointsEntity, ['is_applied' => true]);
            $this->AffliaterPoints->saveOrFail($affliaterPointsEntity);
        }
    }
}
