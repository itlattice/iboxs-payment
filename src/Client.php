<?php

namespace iboxs\payment;

use Exception;
use iboxs\payment\lib\Base;

class Client extends Base
{
    public static function install(){
        if(function_exists('root_path')){
            $path=root_path('config')."/payment.php";
            $text=__DIR__."/../test/config_example.php";
            if(file_exists($text)){
                $text=file_get_contents($text);
            }
            file_put_contents($path,$text);
        }
    }
}