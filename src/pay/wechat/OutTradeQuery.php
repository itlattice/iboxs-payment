<?php
namespace iboxs\payment\pay\wechat;

use iboxs\payment\pay\PaymentResult;

class OutTradeQuery extends BaseWechatPay{
    /**
     * 运行
     */
    public function main(string $out_trade_no):PaymentResult|false{
        $requestData = array();
        $publicData=$this->getRequestPublicData($requestData);
        $requestResult=$this->wechatV3Get('/v3/pay/transactions/out-trade-no/'.$out_trade_no,$publicData);
        return new PaymentResult($requestResult,$publicData);
    }
}