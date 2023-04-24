<?php
use Migrations\AbstractMigration;

class AddRewordToStampcards extends AbstractMigration
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
        $this->table('stampcards')
        ->addColumn('reword', 'string', [
            'after' => 'content',
            'null' => false
        ])->update();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table('stampcards')
            ->removeColumn('reword')
            ->update();
    }
}
