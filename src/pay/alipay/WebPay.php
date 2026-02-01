<?php
namespace iboxs\payment\pay\alipay;

use iboxs\payment\pay\alipay\BaseAlipayPay;
use iboxs\payment\pay\PaymentResult;

class WebPay extends BaseAlipayPay{
    /**
     * 运行
     */
    public function main(string $out_trade_no,float $amount,string $subject,string $product_code='FAST_INSTANT_TRADE_PAY'):PaymentResult{
        $requestData = array(
            'out_trade_no'=>$out_trade_no,
            'total_amount'=>$amount, //单位 元
            'subject'=>$subject,  //订单标题
            'product_code'=>$product_code
        );
        if($this->is_mobile_request() && ($this->config['has_mobile']??false)){
            $publicData=$this->getRequestPublicData('alipay.trade.wap.pay',$requestData);
        } else{
            $publicData=$this->getRequestPublicData('alipay.trade.page.pay',$requestData);
        }
        =$this->buildRequestForm($publicData);
        return new PaymentResult(,$publicData);
    }

    private function buildRequestForm(array $para_temp) {
        $sHtml = "正在跳转至支付页面...<form id='alipaysubmit' name='alipaysubmit' action='".$this->config['gatewayUrl']."?charset=".$this->config['charset']."' method='POST'>";
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