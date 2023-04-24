<?php
namespace App\Test\TestCase\Controller;

use App\Controller\AffiliaterPointsController;
use App\Model\Table\MyusersTable;
use App\Test\Fixture\AffiliaterCouponsFixture;
use App\Test\Fixture\AffiliaterPointsFixture;
use App\Test\Fixture\CouponsFixture;
use App\Test\Fixture\MyusersFixture;
use App\Test\Fixture\ShopsFixture;
use App\Test\Traits\ModelSaveTrait;
use App\Test\Traits\SettingTestTrait;
use Cake\I18n\Time;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use Fabricate\Fabricate;
use Fabricate\FabricateContext;

/**
 * App\Controller\AffiliaterPointsController Test Case
 *
 * @uses \App\Controller\AffiliaterPointsController
 */
class AffiliaterPointsControllerTest extends TestCase
{
    use IntegrationTestTrait, ModelSaveTrait, SettingTestTrait;

    /**
     * @var MyusersTable
     */
    private $Myusers;
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.AffiliaterPoints',
        'app.Myusers',
        'app.AffiliaterCoupons',
        'app.Companies',
        'app.Shops',
        'app.Coupons',
        'app.Roles',
        'app.ReleaseStates',
        'app.AffiliaterApplications'
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
                'Coupons' => $world->association('Coupons', 1, function() use($data){
                    return CouponsFixture::map([
                        'company_id' => $data['id']
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
    }

    public function setUp()
    {
        parent::setUp();

        $this->settingsTest();
        $this->registerTables(['AffiliaterPoints', 'Myusers', 'AffiliaterCoupons']);
        $this->init();
    }

    public function tearDown()
    {
        Time::setTestNow();
        $this->unsetTables();
        parent::tearDown();
    }

    /**
     * @group affiliaterPoints
     * @throws \PHPUnit\Exception
     */
    public function test_総ポイントより多い換金額の場合はバリデーションエラー() {
        $user = $this->Myusers->get(1);
        $data = [
            'point' => $user->point + 1000,
            'consent' => true,
            'myuser_id' => $user->id
        ];

        $this->post('/affiliater/application', $data);

        $validate = $this->viewVariable('validate');
        $this->assertNotEmpty($validate->getErrors());
        $this->assertEquals($validate->getErrors()['point']['maxPoint'], '換金額が保有ポイントを超えています');
    }

    /**
     * @group affiliaterPoints
     * @throws \PHPUnit\Exception
     */
    public function test_換金申請正常() {
        $this->enableRetainFlashMessages();
        $user = $this->Myusers->get(1);
        $data = [
            'point' => $user->point,
            'consent' => true,
            'myuser_id' => $user->id
        ];

        $this->post('/affiliater/application', $data);

        $validate = $this->viewVariable('validate');
        $this->assertEmpty($validate->getErrors());

        $user = $this->Myusers->get(1);
        $this->assertEquals($user->point, 0);
        $this->assertFlashMessage('換金申請が完了しました。');
        $this->assertRedirect('/affiliater/application/success');
    }

    /**
     * @throws \PHPUnit\Exception
     * @group affiliaterPoints
     */
    public function test_validationエラー確認() {
        $user = $this->Myusers->get(1);
        $data = [
            'point' => 0,
            'consent' => false,
            'myuser_id' => $user->id
        ];

        $this->post('/affiliater/application', $data);

        $validate = $this->viewVariable('validate')->getErrors();
        $this->assertEquals($validate['consent']['equals'], '利用規約にご同意ください');
        $this->assertEquals($validate['point']['greaterThanOrEqual'], '換金額を入力してください');
    }

}
