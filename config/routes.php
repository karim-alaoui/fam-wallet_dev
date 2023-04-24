<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 * Cache: Routes are cached to improve performance, check the RoutingMiddleware
 * constructor in your `src/Application.php` file to change this behavior.
 *
 */
Router::defaultRouteClass(DashedRoute::class);
Router::extensions(['json', 'xml']);
Router::scope('/', function (RouteBuilder $routes) {
    // Register scoped middleware for in scopes.
//    $routes->registerMiddleware('csrf', new CsrfProtectionMiddleware([
//        'httpOnly' => true
//    ]));
//
//    /**
//     * Apply a middleware to the current route scope.
//     * Requires middleware to be registered via `Application::routes()` with `registerMiddleware()`
//     */
//    $routes->applyMiddleware('csrf');

    /**
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    #$routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);

    /**
     * ...and connect the rest of 'Pages' controller's URLs.
     */
    // $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);

    // login
    // vender/cakedc/users/config/routes.php 変更必要
    $routes->connect('/', ['controller' => 'Myusers', 'action' => 'login']);
    $routes->connect('/login', [ 'controller' => 'Myusers', 'action' => 'login']);
    // ユーザページ
    $routes->connect('/:id', ['controller' => 'Myusers', 'action' => 'index'])
      ->setPatterns(['id' => '\d+'])
      ->setPass(['id']);

    /**
     * 新規会員登録関連
     */
    // 新規登録
    $routes->connect('/signup', ['controller' => 'Myusers', 'action' => 'signUp']);
    // 登録確認
    $routes->connect('/sign_up_confirm', ['controller' => 'Myusers', 'action' => 'signUpConfirm']);
    // 登録完了
    $routes->connect('/sign_up_tem_registration', ['controller' => 'Myusers', 'action' => 'register']);
    // アカウント認証
    $routes->connect('/sign_up_registration', ['controller' => 'Myusers', 'action' => 'sign_up_registration']);

    /**
     * パスワード変更関連
     */
    // メール送信
    $routes->connect('/password/new', ['controller' => 'Myusers', 'action' => 'request_reset_password']);
    // 再設定送信後
    $routes->connect('/password_reset_send', ['controller' => 'Myusers', 'action' => 'password_reset_send']);
    // パスワード再設定
    $routes->connect('/password_reset_input', ['controller' => 'Myusers', 'action' => 'password_reset_input']);
    // パスワード変更完了
    $routes->connect('/password_reset_done', ['controller' => 'Myusers', 'action' => 'password_reset_done']);

    /*
    * ユーザー管理
    */
    $routes->connect('/users', ['controller' => 'Myusers', 'action' => 'user_management']);
    $routes->connect('/:id/edit', ['controller' => 'Myusers', 'action' => 'edit'])
      ->setPatterns(['id' => '\d+'])
      ->setPass(['id']);
    $routes->connect('/:id/payment_edit', ['controller' => 'Myusers', 'action' => 'account_edit'])
        ->setPatterns(['id' => '\d+'])
        ->setPass(['id']);
    /**
    * リーダー関連
    */
//    $routes->connect('/leaders', ['controller' => 'Myusers', 'action' => 'leader_list']);
//    $routes->connect('/leaders/new', ['controller' => 'Myusers', 'action' => 'leaderInput']);
//    $routes->connect('/leaders/confirm', ['controller' => 'Myusers', 'action' => 'leader_confirm']);
//    $routes->connect('leaders/edit/:id', ['controller' => 'Leaders', 'action' => 'edit'])
//      ->setPatterns(['id' => '\d+'])
//      ->setPass(['id']);

    /**
    * メンバー関連
    */
//    $routes->connect('/members', ['controller' => 'Myusers', 'action' => 'member_list']);
//    $routes->connect('/members/new', ['controller' => 'Myusers', 'action' => 'member_input']);
//    $routes->connect('/members/confirm', ['controller' => 'Myusers', 'action' => 'member_confirm']);
//    $routes->connect('/members/:id/edit', ['controller' => 'Myusers', 'action' => 'member_edit'])
//      ->setPatterns(['id' => '\d+'])
//      ->setPass(['id']);

    /**
    * アフィリエイター関連
    */
    $routes->connect('/affiliaters', ['controller' => 'Myusers', 'action' => 'affiliater_list']);
    $routes->connect('/affiliater/new', ['controller' => 'Myusers', 'action' => 'affiliater_new']);
    $routes->connect('/affiliater/confirm', ['controller' => 'Myusers', 'action' => 'affiliater_confirm']);
    $routes->connect('/affiliater/:id/edit', ['controller' => 'Myusers', 'action' => 'affiliater_edit'])
      ->setPatterns(['id' => '\d+'])
      ->setPass(['id']);
    $routes->connect('/affiliater/:id/payment', ['controller' => 'Myusers', 'action' => 'affiliater_payment'])
      ->setPatterns(['id' => '\d+'])
      ->setPass(['id']);

    $routes->scope('/affiliater', function(RouteBuilder $builder) {
        $builder->connect('/point', ['controller' => 'AffiliaterPoints', 'action' => 'index']);
        $builder->connect('/__insert/:id', ['controller' => 'AffiliaterPoints', 'action' => 'insert'])
            ->setPatterns(['id' => '\d+'])
            ->setPass(['id']);
        $builder->connect('/application', ['controller' => 'AffiliaterPoints', 'action' => 'application']);
        $builder->connect('/application/success', ['controller' => 'AffiliaterPoints', 'action' => 'success']);
        $builder->connect('/detail', ['controller' => 'Affiliaters', 'action' => 'detail']);
        $builder->connect('/detail/account_edit', ['controller' => 'Affiliaters', 'action' => 'account_edit']);
        $builder->connect('/detail/account_confirm', ['controller' => 'Affiliaters', 'action' => 'account_confirm']);
    });


    $routes->scope('/affiliater-coupons', function(RouteBuilder $builder) {
       $builder->connect('/:parent_id/:id', ['controller' => 'AffiliaterCoupons', 'action' => 'edit'])
           ->setPatterns(['parent_id' => '\d+'])
           ->setPatterns(['id' => '\d+'])
           ->setPass(['id']);

       $builder->connect('/qr-confirm/:id', [
           'controller' => 'AffiliaterCoupons', 'action' => 'qrConfirm'
       ])
           ->setPatterns(['id' => '\d+'])
           ->setPass(['id']);
    });

    /*
    * プライバシーポリシー
    */
    $routes->connect('/policy', ['controller' => 'Myusers', 'action' => 'privacy_policy']);

    /*
    * アカンウト認証メール再送
    */
    $routes->connect('/confirmation/new', ['controller' => 'Myusers', 'action' => 'resend_token_validation']);

    /*
    * 店舗関連
    */
    // 一覧
    $routes->connect('/shops', ['controller' => 'Shops', 'action' => 'index']);
    // 登録
    $routes->connect('/shops/new', ['controller' => 'Shops', 'action' => 'new']);
    // 編集
    $routes->connect('/shops/:id/edit', ['controller' => 'Shops', 'action' => 'edit'])
      ->setPatterns(['id' => '\d+'])
      ->setPass(['id']);
    // 詳細
    $routes->connect('/shops/:id/view', ['controller' => 'Shops', 'action' => 'view'])
      ->setPatterns(['id' => '\d+'])
      ->setPass(['id']);

    /*
    * クーポン関連
    */
    // 一覧
    $routes->connect('/coupons', ['controller' => 'Coupons', 'action' => 'index']);
    // 登録
    $routes->connect('/coupons/new', ['controller' => 'Coupons', 'action' => 'new']);
    // 編集
    $routes->connect('/coupons/:id/edit', ['controller' => 'Coupons', 'action' => 'edit'])
      ->setPatterns(['id' => '\d+'])
      ->setPass(['id']);
    // QR表示
    $routes->connect('/coupons/qrcode', ['controller' => 'Coupons', 'action' => 'qrcode']);
    // QRリーダー読み込み後
    $routes->connect('/coupons/qr_confirm', ['controller' => 'Coupons', 'action' => 'coupon_qr_confirm']);

    /**
     * スタンプ関連
     */
    // 一覧
    $routes->connect('/stampcards', ['controller' => 'Stampcards', 'action' => 'index']);
    // 登録
    $routes->connect('/stampcards/new', ['controller' => 'Stampcards', 'action' => 'new']);
    // 編集
    $routes->connect('/stampcards/:id/edit', ['controller' => 'Stampcards', 'action' => 'edit'])
      ->setPatterns(['id' => '\d+'])
      ->setPass(['id']);
    // QR表示
    $routes->connect('/stampcards/qrcode', ['controller' => 'Stampcards', 'action' => 'qrcode']);
    // QRリーダー読み込み後
    $routes->connect('/stampcards/qr_confirm', ['controller' => 'Stampcards', 'action' => 'stamp_qr_confirm'])
      ->setPatterns(['id' => '\d+'])
      ->setPass(['id']);

    // QRリーダー
    $routes->connect('/qr_leader', ['controller' => 'Test', 'action' => 'qr_leader']);

    // 分析切り分け
    $routes->connect('/analytics/coupons', ['controller' => 'Analytics', 'action' => 'coupons']);
    $routes->connect('/analytics/stampcards', ['controller' => 'Analytics', 'action' => 'stampcards']);


    /* ここまで追加 */
    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *
     * ```
     * $routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);
     * $routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);
     * ```
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks(DashedRoute::class);
});

/**
 * If you need a different set of middleware or none at all,
 * open new scope and define routes there.
 *
 * ```
 * Router::scope('/api', function (RouteBuilder $routes) {
 *     // No $routes->applyMiddleware() here.
 *     // Connect API actions here.
 * });
 * ```
 */
    Router::scope('/api', function(RouteBuilder $builder) {
        $builder->prefix('v1' ,function(RouteBuilder $builder) {
            $builder->post('/devices/:device_id/registrations/:pass_type_id/:serial_number', [
                'controller' => 'Pass', 'action' => 'registrations'
            ])
                ->setPatterns(['device_id' => '.*', 'pass_type_id' => '.*', 'serial_number' => '.*'])
                ->setPass(['device_id', 'pass_type_id', 'serial_number']);

            $builder->get('/devices/:device_id/registrations/:pass_type_id', [
                'controller' => 'Pass', 'action' => 'getDeviceRegistrations'
            ])
                ->setPatterns(['device_id' => '.*', 'pass_type_id' => '.*'])
                ->setPass(['device_id', 'pass_type_id']);

            $builder->delete('/devices/:device_id/registrations/:pass_type_id/:serial_number', [
                'controller' => 'Pass', 'action' => 'deleteDevice'
            ])
                ->setPatterns(['device_id' => '.*', 'pass_type_id' => '.*', 'serial_number' => '.*'])
                ->setPass(['device_id', 'pass_type_id', 'serial_number']);

            $builder->post('/log', ['controller' => 'Pass', 'action' => 'logAction']);
        });
    });
