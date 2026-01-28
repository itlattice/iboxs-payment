<?php
namespace iboxs\payment\pay;

use iboxs\payment\lib\Common;

abstract class BasePay{
    use Common;

    protected $options=[];
    protected $config;

    public function __construct(array $config){
        $this->config=$config;
    }

    public function addOptions(array $options=[]){
        $this->options=array_merge($this->options,$options);
        return $this;
    }

    public function __call($name, $arguments)
    {
        $name=ucfirst($name);
        $class=static::class;
        $classNameArr=explode("\\",$class);
        $className=end($classNameArr);
        $className.=$name;
        $class=strtolower($class);
        $class.="\\". $className;
        $obj=new $class($this->config);
        return $obj->setArguments($arguments);
    }
}