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

$activationUrl = [
    '_full' => true,
    'prefix' => false,
    'plugin' => null,
    'controller' => 'Myusers',
    'action' => 'validateEmail',
    isset($token) ? $token : ''
];
?>
<?= __d('CakeDC/Users', "We become indebted to. ") ?> 
<?= __d('CakeDC/Users', "Get real management office. ") ?> 
 
<?= __d('CakeDC/Users', "Thank you for your registration application. ") ?> 
<?= __d('CakeDC/Users', "For this registration, please authenticate your email from the following URL. ") ?> 
<?= $this->Url->build($activationUrl) ?> 
 
<?= __d('CakeDC/Users', 'After accessing, the login button will be displayed.') ?> 
<?= __d('CakeDC/Users', 'Please log in from there.') ?> 
 
<?= __d('CakeDC/Users', 'Thank you !') ?>

