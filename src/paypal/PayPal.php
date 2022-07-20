<?php
namespace iboxs\payment\paypal;

use app\common\info\Cache;
use Illuminate\Http\Request;
use PayPal\Api\Amount;
use PayPal\Api\CartBase;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Refund;
use PayPal\Api\Transaction;
use PayPal\Api\ExecutePayment;
use PayPal\Api\MerchantInfo;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentExecution;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Api\Sale;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use function AlibabaCloud\Client\json;

class PayPal
{
    const Currency     = 'USD';//币种
    const accept_url   = 'https://oauth.itgz8.com/paypal/return';//返回地址
    const notify_url   = 'https://oauth.itgz8.com/paypal/notice';

    protected $config=[
        'clientId'=>'',
        'clientSecret'=>''
    ];

    public function __construct()
    {
        $this->config=[
            'clientId'=>env('paypal.clientid'),
            'clientSecret'=>env('paypal.secret')
        ];
    }
    
    public function payment($price, $order)
    {
        $price=$this->getScale($price);
        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                $this->config['clientId'],
                $this->config['clientSecret']
            )
        );
        /*sandbox 模式*/
        if(env('app_debug',false)==true){
            $apiContext->setConfig(array('mode' => 'sandbox'));
        } else{
            $apiContext->setConfig(array('mode' => 'live'));
        }

        $payer = new \PayPal\Api\Payer();
        $payer->setPaymentMethod('paypal');

        $amount = new \PayPal\Api\Amount();
        $amount->setTotal($price);
        $amount->setCurrency('USD');

        $transaction = new \PayPal\Api\Transaction();
        $transaction->setAmount($amount);

        $redirectUrls = new \PayPal\Api\RedirectUrls();
        $sn=time();
        $redirectUrls->setReturnUrl(self::accept_url."?result=success&order=".$order."&".$sn)
            ->setCancelUrl(self::accept_url."?result=cancel&order=".$order."&".$sn);

        $payment = new \PayPal\Api\Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions(array($transaction))
            ->setRedirectUrls($redirectUrls);

        try {
            $payment->create($apiContext);
            $url=$payment->getApprovalLink();
            return $url;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getData();
        }
    }

    public function ReturnInfo()
    {
        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                $this->config['clientId'],
                $this->config['clientSecret']
            )
        );
        /*sandbox 模式*/
        if(env('app_debug',false)==true){
            $apiContext->setConfig(array('mode' => 'sandbox'));
        } else{
            $apiContext->setConfig(array('mode' => 'live'));
        }
        // Get payment object by passing paymentId
        $paymentId = request()->param('paymentId');
        $payment = new \PayPal\Api\Payment();
        $payment = $payment->get($paymentId, $apiContext);
        $payerId = request()->param('PayerID');

        // Execute payment with payer ID
        $execution = new \PayPal\Api\PaymentExecution();
        $execution->setPayerId($payerId);

        try {
            // Execute payment
            $result = $payment->execute($execution, $apiContext);
            if ($result && isset($result->state) && $result->state == 'approved') {
                return [$result->cart,$result];
            } else {
                return false;
            }
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return false;
        } catch (\Exception $ex) {
            return false;
        }
    }

    public function getScale($price)
    {
        $url='http://op.juhe.cn/onebox/exchange/currency?key=7b7c57bb2a940a528c7ddb7e151b06a6&from=USD&to=CNY';
        $info=file_get_contents($url);
        $result=json_decode($info, true)['result'];
        foreach ($result as $k=>$v) {
            if ($v['currencyF']=='CNY') {
                $scale=$v['result'];
            }
        }
        Cache::set("huobiscale:cny:usd", $scale, 3600);
        $price=$price*$scale;
        return round($price, 2);
    }

    public function refound($serial, $amount, $remark)
    {
        $refundRequest = new \PayPal\Api\RefundRequest();
        $amount = new \PayPal\Api\Amount();
        $amount->setCurrency("USD")->setTotal($amount);//退总金额
        $refundRequest->setAmount($amount);
        $refundRequest->setDescription($remark);
        $sale = new \PayPal\Api\Sale();
        $sale->setId($serial);//支付单号,支付成功时保存的支付流水单号
        $oAuth = new \PayPal\Auth\OAuthTokenCredential($this->config['clientId'], $this->config['clientSecret']);
        $apiContext =  new \PayPal\Rest\ApiContext($oAuth);
        if (env('APP_DEBUG',false) === false) {
            $apiContext->setConfig(['mode' => 'live']);//设置线上环境,默认是sandbox
        }
        $detailedRefund = $sale->refundSale($refundRequest, $apiContext);//调接口
        $refundState = $detailedRefund->getState();//Possible values: pending, completed, cancelled, failed.

        //var_dump($refundedSale);
        if ($refundState == 'completed') {
            return [true];
        } else {
           return false;
        }
    }
}
