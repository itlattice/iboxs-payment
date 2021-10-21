<?php
/**
 * 快速校验
 * @author  zqu
 */
namespace iboxs\payment;

use iboxs\payment\alipay\AlipayNotify;
use iboxs\payment\wxpay\WxpayNotify;

class Notify
{
    public function alipayNotify($config)
    {
        $params=$_POST;
        $notify=new AlipayNotify($config);
        $result=$notify->rsaCheck($params);
        return $result;
    }

    public function WxPayNotify($config){
        $notify=new WxpayNotify($config['mchid'],$config['appid'],$config['key']);
        $result=$notify->Check();
        return $result;
    }
}