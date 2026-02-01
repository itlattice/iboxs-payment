<?php
/**
 * 这里是支付宝app支付接口2.0 demo
 * 文档地址：https://opendocs.alipay.com/open/cd12c885_alipay.trade.app.pay
 */
require_once "../vendor/autoload.php";
use iboxs\payment\Payment;
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    var_dump('错误',$errfile,$errline,$errstr);
});
$no="2021101247845559";   //商户订单号
$amount=1;   //订单金额，单位元
$subject="订单测试";  //订单标题
try{
    $config=require("./config/config.php");  //可将config_example.php复制为config.php，填入自己的参数，并在这里引入
    $payment= Payment::setConfig($config);  //laravel/thinkphp框架下按文档说明把配置文件放入config文件夹下后，无需调用本函数，可直接使用Payment::alipayAppPay()
    $payment=$payment->alipayAppPay($no,$amount,$subject);
    // $payment=$payment->addOptions([  //添加其他非必选参数，若有需要可添加，具体看支付接口文档，若不添加，可忽略调用本函数，直接调用$payment->run()即可
    //     //添加其他非必选参数
    // ]);
    $r=$payment->run();
    if($r==false){
        var_dump('请求错误');
        exit;
    }
    $response=$r->getBody();  //获取响应参数(支付宝app支付这里返回的是一个orderString字符串，所以需要使用getBody函数获得)
    var_dump('响应参数',$response);
}catch (\Exception $e){
    var_dump($e);
}