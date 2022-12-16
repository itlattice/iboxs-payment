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

    public function h5pay($orderInfo){
        $data=[
            'out_trade_no'=>$orderInfo[0],
            'total_amount'=>$orderInfo[1],
            'subject'=>$orderInfo[2],
            'wap_url'=>$orderInfo[3],
        ];
        $wxPay = new wxpayService($data,$this->config);
        return $wxPay->H5Pay();
    }
    public function jspay($orderInfo){
        $data=[
            'out_trade_no'=>$orderInfo[0],
            'total_amount'=>$orderInfo[1],
            'subject'=>$orderInfo[2],
            'openid'=>$orderInfo[3]
        ];
        $wxPay = new wxpayService($data,$this->config);
        return $wxPay->Jspay();
    }

    public function apppay($orderInfo){
        $data=[
            'out_trade_no'=>$orderInfo[0],
            'total_amount'=>$orderInfo[1],
            'subject'=>$orderInfo[2],
            'trade_type'=>$orderInfo[3]?'JSAPI':'APP',
        ];
        $wxPay = new wxpayService($data,$this->config);
        return $wxPay->Apppay();
    }

    public function refound($orderInfo){
        $data=[
            'out_trade_no'=>$orderInfo[0],
            'total_amount'=>$orderInfo[1],
            'reson'=>$orderInfo[2],
            'refund_amount'=>$orderInfo[3],
            'transaction_id'=>$orderInfo[4],
            'out_refund_no'=>$orderInfo[5]
        ];
        $wxPay = new wxpayService($data,$this->config);
        return $wxPay->refound();
    }

    public function transfer($orderInfo){
        $data=[
            'openid'=>'',
            're_user_name'=>'',
            'out_refund_no'=>'',
            'desc'=>''
        ];
        $wxPay = new wxpayService($data,$this->config);
        return $wxPay->transfer();
    }
}