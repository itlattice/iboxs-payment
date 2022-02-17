<?php
namespace iboxs\payment\factory;

use iboxs\payment\factory\alipay\CodePay;
use iboxs\payment\factory\alipay\Transfer;
use iboxs\payment\factory\alipay\WapPay;
use iboxs\payment\factory\alipay\WebPay;

/**
 * 支付宝支付
 */
class Alipay extends Base{
    /**
     * 网页支付
     * @param string $orderName 订单名称
     * @param string $outTradeNo 商户订单号
     * @param double|int|float $amount 订单金额
     * @param bool $mobile 是否启用wap端
     * @return void 这里发起后将直接启动支付，不用管返回值
     */
    public function webPay($orderName,$outTradeNo,$amount,$mobile=true){
        $orderInfo=[
            'amount'=>$amount,
            'out_trade_no'=>$outTradeNo,
            'name'=>$orderName
        ];
        $payClass=[];
        if($this->is_mobile_request() && $mobile){  //手机发起
            $payClass=new WapPay($this->config);
        } else{
            $payClass=new WebPay($this->config);
        }
        echo $payClass->Main($orderInfo);
        exit();
    }

    /**
     * 二维码当面付（获取支付二维码，用户扫码支付）
     * @param string $orderName 订单名称
     * @param string $outTradeNo 商户订单号
     * @param double|int|float $amount 订单金额
     * @param string $timeoutExpress 该笔订单允许的最晚付款时间
     * @return array 请求结果
     */
    public function codePay($orderName,$outTradeNo,$amount,$timeoutExpress='30m'){
        $orderInfo=[
            'ordername'=>$orderName,
            'amount'=>$amount,
            'out_trade_no'=>$outTradeNo,
            'time_express'=>$timeoutExpress
        ];
        $payClass=new CodePay($this->config);
        return $payClass->Main($orderInfo);
    }

    /**
     * 支付宝转账到个人账户
     * @param string $account 账号
     * @param string $realName 用户真实姓名
     * @param double $amount 金额
     * @param string $remark 转账备注
     * @param string $outTradeNo 商户端订单号(单笔转账务必唯一，以免多次支付)
     * @return array 请求结果
     */
    public function transfer($account,$realName,$amount,$remark,$outTradeNo){
        $requestConfigs = array(
            'out_biz_no'=>$outTradeNo,
            'payee_type'=>'ALIPAY_LOGONID',
            'payee_account'=>$account,   //收款账户
            'payee_real_name'=>$realName,  //收款方真实姓名
            'amount'=>$amount, //转账金额，单位：元。
            'remark'=>$remark,  //转账备注（选填）
        );
        $payClass=new Transfer($this->config);
        return $payClass->Main($requestConfigs);
    }
}