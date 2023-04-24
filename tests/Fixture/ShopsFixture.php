<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;
use Faker\Factory;

/**
 * ShopsFixture
 */
class ShopsFixture extends TestFixture
{

    public $import = ['table' => 'shops'];
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
        $f = Factory::create('ja_JP');
        return array_merge([
            'name' => $f->name,
            'tel' => $f->numerify('###########'),
            'address' => $f->address,
            'homepage' => $f->url,
            'line' => $f->url,
            'twitter' => $f->url,
            'facebook' => $f->url,
            'instagram' => $f->url,
            'image' => $f->imageUrl(),
        ], $data);
    }
}
