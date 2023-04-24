<?php
namespace App\Test\Traits;

use Cake\ORM\Table;
use Cake\TestSuite\TestCase;

/**
 * Trait ModelSaveTrait
 * @package App\Test\Traits
 * @mixin TestCase
 */
trait ModelSaveTrait {

    /**
     * @param $modelName
     * @param $data
     */
    public function saveAssociation($modelName, $data) {
        /**
         * @var Table
         */
        $model = $this->getTableLocator()->get($modelName);
        foreach ($data as $d) {
            foreach ($d[$modelName] as $v) {
                $entity = $model->newEntity($v);
                $model->save($entity);
            }
        }
    }
}
