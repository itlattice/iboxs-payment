<?php
namespace iboxs\payment\pay;

class PaymentResult{

    /**
     * @param string $body 响应内容原文
     * @param array $requestData 请求原始参数，便于排查
     */
    public function __construct(
       protected string $body,
       protected array $requestData
    )
    {
    }
    /**
     * 获取响应内容原文（字符串）
     */
    public function getBody():string{
        return $this->body;
    }

    /**
     * 获取响应内容（格式化结果）
     */
    public function getResponse():array{
        return json_decode($this->body,true);
    }

    /**
     * 获取原始请求参数
     */
    public function getRequestData():array{
        return $this->requestData;
    }
}