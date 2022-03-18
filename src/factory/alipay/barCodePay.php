<?php

namespace iboxs\payment\factory\alipay;

class barCodePay extends alipayBase
{
    public function Main($orderInfo){
        $commonConfigs=$this->createParam($orderInfo,'alipay.trade.pay');
        $commonConfigs["sign"] = $this->generateSign($commonConfigs, $commonConfigs['sign_type']);
        $result = $this->curlPost($this->GatewayUrl,$commonConfigs);
        if($result==false){
            return array(false,'请求失败',$result);
        }
        $response=$result['alipay_trade_precreate_response'];
        return $response;
    }
}