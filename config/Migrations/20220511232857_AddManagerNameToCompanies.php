<?php
use Migrations\AbstractMigration;

class AddManagerNameToCompanies extends AbstractMigration
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
        $this->table('companies')
        ->addColumn('manager_name', 'string', [
            'after' => 'tel',
            'null' => false
        ])->update();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table('companies')
            ->removeColumn('manager_name')
            ->update();
    }
}
