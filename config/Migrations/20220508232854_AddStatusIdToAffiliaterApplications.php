<?php
use Migrations\AbstractMigration;

class AddStatusIdToAffiliaterApplications extends AbstractMigration
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
        $this->table('affiliater_applications')
        ->addColumn('status_id', 'integer', [
            'comment' => 'ステータス 0:申請中, 1:承認済み, 2:支払い済み, 3:支払いエラー',
            'default' => 0,
            'after' => 'company_id'
        ])->update();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table('affiliater_applications')
            ->removeColumn('status_id')
            ->update();
    }
}
