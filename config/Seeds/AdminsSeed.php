<?php
use Migrations\AbstractSeed;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Datasource\ConnectionManager;

/**
 * Dummy seed.
 */
class AdminsSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
      // 外部キー制約解除
      $this->execute('SET FOREIGN_KEY_CHECKS = 0');
      $datetime = date('Y-m-d H:i:s');

      // myusersテーブル
      $myuser_owner_data = [
        [
          'username' => '管理者',
          'email' => 'admin@exsample.com',
          'company_id' => '1',
          'role_id' => \App\Model\Entity\Myuser::ROLE_ADMIN,
          'password' => $this->_setPassword(123456),
          'active' => '1',
          'created' => $datetime,
          'modified' => $datetime,
        ],
      ];

      $table = $this->table('myusers');
      $table->insert($myuser_owner_data)->save();

      # 外部キー制約戻す
      $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }

    // パスワードハッシュ化
    public function _setPassword($value)
    {
      $hasher = new DefaultPasswordHasher();
      return $hasher->hash($value);
    }
}
