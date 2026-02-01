<?php
/**
 * 这里是微信支付关闭订单demo
 * 文档地址：https://pay.weixin.qq.com/doc/v3/merchant/4012791860
 */
require_once "../vendor/autoload.php";
use iboxs\payment\Payment;
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    var_dump('错误',$errfile,$errline,$errstr);
});
$out_trade_no="2091101247845559";   //商户端订单号
try{
    $config=require("./config/config.php");  //可将config_example.php复制为config.php，填入自己的参数，并在这里引入
    $payment= Payment::setConfig($config);//laravel/thinkphp框架下按文档说明把配置文件放入config文件夹下后，无需调用本函数，可直接使用Payment::wechatCloseTrade()
    $payment=$payment->wechatCloseTrade($out_trade_no);
    // $payment=$payment->addOptions([  //添加其他非必选参数，若有需要可添加，具体看支付接口文档，若不添加，可忽略调用本函数，直接调用$payment->run()即可
    //     //添加其他非必选参数
    // ]);
    $r=$payment->run();
    if($r==false){
        var_dump('请求错误');
        exit;
    }
    //注意,本接口成功响应为空包,空的才是成功
    if($r->getBody()==''){
        var_dump('关闭订单请求成功');  //至于是否关闭成功,需要调用查询接口或者等待微信回调(具体查看微信文档相关说明)
    }else{

    }
}catch (\Exception $e){
    var_dump($e);
}