<?php
/**
 * 微信异步通知Demo
 */
require_once "../vendor/autoload.php";
use iboxs\payment\Notify;
$config=require("./config/config.php");
/**
 * 三个参数分别为
 * $echo 验签结果是否回显（若不回显，微信会认为通知失败，重复发送通知，直到成功为止）,您也可以自行进行处理
 * $config 配置参数，若不传入则使用配置文件中的配置
 */
$notifyResult=Notify::Wechat(true,$config);  //返回false为验签失败，否则返回的数据为格式化或者未格式化的数据，具体格式化内容看readme相关说明
if($notifyResult==false){
    var_dump('验证失败');
    exit;
}
var_dump('验签成功');
$tradeNo=$notifyResult->getTradeNo();  //微信账单号
var_dump('微信账单号',$tradeNo);
