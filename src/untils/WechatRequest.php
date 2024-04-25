<?php

namespace iboxs\payment\untils;

use iboxs\payment\lib\HttpCurl;

trait WechatRequest
{
    public function wechatPost($url,$data){
        $header=[
            'Authorization: '.$this->getAuthorization($url,$data),
            'Accept: application/json',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36',
            'Content-Type: application/json'
        ];
        $result=HttpCurl::post($url,$header,$data);
        return $result;
    }

    private function getAuthorization($url,$params,$method='POST'){
        $pathinfo=str_replace($this->payConfig['host'],'',$url);
        $time=time();
        $str=strtoupper($this->createNonceStr(32));
        $paramsStr='';
        if($method=='POST'||$method=='PUT'){
            $paramsStr=json_encode($params,JSON_UNESCAPED_UNICODE);
        }
        $signStr="{$method}\n{$pathinfo}\n{$time}\n{$str}\n{$paramsStr}\n";
        if(!file_exists($this->payConfig['merchantPrivateKeyFilePath'])){
            throw new \Exception('商户私钥文件不存在');
        }
        $privateKey=file_get_contents($this->payConfig['merchantPrivateKeyFilePath']);
        $sign=$this->getSHA256SignWithRSA($signStr,$privateKey);

        return 'WECHATPAY2-SHA256-RSA2048 mchid="'.$this->payConfig['mchid'].
            '",nonce_str="'.$str.
            '",signature="'.$sign.
            '",timestamp="'.$time.
            '",serial_no="'.$this->payConfig['merchantCertificateSerial'].'"';
    }

    public function getSHA256SignWithRSA($signContent = null, $privateKey = ''){
        $key = openssl_get_privatekey($privateKey);
        //开始加密
        openssl_sign($signContent, $signature, $key, OPENSSL_ALGO_SHA256);
        //进行 base64编码 加密后内容
        $encryptedData = base64_encode($signature);
        openssl_free_key($key);
        return $encryptedData;
    }
}