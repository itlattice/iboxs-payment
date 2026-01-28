<?php
require_once "../vendor/autoload.php";
use iboxs\payment\Payment;

$no="2021101247845559";
$amount=1;
$subject="订单测试";

$config=require_once "config_example.php";


Payment::setConfig($config)->alipayWebPay($no,$amount,$subject)->addOptions([

])->run();