# 2.0版本正在开发中，不要使用，不要使用，不要使用

### 项目来历


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
```php
$orderInfo=array(
    'order_name'=>"订单测试",   //订单名称或标题
    'amount'=>1,               //订单金额（最低0.01）
    'out_trade_no'=>"2021101247845",    //商户订单号（同一个商户本订单号需唯一）
		'authCode'=>'4444444444444444',   //条码信息
  	'store_id'=>'Stroe01'   //分店ID（本参数可有可无）
);
```


- 支付宝转账到个人账户(AlipayTransfer)传入的$orderInfo参数需包含:
```php
$orderInfo=array(
    'account'=>"zqu1016@qq.com",   //转入的账户（支持手机号或邮箱）
    'real_name'=>'张三',               //账户的真实姓名
    'amount'=>1,    //转账金额
	'remark'=>'佣金'   //转账备注
);
```



* 支付宝转账结果查询（AlipayTransferQuery）传入的$orderInfo参数需包含
```
$orderInfo=array(
	'outBizBo' =>'1212121212', //商户转账唯一订单号（商户转账唯一订单号、支付宝转账单据号 至少填一个）
	'orderId'  =>'123456789'   //支付宝转账单据号（商户转账唯一订单号、支付宝转账单据号 至少填一个）  
);
```



#### 微信


- 已提供接口有：

| 接口 | 函数 | 备注 |
| --- | --- | --- |
| WxPayCode | Native支付 | 获取支付二维码后用户扫码支付（一般用于PC端） |
| WxPayWap | 手机网页支付 | 在手机浏览器内调用本接口启动微信支付 |
| WxJsPay | 微信公众号支付 | 微信内公众号网页调用微信启动支付 |
| WxJsapiParams | 微信小程序/APP支付 | 获取预支付码，后可在小程序端调用支付接口启动支付 |
| WxRefund | 微信支付退款 | 微信支付退款，可部分或全部退款，原路退回 |
| WxTransfers | 微信支付到零钱 | 使用微信支付向用户转账，直接转账到用户零钱内 |

```
*其他接口陆续更新中
```



##### 参数


- Native支付（WxPayCode）、手机网页支付（WxPayWap）、微信小程序/APP支付(WxJsapiParams)需传入的$orderInfo参数需包含：

```
$orderInfo=array(
    'order_name'=>"订单测试",   //订单名称或标题
    'amount'=>1,               //订单金额（最低0.01)
    'out_trade_no'=>"2021101247845",    //商户订单号（同一个商户本订单号需唯一）,
    'body'=>''   //微信小程序/APP支付需要（本参数可选）
);
```




- 微信公众号支付（WxJsPay）需传入的$orderInfo参数需包含：

```
$orderInfo=array(
    'order_name'=>"订单测试",   //订单名称或标题
    'amount'=>1,               //订单金额（最低0.01)
    'out_trade_no'=>"2021101247845",    //商户订单号（同一个商户本订单号需唯一）,
    'code'=>''   //微信会话code（通过微信内网页js获取，需使用其获得用户openid）
);
```




- 微信退款接口（WxRefund）需传入的$orderInfo参数需包含：

```
$orderInfo=array(
    'trade_no'=>21010101010101,   //微信支付流水号（微信支付回调获得的微信支付内的交易号）
    'amount'=>1,               //订单金额（最低0.01)
    'out_trade_no'=>"2021101247845",    //商户订单号（需退款的订单号）,
    'refund_amount'=>0.5,    //退款金额
    'refund_trade_no'=>54145414512541,  //退款订单号（与订单号不同，退款的编号，可临时生成）
    'desc'=>'用户退款'   //退款说明
);
```




- 微信转账到零钱接口（WxTransfers）中的$orderInfo参数需包含：

```
$orderInfo=array(
    'amount'=>1,               //转账金额（最低0.01)
    'out_trade_no'=>"2021101247845",    //商户订单号,
    'real_name'=>"张三",    //收款人真实姓名
    'desc'=>'订单奖励'   //转账说明
);
```



### 示例代码


- 可参考包内test文件夹；
- 可以将配置程序写到一个文件内，具体可参考test文件夹内的示例程序
- 示例代码均基于composer安装后的开发，其他方式安装的可参考进行修改。



#### 支付宝示例代码：


- 支付：

```php
<?php
require "../vendor/autoload.php";
use iboxs\payment\Client;
use iboxs\payment\Notify;
//支付宝配置信息
$alipayconfig=[
    'publicKey' =>"", //支付宝公钥
    'rsaPrivateKey' =>"", //商户私钥
    'appid' => "2016192400584878",   //应用APPID
    'notify_url' => "http://auth.itgz8.com/return",  //异步通知地址
    'return_url' => "http://auth.itgz8.com/return",   //同步通知地址
    'charset' => "UTF-8",
    'sign_type'=>"RSA2",
    'gatewayUrl' =>"https://openapi.alipay.com/gateway.do"   //应用网关，若为沙箱环境则为："https://openapi.alipaydev.com/gateway.do"
];
//订单信息
$orderInfo=array(
    'order_name'=>"订单测试",
    'amount'=>1,
    'out_trade_no'=>"2021101247845"
);
$alipay=new Client($alipayconfig);  //实例化，若需使用支付宝则传入支付宝配置数组
var_dump($alipay->AlipayWeb($orderInfo));   //调用网页支付接口启动支付，若为其他接口，则根据上方需示例的各个接口函数调用不同的函数，并传入指定格式的$orderInfo参数即可.
?>
```


- 回调

```php
<?php
require "../vendor/autoload.php";
use iboxs\payment\Client;
use iboxs\payment\Notify;
//支付宝配置信息
$alipayconfig=[
    'publicKey' =>"", //支付宝公钥
    'rsaPrivateKey' =>"", //商户私钥
    'appid' => "2016192400584878",   //应用APPID
    'notify_url' => "http://auth.itgz8.com/notify",  //异步通知地址
    'return_url' => "http://auth.itgz8.com/return",   //同步通知地址
    'charset' => "UTF-8",
    'sign_type'=>"RSA2",
    'gatewayUrl' =>"https://openapi.alipay.com/gateway.do"   //应用网关，若为沙箱环境则为："https://openapi.alipaydev.com/gateway.do"
];
$result=Notify::alipayNotify($alipayconfig);  //调用支付宝异步验签 ////返回布尔型或数组，验签失败返回false，验签成功返回回调的数据
if($result==false){
    echo 'fail';
  	return;
}
//进行订单处理（$result内为回调的数据数组）【这里不需要再输出success，接口内已经输出了，这里只需要进行各种业务流程即可】
//订单处理逻辑，订单号$result['out_trade_no']，订单金额$result['total_amount']等，这里的$result含有所有支付宝的反馈信息
```



#### 微信支付示例代码：


- 支付：

```php
<?php
namespace iboxs\test;
require "../vendor/autoload.php";
use iboxs\payment\Client;

$wxpayconfig=[
    'mchid'=>'1504922561',   //商户号
    'appid'=>'',    //APPID（公众号支付必须）
    'apiKey'=>'',    //Key
    'notify_url'=>'http://auth.itgz8.com/notify',   //异步通知地址
    'return_url'=>'http://auth.itgz8.com/return'  //网站地址（手机网页支付接口必须，其他接口不需要）
];
$orderInfo=array(
    'order_name'=>"订单测试",
    'amount'=>1,
    'out_trade_no'=>"2021101247845"
);
$wxpay=new Client($wxpayconfig);
var_dump($wxpay->WxPayCode($orderInfo));
```




- 回调

```
<?php
require "../vendor/autoload.php";
use iboxs\payment\Notify;
$wxpayconfig=[
    'mchid'=>'1504922561',   //商户号
    'appid'=>'',    //APPID（公众号支付必须）
    'apiKey'=>'',    //Key
    'notify_url'=>'http://auth.itgz8.com/notify',   //异步通知地址
    'return_url'=>'http://auth.itgz8.com/return'  //网站地址（手机网页支付接口必须，其他接口不需要）
];
$result=Notify::WxPayNotify($wxpayconfig);  //调用微信回调验签 //返回布尔型或数组，验签失败返回false，验签成功返回回调的数据
if($result==false){
    echo 'fail';  //回调验签失败
    return;
}
//进行订单处理（$result内为回调的数据数组）
//var_dump($result)
//处理订单逻辑，付款金额$result['cash_fee']，获取订单号$result['out_trade_no']等等，这里的$result含有所有微信返回的通知信息数组
```



#### QQ支付示例代码：


- 支付：

```php
<?php
namespace iboxs\test;
require "../vendor/autoload.php";
use iboxs\payment\Client;

$qqpayconfig=[
    'mchid'=>'1504922561',   //商户号
    'apiKey'=>'',    //Key
    'notify_url'=>'http://auth.itgz8.com/notify'   //异步通知地址
];
$orderInfo=array(
    'order_name'=>"订单测试",  //订单名称
    'amount'=>1,  //订单金额
    'out_trade_no'=>"2021101247845"   //订单号
);
$qqpay=new Client($qqpayconfig);
var_dump($qqpay->QQPay($orderInfo));
```




- 回调

```
<?php
require "../vendor/autoload.php";
use iboxs\payment\Notify;
$qqpayconfig=[
    'mchid'=>'1504922561',   //商户号
    'apiKey'=>'',    //Key
    'notify_url'=>'http://auth.itgz8.com/notify'   //异步通知地址
];
$result=Notify::QqPayNotify($qqpayconfig);  //调用QQ回调验签 //返回布尔型或数组，验签失败返回false，验签成功返回回调的数据
if($result==false){
    echo 'fail';  //回调验签失败
    return;
}
//进行订单处理（$result内为回调的数据数组）
//var_dump($result)
//处理订单逻辑，付款金额$result['cash_fee']，获取订单号$result['out_trade_no']等等，这里的$result含有所有QQ钱包返回的通知信息数组
```


### 更新日志

- V1.2.0
  - 新增支付宝js支付、条码支付、转账接口
  - 修改回调验签成功后的返回值为回调的参数数组（支付宝的已转为数组字典，微信的也已转为数组字典），返回若为false的则为验签失败。

