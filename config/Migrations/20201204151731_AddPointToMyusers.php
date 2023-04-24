<?php
use Migrations\AbstractMigration;

class AddPointToMyusers extends AbstractMigration
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
        $table->addColumn('point', 'integer', [
            'default' => 0,
            'after' => 'active'
        ]);
        $table->update();
    }

    public function down() {
        $table = $this->table('myusers');
        $table->removeColumn('point');
    }
}
