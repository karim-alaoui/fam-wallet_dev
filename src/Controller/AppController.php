<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use App\Model\Table\AffiliaterApplicationsTable;
use App\Model\Entity\AffiliaterApplication;
use Cake\ORM\TableRegistry;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

  /**
     * @var AffiliaterApplicationsTable
     */
    private $AffiliaterApplications;
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->AffiliaterApplications = TableRegistry::getTableLocator()->get('AffiliaterApplications');
        
        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');
        // add CakeDC/Users
        $this->loadComponent('CakeDC/Users.UsersAuth');
        // 全ての認証許可 debug用
        //$this->Auth->allow();
        $this->Auth->allow(['validateEmail', 'register', 'resendTokenValidation', 'requestResetPassword']);
        

        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
    }

    public function beforeFilter(Event $event)
    {
      parent::beforeFilter($event);
      $this->set('auth', $this->Auth->user());
      if(isset($this->Auth->user()['role_id'])){
        $userRoleId = $this->Auth->user()['role_id'];
        $companyId = $this->Auth->user('company_id');
        if($userRoleId == 1 || $userRoleId == 2) {
          $appAffiliater = $this->AffiliaterApplications->find()
          ->where(['status_id In' => [
                AffiliaterApplication::STATUS_ID_APPLYING,
                AffiliaterApplication::STATUS_ID_APPROVED,
                AffiliaterApplication::STATUS_ID_ERROR
              ]
          ])
          ->where([
              'AffiliaterApplications.company_id' => $companyId
          ])
          ->contain(['Myusers'])
          ->toArray();
          $this->set('appAffiliaterVar', $appAffiliater);
        }
      }
    }

    /*
    * paginate設定
    */
    public $paginate = [
      'limit' => 10
    ];

}
