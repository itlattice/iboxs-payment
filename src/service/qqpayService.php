<?php

namespace iboxs\payment\service;

use Exception;

class qqpayService extends BaseService
{
    protected $qqConfig=[];

    public function __construct($data, $config)
    {
        parent::__construct($data, $config);
        $this->qqConfig=[
            'mch_id' => $this->payConfig['mchid'],
            'appid' => $this->payConfig['appid'],
            'apiKey' => $this->payConfig['apiKey'],
        ];
    }

    public function codePay(){
        $unified = array(
            'appid' => $this->qqConfig['appid'],
            'attach' => 'pay',             //商家数据包，原样返回，如果填写中文，请注意转换为utf-8
            'body' => $this->payInfo['subject'],
            'mch_id' => $this->qqConfig['mchid'],
            'nonce_str' => $this->createNonceStr(),
            'notify_url' => $this->payConfig['notify_url'],
            'out_trade_no' => $this->payInfo['out_trade_no'],
            'spbill_create_ip' => $_SERVER['REMOTE_ADDR']??'127.0.0.1',
            'total_fee' => intval($this->payInfo['total_fee'] * 100),       //单位 转为分
            'trade_type' => 'NATIVE',
        );
        $unified['sign'] =$this->getSign($unified, $this->qqConfig['apiKey']);
        $unifiedOrder =$this->curlPostXml('https://qpay.qq.com/cgi-bin/pay/qpay_unified_order.cgi',$this->arrayToXml($unified));
        if ($unifiedOrder->return_code != 'SUCCESS') {
            throw (new Exception($unifiedOrder->return_msg));
        }
        if ($unifiedOrder->result_code != 'SUCCESS') {
            throw (new Exception($unifiedOrder->err_code));
        }
        $codeUrl = (array)($unifiedOrder->code_url);
        if(!$codeUrl[0]) exit('get code_url error');
        return $codeUrl;
    }
}