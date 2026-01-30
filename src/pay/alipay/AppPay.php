<?php
namespace iboxs\payment\pay\alipay;

use iboxs\payment\pay\alipay\BaseAlipayPay;
use iboxs\payment\pay\PaymentResult;

class AppPay extends BaseAlipayPay{
    public function main(string $out_trade_no,float $amount,string $subject):PaymentResult|false{
        $requestData = array(
            'out_trade_no'=>$out_trade_no,
            'total_amount'=>$amount, //单位 元
            'subject'=>$subject,  //订单标题
        );
        $publicData=$this->getRequestPublicData('alipay.trade.app.pay',$requestData);
        $result = $this->curlPost($this->config['gatewayUrl'],$publicData,'params');
        return new PaymentResult($result,$publicData);
    }
}
