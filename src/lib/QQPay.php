<?php
namespace iboxs\payment\lib;
use iboxs\payment\service\qqpayService;
use iboxs\payment\service\wxpayService;

class QQPay extends payBase
{
    public function codepay($orderInfo){
        $data=[
            'out_trade_no'=>$orderInfo[0],
            'total_amount'=>$orderInfo[1],
            'subject'=>$orderInfo[2],
        ];
        $wxPay = new qqpayService($data,$this->config);
        return $wxPay->codePay();
    }
}