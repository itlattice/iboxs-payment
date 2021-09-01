<?php
use think\Loader;
Loader::import("alipay.pagepay.service.AlipayTradeService",EXTEND_PATH);
Loader::import('alipay.pagepay.buildermodel.AlipayTradePagePayContentBuilder',EXTEND_PATH);

class Pagepay
{
    //支付入口
    public static function pay($params)
    {
        //第一步：校检参数
        self::checkParams($params);

        //第二步：构造参数
        $payRequestBuilder = new AlipayTradePagePayContentBuilder();
        $payRequestBuilder->setBody($params['t_body']);//描述
        $payRequestBuilder->setSubject($params['trade_name']);//订单名称，必填
        $payRequestBuilder->setTotalAmount($params['total_amount']);//付款金额，必填
        $payRequestBuilder->setOutTradeNo($params['out_trade_no']);//商户订单号，商户网站订单系统中唯一订单号，必填

        //第三步：获取配置
        $config = config('alipay');
        $aop = new AlipayTradeService($config);

        /**
         * 第四步：电脑网站支付请求(会自动跳转到支付页面)
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @param $return_url 同步跳转地址，公网可以访问
         * @param $notify_url 异步通知地址，公网可以访问
         * @return $response 支付宝返回的信息
         */
        $aop->pagePay($payRequestBuilder, $config['return_url'], $config['notify_url']);
    }

    //支付检验
    private static function checkParams($params)
    {
        //商户订单号
        if(empty(trim($params['out_trade_no']))){
            self::processError("你输入的商户订单号有误！");
        }
        //订单名称
        if(empty(trim($params['trade_name']))){
            self::processError("订单名称为空！");
        }
        //付款金额
        if(floatval(trim($params['total_amount'])) <= 0){
            self::processError("付款金额有误！！");
        }
    }

    //统一错误处理接口
    private static function processError($msg)
    {
        throw new \think\Exception($msg);
    }

}