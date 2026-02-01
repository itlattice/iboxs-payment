<?php
namespace iboxs\payment\pay\wechat;

use iboxs\payment\pay\PaymentResult;

class BarcodePay extends BaseWechatPay{
    /**
     * 运行
     */
    public function main(string $out_trade_no,float $amount,string $description,string $auth_code,string $clientIP='127.0.0.1'):PaymentResult|false{
        $requestData = array(
            'appid'=>$this->config['appid']['default']??'',
            'mch_id'=>$this->config['mchid'],
            'body'=>$description,
            'out_trade_no'=>$out_trade_no,
            'total_fee'=>intval($amount*100),  //单位 分
            'spbill_create_ip'=>$clientIP,
            'auth_code'=>$auth_code,
        );
        $requestResult=$this->wechatV2Post('https://api.mch.weixin.qq.com/pay/micropay',$requestData);
        return new PaymentResult(json_encode($requestResult,256),$requestData);
    }

    private function wechatV2Post($url,array $data):array|false{
        $nonce_str=$this->GetRandStr();
        $data['nonce_str']=$nonce_str;
        $data['sign_type']='MD5';
        $sign=$this->getSign($data,$this->config['apiKeyV2']);
        $data['sign']=$sign;
        $xml=$this->arrayToXml($data);
        $result=$this->postXml($url,$xml);
        return $this->xml_to_data($result);
    }
    /**
     * 将xml转为array
     * @param string $xml
     * return array
     */
    private function xml_to_data($xml)
    {
        if(!$xml)
        {
            return false;
        }
        //将XML转为array
        //禁止引用外部xml实体
        if(PHP_VERSION_ID<80000){
            libxml_disable_entity_loader(true);
            $data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        } else{
            $xmlObject = simplexml_load_string($xml,'SimpleXMLElement', LIBXML_NOCDATA);
            $json = json_encode($xmlObject);
            $data = json_decode($json, true);
        }
        return $data;
    }

    private function arrayToXml($arr)
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

    private function getSign($params, $key)
    {
        ksort($params, SORT_STRING);
        $unSignParaString = $this->formatQueryParaMap($params, false);
        $signStr = strtoupper(md5($unSignParaString . "&key=" . $key));
        return $signStr;
    }
    protected function formatQueryParaMap($paraMap, $urlEncode = false)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if (null != $v && "null" != $v) {
                if ($urlEncode) {
                    $v = urlencode($v);
                }
                $buff .= $k . "=" . $v . "&";
            }
        }
        $reqPar = '';
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }

    
    private function postXml($url,$xml, $useCert = false, $second = 30){
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, false);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if($useCert == true) {
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
            //curl_setopt($ch,CURLOPT_SSLCERT, WxPayConfig::SSLCERT_PATH);
            curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
            //curl_setopt($ch,CURLOPT_SSLKEY, WxPayConfig::SSLKEY_PATH);
        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            return false;
        }
    }
}