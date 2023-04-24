<?php
use Migrations\AbstractMigration;

class AddIsAppliedToAffiliaterPoints extends AbstractMigration
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
        $this->table('affiliater_points')
        ->addColumn('is_applied', 'boolean', [
            'comment' => '申請済み',
            'default' => false,
            'after' => 'point'
        ])->update();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table('affiliater_points')
            ->removeColumn('is_applied')
            ->update();
    }
}
