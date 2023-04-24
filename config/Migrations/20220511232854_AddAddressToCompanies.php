<?php
use Migrations\AbstractMigration;

class AddAddressToCompanies extends AbstractMigration
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
        ->addColumn('address', 'text', [
            'after' => 'name',
            'null' => false
        ])->update();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table('companies')
            ->removeColumn('address')
            ->update();
    }
}
