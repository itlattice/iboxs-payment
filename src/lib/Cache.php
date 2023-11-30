<?php

namespace iboxs\wechat\lib;

class Cache
{
    public static function get($key){
        if(class_exists('\\iboxs\\redis\\Redis')){
            return \iboxs\redis\Redis::basic()->get("iboxspayment:{$key}",false);
        }
        if(class_exists('\\Illuminate\\Support\\Facades\\Cache')){
            return \Illuminate\Support\Facades\Cache::get("iboxspayment:{$key}",false);
        }
        if(class_exists('\\think\\facade\\Cache')){
            return \think\facade\Cache::get("iboxspayment:{$key}",false);
        }
        $file=__DIR__."/../cache/payment.cache";
        if(!file_exists($file)){
            return false;
        }
        $info=file_get_contents($file);
        $result=json_decode($info,true);
        if($result['expire']<time()){
            return $result['result'];
        }
        return false;
    }

    public static function set($key,$val,$time){
        if(class_exists('\\iboxs\\redis\\Redis')){
            return \iboxs\redis\Redis::basic()->set("iboxspayment:{$key}",$val,$time);
        }
        if(class_exists('\\Illuminate\\Support\\Facades\\Cache')){
            return \Illuminate\Support\Facades\Cache::set("iboxspayment:{$key}",$val,$time);
        }
        if(class_exists('\\think\\facade\\Cache')){
            return \think\facade\Cache::set("iboxspayment:{$key}",$val,$time);
        }
        $file=__DIR__."/../cache/payment.cache";
        $result=[
            'expire'=>time()+$time,
            'result'=>$val
        ];
        file_put_contents($file,json_encode($result,256));
    }
}