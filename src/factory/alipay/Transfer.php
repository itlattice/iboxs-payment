<?php
namespace iboxs\payment\factory\alipay;

/**
 * 支付宝转账到个人账户
 */
class Transfer extends alipayBase{
    public function Main($orderInfo){
        $commonConfigs=$this->createParam($orderInfo,'alipay.fund.trans.toaccount.transfer');
        $commonConfigs["sign"] = $this->generateSign($commonConfigs, $commonConfigs['sign_type']);
        $result = $this->curlPost($this->GatewayUrl,$commonConfigs);
        $resultArr = json_decode($result,true);
        if(empty($resultArr)){
            $result = iconv('GBK','UTF-8//IGNORE',$result);
            return json_decode($result,true);
        }
        return $resultArr;
    }
}