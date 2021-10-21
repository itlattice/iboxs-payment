<?php

use iboxs\payment\Client;

$alipayconfig=require("config/alipay.php");
$orderInfo=array(

);
$alipay=new Client($alipayconfig);
$alipay->AlipayWeb($orderInfo);   //发起网页支付（无返回值，系统会自动跳到支付宝网站支付，不用区分手机和电脑，系统自动区分）

/**支付宝支付其他
 * $codeInfo=$alipay->AlipayCode($orderInfo);   //支付宝当面扫码支付（获得返回信息，自行提取二维码信息）
 * 
 * 支付宝其他操作（AlipayRefund 退款）
 * $result=$alipay->AlipayRefund($orderInfo);
 */

?>