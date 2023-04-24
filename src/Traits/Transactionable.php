<?php

namespace App\Traits;

use Cake\Database\Connection;
use Cake\Datasource\ConnectionManager;

trait Transactionable {

    public function callTransaction(\Closure $closure, \Closure $error = null) {
        /**
         * @var $connection Connection
         */
        $connection = ConnectionManager::get('default');

        $connection->begin();

        try {

            $closure();

            $connection->commit();

        } catch (\Exception $exception) {
            $connection->rollback();
            if($error) {
                $error($exception);
            }
        }
    }
}