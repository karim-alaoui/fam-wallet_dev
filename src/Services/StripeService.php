<?php

namespace App\Services;

//use App\Exceptions\Handler;
//use App\Services\PayrollService;
use App\Model\Entity\AffiliaterApplication;
use App\Model\Entity\Myuser;
use App\Model\Table\MyusersTable;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use JsonSchema\Exception\ValidationException;
use SebastianBergmann\Comparator\ComparisonFailure;
use Stripe;

/**
 * Class StripeService
 * @package App\Service
 * @error エラー処理は\Stripe\Errorがthrowされた場合 \App\Exceptions\Handlerで捕縛され
 * それぞれのステータスコードに書き換えられます
 * 本アプリでは、クライアント側で例外の捕縛はせず
 * \Stripe\Error => Handler => /resources/js/plugin/axios::interceptor でハンドリング
 * @see Handler::getError
 */

class StripeService
{

    /** @var string  */
    private $secret;

    /**
     * @var MyusersTable
     */
    private $Myusers;

    public function __construct()
    {
        $this->secret = Configure::read('Stripe.secret');

        Stripe\Stripe::setApiKey(Configure::read('Stripe.secret'));
        $this->Myusers = TableRegistry::get('Myusers');
    }

    public function setApiKey (string $key) {
        Stripe\Stripe::setApiKey($key);
    }

    public function setBase (string $url) {
        Stripe\Stripe::$apiBase = $url;
    }

    public function getApiKey () {
        return $this->secret;
    }

    public function getClientId () {
        return Configure::read('Stripe.client_id');
    }

    public function getEphemeralKey ($customerId, $apiVersion) {
        return Stripe\EphemeralKey::create(
            ["customer" => $customerId],
            ["stripe_version" => $apiVersion]
        );
    }


    /**
     * @param $email
     *
     * @return Stripe\ApiResource
     */
    public function createCustomer ($email) {
        $res = Stripe\Customer::create([
            'email' => $email,
        ]);
        return $res;
    }

    /**
     * @param $token
     * @return Stripe\ApiResource
     */
    public function saveCard($token) {
        return Stripe\Customer::create([
            'source' => $token,
        ]);
    }

    /**
     * @param string $uid
     * @param int    $amount
     *
     * @return Stripe\ApiResource
     */
    public function transfer (string $uid, int $amount) {
        return Stripe\Transfer::create([
           'amount' => $amount,
           'currency' => 'jpy',
           'destination' => $uid
        ]);
    }


    /**
     * @param $customer_id
     * @return Stripe\Customer
     * @throws Stripe\Exception\ApiErrorException
     */
    public function retrieve($customer_id) {
        return Stripe\Customer::retrieve($customer_id);
    }

    /**
     * カード情報取得
     * @param $default_source
     * @param $source
     */
    public function getCard($customer) {
        $res = null;
        try {
//            $res = $this->retrieve($customer);
            $res = $this->getBank('acct_1KtYloLKTOsy4mn4', 'acct_1Kuz7xAcdm1RDefa');
        } catch (\Exception $exception) {

        }

        if(!$res) return ;

        $default_source = $res->default_source;
        $sources = $res->sources;
        if(!$sources) {
            return null;
        }

        $default_card = null;
        foreach ($sources->data as $source) {
            if($source->id === $default_source) {
                $default_card = $source;
            }
        }

        if(!$default_card) {
            return null;
        }

        return [
            'id' =>  $default_card->id,
            'brand' => $default_card->brand,
            'last4' => '**** **** **** '.$default_card->last4,
            'exp_month' => sprintf('%02d', $default_card->exp_month),
            'exp_year' => $default_card->exp_year
        ];
    }

    public function getBank($admin_customer_id, $customer_id) {
        return Stripe\Customer::retrieveSource($admin_customer_id, $customer_id);
    }


    /**
     * カード削除
     * @param $customerId
     * @param $cardId
     * @return Stripe\AlipayAccount|Stripe\BankAccount|Stripe\BitcoinReceiver|Stripe\Card|Stripe\Source
     * @throws Stripe\Exception\ApiErrorException
     */
    public function deleteCard($customerId, $cardId) {
        return Stripe\Customer::deleteSource($customerId, $cardId);
    }


    /**
     * @param Myuser $owner
     * @param Myuser $user
     * @param array $option [int: amount, int: application_fee, bool: capture]
     * @return Stripe\PaymentIntent
     * @throws Stripe\Exception\ApiErrorException
     */
    public function charge ($owner, Myuser $user, $option = []) {
        if (!$owner->customer) {
            throw new ValidationException('クレジット情報を設定してください');
        };

        if(!isset($option['application_fee'])) {
            $option['application_fee'] = 0;
        }

        if(!isset($option['capture'])) {
            $option['capture'] = false;
        }

        if(!isset($option['description'])) {
            $option['description'] = '';
        }

        $admin = $this->Myusers->find()
            ->where('role_id', Myuser::ROLE_ADMIN)
            ->first();

        if (!$admin->customer) {
            throw new ValidationException('管理者のクレジット情報がありません');
        }

        $customer = $this->retrieve($owner->customer);
        $source = $customer->default_source;

        try {
            $res = Stripe\PaymentIntent::create([
                'amount' => $option['amount'],
                'currency' => 'jpy',
                'customer' => $owner->customer,
//                'application_fee_amount' => $option['application_fee'],
                'confirm' => $option['capture'],
                'source' => $source,
                'description' => $option['description']
//                'transfer_data' => [
//                    'destination' => $admin->customer,
//                ]
            ]);
        } catch (\Exception $e) {
            dd($e);
        }


        return $res;
    }

    /**
     * 払い戻し
     * @param $charge_id
     * @throws Stripe\Exception\ApiErrorException
     */
    public function refund($charge_id) {
        Stripe\Refund::create([
            'charge' => $charge_id
        ]);
    }

    /**
     * @param $chargeId
     * @return Stripe\Charge
     * @throws Stripe\Exception\ApiErrorException
     */
    public function chargeRetrieve($chargeId) {
        return Stripe\Charge::retrieve($chargeId);
    }

    /**
     * stripeアカウント取得
     */
    public function getAccounts ($account_id) {
        return \Stripe\Account::retrieve(
            $account_id
        );
    }

    /**
     * ストライプ手数料取得
     * @param $amount
     * @return float
     */
    public function getCommission($amount) {
        // ストライプ手数料(四捨五入)
        return round($amount * (AffiliaterApplication::STRIPE_COMMISSION_RATE /100));
    }

    /**
     * ストライプ手数料を含めた合計金額
     * @param $amount
     * @return float
     */
    public function getTotalAmount($amount) {
        return $amount + $this->getCommission($amount);
    }
}
