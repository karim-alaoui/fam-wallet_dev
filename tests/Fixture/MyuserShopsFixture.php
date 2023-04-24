<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MyuserShopsFixture
 */
class MyuserShopsFixture extends TestFixture
{

    public $import = ['table' => 'myuser_shops'];
    public $records = [];
    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        parent::init();
    }

    public static function map($data) {
        return array_merge([
            'myuser_id' => 1,
            'shop_id' => 1
        ], $data);
    }
}
