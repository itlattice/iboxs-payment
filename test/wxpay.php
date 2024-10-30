<?php
namespace iboxs\test;
require "../vendor/autoload.php";
use iboxs\payment\Client;
use iboxs\payment\Payment;

$wxpayconfig=require("config/wxpay.php");
$orderInfo=array(
    'order_name'=>"订单测试",
    'amount'=>1,
    'out_trade_no'=>"2021101247845559"
);
$wxpay=new Client('wechat',$wxpayconfig);
//var_dump($wxpay->wechatCodePay($orderInfo['out_trade_no'],$orderInfo['amount'],$orderInfo['order_name']));
//var_dump($wxpay->wechatH5Pay($orderInfo['out_trade_no'],$orderInfo['amount'],$orderInfo['order_name']));
// var_dump($wxpay->wechatRefund($orderInfo['out_trade_no'],$orderInfo['amount'],$orderInfo['order_name'],'aaa'));
var_dump($wxpay->wechatBarCodePay($orderInfo['out_trade_no'],0.01,"测试","131861357886735243"));
//Payment::Wechat()
/**
 * 回调验证：
 * $result=Notify::WxPayNotify($wxpayconfig);   ////返回回调数组信息，若返回false的为验签失败
 */