<?php
namespace iboxs\payment\pay\alipay;

use iboxs\payment\pay\PaymentResult;

class TradeRefund extends BaseAlipayPay{
    public function main(float $refund_amount,string $out_trade_no=null,string $trade_no=null):PaymentResult|false{
        $requestData = array(
            'refund_amount'=>$refund_amount, //单位 元
            'out_trade_no'=>$out_trade_no,
            'trade_no'=>$trade_no
        );
        $publicData=$this->getRequestPublicData('alipay.trade.refund',$requestData);
        $result = $this->curlPost($this->config['gatewayUrl'],$publicData);
        return new PaymentResult(json_encode($result['alipay_trade_refund_response'],JSON_UNESCAPED_UNICODE),$publicData);
    }
}