<?php
/**
 * 支付从这里开始
 * @author  zqu zqu1016@qq.com
 */
namespace iboxs\payment;

use iboxs\payment\lib\Base;

class Payment
{
    /**
     * 实例化数据
     * @param string $paymode 支付方式（alipay:支付宝;weixin:微信支付;pay_pal:PayPal支付;qqpay:QQ钱包支付）
     * @param array $config 支付配置信息（一般框架内建议在config/payment.php内配置，若为活动配置，请传入，支付宝就传入支付宝的，微信就传入微信的）
     */
    public static function Client($paymode='alipay',$config=[]){
        return (new Client($paymode,$config));
    }
}