<?php
namespace iboxs\payment\pay\alipay;

use iboxs\payment\pay\PaymentResult;

class TradeCancel extends BaseAlipayPay{
    public function main(string $out_trade_no=null,string $trade_no=null):PaymentResult|false{
        $requestData = array(
            'out_trade_no'=>$out_trade_no,
            'trade_no'=>$trade_no
        );
        $publicData=$this->getRequestPublicData('alipay.trade.cancel',$requestData);
        $result = $this->curlPost($this->config['gatewayUrl'],$publicData);
        return new PaymentResult(json_encode($result['alipay_trade_cancel_response'],JSON_UNESCAPED_UNICODE),$publicData);
    }
}