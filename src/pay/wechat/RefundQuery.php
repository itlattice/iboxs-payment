<?php
namespace iboxs\payment\pay\wechat;

use iboxs\payment\pay\PaymentResult;

class RefundQuery extends BaseWechatPay{
    /**
     * 运行
     */
    public function main(string $out_refund_no):PaymentResult|false{
        $requestData = array();
        $requestResult=$this->wechatV3Get('/v3/refund/domestic/refunds/'.$out_refund_no,$requestData);
        return new PaymentResult($requestResult,$requestData);
    }
}