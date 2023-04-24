<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Entity\Myuser;
use App\Model\Table\AffiliaterCouponsTable;
use App\Model\Table\CompaniesTable;
use App\Model\Table\CouponsTable;
use App\Model\Table\MyusersTable;
use App\Model\Table\RolesTable;
use App\Model\Table\ShopsTable;
use App\Test\Fixture\CouponsFixture;
use App\Test\Fixture\MyusersFixture;
use App\Test\Fixture\ShopsFixture;
use App\Test\Traits\ModelSaveTrait;
use App\Test\Traits\SettingTestTrait;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Fabricate\Fabricate;
use Fabricate\FabricateContext;
use Faker\Factory as Faker;
use Faker\Factory;
use Faker\Generator;

/**
 * App\Model\Table\MyusersTable Test Case
 */
class MyusersTableTest extends TestCase
{
    use ModelSaveTrait, SettingTestTrait;
    /**
     * Test subject
     *
     * @var \App\Model\Table\MyusersTable
     */
    public $Myusers;

    /**
     * @var CompaniesTable
     */
    public $Companies;

    /**
     * @var ShopsTable
     */
    public $Shops;

    /**
     * @var RolesTable
     */
    public $Roles;

    /**
     * @var CouponsTable
     */
    public $Coupons;

    /**
     * @var AffiliaterCouponsTable
     */
    public $AffiliaterCoupons;

    /**
     * @var Generator
     */
    public $faker;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Myusers',
        'app.Companies',
        'app.Roles',
        'app.MyuserShops',
        'app.Shops',
        'app.Coupons',
        'app.ReleaseStates',
        'app.AffiliaterCoupons'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->registerTables(['Myusers', 'Companies', 'Shops', 'Coupons', 'AffiliaterCoupons']);

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
                        'role_id' => array_rand([1,2,3]) + 1,
                        'company_id' => $data['id']
                    ]);
                }),
                'Coupons' => $world->association('Coupons', 10, function() use($data){
                    return CouponsFixture::map([
                        'company_id' => $data['id'],
                        'release_id' => 1
                    ]);
                })
            ];
        });

        $this->saveAssociation('Myusers', $com);
        $this->saveAssociation('Shops', $com);
        $this->saveAssociation('Coupons', $com);

    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        $this->unsetTables();
        parent::tearDown();
    }


    /**
     * @group myusersTable
     */
    public function test_アフィリエイター削除時にAffiliaterCouponsも削除() {
        $user = $this->Myusers->newEntity();
        $user = $this->Myusers->patchEntity($user, $this->getAffiliater());
        $this->Myusers->saveOrFail($user);

        $user = $this->Myusers->get($user->id);
        $this->Myusers->delete($user);

        $expected = $this->AffiliaterCoupons->find('all')->where('myuser_id', $user->id)->count();

        $this->assertEquals($expected, 0);
    }

    private function getAffiliater() {
        return [
            'username' => 'test',
            'company_id' => 1,
            'role_id' => 4,
            'email' => 'test@email.com',
            'password' => 'password'
        ];
    }
}
