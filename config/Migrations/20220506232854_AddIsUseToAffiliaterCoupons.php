<?php
use Migrations\AbstractMigration;

class AddIsUseToAffiliaterCoupons extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $this->table('affiliater_coupons')
        ->addColumn('is_use', 'boolean', [
            'comment' => '自身が使用',
            'default' => false,
            'after' => 'coupon_id'
        ])->update();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table('affiliater_coupons')
            ->removeColumn('is_use')
            ->update();
    }
}
