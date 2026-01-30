<?php
namespace iboxs\payment\pay\wechat;

use iboxs\payment\pay\BasePay;

class BaseWechatPay extends BasePay{
    protected array $payRequestConfigs;

    public function __construct($config)
    {
        $this->config=$config['wechat']??[];
        if(empty($this->config)){
            throw new \Exception("请配置微信支付参数");
        }
        $this->payRequestConfigs = array(
            'mchid'=>$this->config['mchid'],
        );
    }

    protected function getRequestPublicData(array $requestData):array{
        $publicData = array_merge($this->payRequestConfigs,$requestData);
        $publicData=array_merge($publicData,$this->options);
        return $publicData;
    }

    protected function wechatV3Post(string $urlPath,array $data):string|false{
        $url=$this->config['host'].$urlPath;
        $header=[
            'Authorization: '.$this->getAuthorization($url,$data,'POST',$dataStr),
            'Accept: application/json',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36',
            'Content-Type: application/json'
        ];
        $result=$this->jsonPost($url,$header,$dataStr);
        return $result;
    }

    protected function wechatV3Get(string $urlPath,array $data=[]):string|false{
        $url=$this->config['host'].$urlPath;
        $header=[
            'Authorization: '.$this->getAuthorization($url,$data,'GET'),
            'Accept: application/json',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36',
            'Content-Type: application/json'
        ];
        $result=$this->httpGet($url,$header,$data);
        return $result;
    }


    private function getAuthorization($url,$params,$method='POST',&$paramsStr=''){
        $pathinfo=str_replace($this->config['host'],'',$url);
        $time=time();
        $str = trim(strtoupper($this->GetRandStr(32)));
        $paramsStr='';
        if($method=='POST'||$method=='PUT'){
            $paramsStr=json_encode($params,256|64);
        }
        $signStr="{$method}\n{$pathinfo}\n{$time}\n{$str}\n{$paramsStr}\n";
        if(!file_exists($this->config['merchantPrivateKeyFilePath'])){
            throw new \Exception('商户私钥文件不存在');
        }
        $privateKey=file_get_contents($this->config['merchantPrivateKeyFilePath']);
        $sign=$this->getSHA256SignWithRSA($signStr,$privateKey);
        return 'WECHATPAY2-SHA256-RSA2048 mchid="'.$this->config['mchid'].
            '",nonce_str="'.$str.
            '",signature="'.$sign.
            '",timestamp="'.$time.
            '",serial_no="'.$this->config['merchantCertificateSerial'].'"';
    }

    private function getSHA256SignWithRSA($signContent, $privateKey){
        // $signContent=file_get_contents('D:/sign.txt');
        $key = openssl_get_privatekey($privateKey);
        if(!$key) {
            throw new \Exception('获取私钥失败，请检查私钥格式是否正确');
        }
        //开始加密
        openssl_sign($signContent, $signature, $key, OPENSSL_ALGO_SHA256);
        //进行 base64编码 加密后内容
        $encryptedData = str_replace(["\r", "\n"], '', base64_encode($signature));
        if (PHP_VERSION_ID < 80000) {
            openssl_free_key($key);
        }
        return $encryptedData;
    }
}