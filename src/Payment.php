<?php
/**
 * 支付从这里开始
 * @author  zqu zqu1016@qq.com
 */
namespace iboxs\payment;

use iboxs\payment\lib\Common;
use iboxs\payment\pay\{Alipay,Wechat};

/**
 * @see \iboxs\payment\lib\Base
 * @package iboxs\payment
 * @mixin \iboxs\payment\lib\Base
 * @version 2.0
 * @author ITLattice https://github.com/itlattice https://gitee.com/gz8 联系QQ：320587491
 * @license MIT
 * 这里开始是支付宝接口
 * @method static Alipay alipayWebPay(string $out_trade_no,float $total_amount,string $subject,string $product_code='FAST_INSTANT_TRADE_PAY') 支付宝网页支付（含手机端和PC端）
 * @method static Alipay alipayTradePay(string $out_trade_no,float $total_amount,string $subject,string $auth_code,string $scene='bar_code') 支付宝当面付支付(付款码扫码支付)
 * @method static Alipay alipayTradeQuery(string $out_trade_no=null,string $trade_no=null) 支付宝统一收单交易查询接口($trade_no和$out_trade_no二选一，不能同时为空，若两个都传入的，取$trade_no)
 * @method static Alipay alipayTradeRefund(float $refund_amount,string $out_trade_no=null,string $trade_no=null) 支付宝统一收单交易退款接口($trade_no和$out_trade_no二选一，不能同时为空，若两个都传入的，取$trade_no)
 * @method static Alipay alipayRefundQuery(string $out_request_no,string $out_trade_no=null,string $trade_no=null) 支付宝统一收单交易退款查询接口($trade_no和$out_trade_no二选一，不能同时为空，若两个都传入的，取$trade_no)
 * @method static Alipay alipayTradeCancel(string $out_trade_no=null,string $trade_no=null) 支付宝统一收单交易撤销接口($trade_no和$out_trade_no二选一，不能同时为空，若两个都传入的，取$trade_no)
 * @method static Alipay alipayTradePrecreate(string $out_trade_no,float $total_amount,string $subject,string $product_code='QR_CODE_OFFLINE') 支付宝统一收单线下交易预创建(生成二维码)
 * @method static Alipay alipayTradeClose(string $out_trade_no=null,string $trade_no=null) 支付宝统一收单交易关闭接口($trade_no和$out_trade_no二选一，不能同时为空，若两个都传入的，取$trade_no)
 * @method static Alipay alipayAppPay(string $out_trade_no,float $total_amount,string $subject) 支付宝APP支付
 * @method static Alipay alipayBillDownload(string $bill_type,string $bill_date) 支付宝查询对账单下载地址
 * @method static Alipay alipayTradeJSPay(string $out_trade_no,float $total_amount,string $subject,string $op_app_id,string $buyer_id=null,string $buyer_open_idstring=null) 支付宝JS支付统一收单交易创建接口(支付宝小程序使用)
 * 
 * 这里开始是微信支付接口
 * @method static Wechat wechatJspay(string $scene,string $out_trade_no,float $amount,string $description,string $openid) 微信支付JSAPI支付
 * @method static Wechat wechatTradeQuery(string $transaction_id) 微信支付订单号查询订单
 * @method static Wechat wechatOutTradeQuery(string $out_trade_no) 微信支付商户订单号查询订单
 * @method static Wechat wechatCloseTrade(string $out_trade_no) 微信支付关闭订单
 * @method static Wechat wechatRefund(string $out_refund_no,float $amount,float $total,string $transaction_id=null,string $out_trade_no=null) 微信支付退款申请
 * @method static Wechat wechatRefundQuery(string $out_refund_no) 微信支付查询单笔退款（通过商户退款单号）
 * @method static Wechat wechatAppPay(string $out_trade_no,float $amount,string $description) 微信支付APP支付
 * @method static Wechat wechatH5Pay(string $out_trade_no,float $amount,string $description,array $scene_info) 微信支付H5支付
 * @method static Wechat wechatNativePay(string $out_trade_no,float $amount,string $description) 微信支付Native支付(扫码支付)
 * @method static Wechat wechatBarcodePay(string $out_trade_no,float $amount,string $description,string $auth_code,string $clientIP='127.0.0.1') 微信支付付款码支付(V2)
 * @method static Wechat wechatTransfer(string $out_bill_no,string $transfer_scene_id,string $openid,string|null  $user_name,float $transfer_amount,string $transfer_remark,array $transfer_scene_report_infos) 微信支付商家转账发起转账
 */
class Payment
{
    use Common;
    protected $config;

    public static function setConfig($config=null){
        $obj=new self();
        $obj->config=$config;
        if($obj->config==null||$obj->config==[]){
            if(!function_exists('config')){
                throw (new \Exception('无配置数据'));
            }
            $config=config('payment');
        }
        return $obj;
    }

    public static function __callStatic($name, $arguments)
    {
        $name=self::convertUnderline($name);
        $arr=explode('_',$name);
        if(count($arr)<2){
            throw (new \Exception('方法不存在'));
            return;
        }
        $fun='';
        for($i=1;$i<count($arr);$i++){
            $fun.=strtoupper(substr($arr[$i],0,1)).substr($arr[$i],1,strlen($arr[$i])-1);
        }
        $obj=self::setConfig(null,$arr[0]);
        $fun=strtolower(substr($fun,0,1)).substr($fun,1,strlen($fun)-1);
        switch($arr[0]){
            case 'alipay':
                $result=(new Alipay($obj->config));
                break;
            case 'wechat':
                $result=(new Wechat($obj->config));
                break;
            default:
                throw (new \Exception('不支持的支付方式'));
        }
        $result->payType=$fun;
        $result->arguments=$arguments;
        return $result;
    }

    public function __call($name, $arguments)
    {
        $name=self::convertUnderline($name);
        $arr=explode('_',$name);
        if(count($arr)<2){
            throw (new \Exception('方法不存在'));
            return;
        }
        $fun='';
        for($i=1;$i<count($arr);$i++){
            $fun.=strtoupper(substr($arr[$i],0,1)).substr($arr[$i],1,strlen($arr[$i])-1);
        }
        $fun=strtolower(substr($fun,0,1)).substr($fun,1,strlen($fun)-1);
        switch($arr[0]){
            case 'alipay':
                $result=(new Alipay($this->config));
                break;
            case 'wechat':
                $result=(new Wechat($this->config));
                break;
            default:
                throw (new \Exception('不支持的支付方式'));
        }
        $result->payType=$fun;
        $result->arguments=$arguments;
        return $result;
    }

    public static function install(){
        if(class_exists('think\\App')||class_exists('Illuminate\\Http\\Request')||class_exists('iboxs\\App')){
            $path=root_path('config')."/payment.php";
            $text=__DIR__."/../test/config_example.php";
            if(file_exists($text)){
                $text=file_get_contents($text);
            }
            file_put_contents($path,$text);
        }
    }
}