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
        'appid'=>[  //需要先与商户号进行绑定（）
            'default'=>'', //默认使用的APPID(例如Native支付、H5支付、商家转账时会用到，以下三种APPID任选一个填在这里即可，注意部分接口需要openid的，需要填写一个与openid对应的appid，请根据情况选择)
            'app'=> '', //【APP支付用，无就不设置即可】应用APPID(商户在微信开放平台(移动应用)
            'mp'=>'', //【公众号支付用，调用JS支付接口】公众号APPID
            'mini'=>'', //【小程序支付用，调用小程序支付接口】小程序APPID
        ],
        'apiKeyV3'=>'',  //商户APIV3秘钥(除了付款码接口外，其他接口均使用V3秘钥进行签名，请务必设置此参数)
        'apiKeyV2'=>'', //商户APIV2秘钥（付款码支付用，付款码支付仍然在使用V2接口，若无调用，则可以不设置）
        'notify_url'=>"",  //异步回调地址
        'return_url'=>"",  //同步回调地址（H5支付必须）
        'merchantPrivateKeyFilePath'=>'', //商户API私钥证书文件地址（绝对地址）
        'merchantCertificateSerial'=>'', //「商户API证书」的「证书序列号」
        'currency'=>'CNY',  //符合ISO 4217标准的三位字母代码，默认人民币：CNY
        'publicKeyPath'=>'', //微信支付平台公钥证书文件地址（绝对地址）[验签要用](这里注意，如果你使用的是平台证书，请参考https://pay.weixin.qq.com/doc/v3/merchant/4013053420将其导出为平台公钥后放在这里，并在下方参数使用平台证书序列号，如果你使用的已经是平台公钥，那么下面这个参数则使用平台公钥ID)
        'publicKeySerial'=>'' //微信支付平台公钥证书的公钥ID/序列号
    ]
];
?>