<?php
use Migrations\AbstractSeed;

/**
 * AffiliaterCoupons seed.
 */
class AffiliaterCouponsSeed extends AbstractSeed
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
        $user = $myusers->find('all')->where(['role_id' => 4])->first();

        $couponsTable = \Cake\ORM\TableRegistry::getTableLocator()->get('Coupons');
        $coupons = $couponsTable->find('all')->where(['company_id' => 1])->toArray();

        $data = [];

        collection(range(100, 200))
            ->each(function() use(&$data, $user, $coupons){
                $d = \App\Test\Fixture\AffiliaterCouponsFixture::map([
                    'myuser_id' => $user->id,
                    'coupon_id' => collection($coupons)->shuffle()->toList()[0]->id,
                    'hide' => collection(range(0,1))->shuffle()->toList()[0]
                ]);
                array_push($data, $d);
            });

        $table = $this->table('affiliater_coupons');
        $table->truncate();
        $table->insert($data)->save();
    }
}
