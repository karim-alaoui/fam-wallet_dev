<?php
namespace App\Test\Fixture;

use Cake\Auth\DefaultPasswordHasher;
use Cake\TestSuite\Fixture\TestFixture;
use Faker\Factory;

/**
 * MyusersFixture
 */
class MyusersFixture extends TestFixture
{

    public $import = ['table' => 'myusers'];
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
        if(isset($data['password'])) {
            $data['password'] = (new DefaultPasswordHasher)->hash($data['password']);
        }
        return array_merge(
            [
                'username' => $faker->userName,
                'email' => $faker->email,
                'password' => $faker->password(8, 8),
                'token' => $faker->password(20),
                'created' => $faker->dateTime()->format('Y-m-d H:i:s'),
                'modified' => $faker->dateTime()->format('Y-m-d H:i:s'),
                'customer' => 'cus_test'
            ],
            $data
        );
    }
}
