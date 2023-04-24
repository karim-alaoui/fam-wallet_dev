<?php
namespace App\Test\Traits;

use Cake\Core\Plugin;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * Trait TestSettingTrait
 * @package App\Test\Traits
 * @mixin TestCase
 */
trait SettingTestTrait {
    public function settingsTest() {
        if(method_exists($this, 'enableCsrfToken')) {
            $this->enableCsrfToken();
            $this->enableSecurityToken();
        }
        Plugin::load('CakeDC/Users', ['routes' => true, 'bootstrap' => true]);
    }

    public function registerTables(array $data) {
        foreach ($data as $d) {
            $this->$d = TableRegistry::getTableLocator()->get($d);
        }
    }

    public function unsetTables() {
        foreach ($this->fixtures as $fixture) {
            $model = str_replace('app.', '', $fixture);
            if(isset($this->$model) && is_object($this->$model)) {
                unset($this->$model);
            }
        }
    }
}
