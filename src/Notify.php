<?php
/**
 * 异步回调验签从这里开始
 * @author  zqu zqu1016@qq.com
 */
namespace iboxs\payment;

use Exception;
use iboxs\payment\lib\Base;
use iboxs\payment\service\alipayNotifyService;
use iboxs\payment\service\wechatNotifyService;
use iboxs\payment\service\alipayService;

class Notify
{
    /**
     * 支付宝验签
     * 无需传入任何数据
     * @return false|array 若验签成功，返回数据，若验签失败，则返回false
     */
    public static function Alipay($format=true,$config=[]){
        $config=self::getConfig($config,'alipay');
        $params=$_POST;
        $service=new alipayNotifyService($config);
        $info=$service->check($params);
        if($info==false){
            return false;
        }
        if($format==false){
            return $params;
        }
        if(!($params['trade_status']=='TRADE_SUCCESS'||$params['trade_status']=='TRADE_FINISHED')){
            return false;
        }
        $subject=$params['subject'];
        try{
            $subject=iconv('GBK//IGNORE','UTF-8',$params['subject']);
        } catch(Exception $e){}

        $result=[
            'notify_type'=>$params['notify_type'],  //通知的类型
            'trade_no'=>$params['trade_no'],  //支付宝交易凭证号
            'out_trade_no'=>$params['out_trade_no'],  //商家订单号
            'out_biz_no'=>$params['out_biz_no']??null,  //商家业务 ID，主要是退款通知中返回退款申请的流水号。
            'trade_status'=>$params['trade_status'],  //交易目前所处的状态，见下表 交易状态说明。
            'receipt_amount'=>$params['receipt_amount']??0,  //商家在交易中实际收到的款项，单位为人民币
            'buyer_pay_amount'=>$params['buyer_pay_amount']??0,  //用户在交易中支付的金额
            'refund_fee'=>$params['refund_fee']??0,  //退款通知中，返回总退款金额
            'subject'=>$subject,  //商品的标题/交易标题/订单标题/订单关键字等，是请求时对应的参数，在通知中原样传回。
            'body'=>$params['body']??'', //该笔订单的备注、描述、明细等。对应请求时的 body 参数，在通知中原样传回。
            'params'=>$params  //原文
        ];
        return $result;
    }

    /**
     * 微信支付验签
     * @param $config
     * @return false|mixed
     */
    public static function Wechat($config=[]){
        $config=self::getConfig($config,'wexin');
        header("Content-type: text/xml");
        $notify=new wechatNotifyService($config['mchid'],$config['appid'],$config['apiKey']);
        $result=$notify->Check();
        $notifiedData = file_get_contents('php://input');
        //XML格式转换
        $xmlObj = simplexml_load_string($notifiedData, 'SimpleXMLElement', LIBXML_NOCDATA);
        $xmlObj = json_decode(json_encode($xmlObj), true);
        //支付成功
        if ($xmlObj['return_code'] == "SUCCESS" && $xmlObj['result_code'] == "SUCCESS") {
            if($result==true){
                echo sprintf("<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>");
                return $xmlObj;
            }
        }
        return false;
    }

    /**
     * QQ支付验签
     * @param $config
     * @return false|mixed
     */
    public static function QQPay($config=[]){
        $config=self::getConfig($config,'qqpay');
        header("Content-type: text/xml");
        $notify=new wechatNotifyService($config['mchid'],$config['appid'],$config['apiKey']);
        $result=$notify->Check();
        $notifiedData = file_get_contents('php://input');
        //XML格式转换
        $xmlObj = simplexml_load_string($notifiedData, 'SimpleXMLElement', LIBXML_NOCDATA);
        $xmlObj = json_decode(json_encode($xmlObj), true);
        //支付成功
        if ($xmlObj['return_code'] == "SUCCESS" && $xmlObj['result_code'] == "SUCCESS") {
            if($result==true){
                echo sprintf("<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>");
                return $xmlObj;
            }
        }
        return false;
    }

    private static function getConfig($config,$paymode){
        if($config==[]){
            if(!function_exists('config')){
                throw (new Exception('无配置数据'));
            }
            $config=config('payment.'.$paymode);
        }
        return $config;
    }
}