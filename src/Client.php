<?php
/**
 * 支付从这里开始
 * @author  zqu
 */
namespace iboxs\payment;

use iboxs\payment\alipay\AlipayService;
use iboxs\payment\extend\Common;
use iboxs\payment\wxpay\App;
use iboxs\payment\wxpay\WxpayService;

class Client
{
    protected $config=[];

    /**
     * 传入支付配置信息
     * 如果需要支付宝支付就传入支付宝支付的配置信息，需要微信支付就传入微信支付配置信息，均为数组字典，具体格式参考文档及示例程序
     */
    public function __construct($config){
        if(!isset($config['gatewayUrl'])){
            $config['gatewayUrl']="https://openapi.alipay.com/gateway.do";
        }
        $this->config=$config;
    }

    /**
     * 支付宝网页支付（会自动分手机端及pc端支付）
     */
    public function AlipayWeb($orderInfo){
        $aliPay = new AlipayService();
        $aliPay->setAppid($this->config['appid']);
        $aliPay->setReturnUrl($this->config['return_url']);
        $aliPay->setNotifyUrl($this->config['notify_url']);
        $aliPay->setRsaPrivateKey($this->config['rsaPrivateKey']);
        $aliPay->setTotalFee($orderInfo['amount']);
        $aliPay->setOutTradeNo($orderInfo['out_trade_no']);
        $aliPay->setOrderName($orderInfo['order_name']);
        $aliPay->setGatewayUrl($this->config['gatewayUrl']);
        $sHtml="";
        if(Common::is_mobile_request()){
            $sHtml = $aliPay->wapPay();
        } else{
            $sHtml = $aliPay->webPay();
        }
        echo $sHtml;
    }

    /**
     * 支付宝扫码支付获取二维码
     */
    public function AlipayCode($orderInfo){
        $aliPay = new AlipayService();
        $aliPay->setAppid($this->config['appid']);
        $aliPay->setNotifyUrl($this->config['notify_url']);
        $aliPay->setRsaPrivateKey($this->config['rsaPrivateKey']);
        $aliPay->setTotalFee($orderInfo['amount']);
        $aliPay->setOutTradeNo($orderInfo['out_trade_no']);
        $aliPay->setOrderName($orderInfo['order_name']);
        $aliPay->setGatewayUrl($this->config['gatewayUrl']);
        $result = $aliPay->codePay();
        return $result;
    }

    /**
     * 支付宝支付退款
     */
    public function AlipayRefund($orderInfo){
        $aliPay = new AlipayService();
        $aliPay->setAppid($this->config['appid']);
        $aliPay->setRsaPrivateKey($this->config['rsaPrivateKey']);
        $aliPay->setTradeNo($orderInfo['tradeNo']);
        $aliPay->setOutTradeNo($orderInfo['out_trade_no']);
        $aliPay->setRefundAmount($orderInfo['refund_amount']);
        $result = $aliPay->doRefund();
        $result = $result['alipay_trade_refund_response'];
        if($result['code'] && $result['code']=='10000'){
            return true;
        }else{
            return $result;
        }
    }

    /**
     * 微信支付获取二维码（一般用于pc端支付），获取的为二维码信息，需将二维码信息转换为二维码图片
     */
    public function WxPayCode($orderInfo){
        // $mchid = 'xxxx';          //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
        // $appid = 'xxxx';  //公众号APPID 通过微信支付商户资料审核后邮件发送
        // $apiKey = 'xxxx';   //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
        $wxPay = new WxpayService($this->config['mchid'] ,$this->config['appid'],$this->config['apiKey']);
        $outTradeNo = $orderInfo['out_trade_no'];     //你自己的商品订单号
        $payAmount = $orderInfo['amount'];          //付款金额，单位:元
        $orderName = $orderInfo['order_name'];    //订单标题
        $notifyUrl = $this->config['notify_url'];     //付款成功后的回调地址(不要有问号)
        $payTime = time();      //付款时间
        $arr = $wxPay->NativePay($payAmount,$outTradeNo,$orderName,$notifyUrl,$payTime);
        return $arr;
        // //生成二维码
        // $url = 'http://qr.liantu.com/api.php?text='.$arr['code_url'];
        // echo "<img src='{$url}' style='width:300px;'><br>";
        // echo '二维码内容：'.$arr['code_url'];
    }

    /**
     * 微信手机网页端支付（微信内网页可以直接使用微信提供的js调起支付）
     */
    public function WxPayWap($orderInfo){
        $wxPay = new WxpayService($this->config['mchid'] ,$this->config['appid'],$this->config['apiKey']);
        $wxPay->setTotalFee($orderInfo['amount']);
        $wxPay->setOutTradeNo($orderInfo['out_trade_no']);
        $wxPay->setOrderName($orderInfo['order_name']);
        $wxPay->setNotifyUrl($this->config['notify_url']);
        $wxPay->setWapUrl($this->config['return_url']);
        $wxPay->setWapName($orderInfo['order_name']);
        $mwebUrl= $wxPay->H5Pay($$orderInfo['amount'],$orderInfo['out_trade_no'],$orderInfo['order_name'],$this->config['notify_url']);
        header("Location: {$mwebUrl}");
        exit();
    }
    /**
     * 微信公众号支付
     */
    public function WxJsPay($orderInfo){
        $wxPay = new WxpayService($this->config['mchid'] ,$this->config['appid'],$this->config['apiKey']);
        $openId = $wxPay->GetOpenid($orderInfo['code']);      //获取openid
        if(!$openId) exit('获取openid失败');
        $outTradeNo = $orderInfo['out_trade_no'];     //你自己的商品订单号
        $payAmount =$orderInfo['amount'];         //付款金额，单位:元
        $orderName = $orderInfo['order_name'];    //订单标题
        $notifyUrl = $this->config['notify_url'];     //付款成功后的回调地址(不要有问号)
        $payTime = time();      //付款时间
        $jsApiParameters = $wxPay->JsPay($openId,$payAmount,$outTradeNo,$orderName,$notifyUrl,$payTime);
        return $jsApiParameters;
    }

    /**
     * 微信APP支付(获取支付码)
     */
    public function WxJsapiParams($orderInfo,$is_micro_app=false){
        $app=new App();
        $params=array(
            'body'=>$orderInfo['body'],
            'out_trade_no'=>$orderInfo['out_trade_no'],
            'total_fee'=>$orderInfo['amount'],
            'trade_type'=>$is_micro_app?'JSAPI':'APP',
            'appid'=>$this->config['appid'],
            'mch_id'=>$this->config['mchid'],
            'nonce_str'=>Common::genRandomString(),
            'notify_url'=>$this->config['notify_url']
        );
        $result=$app->unifiedOrder($params);
        return $result;
    }

    /**
     * 微信支付退款
     */
    public function WxRefund($orderInfo){
        $orderNo = $orderInfo['out_trade_no'];                   //商户订单号（商户订单号与微信订单号二选一，至少填一个）
        $wxOrderNo =  $orderInfo['trade_no'];                    //微信订单号（商户订单号与微信订单号二选一，至少填一个）
        $totalFee =$orderInfo['amount'];                   //订单金额，单位:元
        $refundFee = $orderInfo['refund_amount'];                 //退款金额，单位:元
        $refundNo = $orderInfo['refund_trade_no'];        //退款订单号(可随机生成)
        $desc=$orderInfo['desc'];  //说明
        $wxPay = new WxpayService($this->config['mchid'] ,$this->config['appid'],$this->config['apiKey']);
        $result = $wxPay->doRefund($totalFee, $refundFee, $refundNo, $wxOrderNo,$orderNo,$desc);
        return $result;
    }

    /**
     * 微信支付到零钱
     */
    public function Transfers($orderInfo){
        //①、获取当前访问页面的用户openid（如果给指定用户转账，则直接填写指定用户的openid)
        $wxPay = new WxpayService($this->config['mchid'] ,$this->config['appid'],$this->config['apiKey']);
        $openId = $wxPay->GetOpenid($orderInfo['code']);      //获取openid
        if(!$openId) exit('获取openid失败');
        //②、付款
        $outTradeNo = $orderInfo['out_trade_no'];     //订单号
        $payAmount = $orderInfo['amount'];           //转账金额，单位:元。转账最小金额为1元
        $trueName = $orderInfo['real_name'];         //收款人真实姓名
        $desc=$orderInfo['desc'];
        $result = $wxPay->createJsBizPackage($openId,$payAmount,$outTradeNo,$trueName,$desc);
        return $result;
    }
}