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
     * @param string $paymode 支付方式（alipay:支付宝;wxpay:微信支付）
     * @param array $config 支付配置信息（一般框架内建议在config/payment.php内配置，若为活动配置，请传入，支付宝就传入支付宝的，微信就传入微信的）
     */
    private static function Client($paymode='alipay',$config=[]){
        return (new Client($paymode,$config));
    }

    /**
     * 发起支付宝支付
     * @param array $config 支付宝配置信息（一般框架内建议在config/payment.php内配置，若已配置，则无需传入，若为活动配置，请传入）
     * @return Client
     */
    public static function Alipay($config=[])
    {
        return self::Client('alipay',$config);
    }

    /**
     * 发起微信支付
     * @param array $config 微信支付配置信息（一般框架内建议在config/payment.php内配置，若已配置，则无需传入，若为活动配置，请传入）
     * @return Client
     */
    public static function Wechat($config=[])
    {
        return self::Client("wechat",$config);
    }
}