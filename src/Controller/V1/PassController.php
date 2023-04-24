<?php
namespace App\Controller\V1;

use App\Controller\AppController;
use App\Model\Entity\AffiliaterChildCoupon;
use App\Model\Entity\Device;
use App\Model\Table\AffiliaterChildCouponsTable;
use App\Model\Table\DevicesTable;
use Cake\Event\Event;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;

/**
 * Pass Controller
 *
 *
 * @method \App\Model\Entity\Api/Pas[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PassController extends AppController
{

    /**
     * @var DevicesTable
     */
    private $Devices;

    /**
     * @var AffiliaterChildCouponsTable
     */
    private $AffiliaterChildCoupons;

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Csrf');
        $this->Devices = TableRegistry::getTableLocator()->get('Devices');
        $this->AffiliaterChildCoupons = TableRegistry::getTableLocator()->get('AffiliaterChildCoupons');
    }


    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow();
        $this->getEventManager()->off($this->Csrf);
    }

    public function registrations($deviceId, $passTypeId, $serialNumber) {
        $this->disableAutoRender();

        $params = ['deviceId' => $deviceId, 'passTypeId' => $passTypeId, 'serialNumber' => $serialNumber];
        $this->registerDevice($params);

        return $this->response->withStatus(200);
    }

    public function getDeviceRegistrations($deviceId, $passTypeID) {
        $this->disableAutoRender();

        /**
         * @var $device Device
         */
        $device = $this->Devices->find('all')->where(['device_id' => $deviceId])->first();
        if(!$device) {
            $this->response->withStatus(404);
            return;
        }

        $wallet = $this->AffiliaterChildCoupons->get($device->wallet_id);

        $res = [
            'serialNumbers' => [$wallet->serial_number],
            'lastUpdated' => $wallet->update_at
        ];

        $this->logging('info', $res);

        $this->response->withType('json')
            ->withStringBody(json_encode($res));
    }

    public function deleteDevice($deviceId, $passTypeId, $serialNumber) {
        $this->disableAutoRender();
        /**
         * @var $wallet AffiliaterChildCoupon
         */
        $wallet = $this->AffiliaterChildCoupons->find('all')->where(['serial_number' => $serialNumber])->first();

        if(!$wallet) {
            $this->logging('error', $serialNumber, 'Not Wallet E100');
            $this->response->withStatus(401);
            return;
        }

        if($wallet->pass_type_id != $passTypeId || $wallet->authentication_token != $this->getAuthToken()) {
            $this->logging('error', 'データが正しくありません E101');
            $this->response->withStatus(401);
            return;
        }

        $device = $this->Devices->find('all')->where(['wallet_id' => $wallet->id])->first();

        if(!$device) {
            $this->logging('error', 'Not Device E102');
            $this->response->withStatus(401);
            return;
        }

        $this->Devices->delete($device);
    }

    public function logAction() {
        $this->disableAutoRender();
        Log::info(json_encode($this->request->getData()));
    }

    private function registerDevice($params) {
        $pushToken = $this->request->getData('pushToken');
        $token = $this->getAuthToken();

        if(!$token) {
            $this->response->withStatus(401);
            return;
        }

        /**
         * @var $wallet AffiliaterChildCoupon
         */
        $wallet = $this->AffiliaterChildCoupons->find('all')->where(['serial_number' => $params['serialNumber']])->first();

        if(!$wallet) {
            $this->response->withStatus(401);
            return;
        }

        if($wallet->authentication_token != $token) {
            $this->response->withStatus(401);
            return;
        }

        $device = $this->Devices->find('all')->where(['wallet_id' => $wallet->id]);

        if(!$device->count()) {
            $entity = $this->Devices->newEntity();
            $entity = $this->Devices->patchEntity($entity, [
                'device_id' => $params['deviceId'],
                'wallet_id' => $wallet->id,
                'push_token' => $pushToken
            ]);
            $this->Devices->save($entity);

            $wallet->download_count = $wallet->download_count + 1;
            $this->AffiliaterChildCoupons->save($wallet);

            $this->response->withStatus(201);
            return;
        }
        $this->response->withStatus(200);
   }

   private function getAuthToken() {
       $token = $this->request->getHeader('Authorization');
       return trim(str_replace('ApplePass', '', $token[0]));
   }

   private function logging($level, ...$message) {
        $this->log(json_encode($message), $level);
   }
}
