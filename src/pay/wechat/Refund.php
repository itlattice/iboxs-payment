<?php
namespace iboxs\payment\pay\wechat;

use iboxs\payment\pay\PaymentResult;

class Refund extends BaseWechatPay{
    /**
     * 运行
     */
    public function main(string $out_refund_no,float $amount,float $total,string $transaction_id=null,string $out_trade_no=null):PaymentResult|false{
        $requestData = array(
            'out_refund_no'=>$out_refund_no,
            'out_trade_no'=>$out_trade_no,
            'transaction_id'=>$transaction_id,
            'amount'=>array(
                'total'=>intval($total*100),  //单位 分
                'currency'=>$this->config['currency'],
                'refund'=>intval($amount*100)  //单位 分
            ),
            'notify_url'=>$this->config['notify_url'],
        );
        $publicData=$this->getRequestPublicData($requestData);
        $requestResult=$this->wechatV3Post('/v3/refund/domestic/refunds',$publicData);
        return new PaymentResult($requestResult,$publicData);
    }
}