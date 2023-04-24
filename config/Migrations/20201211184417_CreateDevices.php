<?php
use Migrations\AbstractMigration;

class CreateDevices extends AbstractMigration
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
        $table = $this->table('devices');
        $table
            ->addColumn('wallet_id', 'integer')
            ->addColumn('device_id', 'string')
            ->addColumn('push_token', 'string', [
                'null' => true
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
            ]);

        $table->addIndex(['wallet_id']);

        $table->create();
    }

    public function down() {
        $this->table('devices')
            ->dropForeignKey(
                'wallet_id'
            )->save();

        $this->table('devices')->drop()->save();
    }
}
