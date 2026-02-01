<?php
namespace iboxs\payment\pay\wechat;

use iboxs\payment\pay\PaymentResult;

class Jspay extends BaseWechatPay{
    /**
     * 运行
     */
    public function main(string $scene,string $out_trade_no,float $amount,string $description,string $openid):PaymentResult|false{
        $requestData = array(
            'appid'=>$this->config['appid'][$scene]??'',
            'description'=>$description,
            'out_trade_no'=>$out_trade_no,
            'amount'=>array(
                'total'=>intval($amount*100),  //单位 分
                'currency'=>$this->config['currency'],
            ),
            'payer'=>array(
                'openid'=>$openid
            ),
            'notify_url'=>$this->config['notify_url'],
        );
        $publicData=$this->getRequestPublicData($requestData);
        $requestResult=$this->wechatV3Post('/v3/pay/transactions/jsapi',$publicData);
        return new PaymentResult($requestResult,$publicData);
    }
}