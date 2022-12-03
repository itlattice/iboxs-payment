<?php
namespace iboxs\payment\lib;
use iboxs\payment\service\wxpayService;

class Wxpay extends payBase
{
    public function __construct($config)
    {
        parent::__construct($config);
    }

    public function codepay($orderInfo){
        $data=[
            'out_trade_no'=>$orderInfo[0],
            'total_amount'=>$orderInfo[1],
            'subject'=>$orderInfo[2],
        ];
        $wxPay = new wxpayService($data,$this->config);
        return $wxPay->codePay();
    }
}