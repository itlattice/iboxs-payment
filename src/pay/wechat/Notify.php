<?php
namespace iboxs\payment\pay\wechat;

use iboxs\payment\pay\wechat\BaseWechatPay;

class Notify extends BaseWechatPay{
    public function check(){
        $headers=getallheaders();
        $wechatpaySignature=$headers['Wechatpay-Signature']??'';
        $wechatpayTimestamp=$headers['Wechatpay-Timestamp']??'';
        if(abs(time()-intval($wechatpayTimestamp))>300){ //时间戳超过五分钟，拒绝处理
            // return false;
        }
        $wechatpayNonce=$headers['Wechatpay-Nonce']??'';
        $wechatpaySerial=$headers['Wechatpay-Serial']??'';
        $body=file_get_contents('php://input');
        if($wechatpaySerial!=$this->config['publicKeySerial']){  //证书序列号不匹配，验签失败
            return false;
        }
        $signStr=$wechatpayTimestamp."\n".$wechatpayNonce."\n".$body."\n";
        $wechatpaySignature=base64_decode($wechatpaySignature);
        $public_key_path = $this->config['publicKeyPath']??'';
        if(!file_exists($public_key_path)){
            throw new \Exception('微信支付公钥文件不存在');
        }
        $public_key = file_get_contents($public_key_path);
        $publicKey=openssl_pkey_get_public($public_key);
        if($publicKey===false){
            return false;
        }
        $verifyResult=openssl_verify($signStr,$wechatpaySignature,$publicKey,OPENSSL_ALGO_SHA256);
        if($verifyResult!==1){  //验签失败
            return false;
        }
        return json_decode($body,true);
    }

    public function decryptToString($associatedData, $nonceStr, $ciphertext) {
        $ciphertext = base64_decode($ciphertext);
        if (strlen($ciphertext) <= 16) {
            return false;
        }
        if (in_array('aes-256-gcm', \openssl_get_cipher_methods())) {
            $ctext = substr($ciphertext, 0, -16);
            $authTag = substr($ciphertext, -16);
            return openssl_decrypt($ctext, 'aes-256-gcm', $this->config['apiKeyV3'], OPENSSL_RAW_DATA, $nonceStr,
                $authTag, $associatedData);
        }
        return false;
    }
}