<?php
/**
 * 这里是支付宝查询对账单下载地址demo
 * 文档地址：https://opendocs.alipay.com/open/b5c20219_alipay.data.dataservice.bill.downloadurl.query
 */
require_once "../vendor/autoload.php";
use iboxs\payment\Payment;
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    var_dump('错误',$errfile,$errline,$errstr);
});
$billType='trade';   //账单类型
/**
 * 商户基于支付宝交易收单的业务账单: trade
 * 基于商户支付宝余额收入及支出等资金变动的账务账单: signcustomer
 * 营销活动账单，包含营销活动的发放，核销记录: merchant_act
 * 直付通二级商户查询交易的业务账单: trade_zft_merchant
 * 直付通平台商查询二级商户流水使用，返回所有二级商户流水。: zft_acc
 * 每日结算到卡的资金对应的明细，下载内容包含批次结算到卡明细文件（示例）和批次结算到卡汇总文件（示例）；若查询时间范围内有多个批次，会将多个批次的明细和汇总文件打包到一份压缩包中；: settlementMerge
 */
$bill_date='2021-10-10'; //账单时间

try{
    $config=require("./config/config.php");  //可将config_example.php复制为config.php，填入自己的参数，并在这里引入
    $payment= Payment::setConfig($config);  //laravel/thinkphp框架下按文档说明把配置文件放入config文件夹下后，无需调用本函数，可直接使用Payment::alipayBillDownload()
    $payment=$payment->alipayBillDownload($billType,$bill_date);
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