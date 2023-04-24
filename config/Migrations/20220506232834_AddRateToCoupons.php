<?php
use Migrations\AbstractMigration;

class AddRateToCoupons extends AbstractMigration
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
        $this->table('coupons')
        ->addColumn('rate', 'integer', [
            'comment' => '報酬額',
            'default' => null,
            'null' => true,
            'after' => 'company_id'
        ])->update();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table('coupons')
            ->removeColumn('rate')
            ->update();
    }
}
