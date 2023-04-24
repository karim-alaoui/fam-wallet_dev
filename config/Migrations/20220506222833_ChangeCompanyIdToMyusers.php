<?php
use Migrations\AbstractMigration;

class ChangeCompanyIdToMyusers extends AbstractMigration
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
        $table = $this->table('myusers');
        $table->changeColumn('company_id', 'integer', [
            'comment' => '企業ID',
            'default' => null,
            'limit' => 11,
            'null' => true,
        ]);
        $table->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}
