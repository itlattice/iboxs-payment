<?php
/**
 * 这里是微信支付查询单笔退款（通过商户退款单号）demo
 * 文档地址：https://pay.weixin.qq.com/doc/v3/merchant/4013070374
 */
require_once "../vendor/autoload.php";
use iboxs\payment\Payment;
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    var_dump('错误',$errfile,$errline,$errstr);
});
$out_refund_no='2021101247845559'; //商户退款单号
try{
    $config=require("./config/config.php");  //可将config_example.php复制为config.php，填入自己的参数，并在这里引入
    $payment= Payment::setConfig($config);//laravel/thinkphp框架下按文档说明把配置文件放入config文件夹下后，无需调用本函数，可直接使用Payment::wechatRefundQuery()
    $payment=$payment->wechatRefundQuery($out_refund_no);
    // $payment=$payment->addOptions([  //添加其他非必选参数，若有需要可添加，具体看支付接口文档，若不添加，可忽略调用本函数，直接调用$payment->run()即可
    //     //添加其他非必选参数
    // ]);
    $r=$payment->run();
    if($r==false){
        var_dump('请求错误');
        exit;
    }
    $responseHtml=$r->getResponse();  //获取响应参数(注意,这里返回签名错误也可能是各种原因,微信支付不像支付宝有严格的返回原因)
    var_dump('响应参数',$responseHtml);
}catch (\Exception $e){
    var_dump($e);
}