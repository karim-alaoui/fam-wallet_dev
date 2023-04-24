<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;
use Faker\Factory;

/**
 * AffiliaterCouponsFixture
 */
class AffiliaterCouponsFixture extends TestFixture
{
    public $import = ['table' => 'affiliater_coupons'];
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
        $faker = Factory::create('ja_JP');
        return array_merge(
            [
                'rate' => $faker->numberBetween(100, 500),
                'type' => [1,2][array_rand([1,2])]
            ],
            $data
        );
    }
}
