<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ReleaseStatesFixture
 */
class ReleaseStatesFixture extends TestFixture
{
    public $import = ['table' => 'release_states'];
    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            ['title' => '公開'],
            ['title' => '非公開']
        ];
        parent::init();
    }
}
