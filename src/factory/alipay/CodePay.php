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
            'timeout_express'=>$orderInfo['time_express']
        );
        $commonConfigs=$this->createParam($requestConfigs,'alipay.trade.precreate');
        $commonConfigs["sign"] = $this->generateSign($commonConfigs, $commonConfigs['sign_type']);
        $result = $this->curlPost($this->GatewayUrl,$commonConfigs);
        if($result==false){
            return array(false,'请求失败',$result);
        }
        $response=$result['alipay_trade_precreate_response'];
        if(strtolower($response['msg'])!='success'){
            return array(false,$response['msg'],$result);
        }
        $bar=$response['qr_code'];
        return array(true,$bar,$result);
    }
}