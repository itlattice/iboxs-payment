<?php
/**
 * 快速校验
 * @author  zqu
 */
namespace iboxs\payment;

use iboxs\payment\alipay\AlipayNotify;
use iboxs\payment\wxpay\WxpayNotify;

/**
 * 回调验签
 */
class Notify
{
    /**
     * 支付宝验签（异步）
     * @param array $config 支付宝配置信息
     * @return bool 验签成功返回true，失败返回false
     */
    public static function alipayNotify($config)
    {
        $params=$_POST;
        $notify=new AlipayNotify($config);
        $result=$notify->rsaCheck($params);
        if($result===true){
            echo "success";
        }
        return $result;
    }

    /**
     * 微信验签
     * @param array $config 微信配置信息
     * @return bool 验签成功返回true，失败返回false
     */
    public static function WxPayNotify($config){
        $notify=new WxpayNotify($config['mchid'],$config['appid'],$config['key']);
        $result=$notify->Check();
        return $result;
    }
}