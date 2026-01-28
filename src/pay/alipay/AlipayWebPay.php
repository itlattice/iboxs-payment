<?php
namespace iboxs\payment\pay\alipay;

use iboxs\payment\pay\alipay\BaseAlipayPay;
use iboxs\payment\pay\PaymentResult\PaymentResult;

class AlipayWebPay extends BaseAlipayPay{
    protected $amount;
    protected $out_trade_no;
    protected $subject;
    protected $product_code;

    public function setArguments(array $arguments){
        $this->out_trade_no=$arguments[0];
        $this->amount=$arguments[1];
        $this->subject=$arguments[2];
        $this->product_code=$arguments[3];
        return $this;
    }

    /**
     * 运行
     */
    public function run(){
        $requestData = array(
            'out_trade_no'=>$this->out_trade_no,
            'total_amount'=>$this->amount, //单位 元
            'subject'=>$this->subject,  //订单标题
            'product_code'=>$this->product_code
        );
        $requestData=array_merge($requestData,$this->options);
        $this->commonConfigs['method']='alipay.trade.wap.pay';
        $this->commonConfigs['biz_content']=json_encode($requestData,256);
        $this->commonConfigs["sign"] = $this->generateSign($this->commonConfigs, $this->commonConfigs['sign_type']);
        $html=$this->buildRequestForm($this->commonConfigs);
        return new PaymentResult($html,$requestData)->setBody($html);
    }

    private function buildRequestForm(array $para_temp) {

        $sHtml = "正在跳转至支付页面...<form id='alipaysubmit' name='alipaysubmit' action='".$this->payConfig['gatewayUrl']."?charset=".$this->payConfig['charset']."' method='POST'>";
        foreach($para_temp as $key=>$val){
            if (false === $this->checkEmpty($val)) {
                $val = str_replace("'","&apos;",$val);
                $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
            }
        }
        //submit按钮控件请不要含有name属性
        $sHtml = $sHtml."<input type='submit' value='ok' style='display:none;''></form>";
        $sHtml = $sHtml."<script>document.forms['alipaysubmit'].submit();</script>";
        return $sHtml;
    }
}