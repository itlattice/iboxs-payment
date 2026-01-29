<?php
namespace iboxs\payment\pay;

use iboxs\payment\lib\Common;

class Wechat extends BasePay{
    use Common;

    protected $config=[];
    public function __construct($config)
    {
        $this->config=$config;
    }
}