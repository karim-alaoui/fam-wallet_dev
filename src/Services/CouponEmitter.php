<?php

namespace App\Services;


use App\Interfaces\IChildCoupon;
use App\Model\Entity\Coupon;
use App\Model\Entity\CouponShop;
use App\Traits\Transactionable;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use PKPass\PKPass;

class DummyPass extends PKPass{
    function addFile($path, $name = null)
    {
    }

    function setData($data)
    {
        return $data;
    }

    function create($output = false)
    {
        throw new \Exception();
    }
}

class CouponEmitter {
    use Transactionable;

    /**
     * @var PKPass
     */
    private $pass;

    public function __construct()
    {
        $this->pass = new PKPass(env('certificate_path'), env('keypasswd'));
        $this->pass->setTempPath(TMP);

        if(env('APP_ENV') && env('APP_ENV') === 'testing') {
            $this->pass = new DummyPass();
        }
    }

    /**
     * @param IChildCoupon $childCoupon
     * @param $userId
     * @return bool|string|void
     */
    public function emit(IChildCoupon $childCoupon, $userId) {

        /**
         * @var $coupon Coupon
         */
        $coupon = $this->getParentCoupon($childCoupon, [
            'contain' => ['CouponShops', 'CouponShops.Shops'],
        ]);

        $data = [
            "formatVersion" => 1,
            "passTypeIdentifier" => env('passTypeID'),
            "serialNumber" => $childCoupon->getSerialNumber(),
            "teamIdentifier" => env('teamID'),
            "webServiceURL" => env('webServiceURL'),
            "authenticationToken" => $childCoupon->getAuthenticationToken(),
            "expirationDate" => $coupon->after_expiry_date,
            "barcode" => [
                "message" => $this->createBarcodeUrl($childCoupon, $userId),
                "format" => "PKBarcodeFormatQR",
                "messageEncoding" => "utf-8"
            ],
            "locations" => [
                [
                    "longitude" => (float)$coupon->longitude,
                    "latitude" => (float)$coupon->latitude,
                    "relevantText" => $coupon->relevant_text,
                ],
            ],
            "organizationName" => "Paw Planet",
            "description" => "メゾンマークーポンタイトル",
            "logoText" => $coupon->title,
            "foregroundColor" => "rgb($coupon->foreground_color)",
            "backgroundColor" => "rgb($coupon->background_color)",
            "coupon" => [
                "primaryFields" => [
                    [
                        "key"  =>"content",
                        "label" => $coupon->reword,
                        "value" => $coupon->content
                    ]
                ],
                "auxiliaryFields" => [
                    [
                        "key" => "expires",
                        "label" => "有効期限",
                        "value" => date("Y/m/d", strtotime($coupon->before_expiry_date)) . "〜". date("Y/m/d", strtotime($coupon->after_expiry_date)),
                    ]
                ],
                "backFields" => [
                    [
                        'key' => 'content',
                        'label' => '内容',
                        'value' => $coupon->content,
                    ],
                ]
            ]
        ];

        $data = $this->attachShopList($coupon, $data);

        $shopId = $coupon->coupon_shops[0]['shop_id'];
        if (file_exists(WWW_ROOT.'img/shop_images/'.$shopId.'/icon.png')) {
            $this->pass->addFile(WWW_ROOT. 'img/shop_images/'.$shopId.'/icon.png');
            $this->pass->addFile(WWW_ROOT. 'img/shop_images/'.$shopId.'/logo.png');
        } else {
            $this->pass->addFile(ROOT. '/src/wallet_resource/images/test/icon.png');
            $this->pass->addFile(ROOT. '/src/wallet_resource/images/test/icon@2x.png');
            $this->pass->addFile(ROOT. '/src/wallet_resource/images/test/logo.png');
        }

        $model = $childCoupon->getModel();

        $res = false;
        $this->callTransaction(function() use(&$res, $model, $childCoupon, $data, $userId){
            $entity = $model->find('all')->where(['serial_number' => $childCoupon->getSerialNumber()]);
            if(!$entity->count()) {
                $childCoupon->pass_type_id = env('passTypeID');
//                $model->saveOrFail($childCoupon);
            }

            $model->saveOrFail($childCoupon);

            $entity = $model->find('all')->where(['serial_number' => $childCoupon->getSerialNumber()])->first();
            $data['barcode']['message'] = $this->createBarcodeUrl($entity, $userId);
            $this->pass->setData($data);
            $res = $this->pass->create();
        });

        return $res;
    }

    /**
     * @param IChildCoupon $childCoupon
     * @param array $option
     * @return array|\Cake\Datasource\EntityInterface
     */
    private function getParentCoupon(IChildCoupon $childCoupon, $option = []) {
        $couponsTable = TableRegistry::getTableLocator()->get('Coupons');

        return $couponsTable->get($childCoupon->getParentId(), $option);
    }

    /**
     * @param IChildCoupon $childCoupon
     * @return string
     */
    private function createBarcodeUrl(IChildCoupon $childCoupon, $userId) {
        return Router::url(
            [
                'controller' => 'AffiliaterCoupons',
                'action' => 'qrDetail',
                $childCoupon->getId(),
                '?' => ['token' => $childCoupon->getToken(), 'user_id' => $userId]
            ], true);
    }

    /**
     * @param $ids
     * @return array
     */
    private function getShopList($ids) {
        return TableRegistry::getTableLocator()->get('Shops')
            ->find('all')
            ->where(['id IN' => $ids])->toArray();
    }

    /**
     * @param Coupon $coupon
     * @param $data
     * @return mixed
     */
    private function attachShopList(Coupon $coupon, $data) {
        $ids = collection($coupon->coupon_shops)->map(function(CouponShop $shop) {
            return $shop->shop_id;
        })->toArray();

        $shop_list = $this->getShopList($ids);

        $i = 1;
        foreach ($shop_list as $shop_data) {
            collection([
                ['key' => 'name', 'label' => '店舗名'],
                ['key' => 'homepage', 'label' => 'Homepage'],
                ['key' => 'line', 'label' => 'LINE'],
                ['key' => 'twitter', 'label' => 'Twitter'],
                ['key' => 'facebook', 'label' => 'Facebook'],
                ['key' => 'instagram', 'label' => 'instagram'],
            ])->map(function($x) use($shop_data){
                return $this->dataDrawer($shop_data, $x);
            })
            ->each(function($x) use(&$data, $i){
                if($x['key'] === 'name') {
                    $x['key'] = 'shopname';
                }
                $x['key'] = $x['key']. $i;
                array_push($data['coupon']['backFields'], $x);
            });
            $i++;
        }

        return $data;
    }

    /**
     * @param $data
     * @param $arr
     * @return array|null
     */
    private function dataDrawer($data, $arr) {
        if(!$data[$arr['key']]) return null;
        return [
            'key' => $arr['key'],
            'label' => $arr['label'],
            'value' => $data[$arr['key']]
        ];
    }
}
