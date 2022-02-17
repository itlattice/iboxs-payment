<?php
namespace iboxs\payment\factory\alipay;

/**
 * 手机网页支付
 */
class WapPay extends alipayBase{
    public function Main($orderInfo){
        $requestConfigs = array(
            'out_trade_no'=>$orderInfo['out_trade_no'],
            'product_code'=>'QUICK_WAP_WAY',
            'total_amount'=>$orderInfo['amount'], //单位 元
            'subject'=>$orderInfo['name'],  //订单标题
        );
        $commonConfigs=$this->createParam($requestConfigs,'alipay.trade.wap.pay');
        $commonConfigs["sign"] = $this->generateSign($commonConfigs,'QUICK_WAP_WAY');
        return $this->buildRequestForm($commonConfigs);
    }
}