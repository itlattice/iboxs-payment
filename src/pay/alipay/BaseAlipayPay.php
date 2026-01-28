<?php
namespace iboxs\payment\pay\alipay;

use iboxs\payment\pay\BasePay;

class BaseAlipayPay extends BasePay{
    
    public function __construct($config)
    {
        $this->config=$config['alipay']??[];
        if(empty($this->config)){
            throw new \Exception("请配置支付宝参数");
        }
    }
}