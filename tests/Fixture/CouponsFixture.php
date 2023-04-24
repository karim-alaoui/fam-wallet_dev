<?php
namespace App\Test\Fixture;

use Cake\I18n\Time;
use Cake\TestSuite\Fixture\TestFixture;
use Faker\Factory;

/**
 * CouponsFixture
 */
class CouponsFixture extends TestFixture
{

    public $import = ['table' => 'coupons'];
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
                'title' => $faker->title,
                'content' => $faker->text(10),
                'reword' => $faker->text(10),
                'address' => $faker->address,
                'token' => 'token',
                'limit' => (string)$faker->numberBetween(1, 20),
                'background_color' => '0,0,0',
                'foreground_color' => '1,1,1',
                'longitude' => (string)$faker->longitude,
                'latitude' => (string)$faker->latitude,
                'relevant_text' => null,
                'after_expiry_date' => Time::now()->addDays(10),
                'before_expiry_date' => Time::now()->addDays(-1)
            ],
            $data
        );
    }
}
