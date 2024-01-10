<?php
namespace iboxs\payment\service;
class alipayService extends BaseService{
    protected $commonConfigs;
    /**
     *
     * @param $data
     * @param $config
     */
    public function __construct($data, $config)
    {
        parent::__construct($data, $config);
        $this->commonConfigs = array(
            //公共参数
            'app_id' => $this->payConfig['appid'],
            'format' => 'json',
            'return_url' => $this->payConfig['return_url'],
            'charset'=>$this->payConfig['charset'],
            'sign_type'=>$this->payConfig['sign_type'],
            'timestamp'=>date('Y-m-d H:i:s'),
            'version'=>'1.0',
            'notify_url' => $this->payConfig['notify_url']
        );
    }

    public function webPay()
    {
        $requestConfigs=[
            'out_trade_no'=>$this->payInfo['out_trade_no'],
            'product_code'=>'FAST_INSTANT_TRADE_PAY',
            'total_amount'=>round($this->payInfo['total_amount'],2), //单位 元
            'subject'=>$this->payInfo['subject'],  //订单标题
        ];
        $this->commonConfigs['method']='alipay.trade.page.pay';
        $this->commonConfigs['biz_content']=json_encode($requestConfigs,256);
        $this->commonConfigs["sign"] = $this->generateSign($this->commonConfigs, $this->commonConfigs['sign_type']);
        return $this->buildRequestForm($this->commonConfigs);
    }

    public function wapPay(){
        $requestConfigs = array(
            'out_trade_no'=>$this->payInfo['out_trade_no'],
            'product_code'=>'FAST_INSTANT_TRADE_PAY',
            'total_amount'=>$this->payInfo['total_amount'], //单位 元
            'subject'=>$this->payInfo['subject'],  //订单标题
        );
        $this->commonConfigs['method']='alipay.trade.wap.pay';
        $this->commonConfigs['biz_content']=json_encode($requestConfigs,256);
        $this->commonConfigs["sign"] = $this->generateSign($this->commonConfigs, $this->commonConfigs['sign_type']);
        return $this->buildRequestForm($this->commonConfigs);
    }

    public function codePay(){
        $requestConfigs = array(
            'out_trade_no'=>$this->payInfo['out_trade_no'],
            'total_amount'=>$this->payInfo['total_amount'], //单位 元
            'subject'=>$this->payInfo['subject'],  //订单标题
            'timeout_express'=>$this->payInfo['timeout_express']??'30m'       //该笔订单允许的最晚付款时间，逾期将关闭交易。取值范围：1m～15d。m-分钟，h-小时，d-天，1c-当天（1c-当天的情况下，无论交易何时创建，都在0点关闭）。 该参数数值不接受小数点， 如 1.5h，可转换为 90m。
        );
        $this->commonConfigs['method']='alipay.trade.precreate';
        $this->commonConfigs['biz_content']=json_encode($requestConfigs);
        $this->commonConfigs["sign"] = $this->generateSign($this->commonConfigs, $this->commonConfigs['sign_type']);
        return $this->curlPost($this->payConfig['gatewayUrl'],$this->commonConfigs);
    }

    public function refund(){
        $requestConfigs = array(
            'trade_no'=>$this->payInfo['trade_no'],
            'out_trade_no'=>$this->payInfo['out_trade_no'],
            'refund_amount'=>$this->payInfo['refound_amount'],
            'out_request_no'=>$this->payInfo['refound_order'],
        );
        $this->commonConfigs['method']='alipay.trade.refund';
        $this->commonConfigs['biz_content']=json_encode($requestConfigs);
        $this->commonConfigs["sign"] = $this->generateSign($this->commonConfigs, $this->commonConfigs['sign_type']);
        $result = $this->curlPost($this->payConfig['gatewayUrl'],$this->commonConfigs);
        return $result;
    }

    public function jsPay(){
        $requestConfigs = array(
            'out_trade_no'=>$this->payInfo['out_trade_no'],
            'total_amount'=>$this->payInfo['total_amount'], //单位 元
            'subject'=>$this->payInfo['subject'],  //订单标题
            'timeout_express'=>$this->payInfo['timeout_express']??'30m',
            'product_code'=>'QUICK_MSECURITY_PAY', //销售产品码，商家和支付宝签约的产品码，为固定值QUICK_MSECURITY_PAY
        );
        $this->commonConfigs['method']='alipay.trade.app.pay';
        $this->commonConfigs['biz_content']=json_encode($requestConfigs);
        $this->commonConfigs["sign"] = $this->generateSign($this->commonConfigs, $this->commonConfigs['sign_type']);
        return http_build_query($this->commonConfigs);
    }

    public function barCode(){
        $requestConfigs = array(
            'out_trade_no'=>$this->payInfo['out_trade_no'],
            'scene'=>$this->payInfo['scene'],                //条码支付固定传入bar_code
            'auth_code'=>$this->payInfo['auth_code'],        //用户付款码，25~30开头的长度为16~24位的数字，实际字符串长度以开发者获取的付款码长度为准
            'total_amount'=>$this->payInfo['total_amount'],      //单位 元
            'subject'=>$this->payInfo['subject'],           //订单标题
            'store_id'=>$this->payInfo['store_id'],          //商户门店编号
            'timeout_express'=>$this->payInfo['timeout_express']??'2m',            //交易超时时间
            'query_options'=>$this->payInfo['query_options']??null
        );
        $this->commonConfigs['method']='alipay.trade.pay';
        $this->commonConfigs['biz_content']=json_encode($requestConfigs,256);
        $this->commonConfigs["sign"] = $this->generateSign($this->commonConfigs, $this->commonConfigs['sign_type']);
        dump($this->commonConfigs);
        $result = $this->curlPost($this->payConfig['gatewayUrl'],$this->commonConfigs);
        return $result['alipay_trade_pay_response']??false;
    }

    public function transfer(){
        $requestConfigs = array(
            'out_biz_no'=>$this->payInfo['out_trade_no'],
            'payee_type'=>'ALIPAY_LOGONID',
            'payee_account'=>$this->payInfo['payee_account'],   //收款账户
            'payee_real_name'=>$this->payInfo['payee_real_name'],  //收款方真实姓名
            'amount'=>$this->payInfo['amount'], //转账金额，单位：元。
            'remark'=>$this->payInfo['remark'],  //转账备注（选填）
        );
        $this->commonConfigs['method']='alipay.fund.trans.toaccount.transfer';
        $this->commonConfigs['biz_content']=json_encode($requestConfigs);
        $this->commonConfigs["sign"] = $this->generateSign($this->commonConfigs, $this->commonConfigs['sign_type']);
        $result = $this->curlPost($this->payConfig['gatewayUrl'],$this->commonConfigs);
        return $result;
    }

    public function transferQuery(){
        $requestConfigs = array(
            'out_biz_no'=>$this->payInfo['out_biz_no'],
            'order_id'=>$this->payInfo['order_id'],
        );
        $this->commonConfigs['method']='alipay.fund.trans.toaccount.transfer';
        $this->commonConfigs['biz_content']=json_encode($requestConfigs);
        $this->commonConfigs["sign"] = $this->generateSign($this->commonConfigs, $this->commonConfigs['sign_type']);
        $result = $this->curlPost($this->payConfig['gatewayUrl'],$this->commonConfigs);
        return $result;
    }
}
?>