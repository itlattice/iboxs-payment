<?php
namespace iboxs\payment\lib;

use iboxs\payment\service\alipayService;

class Alipay extends payBase
{
    public function __construct($config)
    {
        parent::__construct($config);
    }

    ///alipayWebpay(string $no,float $amount,string $orderName) 支付宝网页支付（含手机端和PC端）
    public function webpay($orderInfo){
        $data=[
            'out_trade_no'=>$orderInfo[0],
            'total_amount'=>$orderInfo[1],
            'subject'=>$orderInfo[2],
        ];
        $aliPay = new alipayService($data,$this->config);
        $sHtml="";
        if($this->is_mobile_request() && $this->config['has_mobile']){
            $sHtml = $aliPay->wapPay();
        } else{
            $sHtml = $aliPay->webPay();
        }
        echo $sHtml;
        exit();
    }

    public function codePay($orderInfo){
        $data=[
            'out_trade_no'=>$orderInfo[0],
            'total_amount'=>$orderInfo[1],
            'subject'=>$orderInfo[2],
            'timeout_express'=>$orderInfo[3]??'30m'
        ];
        $aliPay = new alipayService($data,$this->config);
        return $aliPay->codePay()['alipay_trade_precreate_response']??false;
    }

    public function refund($orderInfo){
        $data=[
            'trade_no'=>$orderInfo[0],
            'out_trade_no'=>$orderInfo[1],
            'refund_amount'=>$orderInfo[2],
            'out_request_no'=>$orderInfo[3],
        ];
        $aliPay = new alipayService($data,$this->config);
        return $aliPay->refund();
    }

    public function jsPay($orderInfo){
        $data=[
            'out_trade_no'=>$orderInfo[0],
            'total_amount'=>$orderInfo[1],
            'subject'=>$orderInfo[2],
            'timeout_express'=>$orderInfo[3],
        ];
        $aliPay=new alipayService($data,$this->config);
        return $aliPay->jsPay();
    }

    public function barcodePay($orderInfo){
        $data=[
            'out_trade_no'=>$orderInfo[0],
            'scene'=>'bar_code',                //条码支付固定传入bar_code
            'auth_code'=>$orderInfo[1],        //用户付款码，25~30开头的长度为16~24位的数字，实际字符串长度以开发者获取的付款码长度为准
            'total_amount'=>$orderInfo[2],      //单位 元
            'subject'=>$orderInfo[3],           //订单标题
            'store_id'=>$orderInfo[4],          //商户门店编号
            'timeout_express'=>$orderInfo[5]??'2m',            //交易超时时间
        ];
        $aliPay=new alipayService($data,$this->config);
        return $aliPay->barCode();
    }

    public function transfer($orderInfo){
        $data = array(
            'out_biz_no'=>$orderInfo[0],
            'payee_type'=>'ALIPAY_LOGONID',
            'payee_account'=>$orderInfo[1],   //收款账户
            'payee_real_name'=>$orderInfo[2],  //收款方真实姓名
            'amount'=>$orderInfo[3], //转账金额，单位：元。
            'remark'=>$orderInfo[4],  //转账备注（选填）
        );
        $aliPay=new alipayService($data,$this->config);
        return $aliPay->transfer();
    }

    public function transferQuery($orderInfo){
        $data=[
            'out_biz_no'=>$orderInfo[0],
            'order_id'=>$orderInfo[1],
        ];
        $aliPay=new alipayService($data,$this->config);
        return $aliPay->transferQuery();
    }
}