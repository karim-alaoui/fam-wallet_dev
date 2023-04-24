<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Services\StripeService;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\ORM\TableRegistry;
use PKPass\PKPass;
use Stripe\Stripe;

/**
 * Test Controller
 *
 *
 * @method \App\Model\Entity\Test[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TestController extends AppController
{
  /**
   * Index method
   * Create pkpass
   * @method PKPass\PKPass
   */
  public function index()
  {

    $pass = new PKPass('../src/wallet_resource/keys/walletkey.p12', env('keypasswd'));

    // test code
    // strcode utf-8
    $data = [

      "formatVersion" => 1,
      "passTypeIdentifier" => env('passTypeID'), // passTypeID
      "serialNumber" => "12345678910", // use update number
      "teamIdentifier" => env('teamID'), // teamID
      "webServiceURL" => env('updateURL'), // updateURLPath
      "authenticationToken" => "vxwxd7J8AlNNFPS8k0a0FfUFtq0ewzFdc", // auth number
      "barcode" => [
        "message" => "123456789",
        "format" => "PKBarcodeFormatPDF417",
        "messageEncoding" => "utf-8"
      ],
      "locations" => [
        [
          "longitude" => -122.3748889,
          "latitude" => 37.6189722
        ],
        [
          "longitude" => -122.03118,
          "latitude" => 37.33182
        ]
      ],
      "organizationName" => "Paw Planet",
      "description" => "メゾンマークーポンタイトル",
      "logoText" => 'メゾンマークーポン',
      "foregroundColor" => "rgb(255,255,255)",
      "backgroundColor" => "rgb(206,140,53)",
      "coupon" => [
        "primaryFields" => [
          [
            "key"  =>"offer",
            "label" => "飲み食べ放題uoooooooo",
            "value" => "50000兆円 off"
          ]
        ],
        "auxiliaryFields" => [
          [
            "key" => "expires",
            "label" => "有効期限",
            "value" => "2010年12月1日から2011年12月2日まで",
            #"isRelative" => true,
            #"dateStyle" => "PKDateStyleShort"
          ]
        ],
        "backFields" => [
          [
            "key" => "website",
            "label" => "web site",
            "value" => "http://google.com"
          ]
        ]
      ]
    ];
$c = ['key' => 'site', 'label' => 'site', 'value' => 'a'];
array_push($data['coupon']['backFields'], $c);
    $pass->setData($data);

    // DocumentRoot:webroot
    $pass->addFile('/var/www/html/fam-wallet/src/wallet_resource/images/test/icon.png');
    $pass->addFile('/var/www/html/fam-wallet/src/wallet_resource/images/test/icon@2x.png');
    $pass->addFile('/var/www/html/fam-wallet/src/wallet_resource/images/test/logo.png');

    // debug
    if(!$pass->create(true)) {
        echo 'Error: ' . $pass->getError();
    }
  }

  public function fileview()
  {
    $mime_type = "application/vnd.apple.pkpass";
    $file_path = "api/v1/passes/pass.wallet.maisonmarc/12345678910/pass.pkpass";
    Header("Content-Type: $mime_type");
    readfile($file_path);
    exit;
  }

  /**
   * View method
   *
   * @param string|null $id Test id.
   * @return \Cake\Http\Response|null
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function view($id = null)
  {
    $test = $this->Test->get($id, [
        'contain' => [],
    ]);

    $this->set('test', $test);
  }

  /**
   * Add method
   *
   * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
   */
  public function add()
  {
    $test = $this->Test->newEntity();
    if ($this->request->is('post')) {
      $test = $this->Test->patchEntity($test, $this->request->getData());
      if ($this->Test->save($test)) {
        $this->Flash->success(__('The test has been saved.'));

        return $this->redirect(['action' => 'index']);
      }
      $this->Flash->error(__('The test could not be saved. Please, try again.'));
    }
    $this->set(compact('test'));
  }

  /**
   * Edit method
   *
   * @param string|null $id Test id.
   * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function edit($id = null)
  {
    $test = $this->Test->get($id, [
      'contain' => [],
    ]);
    if ($this->request->is(['patch', 'post', 'put'])) {
      $test = $this->Test->patchEntity($test, $this->request->getData());
      if ($this->Test->save($test)) {
        $this->Flash->success(__('The test has been saved.'));

        return $this->redirect(['action' => 'index']);
      }
      $this->Flash->error(__('The test could not be saved. Please, try again.'));
    }
    $this->set(compact('test'));
  }

  /**
   * Delete method
   *
   * @param string|null $id Test id.
   * @return \Cake\Http\Response|null Redirects to index.
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function delete($id = null)
  {
    $this->request->allowMethod(['post', 'delete']);
    $test = $this->Test->get($id);

    if ($this->Test->delete($test)) {
      $this->Flash->success(__('The test has been deleted.'));
    } else {
      $this->Flash->error(__('The test could not be deleted. Please, try again.'));
    }

    return $this->redirect(['action' => 'index']);
  }





  //ここから追記していく
  /*
  *
  */
  public function addAaa()
  {

  }
  public function signUp()
  {
  }
  public function signUpConfirm()
  {
  }
  public function signUpTemRegistration()
  {
  }
  public function signUpRegistration()
  {
  }
  public function passwordReset()
  {
  }
  public function passwordResetSend()
  {
  }
  public function passwordResetInput()
  {
  }
  public function passwordResetDone()
  {
  }
  public function signIn()
  {
  }
  public function userTop()
  {
  }
  public function userManagement()
  {
  }
  public function leaderList()
  {
  }
  public function leaderInput()
  {
  }
  public function leaderConfirm()
  {
  }
  public function leaderEdit()
  {
  }
  public function memberList()
  {
  }
  public function memberInput()
  {
  }
  public function memberEdit()
  {
  }
  public function accountInfo()
  {
  }
  public function shopList()
  {
  }
  public function shopInput()
  {
  }
  public function shopEdit()
  {
  }
  public function shopInfo()
  {
  }
  public function privacyPolicy()
  {
  }
  public function couponList()
  {
  }
  public function couponInput()
  {
  }
  public function couponEdit()
  {
  }
  public function couponConfirm()
  {
  }
  public function couponQr()
  {
  }
  public function stampList()
  {
  }
  public function stampInput()
  {
  }
  public function stampEdit()
  {
  }
  public function analytics()
  {
  }
  public function narrowDown()
  {
  }
  public function qrLeader()
  {
      Plugin::load('DebugKit', ['bootstrap' => false]);
  }

  public function stripe() {
      $s = new StripeService();
      $t = TableRegistry::getTableLocator()->get('Myusers');
      $o = $t->get(1);
      $u = $t->get(111);

      $r = $s->charge($o, $u, ['amount' => 250, 'application_fee' => 12]);
      $r->confirm($r->id);
      dd($r);
  }

  public function apns() {
      $apnsurl = 'ssl://gateway.sandbox.push.apple.com:2195';
      $context = stream_context_create();
      $path = TMP. 'pass'. DS. 'walletApns';
      stream_context_set_option($context, 'ssl', 'local_cert', $path);
$fp = stream_socket_client($apnsurl, $err,$errstr, 30, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $context);
dd($err, $errstr);
  }
}
