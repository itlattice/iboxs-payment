<?php
namespace iboxs\payment\pay\alipay;

use iboxs\payment\pay\BasePay;

class BaseAlipayPay extends BasePay{
    protected array $payRequestConfigs;

    public function __construct($config)
    {
        $this->config=$config['alipay']??$config;
        if(empty($this->config)){
            throw new \Exception("请配置支付宝参数");
        }
        $this->payRequestConfigs = array(
            //公共参数
            'app_id' => $this->config['appid'],
            'format' => 'json',
            'return_url' => $this->config['return_url'],
            'charset'=>$this->config['charset'],
            'sign_type'=>$this->config['sign_type'],
            'timestamp'=>date('Y-m-d H:i:s'),
            'version'=>'1.0',
            'notify_url' => $this->config['notify_url']
        );
    }

    
    protected function generateSign($params, $signType = "RSA2") {
        return $this->sign($this->getSignContent($params), $signType);
    }

    protected function sign($data, $signType = "RSA2") {
        $priKey=$this->config['rsaPrivateKey'];
        $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
            wordwrap($priKey, 64, "\n", true) .
            "\n-----END RSA PRIVATE KEY-----";
        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');
        if ("RSA2" == $signType) {
            openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256); //OPENSSL_ALGO_SHA256是php5.4.8以上版本才支持
        } else {
            openssl_sign($data, $sign, $res);
        }
        $sign = base64_encode($sign);
        return $sign;
    }

    public function getSignContent($params) {
        ksort($params);
        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {
                // 转换成目标字符集
                $v = $this->characet($v, $this->config['charset']);
                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }

        unset ($k, $v);
        return $stringToBeSigned;
    }

    protected function curlPost($url = '', $postData = '', $format='json')
    {
        if (is_array($postData)) {
            foreach($postData as $key => $value){
                if($value==null){
                    unset($postData[$key]);
                }
            }
            $postData=http_build_query($postData);
        }
        $result=$this->postHttp($url,$postData);
        if($format=='json'){
            return $this->formatJsonResult($result);
        } else if($format=='xml'){
            return $this->formatXmlResult($result);
        } else{
            return $result;
        }
    }

    private function formatJsonResult($result){
        $resultArr = json_decode($result,true);
        if(empty($resultArr)){
            $data =  iconv('GBK','UTF-8//IGNORE',$result);
            return json_decode($data,true);
        }
        return json_decode($result,true);
    }

    private function formatXmlResult($result){
        
    }

    private function postHttp($url,$params){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset='.$this->config['charset']));
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    protected function getRequestPublicData($method,$requestData){
        $requestData=array_merge($requestData,$this->options);
        $this->payRequestConfigs['method']=$method;
        $this->payRequestConfigs['biz_content']=json_encode($requestData,256);
        $this->payRequestConfigs["sign"] = $this->generateSign($this->payRequestConfigs, $this->payRequestConfigs['sign_type']);
        return $this->payRequestConfigs;
    }
}