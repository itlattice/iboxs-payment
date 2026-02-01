### 项目来历
- 支付宝/微信支付调用一直是PHP开发者经常遇到的情况，各种接口，各种参数调用，SDK代码又太多，很多用不上，所以本项目应运而生，提供多种接口，难度很小，从启动支付到回调验签，都有函数一次性解决。代码量小，冗余低；
* 本分支项目目前暂时仅直接支持Laravel、Thinkphp、IBoxs框架，其他框架使用需要手动进行配置，具体看下方具体说明。
* 本分支功能将全面支持微信支付V3接口和支付宝刷脸支付等。
* 很多接口尚未完善，若有需要的接口这里还没有的，可提issue。
* 本版本暂时仅支持支付宝和微信支付，若需要QQ钱包支付和PayPal支付的，请使用旧版本，可根据情况需要在后续再次新增QQ钱包支付和PayPal支付。
* 若需要接入其他支付接口的（例如云闪付等）可提issue，作者根据实际情况需要可考虑在后续版本中添加。
* 具体的调用方法查看test文件夹下各个示例文件。

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

* 若发现报错，可调用在`$r=$payment->run();`之后的获得的支付结果对象的`getRequestData`方法获取原始请求参数(若直接返回false，则为请求失败或者参数配置问题，请检查你的网络和配置是否完整)，以排查原因，例如：
```
$payment=Payment::wechatCloseTrade($out_trade_no);
$r=$payment->run();
if($r==false){
    var_dump('请求错误');
    exit;
}
if($r['code']!=0){
    $requestData=$r->getRequestData();  //这里可获得请求接口的完整的原始参数
}
```