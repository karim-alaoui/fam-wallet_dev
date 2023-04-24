<?php
use Migrations\AbstractMigration;

class AddFirstMigration extends AbstractMigration
{
    public function up()
    {

        $this->table('child_coupons')
            ->addColumn('parent_id', 'integer', [
                'comment' => '親クーポン',
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('serial_number', 'string', [
                'comment' => '識別番号',
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('authentication_token', 'string', [
                'comment' => '照合トークン',
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('limit_count', 'integer', [
                'comment' => '使用回数',
                'default' => '0',
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('dir_path', 'string', [
                'comment' => '保存先',
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('token', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
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
            ->addIndex(
                [
                    'parent_id',
                ]
            )
            ->create();

        $this->table('child_stampcard_rewords')
            ->addColumn('parent_id', 'integer', [
                'comment' => '親ID',
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('child_id', 'integer', [
                'comment' => 'スタンプID',
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('reword_point', 'integer', [
                'comment' => '特典条件',
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('state_id', 'integer', [
                'comment' => '使用状態',
                'default' => null,
                'limit' => 11,
                'null' => false,
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
            ->addIndex(
                [
                    'child_id',
                ]
            )
            ->addIndex(
                [
                    'parent_id',
                ]
            )
            ->addIndex(
                [
                    'state_id',
                ]
            )
            ->create();

        $this->table('child_stampcards')
            ->addColumn('parent_id', 'integer', [
                'comment' => '親ID',
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('serial_number', 'string', [
                'comment' => '識別番号',
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('authentication_token', 'string', [
                'comment' => '照合トークン',
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('limit_count', 'integer', [
                'comment' => '使用回数',
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('dir_path', 'string', [
                'comment' => 'ディレクトリパス',
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('token', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
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
            ->addIndex(
                [
                    'parent_id',
                ]
            )
            ->create();

        $this->table('companies')
            ->addColumn('name', 'string', [
                'comment' => '名前',
                'default' => null,
                'limit' => 255,
                'null' => false,
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
            ->create();

        $this->table('coupon_shops')
            ->addColumn('shop_id', 'integer', [
                'comment' => '店舗ID',
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('coupon_id', 'integer', [
                'comment' => 'クーポンID',
                'default' => null,
                'limit' => 11,
                'null' => false,
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
            ->addIndex(
                [
                    'coupon_id',
                ]
            )
            ->addIndex(
                [
                    'shop_id',
                ]
            )
            ->create();

        $this->table('coupons')
            ->addColumn('title', 'string', [
                'comment' => '名前',
                'default' => '',
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('content', 'string', [
                'comment' => '内容',
                'default' => '',
                'limit' => 60,
                'null' => false,
            ])
            ->addColumn('limit', 'string', [
                'comment' => '利用制限',
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('release_id', 'integer', [
                'comment' => '公開/非公開ステータス',
                'default' => '1',
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('reword', 'string', [
                'comment' => '特典内容',
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('address', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('longitude', 'string', [
                'comment' => '緯度',
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('latitude', 'string', [
                'comment' => '経度',
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('relevant_text', 'string', [
                'comment' => 'GPSメッセージ',
                'default' => null,
                'limit' => 60,
                'null' => true,
            ])
            ->addColumn('background_color', 'string', [
                'comment' => '背景色',
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('foreground_color', 'string', [
                'comment' => '文字色',
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('before_expiry_date', 'datetime', [
                'comment' => '開始日',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('after_expiry_date', 'datetime', [
                'comment' => '終了日',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('company_id', 'integer', [
                'comment' => '会社ID',
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('token', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
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
            ->addIndex(
                [
                    'company_id',
                ]
            )
            ->addIndex(
                [
                    'release_id',
                ]
            )
            ->create();

        $this->table('myuser_shops')
            ->addColumn('myuser_id', 'integer', [
                'comment' => 'ユーザーID',
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('shop_id', 'integer', [
                'comment' => '店舗ID',
                'default' => null,
                'limit' => 11,
                'null' => false,
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
            ->addIndex(
                [
                    'myuser_id',
                ]
            )
            ->addIndex(
                [
                    'shop_id',
                ]
            )
            ->create();

        $this->table('myusers')
            ->addColumn('username', 'string', [
                'comment' => '名前',
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('email', 'string', [
                'comment' => 'メールアドレス',
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('company_id', 'integer', [
                'comment' => '企業ID',
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('role_id', 'integer', [
                'comment' => '権限ID',
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('password', 'string', [
                'comment' => 'パスワード',
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('token', 'string', [
                'comment' => 'トークン',
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('token_expires', 'datetime', [
                'comment' => 'トークン有効期限',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('activation_date', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('active', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'email',
                ],
                ['unique' => true]
            )
            ->addIndex(
                [
                    'company_id',
                ]
            )
            ->addIndex(
                [
                    'role_id',
                ]
            )
            ->create();

        $this->table('release_states')
            ->addColumn('title', 'string', [
                'comment' => '名前',
                'default' => '',
                'limit' => 255,
                'null' => false,
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
            ->create();

        $this->table('reword_states')
            ->addColumn('name', 'string', [
                'comment' => '内容',
                'default' => null,
                'limit' => 255,
                'null' => false,
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
            ->create();

        $this->table('roles')
            ->addColumn('name', 'string', [
                'comment' => '名前',
                'default' => null,
                'limit' => 255,
                'null' => false,
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
            ->create();

        $this->table('shops')
            ->addColumn('name', 'string', [
                'comment' => '名前',
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('company_id', 'integer', [
                'comment' => '企業ID',
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('introdaction', 'text', [
                'comment' => '紹介文',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('tel', 'string', [
                'comment' => '電話番号',
                'default' => null,
                'limit' => 13,
                'null' => true,
            ])
            ->addColumn('address', 'string', [
                'comment' => '住所',
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('homepage', 'string', [
                'comment' => 'HP',
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('line', 'string', [
                'comment' => 'LINE',
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('twitter', 'string', [
                'comment' => 'twitter',
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('facebook', 'string', [
                'comment' => 'facebook',
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('instagram', 'string', [
                'comment' => 'instagram',
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('image', 'string', [
                'comment' => '店舗画像',
                'default' => null,
                'limit' => 255,
                'null' => true,
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
            ->addIndex(
                [
                    'company_id',
                ]
            )
            ->create();

        #$this->table('stamp_shops')
        #    ->create();

        $this->table('stampcard_rewords')
            ->addColumn('stamp_id', 'integer', [
                'comment' => 'スタンプカードID',
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('reword', 'string', [
                'comment' => '内容',
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('reword_point', 'integer', [
                'comment' => '特典条件',
                'default' => null,
                'limit' => 11,
                'null' => false,
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
            ->addIndex(
                [
                    'stamp_id',
                ]
            )
            ->create();

        $this->table('stampcard_shops')
            ->addColumn('shop_id', 'integer', [
                'comment' => '店舗ID',
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('stamp_id', 'integer', [
                'comment' => 'スタンプカードID',
                'default' => null,
                'limit' => 11,
                'null' => false,
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
            ->addIndex(
                [
                    'shop_id',
                ]
            )
            ->addIndex(
                [
                    'stamp_id',
                ]
            )
            ->create();

        $this->table('stampcards')
            ->addColumn('title', 'string', [
                'comment' => 'タイトル',
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('content', 'string', [
                'comment' => '内容',
                'default' => '',
                'limit' => 60,
                'null' => false,
            ])
            ->addColumn('longitude', 'string', [
                'comment' => '緯度',
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('latitude', 'string', [
                'comment' => '経度',
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('relevant_text', 'string', [
                'comment' => 'GPSメッセージ',
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('background_color', 'string', [
                'comment' => '背景色',
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('foreground_color', 'string', [
                'comment' => '文字色',
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('before_expiry_date', 'datetime', [
                'comment' => '開始日',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('after_expiry_date', 'datetime', [
                'comment' => '終了日',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('release_id', 'integer', [
                'comment' => '公開/非公開状態',
                'default' => '1',
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('address', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => true,
            ])
            ->addColumn('max_limit', 'integer', [
                'comment' => 'スタンプ上限',
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('company_id', 'integer', [
                'comment' => '企業ID',
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('token', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => true,
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
            ->addIndex(
                [
                    'company_id',
                ]
            )
            ->addIndex(
                [
                    'release_id',
                ]
            )
            ->create();

        $this->table('child_coupons')
            ->addForeignKey(
                'parent_id',
                'coupons',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('child_stampcard_rewords')
            ->addForeignKey(
                'child_id',
                'child_stampcards',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->addForeignKey(
                'parent_id',
                'stampcards',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->addForeignKey(
                'state_id',
                'reword_states',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT'
                ]
            )
            ->update();

        $this->table('child_stampcards')
            ->addForeignKey(
                'parent_id',
                'stampcards',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('coupon_shops')
            ->addForeignKey(
                'coupon_id',
                'coupons',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->addForeignKey(
                'shop_id',
                'shops',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('coupons')
            ->addForeignKey(
                'company_id',
                'companies',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT'
                ]
            )
            ->addForeignKey(
                'release_id',
                'release_states',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT'
                ]
            )
            ->update();

        $this->table('myuser_shops')
            ->addForeignKey(
                'myuser_id',
                'myusers',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->addForeignKey(
                'shop_id',
                'shops',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('myusers')
            ->addForeignKey(
                'company_id',
                'companies',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT'
                ]
            )
            ->addForeignKey(
                'role_id',
                'roles',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT'
                ]
            )
            ->update();

        $this->table('shops')
            ->addForeignKey(
                'company_id',
                'companies',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('stampcard_rewords')
            ->addForeignKey(
                'stamp_id',
                'stampcards',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('stampcard_shops')
            ->addForeignKey(
                'shop_id',
                'shops',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->addForeignKey(
                'stamp_id',
                'stampcards',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('stampcards')
            ->addForeignKey(
                'company_id',
                'companies',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT'
                ]
            )
            ->addForeignKey(
                'release_id',
                'release_states',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->update();
    }

    public function down()
    {
        $this->table('child_coupons')
            ->dropForeignKey(
                'parent_id'
            )->save();

        $this->table('child_stampcard_rewords')
            ->dropForeignKey(
                'child_id'
            )
            ->dropForeignKey(
                'parent_id'
            )
            ->dropForeignKey(
                'state_id'
            )->save();

        $this->table('child_stampcards')
            ->dropForeignKey(
                'parent_id'
            )->save();

        $this->table('coupon_shops')
            ->dropForeignKey(
                'coupon_id'
            )
            ->dropForeignKey(
                'shop_id'
            )->save();

        $this->table('coupons')
            ->dropForeignKey(
                'company_id'
            )
            ->dropForeignKey(
                'release_id'
            )->save();

        $this->table('myuser_shops')
            ->dropForeignKey(
                'myuser_id'
            )
            ->dropForeignKey(
                'shop_id'
            )->save();

        $this->table('myusers')
            ->dropForeignKey(
                'company_id'
            )
            ->dropForeignKey(
                'role_id'
            )->save();

        $this->table('shops')
            ->dropForeignKey(
                'company_id'
            )->save();

        $this->table('stampcard_rewords')
            ->dropForeignKey(
                'stamp_id'
            )->save();

        $this->table('stampcard_shops')
            ->dropForeignKey(
                'shop_id'
            )
            ->dropForeignKey(
                'stamp_id'
            )->save();

        $this->table('stampcards')
            ->dropForeignKey(
                'company_id'
            )
            ->dropForeignKey(
                'release_id'
            )->save();

        $this->table('child_coupons')->drop()->save();
        $this->table('child_stampcard_rewords')->drop()->save();
        $this->table('child_stampcards')->drop()->save();
        $this->table('companies')->drop()->save();
        $this->table('coupon_shops')->drop()->save();
        $this->table('coupons')->drop()->save();
        $this->table('myuser_shops')->drop()->save();
        $this->table('myusers')->drop()->save();
        $this->table('release_states')->drop()->save();
        $this->table('reword_states')->drop()->save();
        $this->table('roles')->drop()->save();
        $this->table('shops')->drop()->save();
        #$this->table('stamp_shops')->drop()->save();
        $this->table('stampcard_rewords')->drop()->save();
        $this->table('stampcard_shops')->drop()->save();
        $this->table('stampcards')->drop()->save();
    }
}
