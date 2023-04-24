<?php
use Migrations\AbstractSeed;
use Fabricate\Fabricate;
use Fabricate\FabricateContext;

/**
 * Affiliaters seed.
 */
class AffiliatersSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $myusers = \Cake\ORM\TableRegistry::getTableLocator()->get('Myusers');
        $point = \Cake\ORM\TableRegistry::getTableLocator()->get('AffiliaterPoints');
        $data = \App\Test\Fixture\MyusersFixture::map([
            'role_id' => 4,
            'password' => 'password',
            'username' => 'affiliater',
            'company_id' => 1,
            'active' => 1,
            'token' => null
        ]);

        $table = $this->table('myusers');
        $myusers->deleteAll(['role_id' => 4]);
        $table->insert($data)->save();

        $user = $myusers->find('all')->where(['role_id' => 4])->first();

        $data = \App\Test\Fixture\AffiliaterCouponsFixture::map([
           'myuser_id' => $user->id,
           'coupon_id' => 1,
            'type' => 1,
        ]);

        $table = $this->table('affiliater_coupons');
        $table->truncate();
        $table->insert($data)->save();

        $data = [];
        collection(range(5, 10))
            ->each(function() use(&$data, $user){
                array_push(
                    $data,
                    \App\Test\Fixture\AffiliaterPointsFixture::map([
                        'myuser_id' => $user->id,
                        'affiliater_coupon_id' => 1
                    ])
                );
            });

        $table = $this->table('affiliater_points');
        $table->truncate();
        $table->insert($data)->save();

        $p = $point->find('all')->sumOf('point');
        $user = $myusers->patchEntity($user, ['point' => $p]);
        $myusers->save($user);
    }
}
