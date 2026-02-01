<?php
/**
 * 这里是微信支付退款申请demo
 * 文档地址：https://pay.weixin.qq.com/doc/v3/merchant/4012791862
 */
require_once "../vendor/autoload.php";
use iboxs\payment\Payment;
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    var_dump('错误',$errfile,$errline,$errstr);
});
$transaction_id="2021101247845559";   //微信交易订单号(二选一,和$transaction_id二选一即可,若两个都传入,则优先取$transaction_id)
$out_trade_no=null; //商户端订单号(二选一,和$transaction_id二选一即可,若两个都传入,则优先取$transaction_id)
$out_refund_no='2021101247845559'; //商户退款单号
$amount=1;   //退款金额,单位元
$total=1;  //订单总金额,单位元
try{
    $config=require("./config/config.php");  //可将config_example.php复制为config.php，填入自己的参数，并在这里引入
    $payment= Payment::setConfig($config);//laravel/thinkphp框架下按文档说明把配置文件放入config文件夹下后，无需调用本函数，可直接使用Payment::wechatTradeQuery()
    $payment=$payment->wechatRefund($out_refund_no,$amount,$total,$transaction_id,$out_trade_no);
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