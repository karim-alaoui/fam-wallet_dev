<?php
use Migrations\AbstractMigration;

class CreateAffiliaterChildCoupons extends AbstractMigration
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
        $table = $this->table('affiliater_child_coupons');
        $table->addColumn('parent_id', 'integer')
            ->addColumn('affiliater_coupon_id', 'integer')
            ->addColumn('serial_number', 'string')
            ->addColumn('authentication_token', 'string')
            ->addColumn('download_count', 'integer', ['default' => 0])
            ->addColumn('used_count', 'integer', ['default' => 0])
            ->addColumn('dir_path', 'string', ['null' => true])
            ->addColumn('token', 'string')
            ->addColumn('pass_type_id', 'string')
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
            ]);

        $table->addIndex(['parent_id']);
        $table->create();
    }

    public function down() {
        $this->table('affiliater_child_coupons')
            ->dropForeignKey(
                'coupon_id'
            )->save();

        $this->table('affiliater_child_coupons')->drop()->save();
    }
}
