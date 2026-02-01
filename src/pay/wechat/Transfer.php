<?php
namespace iboxs\payment\pay\wechat;

use iboxs\payment\pay\PaymentResult;

class Transfer extends BaseWechatPay{
    /**
     * 运行
     */
    public function main(string $out_bill_no,string $transfer_scene_id,string $openid,string|null $user_name,float $transfer_amount,string $transfer_remark,array $transfer_scene_report_infos):PaymentResult|false{
        $requestData = array(
            'appid'=>$this->config['appid']['default']??'',
            'out_bill_no'=>$out_bill_no,
            'transfer_scene_id'=>$transfer_scene_id,
            'openid'=>$openid,
            'transfer_amount'=>intval($transfer_amount*100),  //单位 分
            'transfer_remark'=>$transfer_remark,
            'transfer_scene_report_infos'=>$transfer_scene_report_infos,
        );
        if($user_name!=null){
            $requestData['user_name']=$this->publicKeySerialize($user_name);
        }
        $requestResult=$this->wechatV3PostSerial('/v3/fund-app/mch-transfer/transfer-bills',$requestData);
        return new PaymentResult($requestResult,$requestData);
    }
}