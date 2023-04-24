<?php
use Migrations\AbstractMigration;

class CreateApplicationPoints extends AbstractMigration
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
        $table = $this->table('application_point');
        $table->addColumn('affiliater_application_id', 'integer', [
            'comment' => 'AffiliaterApplicationID',
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('affiliater_point_id', 'integer', [
            'comment' => 'AffiliaterPointID',
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->create();
    }
}
