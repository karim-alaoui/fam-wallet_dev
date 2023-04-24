<?php
namespace App\Test\TestCase\Controller;

use App\Controller\CouponsController;
use App\Model\Table\AffiliaterCouponsTable;
use App\Model\Table\CouponsTable;
use App\Test\Fixture\MyusersFixture;
use App\Test\Fixture\ShopsFixture;
use App\Test\Traits\ModelSaveTrait;
use App\Test\Traits\SettingTestTrait;
use Cake\Core\Plugin;
use Cake\I18n\Time;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use Fabricate\Fabricate;
use Fabricate\FabricateContext;

/**
 * App\Controller\CouponsController Test Case
 *
 * @uses \App\Controller\CouponsController
 */
class CouponsControllerTest extends TestCase
{
    use IntegrationTestTrait, ModelSaveTrait, SettingTestTrait;

    /**
     * @var \App\Model\Table\MyusersTable
     */
    public $Myusers;

    /**
     * @var AffiliaterCouponsTable
     */
    public $AffiliaterCoupons;

    /**
     * @var CouponsTable
     */
    public $Coupons;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Coupons',
        'app.Companies',
        'app.AffiliaterCoupons',
        'app.Myusers',
        'app.Shops',
        'app.Roles',
        'app.ReleaseStates',
        'app.CouponShops'
    ];

    private function init() {
        $companies = ['株式会社A','株式会社B','株式会社C','株式会社D','株式会社E',];
        $com = Fabricate::create('Companies', 1, function($data, FabricateContext $world) use(&$companies) {
            return [
                'name' => array_shift($companies),
                'Shops' => $world->association('Shops', 3, function() use($data) {
                    return ShopsFixture::map([
                        'company_id' => $data['id']
                    ]);
                }),
                'Myusers' => $world->association('Myusers', 5, function($d) use($data){
                    return MyusersFixture::map([
                        'role_id' => 1,
                        'company_id' => $data['id']
                    ]);
                }),
            ];
        });

        $this->saveAssociation('Myusers', $com);
        $this->saveAssociation('Shops', $com);
    }

    public function setUp()
    {
        parent::setUp();

        $this->settingsTest();
        $this->registerTables(['Coupons', 'Companies', 'CouponShops', 'AffiliaterCoupons', 'Myusers']);
        $this->init();

        $authUser = $this->Myusers->find('all')->where(['role_id' => 1])->first();

        $this->session([
            'Auth.User' => $authUser->toArray()
        ]);
    }

    public function tearDown()
    {
        $this->unsetTables();
        parent::tearDown();
    }

    /**
     * @group couponsCt
     */
    public function test_アフィリエイターバリデーションエラー() {
        $data = $this->createCouponPostData();
        $data['affiliater'] = null;
        $data['rate'] = null;
        $this->post('/coupons/new', $data);
        $this->assertEquals(
            [
                'affiliater' => ['_empty' => 'アフィリエイターを選択して下さい'],
                'rate' => ['_empty' => '報酬金額を設定してください']
            ],
            $this->viewVariable('coupon_validate')->getErrors()
        );
    }

    /**
     * @group couponsCt
     */
    public function test_is_affiliaterにチェックがなければ通る() {
        Fabricate::create('Myusers', 1, function() {
           return MyusersFixture::map([
             'role_id' => 4,
             'company_id' => 1
           ]);
        });
        $data = $this->createCouponPostData();
        $data['is_affiliater'] = null;
        $this->post('/coupons/new', $data);
        $this->assertEmpty($this->viewVariable('coupon_validate')->getErrors());
    }

    /**
     * @group couponsCt
     */
    public function test_アフィリエイタークーポン登録正常() {
        $user = Fabricate::create('Myusers', 1, function() {
            return MyusersFixture::map([
                'role_id' => 4,
                'company_id' => 1,
                'username' => 'test test2'
            ]);
        });
        $data = $this->createCouponPostData();
        $data['before_expiry_date'] = Time::now();
        $data['mode'] = 'test1';
        $this->post('/coupons/new', $data);

        $ac = $this->AffiliaterCoupons->find('all')->where(['myuser_id' => $user[0]->id]);
        $this->assertEquals($ac->count(), 1);
        $this->assertEquals($ac->first()->rate, 10);
        $this->assertEquals($ac->first()->type, 1);
    }

    /**
     * @group couponsCt
     */
    public function test_アフィリエイター登録失敗ロールバック() {
        $user = Fabricate::create('Myusers', 1, function() {
            return MyusersFixture::map([
                'role_id' => 4,
                'company_id' => 1,
            ]);
        });
        $data = $this->createCouponPostData();
        $data['mode'] = null;
        $data['reword_type'] = 'test';
        $this->post('/coupons/new', $data);

        $ac = $this->AffiliaterCoupons->find('all')->where(['myuser_id' => $user[0]->id]);
        $this->assertEquals($ac->count(), 0);

        $coupon = $this->Coupons->find('all')->where(['title' => 'testクーポン']);
        $this->assertEquals($coupon->count(), 0);

    }

    /**
     * @group couponsCt
     */
    public function test_クーポン削除時紐づくアフィリエイタークーポンも削除() {
        $data = $this->createCouponPostData();

        $e = $this->Coupons->newEntity($data);
        $d = $this->Coupons->save($e);

        $this->delete('/coupons/delete/'. $d->id);

        $expected = $this->Coupons->find('all')->where(['id' => 1]);
        $this->assertEquals($expected->count(), 0);

        $expected = $this->AffiliaterCoupons->find('all')->where(['myuser_id' => 1, 'coupon_id' => 1]);
        $this->assertEquals($expected->count(), 0);

    }

    private function createCouponPostData() {
        return [
            'title' => 'testクーポン',
            'content' => 'test content',
            'reword' => '10%OFF',
            'limit' => 5,
            'shop_id' => [1],
            'after_save_data' => [1],
            "before_expiry_date" => Time::now()->addDays(1),
            "after_expiry_date" => Time::now()->addDays(10),
            "white" => "0",
            "black" => "",
            "red" => "",
            "blue" => "",
            "green" => "",
            "yellow" => "",
            "orange" => "",
            "purple" => "",
            "pink" => "",
            "brown" => "",
            "relevant_text" => "test",
            "address" => "",
            "is_affiliate" => "1",
            "reword_type" => "1",
            "rate" => "10",
            "affiliater" => "1",
            "company_id" => "1",
            "foreground_color" => "null",
            "background_color" => "null",
            "latitude" => "",
            "longitude" => "",
            "token" => "5fc8a53d0c5ca0.54890038",
            'mode' => 'confirm',
        ];
    }

}
