<?php
namespace iboxs\payment\pay\PaymentResult;

class PaymentResult{

    /**
     * @var string 响应原文内容string
     */
    protected string $body;

    /**
     * @var array 请求参数(方便后续排查)
     */
    protected array $requestData;

    /**
     * @param $body
     * @param $requestData
     */
    public function __construct($body,$requestData)
    {
        $this->body=$body;
        $this->requestData=$requestData;
    }
}