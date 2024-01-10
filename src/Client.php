<?php
/**
 * 支付从这里开始
 * @author  zqu zqu1016@qq.com
 * 
 */
namespace iboxs\payment;

use Exception;
use iboxs\payment\lib\Alipay;
use iboxs\payment\lib\Base;
use iboxs\payment\lib\QQPay;
use iboxs\payment\lib\Wxpay;
/**
 * @see \iboxs\payment\lib\Base
 * @package iboxs\payment
 * @mixin \iboxs\payment\lib\Base
 * @version 2.0
 * @author ITLattice https://github.com/itlattice https://gitee.com/gz8 联系QQ：320587491
 * @license MIT
 * @method static void alipayWebpay(string $no,float $amount,string $orderName) 支付宝网页支付（含手机端和PC端）[直接跳转]
 * @method static array alipayCodePay(string $no,float $amount,string $orderName,string $time_out='30m') 获取支付宝当面付二维码
 * @method static array alipayRefund(string $trade_no,string $out_trade_no,float $refound_amount,string $refound_order) 支付宝退款接口
 * @method static string alipayJsPay(string $no,float $amount,string $orderName,string $time_out='30m') 支付宝JS支付（小程序、APP、生活号）
 * @method static array alipayBarcodePay(string $no,string $barcode,float $amount,string $order_name,string $store_id,string $time_out="2m",$scene='bar_code',$query_options=[]) //支付宝条形码支付
 * @method static array alipayTransfer(string $biz_no,string $account,string $real_name,float $amount,string $remark)  支付宝转账
 * @method static array alipayTransferQuery(string $biz_no,string $order_id)  //支付宝转账结果查询
 * @method static array wechatCodePay(string $no,float $amount,string $orderName) 微信Native支付（一般用于PC端）,返回二维码的信息
 * @method static array wechatH5Pay(string $no,float $amount,string $orderName) 微信H5支付（一般用于手机网页端，非公众号）[直接跳转]
 * @method static array wechatJsPay(string $no,float $amount,string $orderName,string $openid) 微信JS支付（一般用于公众号/小程序）
 * @method static array wechatApppay(string $no,float $amount,string $orderName,bool $trade_type=false) 微信APP支付
 * @method static array wechatBarCodePay($out_trade_no,$total_amount,$body,$auth_code,$device_info=null,$limit_pay=null,$time_expire=600) 微信条形码支付
 * @method static array wechatRefund(string $no,float $total_amount,string $reson,float $refund_amount,string $transaction_id,string $out_refund_no) 微信退款
 * @method static array wechatTransfer(string $no,string $name,string $desc,string $openid) 微信转账到微信零钱账户
 * @method static string qqpayCodepay(string $no,float $amount,string $orderName) QQ钱包Native支付
 * @method static string qqpayRefund(string $trade_no,string $out_trade_no,float $refound_amount,string $subject,string $refound_order)  QQ钱包订单退款
 * @method static string qqpayJspay(string $no,float $amount,string $orderName) QQ钱包JS支付
 */
class Client extends Base
{
    /**
     * 实例化数据
     * @param string $paymode 支付方式（alipay:支付宝;weixin:微信支付;pay_pal:PayPal支付;qqpay:QQ钱包支付）
     * @param array $config 支付配置信息（一般框架内建议在config/payment.php内配置，若为活动配置，请传入，支付宝就传入支付宝的，微信就传入微信的）
     */
    public function __construct($paymode='alipay',$config=[])
    {
        if($config==[]){
            if(!function_exists('config')){
                throw (new Exception('无配置数据'));
            }
            $config=config('payment.'.$paymode);
        }
        $this->config=$config;
    }

    public static function install(){
        if(function_exists('root_path')){
            $path=root_path('config')."/payment.php";
            $text=__DIR__."/../test/config_example.php";
            if(file_exists($text)){
                $text=file_get_contents($text);
            }
            file_put_contents($path,$text);
        }
    }

    public function __call($name, $arguments)
    {
        $name=$this->convertUnderline($name);
        $arr=explode('_',$name);
        if(count($arr)<2){
            throw (new Exception('方法不存在'));
            return;
        }
        $fun='';
        for($i=1;$i<count($arr);$i++){
            $fun.=strtoupper(substr($arr[$i],0,1)).substr($arr[$i],1,strlen($arr[$i])-1);
        }

        $fun=strtolower(substr($fun,0,1)).substr($fun,1,strlen($fun)-1);
        switch($arr[0]){
            case 'alipay':
                return (new Alipay($this->config))->$fun($arguments);
                break;
            case 'wechat':
                return (new Wxpay($this->config))->$fun($arguments);
                break;
            case 'qqpay':
                return (new QQPay($this->config))->$fun($arguments);
                break;
            // case 'paypal':
            //     return (new Alipay($this->config))->$fun($arguments);
            //     break;
            default:
                throw (new Exception('不支持的支付方式'));
        }
    }
}