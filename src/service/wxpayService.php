<?php

namespace iboxs\payment\service;

class wxpayService extends BaseService{
    protected $commonConfigs;
    protected $unified;

    public function __construct($data, $config)
    {
        parent::__construct($data, $config);
        $this->commonConfigs= array(
            'mch_id' => $config['mchid'],
            'appid' => $config['appid'],
            'apiKey' => $config['apiKey'],
        );
        $this->unified=[
            'appid' => $config['appid'],
            'mch_id' => $config['mchid'],
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
        $unifiedOrder=$this->wechatResult('pay/unifiedorder',$unified);
        $codeUrl = (array)($unifiedOrder->code_url);
        if(!$codeUrl[0]) exit('get code_url error');
        return $codeUrl[0];
    }

    public function H5Pay(){
        $scene_info = array(
            'h5_info' =>array(
                'type'=>'Wap',
                'wap_url'=>$this->payInfo['wap_url'],
                'wap_name'=>$this->payInfo['subject'],
            )
        );
        $unified2 = array(
            'attach' => 'pay',             //商家数据包，原样返回，如果填写中文，请注意转换为utf-8
            'body' => $this->payInfo['subject'],
            'nonce_str' => self::createNonceStr(),
            'notify_url' => $this->payConfig['notify_url'],
            'out_trade_no' => $this->payInfo['out_trade_no'],
            'spbill_create_ip' => $_SERVER['REMOTE_ADDR']??'127.0.0.1',
            'total_fee' => intval($this->payInfo['total_amount'] * 100),       //单位 转为分
            'trade_type' => 'MWEB',
            'scene_info'=>json_encode($scene_info,256)
        );
        $unified=array_merge($this->unified,$unified2);
        $unifiedOrder= $this->wechatResult('pay/unifiedorder',$unified);
        if ($unifiedOrder->return_code != 'SUCCESS') {
            die($unifiedOrder->return_msg);
        }
        if($unifiedOrder->mweb_url){
            return $unifiedOrder->mweb_url;
        }
        exit('error');
    }

    public function Jspay(){
        $timestamp=time();
        $unified2 = array(
            'attach' => 'pay',             //商家数据包，原样返回，如果填写中文，请注意转换为utf-8
            'body' => $this->payInfo['subject'],
            'nonce_str' => self::createNonceStr(),
            'notify_url' => $this->payConfig['notify_url'],
            'out_trade_no' => $this->payInfo['out_trade_no'],
            'spbill_create_ip' => $_SERVER['REMOTE_ADDR']??'127.0.0.1',
            'total_fee' => intval($this->payInfo['total_amount'] * 100),       //单位 转为分//单位 转为分
            'trade_type' => 'JSAPI',
            'openid' => $this->payInfo['openid'],
        );
        $unified=array_merge($this->unified,$unified2);
        $unifiedOrder= $this->wechatResult('pay/unifiedorder',$unified);
        $arr = array(
            "appId" => $this->payConfig['appid'],
            "timeStamp" => "$timestamp",        //这里是字符串的时间戳，不是int，所以需加引号
            "nonceStr" => self::createNonceStr(),
            "package" => "prepay_id=" . $unifiedOrder->prepay_id,
            "signType" => 'MD5',
        );
        $arr['paySign'] = self::getSign($arr, $this->payConfig['apiKey']);
        return $arr;
    }

    public function Apppay(){
        $params=array(
            'body' => $this->payInfo['subject'],
            'out_trade_no' => $this->payInfo['out_trade_no'],
            'total_fee' => intval($this->payInfo['total_amount'] * 100),       //单位 转为分//单位 转为分
            'trade_type'=>$this->payInfo['trade_type'],
            "appId" => $this->payConfig['appid'],
            'mch_id' => $this->payConfig['mchid'],
            'nonce_str'=>$this->createNonceStr(),
            'notify_url'=>$this->payConfig['notify_url']
        );
        $this->params['sign'] = $this->getSign($params,$this->payConfig['apiKey']);
        $xml = $this->arrayToXml($params);
        $response = $this->httpPost(self::HOST.'pay/unifiedorder', $xml);
        if( !$response )
        {
            return false;
        }
        $result = $this->xml_to_data( $response );
        if( !empty($result['result_code']) && !empty($result['err_code']) )
        {
            return $result['err_msg'] = $this->error_code( $result['err_code'] );
        }
        else
        {
            return $this->getAppPayParams($result['prepay_id']);
        }
    }

    public function refound(){
        $unified2 = array(
            'total_fee' => intval($this->payInfo['total_amount'] * 100),       //订单金额	 单位 转为分
            'refund_fee' => intval($this->payInfo['refund_amount'] * 100),       //退款金额 单位 转为分
            'sign_type' => 'MD5',           //签名类型 支持HMAC-SHA256和MD5，默认为MD5
            'transaction_id'=>$this->payInfo['transaction_id'],               //微信订单号
            'out_trade_no'=>$this->payInfo['out_trade_no'],        //商户订单号
            'out_refund_no'=>$this->payInfo['out_refund_no'],        //商户退款单号
            'refund_desc'=>$this->payInfo['reson'],     //退款原因（选填）
        );

        $unified=array_merge($this->unified,$unified2);
        $unifiedOrder=$this->wechatResult('secapi/pay/refund',$unified,true);
        if ($unifiedOrder->result_code != 'SUCCESS') {
            die($unifiedOrder->err_code);
        }
        return true;
    }

    public function transfer(){
        $unified = array(
            'mch_appid' => $this->payConfig['appid'],
            'mchid' => $this->payConfig['mchid'],
            'nonce_str' => self::createNonceStr(),
            'openid' => $this->payInfo['openid'],
            'check_name'=>'FORCE_CHECK',        //校验用户姓名选项。NO_CHECK：不校验真实姓名，FORCE_CHECK：强校验真实姓名
            're_user_name'=>$this->payInfo['re_user_name'],             //收款用户真实姓名（不支持给非实名用户打款）
            'partner_trade_no' => $this->payInfo['out_refund_no'],
            'spbill_create_ip' => '127.0.0.1',
            'amount' => intval($this->payInfo['total_amount'] * 100),       //单位 转为分
            'desc'=>$this->payInfo['desc'],            //企业付款操作说明信息
        );
        $unifiedOrder=$this->wechatResult('mmpaymkttransfers/promotion/transfers',$unified,true);
        if ($unifiedOrder->result_code != 'SUCCESS') {
            die($unifiedOrder->err_code);
        }
        return true;
    }
}