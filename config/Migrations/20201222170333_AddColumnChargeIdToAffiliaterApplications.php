<?php
use Migrations\AbstractMigration;

class AddColumnChargeIdToAffiliaterApplications extends AbstractMigration
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
        $table = $this->table('affiliater_applications');
        $table->addColumn('charge_id', 'string', [
           'null' => true,
           'after' => 'is_transferred'
        ]);
        $table->update();
    }
}
