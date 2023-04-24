<?php
use Migrations\AbstractMigration;

class CreateAffiliaterPoints extends AbstractMigration
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
        $table = $this->table('affiliater_points');
        $table
            ->addColumn('myuser_id', 'integer', [
                'null' => false,
                'limit' => 11
            ])
            ->addColumn('affiliater_coupon_id', 'integer', [
                'null' => false,
                'limit' => 11
            ])
            ->addColumn('point', 'integer', [
                'null' => false
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
            ->addIndex(['affiliater_coupon_id'])
            ;
        $table->create();
    }

    public function down() {
        $this->table('affiliater_points')
            ->dropForeignKey(
                'affiliater_coupon_id'
            )
            ->dropForeignKey(
                'myuser_id'
            )->save();

        $this->table('affiliater_points')->drop()->save();
    }
}
