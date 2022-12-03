<?php
namespace iboxs\test;
require __DIR__."/../vendor/autoload.php";
use iboxs\payment\Payment;
$config=require_once __DIR__."/config.php";
$result=Payment::Client('wxpay',$config['weixin'])->wxpayRefound('2022',10,'名称','10m');
var_dump($result);
?>