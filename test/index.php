<?php
namespace iboxs\test;
require __DIR__."/../vendor/autoload.php";
use iboxs\payment\Payment;
$config=require_once __DIR__."/config_example.php";
$result=Payment::QQPay($config['qqpay'])->qqpayRefund('2022',time(),10,'名称');
var_dump($result);
?>