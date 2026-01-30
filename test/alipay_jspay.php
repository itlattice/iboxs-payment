<?php
/**
 * 这里是支付宝JS支付统一收单交易创建接口demo(支付宝小程序使用)
 * 文档地址：https://opendocs.alipay.com/mini/6039ed0c_alipay.trade.create
 */
require_once "../vendor/autoload.php";
use iboxs\payment\Payment;
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    var_dump('错误',$errfile,$errline,$errstr);
});
$no="2021101247845559";   //商户订单号
$amount=1;   //订单金额，单位元
$subject="订单测试";  //订单标题
$op_app_id='2014072300007148';  //小程序支付中，商户实际经营主体的小程序应用的appid
$buyer_id='2088102177846881'; //买家支付宝用户ID（与buyer_open_idstring二选一）
$buyer_open_idstring=null; //买家支付宝用户唯一标识（与buyer_id二选一）

try{
    $config=require("./config/config.php");  //可将config_example.php复制为config.php，填入自己的参数，并在这里引入
    $payment= Payment::setConfig($config);  //laravel/thinkphp框架下按文档说明把配置文件放入config文件夹下后，无需调用本函数，可直接使用Payment::alipayTradeJSPay()
    $payment=$payment->alipayTradeJSPay($no,$amount,$subject,$op_app_id,$buyer_id,$buyer_open_idstring);
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