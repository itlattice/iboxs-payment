<?php
namespace iboxs\payment\lib;
use iboxs\payment\service\weixinService;

class Wxpay extends payBase
{
    public function codePay($orderInfo){
        $data=[
            'out_trade_no'=>$orderInfo[0],
            'total_fee'=>$orderInfo[1],
            'subject'=>$orderInfo[2],
        ];
        $wechat=new weixinService($data,$this->config);
        return $wechat->codePay();
    }

    public function h5Pay($orderInfo){
        $data=[
            'out_trade_no'=>$orderInfo[0],
            'total_fee'=>$orderInfo[1],
            'subject'=>$orderInfo[2],
        ];
        $wechat=new weixinService($data,$this->config);
        $url=$wechat->h5Pay();
        header('HTTP/1.1 301 Moved Permanently');//发出301头部
        header('Location: '.$url);//跳转到新域名地址
        exit();
    }
}