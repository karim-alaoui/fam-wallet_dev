<?php
use Migrations\AbstractMigration;

class CreateAffiliaterCoupons extends AbstractMigration
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
        $table = $this->table('affiliater_coupons');
        $table
            ->addColumn('myuser_id', 'integer', [
                'null' => false,
                'limit' => 11
            ])
            ->addColumn('coupon_id', 'integer', [
                'null' => false,
                'limit' => 11
            ])
            ->addColumn('hide', 'boolean', [
                'default' => false
            ])
            ->addColumn('type', 'integer', [
                'comment' => '1:料率方式,2:固定金額方式'
            ])
            ->addColumn('rate', 'integer', [
                'comment' => '報酬額'
            ])
            ->addColumn('create_at', 'datetime', [
                'comment' => '作成日',
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('update_at', 'datetime', [
                'comment' => '更新日',
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(['myuser_id'])
            ->addIndex(['coupon_id'])
        ;
        $table->create();
    }

    public function down() {
        $this->table('affiliater_coupons')
            ->dropForeignKey(
                'coupon_id'
            )
            ->dropForeignKey(
                'myuser_id'
            )->save();

        $this->table('affiliater_coupons')->drop()->save();
    }
}
