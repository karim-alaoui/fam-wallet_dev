<?php
use Migrations\AbstractSeed;

/**
 * CouponsBatch seed.
 */
class CouponsBatchSeed extends AbstractSeed
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
        $couponsTable = \Cake\ORM\TableRegistry::getTableLocator()->get('Coupons');

        collection($couponsTable->find('all')->toArray())
            ->each(function(\App\Model\Entity\Coupon $coupon) use($couponsTable){
                $days = [5, 10, 20, 30];
                shuffle($days);
                $after = \Cake\I18n\Time::now()->addDays($days[0])->format('Y-m-d H:i:s');
                $before = \Cake\I18n\Time::now()->addDays('-'. $days[0])->format('Y-m-d H:i:s');
                $c = $couponsTable
                    ->patchEntity($coupon, ['after_expiry_date' => $after, 'before_expiry_date' => $before]);
                $couponsTable->save($c);
            });
    }
}
