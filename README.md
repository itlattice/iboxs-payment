### 项目来历
- 支付宝/微信支付调用一直是PHP开发者经常遇到的情况，各种接口，各种参数调用，SDK代码又太多，很多用不上，所以本项目应运而生，提供多种接口，难度很小，从启动支付到回调验签，都有函数一次性解决。代码量小，冗余低；
* 本分支项目目前暂时仅直接支持Laravel、Thinkphp、IBoxs框架，其他框架使用需要手动进行配置，具体看下方具体说明。
* 本分支功能将全面支持微信支付V3接口和支付宝刷脸支付等。
* 很多接口尚未完善，若有需要的接口这里还没有的，可提issue。
* 本版本暂时仅支持支付宝和微信支付，若需要QQ钱包支付和PayPal支付的，请使用旧版本，可根据情况需要在后续再次新增QQ钱包支付和PayPal支付。
* 若需要接入其他支付接口的（例如云闪付等）可提issue，作者根据实际情况需要可考虑在后续版本中添加。
* 具体的调用方法查看test文件夹下各个示例文件。
* 完整代码请以github上发布的为准，国内码云上的只是同步项目，不一定是最新的。
* 本项目要求PHP版本最低8.0，内部代码大部分开始使用强类型，必须在PHP8.0以上才可以运行，请注意版本问题。
* 本项目微信支付全面使用微信支付V3接口（付款码支付接口除外，V3没有这个接口）,请注意相关的接口处理。
* 若你使用的微信回调验签方式是平台证书，请参阅https://pay.weixin.qq.com/doc/v3/merchant/4013053420 转为微信公钥文件，并在配置的`publicKeySerial`参数内填写平台证书序列号，如果你已经使用的微信公钥，那么可直接使用，并在`publicKeySerial`参数内填写公钥ID。
* 更多的配置说明，请参考下方配置文件示例后的具体说明
* 若有疑问或者bug，可提issue，或者联系QQ320587491  微信：itlattices

### 安装方法
* 推荐使用composer
```
composer require iboxs/payment
```

#### 更新注意
* 如果你之前已在使用本组件，请注意，本组件3.0版本为破坏性更新，代码和结构被完全重构，若你之前的项目已跑的很好，不建议更新至3.0以上版本。
* 如果你使用的PHP版本是8.0以下版本（不含8.0），可使用旧版本的本组件，调用方法不一致，请查看具体版本的文档。

### 配置
* Laravel/ThinkPHP框架
    * 需要在config文件夹下创建一个文件payment.php文件，内容为：
```php
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
        'publicKeySerial'=>'' //微信支付平台公钥证书的公钥ID/序列号(使用平台证书的，这里填写证书序列号，使用微信平台公钥的，这里填公钥ID)
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

#### 回调验签
* 支付宝支付
```
$notifyResult=Notify::Alipay();  //返回false为验签失败，成功将返回一个回调数据对象，便于后续处理
```
* 微信支付
```
$notifyResult=Notify::Wechat();  //返回false为验签失败，成功将返回一个回调数据对象(已解密)，便于后续处理
```

* 具体的回调对象处理方法可查看test文件夹下相应的示例代码。