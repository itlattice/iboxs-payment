<?php
namespace iboxs\payment\pay;

use iboxs\payment\lib\Common;
use iboxs\payment\pay\PaymentResult;

abstract class BasePay{
    use Common;

    protected $options=[];
    protected $config;
    public string $payType;
    public array $arguments;

    public function __construct(array $config){
        $this->config=$config;
    }

    public function addOptions(array $options=[]){
        $this->options=array_merge($this->options,$options);
        return $this;
    }

    public function run():PaymentResult|false
    {
        $name=ucfirst($this->payType);
        $class=static::class;
        $class=strtolower($class);
        $class.="\\". $name;
        $obj=new $class($this->config);
        $result=$obj->main(...$this->arguments);
        return $result;
    }

    public function is_mobile_request() { 
        $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : ''; 
        $mobile_browser = '0'; 
        if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) 
            $mobile_browser++; 
        if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false)) 
            $mobile_browser++; 
        if(isset($_SERVER['HTTP_X_WAP_PROFILE'])) 
           $mobile_browser++; 
        if(isset($_SERVER['HTTP_PROFILE'])) 
            $mobile_browser++; 
        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4)); 
        $mobile_agents = array( 
            'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac', 
            'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno', 
            'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-', 
            'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-', 
            'newt','noki','oper','palm','pana','pant','phil','play','port','prox', 
            'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar', 
            'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-', 
            'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp', 
            'wapr','webc','winw','winw','xda','xda-'
        ); 
        if(in_array($mobile_ua, $mobile_agents)) 
           $mobile_browser++; 
        if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false) 
            $mobile_browser++;
        if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false) 
            $mobile_browser=0;
        if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false) 
            $mobile_browser++; 
        if($mobile_browser>0)
            return true; 
        else
            return false; 
    }

}