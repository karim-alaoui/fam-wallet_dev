<?php
use Migrations\AbstractSeed;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Datasource\ConnectionManager;

/**
 * Dummy seed.
 */
class DummySeed extends AbstractSeed
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
      $connection = ConnectionManager::get('default');
      $datetime = date('Y-m-d H:i:s');
      // fakerインスタンスを生成する
      $faker = Faker\Factory::create('ja_JP');

      // companiesテーブル
      $companies_data = [
        [
          'name' => '株式会社AAAA',
          'create_at' => $datetime,
          'update_at' => $datetime,
        ],
        [
          'name' => '株式会社BBBB',
          'create_at' => $datetime,
          'update_at' => $datetime,
        ],
        [
          'name' => '株式会社CCCC',
          'create_at' => $datetime,
          'update_at' => $datetime,
        ],
        [
          'name' => '株式会社DDDD',
          'create_at' => $datetime,
          'update_at' => $datetime,
        ],
        [
          'name' => '株式会社EEEE',
          'create_at' => $datetime,
          'update_at' => $datetime,
        ],
        [
          'name' => '株式会社FFFF',
          'create_at' => $datetime,
          'update_at' => $datetime,
        ],
        [
          'name' => '株式会社GGGG',
          'create_at' => $datetime,
          'update_at' => $datetime,
        ],
        [
          'name' => '株式会社HHHH',
          'create_at' => $datetime,
          'update_at' => $datetime,
        ],
        [
          'name' => '株式会社IIII',
          'create_at' => $datetime,
          'update_at' => $datetime,
        ],
        [
          'name' => '株式会社JJJJ',
          'create_at' => $datetime,
          'update_at' => $datetime,
        ],
      ];

      // テーブルレコードの初期化
      $connection->execute('TRUNCATE TABLE companies');
      $table = $this->table('companies');
      $table->insert($companies_data)->save();

      // releaseテーブル
      $release_state_data = [
        [
          'title' => '公開',
          'create_at' => $datetime,
          'update_at' => $datetime,
        ],
        [
          'title' => '非公開',
          'create_at' => $datetime,
          'update_at' => $datetime,
        ]
      ];

      // テーブルレコードの初期化
      $connection->execute('TRUNCATE TABLE release_states');
      $table = $this->table('release_states');
      $table->insert($release_state_data)->save();

      $reword_state_data = [
        [
          'name' => '使用',
          'create_at' => $datetime,
          'update_at' => $datetime,
        ],
        [
          'name' => '未使用',
          'create_at' => $datetime,
          'update_at' => $datetime,
        ]
      ];

      // テーブルレコードの初期化
      $connection->execute('TRUNCATE TABLE reword_states');
      $table = $this->table('reword_states');
      $table->insert($reword_state_data)->save();

      // rolesテーブル
      $role_data = [
        [
          'name' => 'オーナー',
          'create_at' => $datetime,
          'update_at' => $datetime,
        ],
        [
          'name' => 'リーダー',
          'create_at' => $datetime,
          'update_at' => $datetime,
        ],
        [
          'name' => 'メンバー',
          'create_at' => $datetime,
          'update_at' => $datetime,
        ],
        [
          'name' => 'アフィリエイター',
          'create_at' => $datetime,
          'update_at' => $datetime,
        ]
      ];

      // テーブルレコードの初期化
      $connection->execute('TRUNCATE TABLE roles');
      $table = $this->table('roles');
      $table->insert($role_data)->save();

      // myusersテーブル
      $myuser_owner_data = [
        [
          'username' => '株式会社Aオーナー',
          'email' => 'ownerA@exsample.com',
          'company_id' => '1',
          'role_id' => '1',
          'password' => $this->_setPassword(123456),
          'active' => '1',
          'created' => $datetime,
          'modified' => $datetime,
        ],
        [
          'username' => '株式会社Bオーナー',
          'email' => 'ownerB@exsample.com',
          'company_id' => '2',
          'role_id' => '1',
          'password' => $this->_setPassword(123456),
          'active' => '1',
          'created' => $datetime,
          'modified' => $datetime,
        ],
        [
          'username' => '株式会社Cオーナー',
          'email' => 'ownerC@exsample.com',
          'company_id' => '3',
          'role_id' => '1',
          'password' => $this->_setPassword(123456),
          'active' => '1',
          'created' => $datetime,
          'modified' => $datetime,
        ],
        [
          'username' => '株式会社Dオーナー',
          'email' => 'ownerD@exsample.com',
          'company_id' => '4',
          'role_id' => '1',
          'password' => $this->_setPassword(123456),
          'active' => '1',
          'created' => $datetime,
          'modified' => $datetime,
        ],
        [
          'username' => '株式会社Eオーナー',
          'email' => 'ownerE@exsample.com',
          'company_id' => '5',
          'role_id' => '1',
          'password' => $this->_setPassword(123456),
          'active' => '1',
          'created' => $datetime,
          'modified' => $datetime,
        ],
        [
          'username' => '株式会社Fオーナー',
          'email' => 'ownerF@exsample.com',
          'company_id' => '6',
          'role_id' => '1',
          'password' => $this->_setPassword(123456),
          'active' => '1',
          'created' => $datetime,
          'modified' => $datetime,
        ],
        [
          'username' => '株式会社Gオーナー',
          'email' => 'ownerG@exsample.com',
          'company_id' => '7',
          'role_id' => '1',
          'password' => $this->_setPassword(123456),
          'active' => '1',
          'created' => $datetime,
          'modified' => $datetime,
        ],
        [
          'username' => '株式会社Hオーナー',
          'email' => 'ownerH@exsample.com',
          'company_id' => '8',
          'role_id' => '1',
          'password' => $this->_setPassword(123456),
          'active' => '1',
          'created' => $datetime,
          'modified' => $datetime,
        ],
        [
          'username' => '株式会社Iオーナー',
          'email' => 'ownerI@exsample.com',
          'company_id' => '9',
          'role_id' => '1',
          'password' => $this->_setPassword(123456),
          'active' => '1',
          'created' => $datetime,
          'modified' => $datetime,
        ],
        [
          'username' => '株式会社Jオーナー',
          'email' => 'ownerJ@exsample.com',
          'company_id' => '10',
          'role_id' => '1',
          'password' => $this->_setPassword(123456),
          'active' => '1',
          'created' => $datetime,
          'modified' => $datetime,
        ],
      ];

      // テーブル初期化
      $connection->execute('TRUNCATE TABLE myusers');
      $table = $this->table('myusers');
      $table->insert($myuser_owner_data)->save();

      // myusersリーダー
      for ($i = 1, $k = 1, $company_id = 1; $i < 51; $i++, $k++) {
 
        if ($k == 6) {
          $k = 1;
          $company_id++;
        }

        switch ($i) {
          case $i <= 5:
            $company_name = '株式会社A';
            break;
          case $i > 5 && $i <= 10:
            $company_name = '株式会社B';
            break;
          case $i > 10 && $i <= 15:
            $company_name = '株式会社C';
            break;
          case $i > 15 && $i <= 20:
            $company_name = '株式会社D';
            break;
           case $i > 20 && $i <= 25:
             $company_name = '株式会社E';
             break;
           case $i > 25 && $i <= 30:
             $company_name = '株式会社F';
             break;
           case $i > 30 && $i <= 35:
             $company_name = '株式会社G';
             break;
           case $i > 35 && $i <= 40:
             $company_name = '株式会社H';
             break;
           case $i > 40 && $i <= 45:
             $company_name = '株式会社I';
             break;
           default:
             $company_name = '株式会社J';
        }

        $myuser_leader_data[] = [
          'username' => $company_name . 'リーダー'.$i,
          'email' => $i. $faker->safeEmail,
          'company_id' => $company_id,
          'role_id' => '2',
          'password' => $this->_setPassword(123456),
          'active' => '1',
          'created' => $datetime,
          'modified' => $datetime,
        ];
      }
      $table->insert($myuser_leader_data)->save();

      // myusersメンバー
      for ($i = 1, $k = 1, $company_id = 1; $i < 51; $i++, $k++) {
 
        if ($k == 6) {
          $k = 1;
          $company_id++;
        }

        switch ($i) {
          case $i <= 5:
            $company_name = '株式会社A';
            break;
          case $i > 5 && $i <= 10:
            $company_name = '株式会社B';
            break;
          case $i > 10 && $i <= 15:
            $company_name = '株式会社C';
            break;
          case $i > 15 && $i <= 20:
            $company_name = '株式会社D';
            break;
           case $i > 20 && $i <= 25:
             $company_name = '株式会社E';
             break;
           case $i > 25 && $i <= 30:
             $company_name = '株式会社F';
             break;
           case $i > 30 && $i <= 35:
             $company_name = '株式会社G';
             break;
           case $i > 35 && $i <= 40:
             $company_name = '株式会社H';
             break;
           case $i > 40 && $i <= 45:
             $company_name = '株式会社I';
             break;
           default:
             $company_name = '株式会社J';
        }

        $myuser_member_data[] = [
          'username' => $company_name . 'メンバー'.$i,
          'email' => $i. $faker->safeEmail,
          'company_id' => $company_id,
          'role_id' => '3',
          'password' => $this->_setPassword(123456),
          'active' => '1',
          'created' => $datetime,
          'modified' => $datetime,
        ];
      }
      $table->insert($myuser_member_data)->save();

      // shopテーブル
      for ($i = 1, $k = 1, $company_id = 0;  $i < 231; $i++, $company_id++) {

        if ($company_id == 23) {
          $company_id = 0;
          $k++;
        }

        switch ($i) {
          case $i <= 23:
            $shop_name = '株式会社A ';
            break;
          case $i > 23 && $i <= 46:
            $shop_name = '株式会社B ';
            break;
          case $i > 46 && $i <= 69:
            $shop_name = '株式会社C ';
            break;
          case $i > 69 && $i <= 92:
            $shop_name = '株式会社D ';
            break;
           case $i > 92 && $i <= 115:
             $shop_name = '株式会社E ';
             break;
           case $i > 115 && $i <= 138:
             $shop_name = '株式会社F ';
             break;
           case $i > 138 && $i <= 161:
             $shop_name = '株式会社G ';
             break;
           case $i > 161 && $i <= 184:
             $shop_name = '株式会社H ';
             break;
           case $i > 184 && $i <= 207:
             $shop_name = '株式会社I ';
             break;
           default:
             $shop_name = '株式会社J ';
          }

        $ramdom_phone = rand(1, 999).'-'.rand(1000, 9999).'-'.rand(1000, 9999);
        $address = $faker->prefecture . $faker->city . $faker->streetAddress;
        $shop_data[] = [
          'name' => $shop_name . $faker->randomElement(['千代田', '中央', '港', '新宿', '文京', '台東', '墨田', '江東', '品川', '目黒', '大田', '世田谷', '渋谷', '中野', '杉並', '豊島', '北', '荒川', '板橋', '練馬', '足立', '葛飾', '江戸川']) . '店',
          'company_id' => $k,
          'introdaction' => $faker->randomElement(['カラーに定評があるお店', 'カットに定評のあるお店', 'パーマに定評があるお店', '接客に定評があるお店']),
          'tel' => $faker->phoneNumber,
          'address' => $address,
          'homepage' => $faker->randomElement(['https://www.google.com/', 'https://www.yahoo.co.jp/', 'https://maisonmarc.com/']),
          'line' => 'https://line.me/',
          'twitter' => 'https://twitter.com/',
          'facebook' => 'https://www.facebook.com/',
          'instagram' => 'https://www.instagram.com/',
          'image' => $ramdom_phone,
          'create_at' => $datetime,
          'update_at' => $datetime,
        ];
      }
      // テーブルレコードの初期化
      $connection->execute('TRUNCATE TABLE shops');
      $table = $this->table('shops');
      $table->insert($shop_data)->save();

      // myuser_shopsテーブル
      for ($i = 1; $i <= 230; $i++) {
        switch ($i) {
          case $i <= 23:
            // 株式会社A
            $user_id = [11, 12, 13, 14, 15, 61, 62, 63, 64, 65];
            $own_id = 1;
            break;
          case $i > 23 && $i <= 46:
            // 株式会社B
            $user_id = [16, 17, 18, 19, 20, 66, 67, 68, 79, 70];
            $own_id = 2;
            break;
          case $i > 46 && $i <= 69:
            // 株式会社C
            $user_id = [21, 22, 23, 24, 25, 71, 72, 73, 74, 75];
            $own_id = 3;
            break;
          case $i > 69 && $i <= 92:
            // 株式会社D
            $user_id = [26, 27, 28, 29, 30, 76, 77, 78, 79, 80];
             $own_id = 4;
            break;
          case $i > 92 && $i <= 115:
            // 株式会社E
            $user_id = [31, 32, 33, 34, 35, 81, 82, 83, 84, 85];
            $own_id = 5;
            break;
          case $i > 115 && $i <= 138:
            // 株式会社F
            $user_id = [36, 37, 38, 39, 40, 86, 87, 88, 89, 90];
            $own_id = 6;
            break;
          case $i > 138 && $i <= 161:
            // 株式会社G
            $user_id = [41, 42, 43, 44, 45, 91, 92, 93, 94, 95];
            $own_id = 7;
            break;
          case $i > 161 && $i <= 184:
            // 株式会社H
            $user_id = [46, 47, 48, 49, 50, 96, 97, 98, 99, 100];
            $own_id = 8;
            break;
          case $i > 184 && $i <= 207:
            // 株式会社I
            $user_id = [51, 52, 53, 54, 55, 101, 102, 103, 104, 105];
            $own_id = 9;
            break;
          default:
            $user_id = [56, 57, 58, 59, 60, 106, 107, 108, 109, 110];
            $own_id = 10;
          }

        $myuser_shop_data[] = [
          'myuser_id' => $faker->randomElement($user_id),
          'shop_id' => $i,
          'create_at' => $datetime,
          'update_at' => $datetime,
        ];

        $own_data[] = [
          'myuser_id' => $own_id,
          'shop_id' => $i,
          'create_at' => $datetime,
          'update_at' => $datetime,
        ];
      }
      // テーブルレコードの初期化
      $connection->execute('TRUNCATE TABLE myuser_shops');
      $table = $this->table('myuser_shops');
      $table->insert($myuser_shop_data)->save();
      $table->insert($own_data)->save();

      for ($i = 1; $i <= 100; $i++) {
        $release_id = 1;
        $background_color = ['224,224,224', '51,51,51', '213,80,80', '62,122,211', '14,198,116', '245,210,28', '236,161,37', '105,58,202', '233,156,157', '112,82,63'];
        switch ($faker->randomElement($background_color)) {
          case '255,255,255':
            $foreground_color = '51,51,51';
            break;
          case '51,51,51':
            $foreground_color = '255,255,255';
            break;
          case '213,80,80':
            $foreground_color = '255,255,255';
            break;
          case '62,122,211':
            $foreground_color = '255,255,255';
            break;
          case '14,198,116':
            $foreground_color = '255,255,255';
            break;
          case '245,210,28':
            $foreground_color = '51,51,51';
            break;
          case '236,161,37':
            $foreground_color = '255,255,255';
            break;
          case '105,58,202':
            $foreground_color = '255,255,255';
            break;
          case '233,156,157':
            $foreground_color = '255,255,255';
            break;
          default:
            $foreground_color = '255,255,255';
        }
        $tmp1 = date('Y/m/d', strtotime("2020/04/30"));
        $tmp2 = date('Y/m/d', strtotime("2020/06/01"));
        $before_date = [$tmp1, $tmp2];

        $after_start_date = date('Y/m/d');
        $after_end_date = "2020/05/30";
        $after_min = strtotime($after_start_date);
        $after_max = strtotime($after_end_date);
        $after_date = rand($after_min, $after_max);
        $after_date = date('Y/m/d', $after_date);

        if ($i >= 50) {
          $release_id = 2;
        }

        $coupon_data[] = [
          'title' => 'dummy_title'.$i,
          'content' => 'dummy_content'.$i,
          'limit' => $faker->numberBetween(1, 30),
          'release_id' => $release_id,
          'reword' => 'dummy_reword'.$i,
          'address' => null,
          'longitude' => null,
          'latitude' => null,
          'relevant_text' => '',
          'background_color' => $faker->randomElement($background_color),
          'foreground_color' => $foreground_color,
          'before_expiry_date' => $faker->randomElement($before_date),
          'after_expiry_date' => $after_date,
          'company_id' => 1,
          'token' => uniqid('', true),
          'create_at' => $datetime,
          'update_at' => $datetime,
        ];

        $max_limit =  [5, 10, 15, 20, 25, 30];
        $limit = $faker->randomElement($max_limit);
        $stamp_data[] = [
          'title' => 'dummy_title'.$i,
          'content' => 'dummy_content'.$i,
          'max_limit' => $limit,
          'release_id' => $release_id,
          'address' => null,
          'longitude' => null,
          'latitude' => null,
          'relevant_text' => '',
          'background_color' => $faker->randomElement($background_color),
          'foreground_color' => $foreground_color,
          'before_expiry_date' => $faker->randomElement($before_date),
          'after_expiry_date' => $after_date,
          'company_id' => 1,
          'token' => uniqid('', true),
          'create_at' => $datetime,
          'update_at' => $datetime,
        ];

        $stamp_rewords[] = [
          'stamp_id' => $i,
          'reword' => rand(300, 500)."円OFF",
          'reword_point' => $limit,
          'create_at' => $datetime,
          'update_at' => $datetime,
        ];
      }
      // テーブルレコードの初期化
      $connection->execute('TRUNCATE TABLE coupons');
      $connection->execute('TRUNCATE TABLE stampcards');
      $connection->execute('TRUNCATE TABLE stampcard_rewords');
      $table = $this->table('coupons');
      $table->insert($coupon_data)->save();
      $table = $this->table('stampcards');
      $table->insert($stamp_data)->save();
      $table = $this->table('stampcard_rewords');
      $table->insert($stamp_rewords)->save();

      for ($i = 1; $i <= 200; $i++) {
        $shop_id = $i;
        $coupon_id = $i;
        if ($shop_id >= 23) {
          $shop_id = rand(1, 23);
        }
        if ($coupon_id >= 100) {
          $coupon_id = rand(1, 100);
        }
        $stamp_shops[] = [
          'shop_id' => $shop_id,
          'stamp_id' => $coupon_id,
          'create_at' => $datetime,
          'update_at' => $datetime,
        ];
        $coupon_shops[] = [
          'shop_id' => $shop_id,
          'coupon_id' => $coupon_id,
          'create_at' => $datetime,
          'update_at' => $datetime,
        ];
      }

      // テーブルレコードの初期化
      $connection->execute('TRUNCATE TABLE coupon_shops');
      $table = $this->table('coupon_shops');
      $table->insert($coupon_shops)->save();
      // テーブルレコードの初期化
      $connection->execute('TRUNCATE TABLE stampcard_shops');
      $table = $this->table('stampcard_shops');
      $table->insert($stamp_shops)->save();

      $connection->execute('TRUNCATE TABLE child_coupons');
      $connection->execute('TRUNCATE TABLE child_stampcards');
      $connection->execute('TRUNCATE TABLE child_stampcard_rewords');

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
