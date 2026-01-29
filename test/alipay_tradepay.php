<?php
/**
 * 这里是支付宝当面付（付款码支付）demo
 */
require_once "../vendor/autoload.php";
use iboxs\payment\Payment;
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    var_dump('错误',$errfile,$errline,$errstr);
});
$no="2021101247845559";
$amount=1;
$subject="订单测试";
try{
    $config=require("./config/config.php");  //可将config_example.php复制为config.php，填入自己的参数，并在这里引入
    $html= Payment::setConfig($config);  //laravel/thinkphp框架下按文档说明把配置文件放入config文件夹下后，无需调用本函数，可直接使用Payment::alipayTradePay()
    $html=$html->alipayTradePay($no,$amount,$subject,'123456789');
    $html=$html->addOptions([  //添加其他非必选参数，若有需要可添加，具体看支付接口文档，若不添加，可忽略调用本函数，直接调用$html->run()即可
        'timeout_express'=>'90m'
    ]);
    $r=$html->run();
    if($r==false){
        var_dump('请求错误');
        exit;
    }
    $response=$r->getResponse();  //获取响应参数
    var_dump('响应参数',$response);
}catch (\Exception $e){
    var_dump($e);
}