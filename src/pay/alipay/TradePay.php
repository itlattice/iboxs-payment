<?php
namespace iboxs\payment\pay\alipay;

use iboxs\payment\pay\alipay\BaseAlipayPay;
use iboxs\payment\pay\PaymentResult;

class TradePay extends BaseAlipayPay{
    public function main(string $out_trade_no,float $amount,string $subject,string $auth_code,string $scene='bar_code'):PaymentResult|false{
        $requestData = array(
            'out_trade_no'=>$out_trade_no,
            'total_amount'=>$amount, //单位 元
            'subject'=>$subject,  //订单标题
            'auth_code'=>$auth_code,
            'scene'=>$scene
        );
        $publicData=$this->getRequestPublicData('alipay.trade.pay',$requestData);
        $result = $this->curlPost($this->config['gatewayUrl'],$publicData);
        return new PaymentResult(json_encode($result['alipay_trade_pay_response'],JSON_UNESCAPED_UNICODE),$publicData);
    }
}
