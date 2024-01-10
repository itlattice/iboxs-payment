<?php

namespace iboxs\payment\service;

use iboxs\payment\lib\Common;

class wxpayService extends BaseService{
    protected $commonConfigs;
    protected $unified;
    use Common;

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
            'mchid' => $config['mchid']
        ];
    }

    public function codePay(){
        $unified2=[
            'description'=>$this->payInfo['subject'],
            'out_trade_no'=> $this->payInfo['out_trade_no'],
            'time_expire'=>wechatExpireTime($this->payInfo['time_expire']??null),
            'attach'=>$this->payInfo['attach']??null,
            'notify_url'=>$this->payConfig['notify_url'],
            'goods_tag'=>$this->payInfo['goods_tag']??null,
            'support_fapiao'=>$this->payConfig['support_fapiao'],
            'amount'=>[
                'total'=>intval($this->payInfo['total_amount'] * 100),
                'currency'=>$this->payConfig['currency']
            ],
            'detail'=>$this->payInfo['detail']??null,
            'scene_info'=>[
                'payer_client_ip'=>'127.0.0.1'
            ],
            'settle_info'=>$this->payConfig['settle_info'],
        ];
        $unified2=Nullify($unified2);
        $unified=array_merge($this->unified,$unified2);
        $unifiedOrder=$this->wechatResult('/v3/pay/transactions/native',$unified);
        return $unifiedOrder;
    }

    public function H5Pay(){
        $unified2=[
            'description'=>$this->payInfo['subject'],
            'out_trade_no'=> $this->payInfo['out_trade_no'],
            'time_expire'=>wechatExpireTime($this->payInfo['time_expire']??null),
            'attach'=>$this->payInfo['attach']??null,
            'notify_url'=>$this->payConfig['notify_url'],
            'goods_tag'=>$this->payInfo['goods_tag']??null,
            'support_fapiao'=>$this->payConfig['support_fapiao'],
            'amount'=>[
                'total'=>intval($this->payInfo['total_amount'] * 100),
                'currency'=>$this->payConfig['currency']
            ],
            'detail'=>$this->payInfo['detail']??null,
            'scene_info'=>[
                'payer_client_ip'=>'127.0.0.1'
            ],
            'settle_info'=>$this->payConfig['settle_info'],
        ];
        $unified2=Nullify($unified2);
        $unified=array_merge($this->unified,$unified2);
        $unifiedOrder=$this->wechatResult('/v3/pay/transactions/h5',$unified);
        return $unifiedOrder;
    }

    public function Jspay(){
        $unified2=[
            'description'=>$this->payInfo['subject'],
            'out_trade_no'=> $this->payInfo['out_trade_no'],
            'time_expire'=>wechatExpireTime($this->payInfo['time_expire']??null),
            'attach'=>$this->payInfo['attach']??null,
            'notify_url'=>$this->payConfig['notify_url'],
            'goods_tag'=>$this->payInfo['goods_tag']??null,
            'support_fapiao'=>$this->payConfig['support_fapiao'],
            'amount'=>[
                'total'=>intval($this->payInfo['total_amount'] * 100),
                'currency'=>$this->payConfig['currency']
            ],
            'payer'=>[
                'openid'=>$this->payInfo['openid']
            ],
            'detail'=>$this->payInfo['detail']??null,
            'scene_info'=>[
                'payer_client_ip'=>'127.0.0.1'
            ],
            'settle_info'=>$this->payConfig['settle_info'],
        ];
        $unified2=Nullify($unified2);
        $unified=array_merge($this->unified,$unified2);
        $unifiedOrder=$this->wechatResult('/v3/pay/transactions/jsapi',$unified);
        return $unifiedOrder;
    }

    public function barCodePay(){
        $unified2=[
            'device_info'=>$this->payInfo['device_info'],
            'nonce_str'=>$this->GetRandStr(32),
            'body'=>$this->payInfo['body'],
            'out_trade_no'=>$this->payInfo['out_trade_no'],
            'total_fee'=>round($this->payInfo['total_amount']*100),
            'fee_type'=>'CNY',
            'spbill_create_ip'=>'127.0.0.1',
            'limit_pay'=>$this->payInfo['limit_pay']??null,
            'time_expire'=>date('YmdHis',time()+$this->payInfo['time_expire']??600),
            'auth_code'=>$this->payInfo['auth_code'],
            'mch_id' => $this->payConfig['mchid'],
            'appid'=>$this->payConfig['appid']
        ];
        $unified2=Nullify($unified2);
        $unifiedOrder=$this->wechatResultV2('/pay/micropay',$unified2);
        return $unifiedOrder;
    }

    public function Apppay(){
        $unified2=[
            'description'=>$this->payInfo['subject'],
            'out_trade_no'=> $this->payInfo['out_trade_no'],
            'time_expire'=>wechatExpireTime($this->payInfo['time_expire']??null),
            'attach'=>$this->payInfo['attach']??null,
            'notify_url'=>$this->payConfig['notify_url'],
            'goods_tag'=>$this->payInfo['goods_tag']??null,
            'support_fapiao'=>$this->payConfig['support_fapiao'],
            'amount'=>[
                'total'=>intval($this->payInfo['total_amount'] * 100),
                'currency'=>$this->payConfig['currency']
            ],
            'detail'=>$this->payInfo['detail']??null,
            'scene_info'=>[
                'payer_client_ip'=>'127.0.0.1'
            ],
            'settle_info'=>$this->payConfig['settle_info'],
        ];
        $unified2=Nullify($unified2);
        $unified=array_merge($this->unified,$unified2);
        $unifiedOrder=$this->wechatResult('/v3/pay/transactions/app',$unified);
        return $unifiedOrder;
    }

    public function refund(){
        $unified2 = array(
            'transaction_id'=>$this->payInfo['transaction_id'],            //微信订单号
            'out_trade_no'=>$this->payInfo['out_trade_no'],        //商户订单号
            'out_refund_no'=>$this->payInfo['out_refund_no'],        //商户退款单号
            'reason'=>$this->payInfo['reson']??'',     //退款原因（选填）
            'notify_url'=>$this->payConfig['notify_url'],
            'amount'=>[
                'refund'=>$this->payInfo['amount'],
                'total'=>$this->payInfo['total_amount'],
                'currency'=>$this->payConfig['currency']
            ],
            'goods_detail'=>$this->payInfo['goods_detail']??null
        );
        $unified2=Nullify($unified2);
        $unified=array_merge($this->unified,$unified2);
        $unifiedOrder=$this->wechatResult('/v3/refund/domestic/refunds',$unified);
        return $unifiedOrder;
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