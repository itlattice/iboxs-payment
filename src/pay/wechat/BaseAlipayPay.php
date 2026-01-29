<?php
namespace iboxs\payment\pay\alipay;

use iboxs\payment\pay\BasePay;

class BaseWechatPay extends BasePay{
    public function __construct($config)
    {
        $this->config=$config['wechat']??[];
        if(empty($this->config)){
            throw new \Exception("请配置微信支付参数");
        }
    }
}