<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * RolesFixture
 */
class RolesFixture extends TestFixture
{
    public $import = ['table' => 'roles'];
    public $records = [];
    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            ['name' => 'オーナー'],
            ['name' => 'リーダー'],
            ['name' => 'メンバー'],
            ['name' => 'アフィリエイター'],
        ];
        parent::init();
    }
}
