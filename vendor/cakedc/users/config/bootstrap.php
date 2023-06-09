<?php
/**
 * Copyright 2010 - 2017, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2017, Cake Development Corporation (https://www.cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

use Cake\Core\Configure;
use Cake\Core\Exception\MissingPluginException;
use Cake\Core\Plugin;
use Cake\Event\EventManager;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

Configure::load('CakeDC/Users.users');
collection((array)Configure::read('Users.config'))->each(function ($merge, $file) {
	if (is_int($file)) {
		$file = $merge;
		$merge = true;
	}
    Configure::load($file, 'default', $merge);
});

if (!TableRegistry::getTableLocator()->exists('Users')) {
    TableRegistry::getTableLocator()->setConfig('Users', ['className' => Configure::read('Users.table')]);
}
if (!TableRegistry::getTableLocator()->exists('CakeDC/Users.Users')) {
    TableRegistry::getTableLocator()->setConfig('CakeDC/Users.Users', ['className' => Configure::read('Users.table')]);
}

if (Configure::check('Users.auth')) {
    Configure::write('Auth.authenticate.all.userModel', Configure::read('Users.table'));
}

$oauthPath = Configure::read('OAuth.path');
if (is_array($oauthPath)) {
    Router::scope('/auth', function ($routes) use ($oauthPath) {
        $routes->connect(
            '/:provider',
            $oauthPath,
            ['provider' => implode('|', array_keys(Configure::read('OAuth.providers')))]
        );
    });
}
