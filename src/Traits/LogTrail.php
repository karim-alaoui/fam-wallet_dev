<?php

namespace App\Traits;

use Cake\Datasource\ConnectionManager;

Trait LogTrail {

    public function sqlDebug(\Closure $closure) {
        $connection = ConnectionManager::get('default');
        $connection->logQueries(true);

        $closure();

        $connection->logQueries(false);
    }

}