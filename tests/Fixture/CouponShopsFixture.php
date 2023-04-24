<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;
use Faker\Factory;

/**
 * CouponShopsFixture
 */
class CouponShopsFixture extends TestFixture
{
    public $import = ['table' => 'coupon_shops'];
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


    public static function map($data)
    {
        $faker = Factory::create('ja_JP');
        return array_merge(
            [
            ],
            $data
        );
    }
}
