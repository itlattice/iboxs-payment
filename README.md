### 项目来历
本分支相关功能尚未进行完整测试，请不要用于生产环境。
本分支项目目前暂时仅支持Laravel、Thinkphp、IBoxs框架，其他框架使用需要手动进行配置，具体看下方具体说明。
本分支功能将全面支持微信支付V3接口和支付宝刷脸支付等。
目前尚在开发中，请勿使用，你也可以在我的基础上进行继续开发以实现你需要的功能。

### 配置
* Laravel/ThinkPHP框架
    * 需要在config文件夹下创建一个文件payment.php文件，内容为：
```php
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
    ]
];
?>
```

请注意填写好内容，后续请求这里将作为全局配置使用。
`若需要动态配置，可调用iboxs\payment\Payment::setConfig($config)配置进行载入，$config与上方配置文件内容一致。`

* 具体的调用方法和示例程序，请查阅test文件夹下的具体demo。
* 注意：若在laravel/thinkphp框架下使用，则无需调用setConfig方法，可直接调用具体的接口，若在其他框架下使用的，也不想调用setConfig方法时，可将下列函数放入公共函数文件内即可：
```php
// 这里只做示例，请根据实际情况调整
function config(string $key){
    return require("./config/config.php");  //修改为你的配置文件路径
}
```
