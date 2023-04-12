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
        $qqPay = new qqpayService($data,$this->config);
        return $qqPay->codePay();
    }

    public function refund($orderInfo)
    {
        $data=[
            'trade_no'=>$orderInfo[0],
            'out_trade_no'=>$orderInfo[1],
            'total_amount'=>$orderInfo[2],
            'subject'=>$orderInfo[3],
            'refund_trade_no'=>$orderInfo[4]
        ];
        $qqPay = new qqpayService($data,$this->config);
        return $qqPay->refound();
    }

    public function jspay($orderInfo)
    {
        $data=[
            'out_trade_no'=>$orderInfo[0],
            'total_amount'=>$orderInfo[1],
            'subject'=>$orderInfo[2],
        ];
        $qqPay = new qqpayService($data,$this->config);
        return $qqPay->jsPay();
    }
}