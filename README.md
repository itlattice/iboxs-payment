### 项目来历
本分支是用于支持微信支付V3开发所用，相关功能尚未进行完整测试，请不要用于生产环境。
本分支项目目前暂时仅支持Laravel、Thinkphp、IBoxs框架，其他框架使用需要手动进行配置，具体看下方具体说明。

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

### 支付宝
##### 网页支付（包括手机网页支付和电脑网页支付）
* 文档地址
https://open.alipay.com/api/detail?code=I1080300001000041203&index=1

* 获取基本支付对象
```
/**
 * @param string $out_trade_no 商户订单号。
 * @param float $total_amount 订单金额（单位:元）
 * @param string $subject 订单标题
 * @param string $product_code 订单类型，默认为FAST_INSTANT_TRADE_PAY
 * @return PaymentResult|false 调用结果对象
 */
use iboxs\payment\Payment;

$payment=Payment::alipayWebPay(string $out_trade_no,float $total_amount,string $subject,string $product_code='FAST_INSTANT_TRADE_PAY')
                                ->addOptions([  //设置其他参数（如果需要在该接口下传入其他参数，可在这里设置，若无，可不调用本函数），参数请参考具体的接口文档
                                    'time_expire'=>'2026-12-31 10:05:01'
                                ])
                                ->run();  //启动支付
if($payment==false){
    $failReason=$payment->getError();  //获取失败原因
    throw new \Exception($failReason);
}
$body=$payment->getBody();  //获取响应原文
echo $body;  //输出到网页上，网页支付浏览器会自动跳转的
exit();
```