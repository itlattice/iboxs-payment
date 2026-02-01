<?php
namespace iboxs\payment\pay\wechat;

use iboxs\payment\pay\PaymentResult;

class NativePay extends BaseWechatPay{
    /**
     * 运行
     */
    public function main(string $out_trade_no,float $amount,string $description):PaymentResult|false{
        $requestData = array(
            'appid'=>$this->config['appid']['default']??'',
            'description'=>$description,
            'out_trade_no'=>$out_trade_no,
            'amount'=>array(
                'total'=>intval($amount*100),  //单位 分
                'currency'=>$this->config['currency'],
            ),
            'notify_url'=>$this->config['notify_url'],
        );
        $publicData=$this->getRequestPublicData($requestData);
        $requestResult=$this->wechatV3Post('/v3/pay/transactions/native',$publicData);
        return new PaymentResult($requestResult,$publicData);
    }
}