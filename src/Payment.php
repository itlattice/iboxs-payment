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
 * @method static Alipay alipayWebPay(string $out_trade_no,float $total_amount,string $subject,string $product_code='FAST_INSTANT_TRADE_PAY') 支付宝网页支付（含手机端和PC端）
 * @method static Alipay alipayTradePay(string $out_trade_no,float $total_amount,string $subject,string $auth_code,string $scene='bar_code') 支付宝当面付支付(付款码扫码支付)
 * @method static Alipay alipayTradeQuery(string $out_trade_no=null,string $trade_no=null) 支付宝统一收单交易查询接口($trade_no和$out_trade_no二选一，不能同时为空，若两个都传入的，取$trade_no)
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