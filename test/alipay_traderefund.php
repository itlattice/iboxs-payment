<?php
/**
 * 这里是支付宝统一收单交易退款接口demo
 * 文档地址：https://opendocs.alipay.com/open/3aea9b48_alipay.trade.refund
 */
require_once "../vendor/autoload.php";
use iboxs\payment\Payment;
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    var_dump('错误',$errfile,$errline,$errstr);
});
$refund_amount=1;   //退款金额，单位元
$out_trade_no='2021101247845559'; //商户订单号
$trade_no=null;  //支付宝交易号（$out_trade_no和$trade_no不能同时为空，二选一即可，若两个同时传入的，取$trade_no，留空的这个可以传入null）
try{
    $config=require("./config/config.php");  //可将config_example.php复制为config.php，填入自己的参数，并在这里引入
    $payment= Payment::setConfig($config);  //laravel/thinkphp框架下按文档说明把配置文件放入config文件夹下后，无需调用本函数，可直接使用Payment::alipayTradeRefund()
    $payment=$payment->alipayTradeRefund($refund_amount,$out_trade_no,$trade_no);
    // $payment=$payment->addOptions([  //添加其他非必选参数，若有需要可添加，具体看支付接口文档，若不添加，可忽略调用本函数，直接调用$payment->run()即可
    //     //添加其他非必选参数
    // ]);
    $r=$payment->run();
    if($r==false){
        var_dump('请求错误');
        exit;
    }
    $response=$r->getResponse();  //获取响应参数
    var_dump('响应参数',$response);
}catch (\Exception $e){
    var_dump($e);
}