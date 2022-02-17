<?php
namespace iboxs\payment\factory\alipay;

/**
 * 二维码支付
 */
class CodePay extends alipayBase{
    public function Main($orderInfo){
        $requestConfigs = array(
            'out_trade_no'=>$orderInfo['out_trade_no'],
            'total_amount'=>$orderInfo['amount'], //单位 元
            'subject'=>$orderInfo['ordername'],  //订单标题
            'timeout_express'=>$orderInfo['time_express']      //该笔订单允许的最晚付款时间，逾期将关闭交易。取值范围：1m～15d。m-分钟，h-小时，d-天，1c-当天（1c-当天的情况下，无论交易何时创建，都在0点关闭）。 该参数数值不接受小数点， 如 1.5h，可转换为 90m。
        );
        $commonConfigs=$this->createParam($requestConfigs,'alipay.trade.precreate');
        $commonConfigs["sign"] = $this->generateSign($commonConfigs, $commonConfigs['sign_type']);
        $result = $this->curlPost($this->GatewayUrl,$commonConfigs);
        if($result==false){
            return false;
        }
        return json_decode($result,true);
    }
}