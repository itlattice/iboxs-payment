<?php
/**
 * 支付宝异步通知Demo
 */
require_once "../vendor/autoload.php";
use iboxs\payment\Notify;
$config=require("./config/config.php");
/**
 * 三个参数分别为
 * $echo 验签结果是否回显success给支付宝（若不回显，支付宝会认为通知失败，重复发送通知，直到成功为止）,您也可以自行进行处理
 * $config 配置参数，若不传入则使用配置文件中的配置
 */
$notifyResult=Notify::Alipay(true,$config);
if($notifyResult===false){
    var_dump('验证失败');
    exit;
}
var_dump('验签成功');
$tradeNo=$notifyResult->getTradeNo();  //支付宝交易号
var_dump('支付宝交易号',$tradeNo);