<?php
/**
 * 这里是微信支付JSAPI支付demo
 * 文档地址：https://pay.weixin.qq.com/doc/v3/merchant/4012791856
 */
require_once "../vendor/autoload.php";
use iboxs\payment\Payment;
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    var_dump('错误',$errfile,$errline,$errstr);
});
$out_trade_no="2021101247845559";   //商户订单号
$amount=1;   //订单金额，单位元（注意这里，也是传入元为单位，与支付宝一致，组件内会根据情况处理）
$description="订单测试";  //订单标题
$openid='oUpF8uMuAJO_M2pxb1Q9zNjWeS6o'; //用户在商户对应appid下的唯一标识
try{
    $config=require("./config/config.php");  //可将config_example.php复制为config.php，填入自己的参数，并在这里引入
    $html= Payment::setConfig($config);//laravel/thinkphp框架下按文档说明把配置文件放入config文件夹下后，无需调用本函数，可直接使用Payment::alipayWebPay()
    $html=$html->wechatJspay($out_trade_no,$amount,$description,$openid);
    // $payment=$payment->addOptions([  //添加其他非必选参数，若有需要可添加，具体看支付接口文档，若不添加，可忽略调用本函数，直接调用$payment->run()即可
    //     //添加其他非必选参数
    // ]);
    $r=$html->run();
    if($r==false){
        var_dump('请求错误');
        exit;
    }
    $responseHtml=$r->getResponse();  //获取响应参数(注意,这里返回签名错误也可能是各种原因,微信支付不像支付宝有严格的返回原因)
    var_dump('响应参数',$responseHtml);
}catch (\Exception $e){
    var_dump($e);
}