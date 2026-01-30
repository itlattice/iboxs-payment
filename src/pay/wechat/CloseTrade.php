<?php
namespace iboxs\payment\pay\wechat;

use iboxs\payment\pay\PaymentResult;

class CloseTrade extends BaseWechatPay{
    /**
     * 运行
     */
    public function main(string $out_trade_no):PaymentResult|false{
        $requestData = array();
        $publicData=$this->getRequestPublicData($requestData);
        $requestResult=$this->wechatV3Post('/v3/pay/transactions/out-trade-no/'.$out_trade_no.'/close',$publicData);
        if($requestResult==false){
            return false;
        }
        return new PaymentResult($requestResult,$publicData);
    }
}