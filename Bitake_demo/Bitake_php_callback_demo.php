<?php

$data = [

    'amount'=>$_POST["amount"],
    'currency'=>$_POST["currency"],
    'exchangeRate'=>$_POST["exchangeRate"],
    'poundage'=>$_POST['poundage'],
    'merchantNo'=>$_POST['merchantNo'],
    'merchantOrderNo'=>$_POST['merchantOrderNo'],
    'orderNo'=>$_POST['orderNo'],
    'timestamp'=>$_POST['timestamp'],
    'sign'=>$_POST['sign']

];


$str = var_export($data,TRUE);

//追加写入
 file_put_contents(__DIR__."/log.txt", date('Y-m-d H:i:s'). $str.PHP_EOL, FILE_APPEND);



$order_num=$data['orderNo'];

   

 

//HTTP请求（支持HTTP/HTTPS，支持GET/POST）
function _http_request($url, $data = null)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}


echo json_encode(array('code' => 200));

?>
