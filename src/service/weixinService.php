<?php

namespace iboxs\payment\service;

class weixinService extends BaseService{
    protected $wechatConfig=[];

    public function __construct($data, $config)
    {
        parent::__construct($data, $config);
        $this->wechatConfig=[
            'mch_id' => $this->payConfig['mchid'],
            'appid' => $this->payConfig['appid'],
            'apiKey' => $this->payConfig['apiKey'],
        ];
    }

    public function codePay(){
        $unified = array(
            'appid' => $this->wechatConfig['appid'],
            'attach' => 'pay',             //商家数据包，原样返回，如果填写中文，请注意转换为utf-8
            'body' => $this->payInfo['subject'],
            'mch_id' => $this->wechatConfig['mch_id'],
            'nonce_str' => $this->createNonceStr(),
            'notify_url' => $this->payConfig['notify_url'],
            'out_trade_no' => $this->payInfo['out_trade_no'],
            'spbill_create_ip' => $_SERVER['REMOTE_ADDR']??'127.0.0.1',
            'total_fee' => intval($this->payInfo['total_fee'] * 100),       //单位 转为分
            'trade_type' => 'NATIVE',
        );
        $unified['sign'] =$this->getSign($unified, $this->wechatConfig['apiKey']);
        $unifiedOrder =$this->curlPostXml('https://api.mch.weixin.qq.com/pay/unifiedorder',$this->arrayToXml($unified));
        if ($unifiedOrder->return_code != 'SUCCESS') {
            die($unifiedOrder->return_msg);
        }
        if ($unifiedOrder->result_code != 'SUCCESS') {
            die($unifiedOrder->err_code);
        }
        $codeUrl = (array)($unifiedOrder->code_url);
        if(!$codeUrl[0]) exit('get code_url error');
        return $codeUrl;
    }

    public function h5Pay(){
        $wapName=$this->payInfo['subject'];
        if(strlen($wapName)>3*10){
            $wapName=substr($wapName,0,30);
        }
        $scene_info = array(
            'h5_info' =>array(
                'type'=>'Wap',
                'wap_url'=>$this->payConfig['return_url'],
                'wap_name'=>$wapName,
            )
        );
        $unified = array(
            'appid' => $this->wechatConfig['appid'],
            'attach' => 'pay',             //商家数据包，原样返回，如果填写中文，请注意转换为utf-8
            'body' => $this->payInfo['subject'],
            'mch_id' => $this->payConfig['mch_id'],
            'nonce_str' => self::createNonceStr(),
            'notify_url' => $this->payConfig['notify_url'],
            'out_trade_no' => $this->payInfo['out_trade_no'],
            'spbill_create_ip' => $_SERVER['REMOTE_ADDR']??'127.0.0.1',
            'total_fee' => intval($this->payInfo['total_fee'] * 100),       //单位 转为分
            'trade_type' => 'MWEB',
            'scene_info'=>json_encode($scene_info)
        );
        $unified['sign'] = self::getSign($unified, $this->payConfig['apiKey']);
        $unifiedOrder =$this->curlPostXml('https://api.mch.weixin.qq.com/pay/unifiedorder', self::arrayToXml($unified));
        if ($unifiedOrder->return_code != 'SUCCESS') {
            die($unifiedOrder->return_msg);
        }
        if($unifiedOrder->mweb_url){
            return $unifiedOrder->mweb_url;
        }
        exit('error');
    }
}