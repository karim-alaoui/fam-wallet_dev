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

use App\Form\SearchAppForm;
use App\Model\Entity\Application;
use App\Model\Entity\Myuser;
use App\Model\Table\AffiliaterApplicationsTable;
use App\Model\Table\AffiliaterCouponsTable;
use App\Model\Table\ApplicationsTable;
use App\Model\Table\CompaniesTable;
use App\Model\Table\MyuserBanksTable;
use App\Model\Table\MyusersTable;
use App\Services\ApplicationService;
use App\Services\StripeService;
use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\View\Exception\MissingTemplateException;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class AdminsController extends AppController
{
    /**
     * @var MyusersTable
     */
    private $Myusers;

    /**
     * @var ApplicationsTable
     */
    private $Applications;

    /**
     * @var MyuserBanksTable
     */
    private $MyuserBanks;

  /**
   * @var CompaniesTable
   */
    private $Companies;

    /**
     * @var AffiliaterApplicationsTable
     */
    private $AffiliaterApplications;

    public function initialize() {
        parent::initialize();

        $user = $this->Auth->user();
        $this->set('User', $user);

        $this->AffiliaterApplications = TableRegistry::getTableLocator()->get('AffiliaterApplications');
        $this->Myusers = TableRegistry::getTableLocator()->get('Myusers');
        $this->Applications = TableRegistry::getTableLocator()->get('Applications');
        $this->MyuserBanks = TableRegistry::getTableLocator()->get('MyuserBanks');
        $this->Companies = TableRegistry::getTableLocator()->get('Companies');
    }

    public function index() {
        $search = new SearchAppForm();
        $data = $this->request->getData();
        $status = Application::STATUS_ID_NONE;

        $beforeDate = '';
        $afterDate = '';
        if (isset($data['search'])) {
            if ($data['before_date']) {
                $beforeDate = Time::parse($data['before_date']);
                $beforeDate = $beforeDate->format('Y-m-d H:i:s');
            }

            if ($data['after_date']) {
                $afterDate = Time::parse($data['after_date']);
                $afterDate = $afterDate->addDay();
                $afterDate = $afterDate->format('Y-m-d H:i:s');
            }
        }

        if (isset($data['clear'])) {
            $before_date = '';
            $after_date = '';
            $status = Application::STATUS_ID_NONE;
        } else {
            $before_date = $this->request->getData('before_date');
            $after_date = $this->request->getData('after_date');
            $status = $this->request->getData('status');
        }

        $applications = $this->Applications->find()->contain('Myusers');

        if ($beforeDate) {
            $applications->where(['Applications.created >=' => $beforeDate]);
        }

        if ($afterDate) {
            $applications->where(['Applications.created <=' => $afterDate]);
        }

      if (is_null($status)) {
        $status = Application::STATUS_ID_NONE;
      }

        if ($status != Application::STATUS_ID_NONE) {
          $applications->where(['Applications.status_id' => $status]);
        }

        if (!$beforeDate
          && !$afterDate
          && $status == Application::STATUS_ID_NONE) {
            $applications->all();
        }

        $applications = $applications->toArray();

        $this->set(compact('applications', 'search', 'before_date', 'after_date', 'status'));
    }

    public function myPage() {
        $myuser = $this->Myusers->get($this->Auth->user()['id']);
        $this->set(compact('myuser'));

        if($myuser->customer) {
            $stripeService = new StripeService();
            try {
                $account = $stripeService->getAccounts($myuser->customer);
                $this->set(compact('account'));
            } catch (\Exception $exception) {
                $this->Flash->error(__('口座情報の取得に失敗しました'));
            }
        }
    }

    public function accountEdit() {
        $client_id = env('CLIENT_ID');
        $uri = $this->request->getUri();
        $redirect_uri = 'https://'.$uri->getHost().'/admins/account_confirm';

        $this->set(compact('client_id', 'redirect_uri'));
    }

    public function accountConfirm() {
        $token_request_body = array(
            'client_secret' => env('STRIPE_SECRET', 'sk_test_p8FZiwisBl1sctoLGyZtVWUW'),
            'grant_type' => 'authorization_code',
            'client_id' => env('CLIENT_ID','ca_CmUon45AueYQEKcsJ9XaBUl6NSohJMTM'),
            'code' => $this->request->query('code'),
        );

        $req = curl_init('https://connect.stripe.com/oauth/token');
        curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($req, CURLOPT_POST, true );
        curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));

        $respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);

        $res = curl_exec($req);
        $resp = json_decode($res, true);
        curl_close($req);

        try {
            $entity = $this->Myusers->get($this->Auth->user('id'));
            $entity = $this->Myusers->patchEntity($entity, ['customer' => $resp['stripe_user_id']]);
            $this->Myusers->saveOrFail($entity);
            $this->Flash->success(__('口座情報を登録しました'));
        } catch (\Exception $exception) {
            $this->Flash->error(__('口座情報登録に失敗しました'));
        }

        return $this->redirect(['action' => 'myPage']);
    }

    public function affiliaterPayment($id = null) {
        $application = $this->Applications
            ->find()
            ->where(['id' => $id])
            ->first();

        $affiliaterApplications = $this->AffiliaterApplications
            ->find()
            ->where(['application_id' => $application->id])
            ->contain(['Companies'])
            ->toArray();

        $myuser = $this->Myusers->find()
            ->where(['Myusers.id' => $application->myuser_id])
            ->contain('MyuserBanks')
            ->first();

        if(!$application) {
            return $this->redirect('/admins');
        }

        $this->set(compact('application','affiliaterApplications', 'myuser'));

        if(!$this->request->is('post')) {
            return;
        }

        try {
            $entity = $this->Applications->patchEntity($application, ['status_id' => $this->request->getData('status_id')]);
            $this->Applications->saveOrFail($entity);
            $this->Flash->success(__('保存しました'));
        } catch (\Exception $exception) {
            $this->Flash->error(__('保存に失敗しました'));
        }
    }

    public function applicationDetail($id) {
      if ($this->Auth->user('role_id') != Myuser::ROLE_ADMIN) {
        return;
      }

      $affiliaterApplication = $this->AffiliaterApplications->get($id);
      $affiliater = $this->Myusers->get($affiliaterApplication->myuser_id);
      $company = $this->Companies->get($affiliaterApplication->company_id);

      $this->set(compact('affiliaterApplication','affiliater', 'company'));

    }
}
