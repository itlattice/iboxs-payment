<?php
if(!function_exists('dd')){
    /**
     * 断点输出
     * @param $data
     * @return void
     */
    function dd(...$data){
        echo '<pre>';
        foreach ($data as $d){
            print_r($d);
        }
        echo '</pre>';
        exit;
    }
}

if(!function_exists('wechatExpireTime')){
    function wechatExpireTime($time){
        if($time==null){
            $time=86400; //24小时
        }
        if(is_numeric($time)){ //过期秒数
            $date=date('Y-m-d',time()+$time);
            $time=date('H:i:s',time()+$time);
            return $date.'T'.$time.'+08:00';
        } else{
            $time=strtotime($time);
            if($time==false){
                throw new Exception('过期时间格式错误');
            }
            return wechatExpireTime($time-time());
        }
    }
}

if(!function_exists('Nullify')){
    function Nullify(array $arr){
        $result=[];
        foreach ($arr as $key=>$item) {
            if($item==null){
                continue;
            }
            $result[$key]=$item;
        }
        return $result;
    }
}
?>