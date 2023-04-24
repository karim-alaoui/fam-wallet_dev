<?php
namespace App\Test\TestCase\Controller;

use App\Controller\AffiliaterCouponsController;
use App\Model\Entity\AffiliaterCoupon;
use App\Model\Entity\Shop;
use App\Model\Table\AffiliaterChildCouponsTable;
use App\Model\Table\AffiliaterCouponsTable;
use App\Model\Table\CouponShopsTable;
use App\Model\Table\CouponsTable;
use App\Model\Table\MyusersTable;
use App\Model\Table\ShopsTable;
use App\Test\Fixture\AffiliaterCouponsFixture;
use App\Test\Fixture\AffiliaterPointsFixture;
use App\Test\Fixture\CouponsFixture;
use App\Test\Fixture\CouponShopsFixture;
use App\Test\Fixture\MyusersFixture;
use App\Test\Fixture\MyuserShopsFixture;
use App\Test\Fixture\ShopsFixture;
use App\Test\Traits\ModelSaveTrait;
use App\Test\Traits\SettingTestTrait;
use Cake\I18n\Time;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use Cake\Utility\Text;
use Fabricate\Fabricate;
use Fabricate\FabricateContext;

/**
 * App\Controller\AffiliaterCouponsController Test Case
 *
 * @uses \App\Controller\AffiliaterCouponsController
 */
class AffiliaterCouponsControllerTest extends TestCase
{
    use IntegrationTestTrait, ModelSaveTrait, SettingTestTrait;

    /**
     * @var MyusersTable
     */
    private $Myusers;

    /**
     * @var AffiliaterCouponsTable
     */
    private $AffiliaterCoupons;

    /**
     * @var ShopsTable
     */
    private $Shops;

    /**
     * @var CouponsTable
     */
    private $Coupons;

    /**
     * @var CouponShopsTable
     */
    private $CouponShops;

    /**
     * @var AffiliaterChildCouponsTable
     */
    private $AffiliaterChildCoupons;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.AffiliaterCoupons',
        'app.Myusers',
        'app.Coupons',
        'app.AffiliaterPoints',
        'app.Companies',
        'app.Shops',
        'app.Roles',
        'app.ReleaseStates',
        'app.AffiliaterApplications',
        'app.AffiliaterChildCoupons',
        'app.CouponShops',
        'app.MyuserShops'
    ];

    private function init() {
        $companies = ['株式会社A','株式会社B','株式会社C','株式会社D','株式会社E',];
        $com = Fabricate::create('Companies', 1, function($data, FabricateContext $world) use(&$companies) {
            return [
                'name' => array_shift($companies),
                'Shops' => $world->association('Shops', 3, function() use($data, $world) {
                    return ShopsFixture::map([
                        'company_id' => $data['id'],
                    ]);
                }),
                'Myusers' => $world->association('Myusers', 5, function($d) use($data){
                    return MyusersFixture::map([
                        'role_id' => 1,
                        'company_id' => $data['id']
                    ]);
                }),
                'Coupons' => $world->association('Coupons', 1, function() use($data){
                    return CouponsFixture::map([
                        'company_id' => $data['id'],
                        'limit' => 10,
                    ]);
                })
            ];
        });

        $authUser = Fabricate::create('Myusers', 1, function($data, FabricateContext $world){
            return [
                'role_id' => 4,
                'company_id' => 1,
                'email' => 'test@mail.com',
                'AffiliaterCoupons' => $world->association('AffiliaterCoupons', 1, function() use($data){
                    return AffiliaterCouponsFixture::map([
                        'myuser_id' => $data['id'],
                        'coupon_id' => 1
                    ]);
                }),
                'AffiliaterPoints' => $world->association('AffiliaterPoints', 10, function() use($data) {
                    return AffiliaterPointsFixture::map([
                        'myuser_id' => $data['id'],
                        'affiliater_coupon_id' => 1
                    ]);
                }),
                'MyuserShops' => $world->association('MyuserShops', 1, function() use($data){
                    return MyuserShopsFixture::map([
                       'myuser_id' => $data['id'],
                       'shop_id' => 1
                    ]);
                })
            ];
        });

        $pointSum = collection($authUser[0]['AffiliaterPoints'])
            ->sumOf('point');
        $e = $this->Myusers->patchEntity($this->Myusers->get(1), ['point' => $pointSum]);
        $this->Myusers->save($e);
        $this->session([
            'Auth.User' => $this->Myusers->get(1)->toArray()
        ]);

        $this->saveAssociation('Myusers', $com);
        $this->saveAssociation('Shops', $com);
        $this->saveAssociation('Coupons', $com);

        $this->saveAssociation('AffiliaterCoupons', $authUser);
        $this->saveAssociation('AffiliaterPoints', $authUser);
        $this->saveAssociation('MyuserShops', $authUser);

        $this->Shops->find('all')->each(function(Shop $shop) {
            $c = $this->Coupons->find('all')->shuffle()->first();

            $entity = $this->CouponShops->newEntity([
                'coupon_id' => $c->id,
                'shop_id' => $shop->id
            ]);
            $this->CouponShops->save($entity);
        });
    }

    public function setUp()
    {
        parent::setUp();

        $this->settingsTest();
        $this->registerTables([
            'AffiliaterPoints',
            'Myusers',
            'AffiliaterCoupons',
            'Shops',
            'Coupons',
            'CouponShops',
            'AffiliaterChildCoupons'
        ]);
        $this->init();
    }

    public function tearDown()
    {
        Time::setTestNow();
        $this->unsetTables();
        parent::tearDown();
    }

    /**
     * @group AffiliaterCouponsCt
     */
    public function test_qrcode生成失敗transaction確認() {
        $uuid = Text::uuid();
        $this->get('/affiliater-coupons/qrcode/1/1?serial_number='. $uuid);

        $d = $this->AffiliaterChildCoupons->find('all')->where(['serial_number' => $uuid])->first();

        $this->assertEmpty($d);
    }

    /**
     * @group AffiliaterCouponsCt
     */
    public function test_qrConfirm最大使用数超過() {
        $this->enableRetainFlashMessages();
        $this->createAffiliaterChildCoupon(['used_count' => 10]);

        $this->get('/affiliater-coupons/qr-confirm/1');

        $this->assertFlashMessage('クーポン利用制限が超過しています');
    }

    /**
     * @group AffiliaterCouponsCt
     */
    public function test_qrConfirm期限切れ() {
        Time::setTestNow(Time::now()->addDays(1));
        $this->enableRetainFlashMessages();
        $this->createAffiliaterChildCoupon();

        $c = $this->Coupons->get(1);
        $c->after_expiry_date = Time::now()->addDays(-1);
        $this->Coupons->save($c);

        $this->get('/affiliater-coupons/qr-confirm/1');

        $this->assertFlashMessage('クーポンの有効期限が切れています');
    }

    /**
     * @group AffiliaterCouponsCt
     */
    public function test_qrConfirm非公開() {
        $this->enableRetainFlashMessages();
        $this->createAffiliaterChildCoupon();

        $c = $this->Coupons->get(1);
        $c->release_id = 2;
        $this->Coupons->save($c);

        $this->get('/affiliater-coupons/qr-confirm/1');

        $this->assertFlashMessage('このクーポンは非公開の為、使用出来ません。');
    }

    /**
     * @group AffiliaterCouponsCt
     */
    public function test_qrConfirmToken相違() {
        $this->enableRetainFlashMessages();
        $this->createAffiliaterChildCoupon();

        $this->get('/affiliater-coupons/qr-confirm/1?token=test');

        $this->assertFlashMessage('不正なパラメータです。');
    }

    /**
     * @group AffiliaterCouponsCt
     */
    public function test_qrConfirm正常() {
        $this->enableRetainFlashMessages();
        $this->createAffiliaterChildCoupon(['token' => 'test']);

        $c = $this->AffiliaterCoupons->get(1);
        $c = $this->AffiliaterCoupons->patchEntity($c, ['hide' => false, 'type' => AffiliaterCoupon::TYPE_FIXED, 'rate' => 200]);
        $this->AffiliaterCoupons->save($c);
        $point = $this->Myusers->get(1)->point + 200;

        $this->get('/affiliater-coupons/qr-confirm/1?token=test');

        $d = $this->Myusers->get(1);
        $this->assertEquals($point, $d->point);

        $d = $this->AffiliaterChildCoupons->get(1);
        $this->assertEquals(1, $d->used_count);

    }

//    /**
//     * @group AffiliaterCouponsCt
//     */
//    public function test_クーポン使用可能期日前() {
//        Time::setTestNow(Time::now()->addDays(1));
//        $c = $this->Coupons->get(1);
//        $c->before_expiry_date = Time::now()->addDays(10);
//        $c->after_expiry_date = Time::now()->addDays(20);
//        $c = $this->Coupons->save($c);
//        $this->enableRetainFlashMessages();
//        $this->createAffiliaterChildCoupon();
//
//        $this->get('/affiliater-coupons/qr-confirm/1?token=test');
//
//        $date = date('Y-m-d', strtotime($c->before_expiry_date));
//        $this->assertFlashMessage('このクーポンは'. $date. 'から使用可能です');
//
//        Time::setTestNow();
//        $c->before_expiry_date = Time::now()->addDays(-1);
//        $c->after_expiry_date = Time::now()->addDays(10);
//        $this->Coupons->save($c);
//
//    }

    private function createAffiliaterChildCoupon($data = []) {
        $uuid = Text::uuid();
        $entity = $this->AffiliaterChildCoupons->newEntity(
            array_merge([
                'serial_number' => $uuid,
                'authentication_token' => Text::uuid(),
                'token' => Text::uuid(),
                'parent_id' => 1,
                'affiliater_coupon_id' => 1,
                'pass_type_id' => env('passTypeID')
            ], $data)
        );

        $this->AffiliaterChildCoupons->save($entity);
    }

}
