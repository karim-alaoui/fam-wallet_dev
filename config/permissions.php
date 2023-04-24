<?php
return [
    'CakeDC/Auth.permissions' => [
        //admin role allowed to all the things
        [
            'role' => '*',
            'prefix' => '*',
            'extension' => '*',
            'plugin' => '*',
            'controller' => '*',
            'action' => '*',
        ],
        //specific actions allowed for the all roles in Users plugin
        /*[
            'role' => 'user',
            'prefix' => '*',
            'extension' => '*',
            'plugin' => '*',
            'controller' => '*',
            'action' => '*',

            'role' => 'user',
            'plugin' => 'CakeDC/Users',
            'action' => ['edit', 'profile', 'logout', 'index'],*/
        ],
];
