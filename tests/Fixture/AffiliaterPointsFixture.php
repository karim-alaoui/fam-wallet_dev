<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;
use Faker\Factory;

/**
 * AffiliaterPointsFixture
 */
class AffiliaterPointsFixture extends TestFixture
{
    public $import = ['table' => 'affiliater_points'];
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
        return array_merge([
            'point' => $faker->numberBetween(100, 500)
        ], $data);
    }
}
