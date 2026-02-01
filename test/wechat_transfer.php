<?php
/**
 * 这里是微信支付商家转账发起转账demo
 * 文档地址：https://pay.weixin.qq.com/doc/v3/merchant/4012716434
 */
require_once "../vendor/autoload.php";
use iboxs\payment\Payment;
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    var_dump('错误',$errfile,$errline,$errstr);
});


$out_bill_no="2021101247845559";   //商户单号,商户系统内部的商家单号
$transfer_scene_id="1001"; //转账场景ID，详见文档
$openid="oUpF8uMuAJO_M2pxb1Q9zNjWeS6o";   //用户在商户对应appid[默认APPID]下的唯一标识
$user_name="张三"; //收款用户姓名(真实姓名，转账大于2000元时必填，否则可传入null即可)
$transfer_amount=1;   //转账金额，单位元
$transfer_remark="转账测试"; //转账备注
$transfer_scene_report_infos=[  //转账场景报备信息
    [
        'info_type'=>"活动名称",
        'info_content'=>"测试活动"
    ]
];

try{
    $config=require("./config/config.php");  //可将config_example.php复制为config.php，填入自己的参数，并在这里引入
    $payment= Payment::setConfig($config);//laravel/thinkphp框架下按文档说明把配置文件放入config文件夹下后，无需调用本函数，可直接使用Payment::wechatTransfer()
    $payment=$payment->wechatTransfer($out_bill_no,$transfer_scene_id,$openid,$user_name,$transfer_amount,$transfer_remark,$transfer_scene_report_infos);
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