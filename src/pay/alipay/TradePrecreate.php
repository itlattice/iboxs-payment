<?php
namespace iboxs\payment\pay\alipay;

use iboxs\payment\pay\alipay\BaseAlipayPay;
use iboxs\payment\pay\PaymentResult;

class TradePrecreate extends BaseAlipayPay{
    public function main(string $out_trade_no,float $total_amount,string $subject,string $product_code='QR_CODE_OFFLINE'):PaymentResult|false{
        $requestData = array(
            'out_trade_no'=>$out_trade_no,
            'total_amount'=>$total_amount, //单位 元
            'subject'=>$subject,  //订单标题
            'product_code'=>$product_code
        );
        $publicData=$this->getRequestPublicData('alipay.trade.precreate',$requestData);
        $result = $this->curlPost($this->config['gatewayUrl'],$publicData);
        return new PaymentResult(json_encode($result['alipay_trade_precreate_response'],JSON_UNESCAPED_UNICODE),$publicData);
    }
}
