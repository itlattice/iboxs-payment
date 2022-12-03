<?php

namespace iboxs\payment\service;

class wxpayService extends BaseService{
    protected $commonConfigs;
    protected $unified;

    const HOST='https://api.mch.weixin.qq.com/';

    public function __construct($data, $config)
    {
        parent::__construct($data, $config);
        $this->commonConfigs= array(
            'mch_id' => $config['mchid'],
            'appid' => $config['appid'],
            'apiKey' => $$config['apiKey'],
        );
        $this->unified=[
            'appid' => $config['appid'],
            'mch_id' => $config['mch_id'],
            'nonce_str' => $this->createNonceStr()
        ];
    }

    public function codePay(){
        $unified2=[
            'attach' => 'pay',             //商家数据包，原样返回，如果填写中文，请注意转换为utf-8
            'body' => $this->payInfo['subject'],
            'notify_url' => $this->payConfig['notify_url'],
            'out_trade_no' => $this->payInfo['out_trade_no'],
            'spbill_create_ip' => '127.0.0.1',
            'total_fee' => intval($this->payInfo['total_amount'] * 100),       //单位 转为分
            'trade_type' => 'NATIVE',
        ];
        $unified=array_merge($this->unified,$unified2);
        return $this->wechatResult('pay/unifiedorder',$unified);
    }


    public function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
        }
        $xml .= "</xml>";
        return $xml;
    }

    public function wechatResult($url,$unified){
        $responseXml =$this->httpPost(self::HOST.'pay/unifiedorder',$this->arrayToXml($unified));
        $unifiedOrder = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($unifiedOrder === false) {
            die('parse xml error');
        }
        if ($unifiedOrder->return_code != 'SUCCESS') {
            die($unifiedOrder->return_msg);
        }
        if ($unifiedOrder->result_code != 'SUCCESS') {
            die($unifiedOrder->err_code);
        }
        $codeUrl = (array)($unifiedOrder->code_url);
        if(!$codeUrl[0]) exit('get code_url error');
        return $codeUrl[0];
    }
}