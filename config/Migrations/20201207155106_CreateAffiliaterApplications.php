<?php
use Migrations\AbstractMigration;

class CreateAffiliaterApplications extends AbstractMigration
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
        $table
            ->addColumn('myuser_id', 'integer', [
                'null' => false,
                'limit' => 11
            ])
            ->addColumn('point', 'integer')
            ->addColumn('is_transferred', 'boolean', [
                'default' => false,
                'comment' => '振込済み'
            ])
            ->addColumn('create_at', 'datetime', [
                'comment' => '作成日',
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('update_at', 'datetime', [
                'comment' => '更新日',
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(['myuser_id'])
        ;
        $table->create();
    }

    public function down() {
        $this->table('affiliater_applications')
            ->dropForeignKey(
                'myuser_id'
            )->save();

        $this->table('affiliater_applications')->drop()->save();
    }
}
