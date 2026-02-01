<?php
/**
 * 异步回调验签从这里开始
 * @author  zqu zqu1016@qq.com
 */
namespace iboxs\payment;

use Exception;
use iboxs\payment\pay\alipay\Notify as AlipayNotify;
use iboxs\payment\pay\NotifyData;
use iboxs\payment\pay\wechat\Notify as WechatNotify;

class Notify
{
    /**
     * 支付宝验签
     * 无需传入任何数据
     * @return false|array 若验签成功，返回数据，若验签失败，则返回false
     */
    public static function Alipay($echo=true,$config=[]):NotifyData|false{
        $config=self::getConfig($config,'alipay');
        $params=$_POST;
        $service=new AlipayNotify($config);
        $info=$service->check($params);
        if($info==false){
            if($echo==true){
                echo 'fail';  //请不要修改或删除
            }
            return false;
        }
        if(isset($params['charset'])&&strtolower($params['charset'])=='gbk'){
            $params=json_decode(iconv('GBK//IGNORE','UTF-8',json_encode($params)),true);
        }
        // $result=[
        //     'trade_no'=>$params['trade_no'],  //支付宝交易号
        //     'out_trade_no'=>$params['out_trade_no'],  //商家订单号
        //     'receipt_amount'=>$params['receipt_amount']??0,  //商家在交易中实际收到的款项，单位为人民币
        //     'buyer_pay_amount'=>$params['buyer_pay_amount']??0,  //用户在交易中支付的金额
        //     'params'=>$params  //原文
        // ];
        if($echo==true){
            echo 'success';  //请不要修改或删除
        }
        return new NotifyData('alipay',$params);
    }

    public static function Wechat($echo=true,$config=[]):NotifyData|false{
        $service=new WechatNotify(self::getConfig($config,'wechat'));
        $notify=$service->check();
        if($notify==false){
            if($echo==true){
                header('HTTP/1.1 400 Bad Request');
            }
            return false;
        }
        $resouse=$notify['resource']??null;
        
        if($resouse==null){
            if($echo==true){
                header('HTTP/1.1 400 Bad Request');
            }
            return false;
        }
        $decData=$service->decryptToString($resouse['associated_data'],$resouse['nonce'], $resouse['ciphertext']);
        if($decData==false){
            if($echo==true){
                header('HTTP/1.1 400 Bad Request');
            }
            return false;
        }
        $decData=json_decode($decData,true);
        // if($decData['trade_state']!='SUCCESS'){
        //     return false;
        // }
        // $result=[
        //     'trade_no'=>$decData['transaction_id'],  //交易号
        //     'out_trade_no'=>$decData['out_trade_no'],  //商家订单号
        //     'receipt_amount'=>$decData['amount']['total']??0,  //商家在交易中实际收到的款项，单位为人民币
        //     'buyer_pay_amount'=>$decData['amount']['payer_total']??0,  //用户在交易中支付的金额
        //     'params'=>$decData  //原文
        // ];
        if($echo==true){
            header('HTTP/1.1 200 OK');
        }
        return new NotifyData('wechat',$decData);
    }

    private static function getConfig($config,$paymode){
        if($config==[]){
            if(!function_exists('config')){
                throw (new Exception('无配置数据'));
            }
            $config=config('payment.'.$paymode);
        }
        return $config[$paymode]??[];
    }
}