<?php
namespace iboxs\payment\lib;
class payBase{
    use Common;

    protected $config=[];

    public function __construct($config)
    {
        $this->config=$config;
    }
}