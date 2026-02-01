<?php
namespace iboxs\payment\pay\wechat;

use iboxs\payment\pay\PaymentResult;

class H5Pay extends BaseWechatPay{
    /**
     * 运行
     */
    public function main(string $out_trade_no,float $amount,string $description,array $scene_info):PaymentResult|false{
        $requestData = array(
            'appid'=>$this->config['appid']['mp']??'',
            'description'=>$description,
            'out_trade_no'=>$out_trade_no,
            'amount'=>array(
                'total'=>intval($amount*100),  //单位 分
                'currency'=>$this->config['currency'],
            ),
            'scene_info'=>$scene_info,
            'notify_url'=>$this->config['notify_url'],
        );
        $publicData=$this->getRequestPublicData($requestData);
        $requestResult=$this->wechatV3Post('/v3/pay/transactions/h5',$publicData);
        return new PaymentResult($requestResult,$publicData);
    }
}