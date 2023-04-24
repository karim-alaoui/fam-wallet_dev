<?php
use Migrations\AbstractMigration;

class AddApplicationIdToAffiliaterApplications extends AbstractMigration
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
        $this->table('affiliater_applications')
        ->addColumn('application_id', 'integer', [
            'default' => 0,
            'after' => 'id',
            'null' => false
        ])->update();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table('affiliater_applications')
            ->removeColumn('application_id')
            ->update();
    }
}
