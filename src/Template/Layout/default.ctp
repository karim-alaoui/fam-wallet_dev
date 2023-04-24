<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'Get real';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription /*<?= $this->fetch('title') ?>*/?>
        
    </title>
    <meta name="description" content="業界初のアフィリエイト機能がついたクーポン発行アプリ 登録してユーザーに対してクーポンを発行するだけ、あとはユーザーが勝手にSNSで拡散してくれる">
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('app.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->Html->script('https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js'); ?>
    <?= $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.0/js/jquery.tablesorter.min.js') ?>
    <?= $this->fetch('script') ?>
    <link rel="apple-touch-icon" href="/apple-touch-icon.png"/>
</head>
<body>
    <?= $this->Flash->render() ?>
    <div class="l-wrap">
        <?= $this->fetch('content') ?>
    </div>
    <?= $this->Html->script('app.js') ?>
</body>
</html>
