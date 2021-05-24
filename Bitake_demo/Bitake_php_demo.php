<?php

$pay = new Bitake;
$pay->rmbPrice('CNY',695); //Merchants deposit digital currency quotation (RMB)
//$pay -> pay('CNY',100);  //Get deposit link
//$pay -> orderQuery('PAY1600328923');  //Query deposit order status
//$pay -> withdrawal();   //Apply for a fiat currency withdrawal
//$pay -> withdrawalHistory(); //fiat currency withdrawal history
//$pay -> withdrawalDelete('WF1609222990622');  //Delete unreviewed fiat currency withdrawal application records




class Bitake{
    private $app_id = "41043343"; //APPID)<Distributed by Bitake, obtained in app -> APIconfiguration (pc merchant area-> API CONFIG)>
    private $mch_no = "9357"; // (UID)<Distributed by Bitake, obtained in app -> APIconfiguration (pc merchant area-> API CONFIG)>
    private $app_key ='73A8946D4ED1AD34ADA3F503601D3833';//Key
    private $into_url = "http://merchant-api.bitake.io/api/recharge/check/v2";
	private $rmb_price_url = "http://merchant-api.bitake.io/api/recharge/convert/v1";
    private $order_query_url = "http://merchant-api.bitake.io/api/recharge/order/query";
	private $withdrawal_url = "http://merchant-api.bitake.io/api/recharge/customer/withdrawal";
    private $withdrawal_history_url = "http://merchant-api.bitake.io/api/recharge/customer/withdrawal/history";
    private $withdrawal_delete_url = "http://merchant-api.bitake.io/api/recharge/customer/withdrawal/delete";

//curl -X GET -H "content-type:application/json" -H "access_key:8A42FADDD2397F4841B1F85C2C13094CAB152151" -H "app_id:41043343" http://merchant-api.bitake.io/api/recharge/convert/v1?timestamp=1609385362&p1=9357&p2=CNY&p3=695

    /**
     * Merchants deposit digital currency quotation (RMB)
     * @param $closeCurrency
     * @param $amount
     */
    public function rmbPrice($closeCurrency,$amount){
        //curl -X GET -H "content-type:application/json" -H "access_key:8A42FADDD2397F4841B1F85C2C13094CAB152151" -H "app_id:41043343" http://merchant-api.bitake.io/api/recharge/convert/v1?timestamp=1609385362&p1=9357&p2=CNY&p3=695
		$timestamp = time();
       $param = array(
           'p1' => $this->mch_no,
		   'p2' => $closeCurrency,
		   'p3' => $amount,
           'timestamp' => $timestamp
       );
	   $str = $this->mch_no.'&'.$timestamp;
        $url = $this->rmb_price_url;
        $url .= '?p1='.$this->mch_no.'&p2='.$closeCurrency.'&p3='.$amount.'&timestamp='.$param['timestamp'];
        $rlt = $this->httpGET($url,$str);
        var_dump($rlt);
        //{"code":200,"data":{"price":6.46},"message":"success"}

    }

    /**
     * Get deposit link
     * @param $closeCurrency
     * @param $amount
     */
    public function pay($closeCurrency,$amount){
		$orderNo = time().rand(1,100);
		$timestamp = time();
       $param = array(
           'p1' => $amount,
           'p2' => $this->mch_no,
           'p3' => $orderNo,
		   'p4' => $closeCurrency,
           'timestamp' => $timestamp
       );
	   $str = $amount.'&'.$this->mch_no.'&'.$orderNo.'&'.$timestamp;
        $url = $this->into_url;
        $url .= '?p1='.$param['p1'].'&p2='.$param['p2'].'&p3='.$param['p3'].'&timestamp='.$param['timestamp'];
        $rlt = $this->httpGET($url,$str);
        var_dump($rlt);
        //{"code":200,"data":{"url":"https://pay-api-ssl.bitake.io/mobile/buy?orderNo=USDT1609233285404590147&amount=100.0&usdtAmount=100.0&exchangeRate=1.0&merchantName=Bitake+Demo&closeCurrency=CNY"},"message":"success"}

    }

    /**
     * Query deposit order status
     * @param $merchantOrderNo
     */
    public function orderQuery($merchantOrderNo){
        $timestamp = time();

        $str = $this->mch_no.'&'.$timestamp;
        $url = $this->order_query_url;
        $url .= '?merchantNo='.$this->mch_no.'&merchantOrderNo='.$merchantOrderNo.'&timestamp='.$timestamp;
        $rlt = $this->httpGET($url,$str);
        var_dump($rlt);
        //{"code":200,"data":{"amount":"1000.0","orderNo":"USDT1600328924378686589","poundage":"34.0","sign":"B00664E84B184770B49630F1D1FD6C0F0E0E805E","currency":"USDT(ERC20)","state":"1","merchantOrderNo":"PAY1600328923","merchantNo":"5066","timestamp":"1609233192"},"message":"success"}

    }

    /** Apply for a fiat currency withdrawal
     * @param $closeCurrency
     * @param $amount
     */
    public function withdrawal(){
        $timestamp = time();
        $jsonStr = json_encode(array(
            'merchantNo' => $this->mch_no,//UID
            'fiatCurrency' => "CNY",   //fiat Currency
            'amount' => 1000.0,  //fiat Currency amount
            'customerId' => "110", //customerId
            'accountName' => "测试", //bank account name
            'bankNumber' => "535224124242",//bank card number
            'account' => "345345245",           //bank account
            'bankName' => "中国银行",         //bank name
            'subbranch' => "北京西二旗分行",             //subbranch
            'iBank' => "344244",               //IBANK
            'bankAddress' => "",             // bank address
            'swiftCode' => "",             //Swift Code
            'bankCode' => "",               // bank code
            'remarks' => "",                //remarks
            'type' => "1"                  //Type: 1 Normal 2 Urgent
        ));

        $str = $timestamp;
        $url = $this->withdrawal_url;
        $url .= '?timestamp='.$timestamp;
        $rlt = $this->http_post_json($url,$jsonStr,$str);
        var_dump($rlt);
        //{"code":200,"message":"success"}

    }

    /**
     * fiat currency withdrawal history
     */
    public function withdrawalHistory(){
        $timestamp = time();
        $str = $this->mch_no.'&'.$timestamp;
        $url = $this->withdrawal_history_url;
        $url .= '?merchantNo='.$this->mch_no
            .'&timestamp='.$timestamp
            .'&currPage=1'
            .'&pageSize=20'
            .'&customerId=110'
            .'&fiatCurrency=CNY';
        $rlt = $this->httpGET($url,$str);
        var_dump($rlt);
        //{"total":2,"code":200,"data":[{"account":"345345245","accountName":"测试","amount":1000.0,"bankAddress":"","bankCode":"","bankName":"中国银行","bankNumber":"535224124242","comeFrom":2,"createTime":"2020-12-29 17:10:42","currencyCount":null,"currencyTypeId":1,"customerId":"110","fee":null,"fiatCurrency":"CNY","financialReviewTime":null,"iBank":"344244","id":7,"investorPassword":null,"merchantNo":null,"orderNo":"WF1609233042438","refuseReason":null,"remarks":"","state":0,"subbranch":"北京西二旗分行","swiftCode":"","traderId":null,"traderReviewTime":null,"type":1,"userId":535,"withdrawalRate":null},{"account":null,"accountName":"接口测试","amount":1000.0,"bankAddress":null,"bankCode":null,"bankName":null,"bankNumber":null,"comeFrom":2,"createTime":"2020-12-28 14:57:15","currencyCount":1000.0,"currencyTypeId":1,"customerId":null,"fee":0.0,"fiatCurrency":"CNY","financialReviewTime":"2020-12-28 16:04:49","iBank":null,"id":5,"investorPassword":null,"merchantNo":null,"orderNo":"WF1609138635895","refuseReason":null,"remarks":null,"state":1,"subbranch":null,"swiftCode":null,"traderId":1,"traderReviewTime":null,"type":1,"userId":535,"withdrawalRate":1.0}],"pageSize":20,"message":"success","currentPage":1}

    }

    /**
     * Delete unreviewed fiat currency withdrawal orders
     * @param $withdrawalOrderNo
     */
    public function withdrawalDelete($withdrawalOrderNo){
        $timestamp = time();
        $str = $this->mch_no.'&'.$timestamp;
        $url = $this->withdrawal_delete_url;
        $url .= '?merchantNo='.$this->mch_no
            .'&timestamp='.$timestamp
            .'&orderNo='.$withdrawalOrderNo;  //merchant order no
        $rlt = $this->httpGET($url,$str);
        var_dump($rlt);
        //{"code":200,"message":"success"}

    }



    /**  PHP's HMAC_SHA1 algorithm implementation
     * @param $str
     * @param $key
     * @return string
     */
    function getSignature($str, $key) {
        $signature = "";
        if (function_exists('hash_hmac')) {
            $signature = bin2hex(hash_hmac("sha1", $str, $key, true));
        } else {
            $blocksize = 64;
            $hashfunc = 'sha1';
            if (strlen($key) > $blocksize) {
                $key = pack('H*', $hashfunc($key));
            }
            $key = str_pad($key, $blocksize, chr(0x00));
            $ipad = str_repeat(chr(0x36), $blocksize);
            $opad = str_repeat(chr(0x5c), $blocksize);
            $hmac = pack(
                'H*', $hashfunc(
                    ($key ^ $opad) . pack(
                        'H*', $hashfunc(
                            ($key ^ $ipad) . $str
                        )
                    )
                )
            );
            $signature = bin2hex($hmac);
        }
        return $signature;
    }
    /**
     * Get method
     */
    function httpGET($url,$str) {

        echo 'url:'.$url."</br>";    //Request url and parameters：http://merchant-api.bitake.io/api/recharge/check/v2?p1=100&p2=11082429&p3=1553155910&timestamp=1553155910

        echo 'str:'.$str."</br>";   //String before encryption：100&11082429&1553155910&1553155910
        if(empty($str)){
            return false;
        }
        $sign = strtoupper($this->getSignature($str,$this->app_key));  //After encryption (uppercase)：5F51F8065B325EC3491526612CB2A47B84E5E10B
        echo 'sign:'.$sign."</br>";
        $headers = array(
            'content-type:application/json',
            'access_key:'.$sign,
            'app_id:'.$this->app_id
        );
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');  
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers); 
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        return $data;
    }

    function http_post_json($url, $jsonStr,$str){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $sign = strtoupper($this->getSignature($str,$this->app_key));  //After encryption (uppercase)：5F51F8065B325EC3491526612CB2A47B84E5E10B
        echo 'sign:'.$sign."</br>";
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($jsonStr),
                'access_key:'.$sign,
                'app_id:'.$this->app_id
            )
        );
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }


}