<?php
namespace iboxs\payment\pay\wechat;

use iboxs\payment\pay\PaymentResult;

class TransQuery extends BaseWechatPay{
    /**
     * 运行
     */
    public function main(string $transaction_id):PaymentResult|false{
        $requestData = array();
        $publicData=$this->getRequestPublicData($requestData);
        $requestResult=$this->wechatV3Get('/v3/pay/transactions/id/'.$transaction_id,$publicData);
        return new PaymentResult($requestResult,$publicData);
    }
}