<?php
use Migrations\AbstractMigration;

class AddEmailToCompanies extends AbstractMigration
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
        ->addColumn('email', 'text', [
            'after' => 'address',
            'null' => false
        ])->update();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table('companies')
            ->removeColumn('email')
            ->update();
    }
}
