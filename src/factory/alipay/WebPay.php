<?php
namespace iboxs\payment\factory\alipay;

/**
 * 网页支付
 */
class WebPay extends alipayBase{
    public function Main($orderInfo){
        $requestConfigs = array(
            'out_trade_no'=>$orderInfo['out_trade_no'],
            'product_code'=>'FAST_INSTANT_TRADE_PAY',
            'total_amount'=>$orderInfo['amount'], //单位 元
            'subject'=>$orderInfo['name'],  //订单标题
        );
        $commonConfigs=$this->createParam($requestConfigs,'alipay.trade.page.pay');
        $commonConfigs["sign"] = $this->generateSign($commonConfigs,'FAST_INSTANT_TRADE_PAY');
        return $this->buildRequestForm($commonConfigs);
    }
}