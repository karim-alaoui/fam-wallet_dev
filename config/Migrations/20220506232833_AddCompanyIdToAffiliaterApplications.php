<?php
use Migrations\AbstractMigration;

class AddCompanyIdToAffiliaterApplications extends AbstractMigration
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
        $table->addColumn('company_id', 'integer', [
            'comment' => '企業ID',
            'default' => null,
            'limit' => 11,
            'null' => false,
            'after' => 'point'
        ]);
        $table->update();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table('affiliater_applications')
            ->removeColumn('company_id')
            ->update();
    }
}
