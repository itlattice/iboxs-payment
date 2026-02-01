<?php
namespace iboxs\payment\pay;

class NotifyData{
    /**
     * @param string $platform 平台名称
     * @param array $params 原始参数
     */
    public function __construct(
       protected string $platform,
       protected array $params
    )
    {
    }

    /**
     * 获取回调平台
     * @return string 平台标识（wechat:微信支付,alipay：支付宝）
     */
    public function getPlatform():string{
        return $this->platform;
    }

    /**
     * 获取回调参数原始数据（微信这里获取到的是解密过后的resource内的数据）
     * @return array 原始参数
     */
    public function getParams():array{
        return $this->params;
    }

    /**
     * 获取交易状态是否是支付成功
     */
    public function getStateIsSuccess():bool{
        switch($this->platform){
            case 'wechat':
                return ($this->params['trade_state']??'')==='SUCCESS';
            case 'alipay':
                return ($this->params['trade_status']??'')==='TRADE_SUCCESS'||($this->params['trade_status']??'')==='TRADE_FINISHED';
        }
        return false;
    }

    /**
     * 获取实际支付金额
     */
    public function getPayAmount():float{
        switch($this->platform){
            case 'wechat':
                return (($this->params['amount']['payer_total']??0)/100);
            case 'alipay':
                return floatval($this->params['buyer_pay_amount']??0);
        }
        return 0.0;
    }

    public function getAmount():float{
        switch($this->platform){
            case 'wechat':
                return (($this->params['amount']['total']??0)/100);
            case 'alipay':
                return floatval($this->params['total_amount']??0);
        }
        return 0.0;
    }

    /**
     * 获取交易号
     */
    public function getTradeNo():string|false{
        switch($this->platform){
            case 'wechat':
                return $this->params['transaction_id']??'';
            case 'alipay':
                return $this->params['trade_no']??'';
        }
        return false;
    }

    /**
     * 获取商户订单号
     * @return string|false
     */
    public function getOutTradeNo():string|false{
        switch($this->platform){
            case 'wechat':
                return $this->params['out_trade_no']??'';
            case 'alipay':
                return $this->params['out_trade_no']??'';
        }
        return false;
    }

    /**
     * 获取统一格式化后的数据（若需留存原始，可选择这个，后期扩展也方便）
     * @return array 统一格式化后的数据
     */
    public function getFormatData():array{
        return [
            'platform'=>$this->platform,
            'is_success'=>$this->getStateIsSuccess(),
            'amount'=>$this->getAmount(),
            'pay_amount'=>$this->getPayAmount(),
            'trade_no'=>$this->getTradeNo(),
            'out_trade_no'=>$this->getOutTradeNo(),
            'params'=>$this->params  //原始数据
        ];
    }
}