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
* 若有疑问或者bug，可提issue，或者联系QQ320587491  微信：itlattices


- 支付宝/微信支付/QQ钱包调用一直是PHP开发者经常遇到的情况，各种接口，各种参数调用，SDK代码又太多，很多用不上，所以本项目应运而生，提供多种接口，难度很小，从启动支付到回调验签，都有函数一次性解决。代码量小，冗余低；

- 本项目目前已支持支付宝/微信/QQ钱包的部分常用接口（扫码/网页/手机/公众号支付及回调验签），其他接口陆续更新中。

- 若发现bug可提交issue或联系邮箱：**zqu1016@qq.com**。

- 本项目支付各种PHP框架内使用，不限框架，原生PHP使用也是可以的。

- 本项目要求PHP最低版本为5.3

- 本项目2.0暂时仅支持支付宝和微信支付,若需其他支付方式,请使用1.*版本.
  

### 更新注意

* V1.1版本至V1.2版本时，考虑到很多验签后涉及业务操作，回调验签返回数据规则已修改为：
  * 验签成功是返回支付宝/微信的通知信息（已转换为数组字典）
  * 验签失败直接返回false




### 本项目安装方式：


- 可使用composer直接安装：



> composer require iboxs/payment

* 建议使用composer下载使用，本仓库已移入github，建议至github访问最新版，码云太扯淡不放新版本了（https://github.com/itlattice/iboxs-payment.git）

- 也可以直接下载源代码后将src文件夹内的代码拷贝出来使用。

```
composer require paypal/rest-api-sdk-php
```
* 注意
  * PayPal的扩展包仅在需要PayPal时使用，无需接入PayPal的无需安装，本插件的安装方式在下面；
  * 注意，PayPal的支付方式与国内的支付宝、微信不同，而是采取用户系统请求PayPal获取地址后，向PayPal网站跳转，然后用户在PayPal网站完成授权后同步跳转返回用户网站，此时并没有支付完毕，返回用户网站时会携带一个秘钥字符串，需再次使用该秘钥向PayPal发送请求，此时在PayPal返回success即为支付成功，否则均为支付失败。不存在类似支付宝、微信的异步同步回调。一定别忘了最后一步，否则用户是没有付款完成的。


### 使用方法：


- 使用相应接口前请确认已获得支付宝/微信支付相关接口授权；
- 支付宝网页支付建议同时申请手机端/电脑网页支付两个接口。



#### 支付宝


- 已提供接口有：

| 接口 | 函数 | 备注 |
| --- | --- | --- |
| AlipayWeb | 网页支付 | 不用区分手机电脑，会自动识别后调用不同接口 |
| AlipayCode | 扫码支付（二维码） | 获得二维码，用户扫描二维码支付，非条码 |
| AlipayRefund | 支付宝单笔退款 | 可部分退款或全部退款 |
| AlipayJsPay | 支付宝Js支付 | 可用于多个场景，包括APP、小程序、支付宝内网页 |
| AlipayBarCode | 支付宝条码支付 | 条码当面付，传入条码后账户扣款 |
| AlipayTransfer | 支付宝转账到个人账户 |  |
| AlipayTransferQuery | 支付宝转账结果查询 | |

```
*其他接口陆续更新中
```



##### 参数


- 网页支付（AlipayWeb）、扫码支付（AlipayCode）、Js支付（AlipayJsPay）中传入的$orderInfo为数组类型，含有参数为：

```
$orderInfo=array(
    'order_name'=>"订单测试",   //订单名称或标题
    'amount'=>1,               //订单金额（最低0.01）
    'out_trade_no'=>"2021101247845"    //商户订单号（同一个商户本订单号需唯一）
);
```




- 支付宝单笔退款（AlipayRefund）传入的$orderInfo参数需包含:

```
$orderInfo=array(
    'tradeNo'   =>'202114141414141410414',     // 要退款的支付宝交易号，支付完成后支付宝回调时传入的交易号
    'refund_amount'=>1,               //退款金额（全额退款则为订单金额，部分退款则为退款金额，不允许大于订单金额）
    'out_trade_no'=>"2021101247845"    //要退款的商户订单号
);
```




- 支付宝条码支付(AlipayBarCode)传入的$orderInfo参数需包含:
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