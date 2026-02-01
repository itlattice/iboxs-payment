<?php
namespace iboxs\payment\pay\alipay;

use iboxs\payment\pay\PaymentResult;

class BillDownload extends BaseAlipayPay{
    public function main(string $bill_type,string $bill_date):PaymentResult|false{
        $requestData = array(
            'bill_type'=>$bill_type,
            'bill_date'=>$bill_date
        );
        $publicData=$this->getRequestPublicData('alipay.data.dataservice.bill.downloadurl.query',$requestData);
        $result = $this->curlPost($this->config['gatewayUrl'],$publicData);
        return new PaymentResult(json_encode($result['alipay_data_dataservice_bill_downloadurl_query_response'],JSON_UNESCAPED_UNICODE),$publicData);
    }
}