<?php
/**
 * 支付从这里开始
 * @author  zqu zqu1016@qq.com
 * 
 */
namespace iboxs\payment;

use iboxs\payment\alipay\AlipayService;
use iboxs\payment\extend\Common;
use iboxs\payment\factory\Alipay;
use iboxs\payment\qqpay\QQPay;
use iboxs\payment\wxpay\App;
use iboxs\payment\wxpay\WxpayService;

class Client
{
    /**
     * 调用支付宝接口
     * @param array $config 支付宝设置信息（为空时自动读取配置，仅laravel/thinkphp支持）
     * @param bool $debug 是否使用沙盒环境
     * @return \iboxs\payment\factory\Alipay 支付宝操作键
     */
    public static function Alipay(array $config=[],bool $debug=false){
        if($config==[]){
            $config=config('alipay'); //这里仅支持thinkphp/laravel框架，其他框架请传入数据
        }
        $alipayConfig=[
            'publicKey' =>$config['publicKey']??exit('支付宝公钥不可为空'),
            'rsaPrivateKey' =>$config['rsaPrivateKey']??exit('应用私钥不可为空'),
            'appid' => $config['appid']??exit('APPID不可为空'),
            'notify_url' => $config['notify_url']??exit('异步通知地址不可为空'),
            'return_url' => $config['return_url']??exit('同步通知地址不可为空'),
            'charset' => $config['charset']??'UTF-8',
            'sign_type'=>$config['sign_type']??'RSA2',
            'gatewayUrl' =>$debug==false?"https://openapi.alipay.com/gateway.do":"https://openapi.alipaydev.com/gateway.do",
        ];
        return (new Alipay($alipayConfig));
    }
}