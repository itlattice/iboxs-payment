<?
return [
    'alipay'=>[
        'publicKey' =>"", //支付宝公钥
        'rsaPrivateKey' =>"", //应用私钥
        'appid' => "",  // 开放平台APPID
        'notify_url' => "",  //异步通知地址
        'return_url' => "",  //同步回调地址
        'charset' => "UTF-8",  //编码方式
        'sign_type'=>"RSA2",  //加密方式（本组件使用RSA2进行加密和回调验签）
        'gatewayUrl' =>"https://openapi.alipay.com/gateway.do",  //支付宝接口地址（若为沙箱环境的记得改为https://openapi.alipaydev.com/gateway.do）
        'has_mobile'=>false //是否已开通手机H5网页支付，若已开通，若用户为手机访问且调用网页支付接口时，会默认跳转手机端支付接口
    ],
    'weixin'=>[
        'host'=>'https://api.mch.weixin.qq.com',
        'mchid'=>'',  //商户号
        'appid'=>'',  //APPID（公众号支付、JS支付必须）
        'apiKey'=>'',  //APIV3秘钥
        'key'=>'', //商户APIV2秘钥（付款码支付用）
        'notify_url'=>'',  //异步回调地址
        'return_url'=>'',  //同步回调地址（H5支付必须）
        'merchantPrivateKeyFilePath'=>'', //商户API私钥证书文件地址
        'merchantCertificateSerial'=>'', //「商户API证书」的「证书序列号」
    ],
    'qqpay'=>[
        'mchid'=>'',  //商户号
        'appid'=>'',  //APPID（公众号支付、JS支付必须）
        'apiKey'=>'',  //商户Key
        'notify_url'=>'',  //异步回调地址
        'return_url'=>'',  //同步回调地址
        'password'=>'', // 操作员密码
        'user_id'=>'' // 操作员账号

    ]
];
?>