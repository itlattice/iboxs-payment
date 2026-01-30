<?php
return [
    'alipay'=>[
        'publicKey' =>"", //支付宝公钥
        'rsaPrivateKey' =>"", //应用私钥
        'appid' => "",  // 开放平台APPID
        'notify_url' => "",  //异步通知地址
        'return_url' => "",  //同步回调地址
        'charset' => "UTF-8",  //编码方式
        'sign_type'=>"RSA2",  //加密方式（本组件使用RSA2进行加密和回调验签）
        'gatewayUrl' =>"https://openapi.alipay.com/gateway.do",  //支付宝接口地址（若为沙箱环境的记得改为https://openapi.alipay.com/gateway.do）
        'has_mobile'=>true //是否已开通手机H5网页支付，若已开通，若用户为手机访问且调用网页支付接口时，会默认跳转手机端支付接口
    ],
    'wechat'=>[
        'host'=>'https://api.mch.weixin.qq.com',  //接入点（若出现异常可访问容灾接入点：https://api2.mch.weixin.qq.com）
        'mchid'=>'',  //商户号
        'appid'=>'',  //APPID(商户在微信开放平台(移动应用)或企业号corpid(即为appid)或公众平台(服务号/政府或媒体类型的公众号/小程序)上的账号开发识别码，该appid必须与商户号mchid进行绑定。)
        'apiKeyV3'=>'',  //商户APIV3秘钥(除了付款码接口外，其他接口均使用V3秘钥进行签名，请务必设置此参数)
        'apiKeyV2'=>'', //商户APIV2秘钥（付款码支付用，付款码支付仍然在使用V2接口，若无调用，则可以不设置）
        'notify_url'=>"",  //异步回调地址
        'return_url'=>"",  //同步回调地址（H5支付必须）
        'merchantPrivateKeyFilePath'=>'', //商户API私钥证书文件地址（绝对地址）
        'merchantCertificateSerial'=>'', //「商户API证书」的「证书序列号」
        'currency'=>'CNY',  //符合ISO 4217标准的三位字母代码，默认人民币：CNY
    ]
];
?>