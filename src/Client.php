<?php
/**
 * 支付从这里开始
 * @author  zqu
 */
namespace iboxs\payment;

class Client
{
    protected $config=[];

    /**
     * 传入支付配置信息
     * 如果需要支付宝支付就传入支付宝支付的配置信息，需要微信支付就传入微信支付配置信息，均为数组字典，具体格式参考文档及示例程序
     */
    public function __construct($config){
        $this->config=$config;
    }

    /**
     * 支付宝网页支付（会自动分手机端及pc端支付）
     */
    public function AlipayWeb($orderInfo){

    }

    /**
     * 支付宝扫码支付获取二维码
     */
    public function AlipayCode($orderInfo){

    }

    /**
     * 支付宝支付退款
     */
    public function AlipayRefund($orderInfo){

    }

    /**
     * 微信支付获取二维码（一般用于pc端支付），获取的为二维码信息，需将二维码信息转换为二维码图片
     */
    public function WxPayCode($orderInfo){

    }

    /**
     * 微信手机网页端支付（微信内网页可以直接使用微信提供的js调起支付）
     */
    public function WxPayWap($orderInfo){

    }

    /**
     * 微信获取统一下单预支付码（一般用于app支付及小程序支付）
     */
    public function WxJsapiParams($orderInfo){

    }

    /**
     * 微信支付退款
     */
    public function WxRefund($orderInfo){

    }

    /**
     * 微信支付到零钱
     */
    public function Transfers($orderInfo){

    }
}