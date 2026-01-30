<?php
namespace iboxs\payment\pay\alipay;

use iboxs\payment\pay\alipay\BaseAlipayPay;
use iboxs\payment\pay\PaymentResult;

class TradeJSPay extends BaseAlipayPay{
    public function main(string $out_trade_no,float $amount,string $subject,string $op_app_id,string $buyer_id=null,string $buyer_open_idstring=null):PaymentResult|false{
        $requestData = array(
            'out_trade_no'=>$out_trade_no,
            'total_amount'=>$amount, //单位 元
            'subject'=>$subject,  //订单标题
            'op_app_id'=>$op_app_id,
            'buyer_id'=>$buyer_id,
            'product_code'=>'JSAPI_PAY',
            'buyer_open_id'=>$buyer_open_idstring
        );
        $publicData=$this->getRequestPublicData('alipay.trade.create',$requestData);
        $result = $this->curlPost($this->config['gatewayUrl'],$publicData);
        return new PaymentResult(json_encode($result['alipay_trade_create_response'],JSON_UNESCAPED_UNICODE),$publicData);
    }
}
