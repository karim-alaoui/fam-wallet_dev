<?php
use Migrations\AbstractMigration;

class CreateMyuserBanks extends AbstractMigration
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
        $table = $this->table('myuser_banks');
        $table->addColumn('myuser_id', 'integer', [
            'comment' => 'MyuserID',
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('account_holder_name', 'string', [
            'comment' => '口座名義 (カタカナ)',
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('bank_name', 'string', [
            'comment' => '金融機関',
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('branch', 'text', [
            'comment' => '支店名',
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('deposit_type', 'integer', [
            'comment' => '預金種類 1: 普通, 2: 当座, 3: 貯蓄',
            'default' => 1,
            'null' => false
        ]);
        $table->addColumn('account_number', 'string', [
            'comment' => '口座番号',
            'default' => null,
            'limit' => 255,
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
