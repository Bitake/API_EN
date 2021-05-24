package com.test.pay;


import com.alibaba.fastjson.JSONObject;

import java.util.HashMap;
import java.util.Map;

/**
 * Fiat currency withdrawal
 */
public class WithdrawalFiat {


    public static void main(String[] args) {
//        withdrawal();
//        withdrawalHistory();
//        withdrawalDelete();
//        withdrawalRate("CNY");
        withdrawalOrderQuery("TEST1618221736");
    }

    /**
     * Apply for withdrawal
     * @return
     */
    public static String withdrawal(){
        //curl -X POST -H "content-type:application/json" -H "access_key:3A1CDB61128A3E693F0F3D7884F226E82E253650" -H "app_id:41043343" -d '{"bankNumber":"535224124242","bankCode":"","amount":1000.0,"iBank":"344244","accountName":"测试","swiftCode":"","bankName":"中国银行","type":"1","bankAddress":"","fiatCurrency":"CNY","customerId":"110","subbranch":"北京西二旗分行","account":"345345245","remarks":"","merchantNo":"9357"}' http://merchant-api.bitake.io/api/recharge/customer/withdrawal?timestamp=1609747081
        long timestamp = System.currentTimeMillis()/1000;
        String pay_url = Constant.URL_ + "api/recharge/customer/withdrawal?timestamp="+timestamp;
        Map<String, String> heads = new HashMap<>();
        heads.put("content-type", "application/json");
        String params = timestamp+"";
        String access_key = HMACSHA1.hamcsha1(params.getBytes(), Constant.key.getBytes());
        heads.put("access_key", access_key);
        heads.put("app_id", Constant.app_id);
        Map<String, Object> param = new HashMap<>();
        param.put("merchantNo",Constant.merchant_no);//UID
        param.put("merchantOrderNo","TEST"+timestamp);//Merchant order number
        param.put("withdrawType",2);//Withdrawal type 1 Fixed fiat currency  2 Fixed USDT
        param.put("fiatCurrency","CNY");   //fiat Currency
//        param.put("amount",1000.0);  //fiat Currency amount
        param.put("currencyCount",500.0);  //USDT count
        param.put("customerId","110"); //customerId
        param.put("accountName","测试"); //bank account name
        param.put("bankNumber","535224124242");//bank card number
        param.put("account","345345245");           //bank account
        param.put("bankName","中国银行");          //bank name
        param.put("subbranch","北京西二旗分行");             //subbranch
        param.put("iBank","344244");               //IBANK
        param.put("bankAddress","");             // bank address
        param.put("swiftCode","");              //Swift Code
        param.put("bankCode","");               // bank code
        param.put("remarks","");                //remarks
        param.put("type","1");                  //Type: 1 Normal 2 Urgent
        String return_url = HttpUtils.postJson(pay_url,param, heads);
        System.out.println(return_url);
        //{"code":200,"data":{"account":"345345245","accountName":"测试","amount":null,"bankAddress":"","bankCode":"","bankName":"中国银行","bankNumber":"535224124242","comeFrom":2,"createTime":null,"currencyCount":500.0,"currencyTypeId":null,"customerId":"110","fee":null,"fiatCurrency":"CNY","financialReviewTime":null,"iBank":"344244","id":null,"investorPassword":null,"merchantNo":"9357","merchantOrderNo":"TEST1618221736","orderNo":null,"refuseReason":null,"remarks":"","state":null,"subbranch":"北京西二旗分行","swiftCode":"","traderId":null,"traderReviewTime":null,"type":1,"userId":null,"withdrawType":2,"withdrawalRate":null},"message":"success"}
        JSONObject jsonObject = JSONObject.parseObject(return_url);
        if (jsonObject.getInteger("code") == 200){
            System.out.println("success");
            return "success";
        }else {
            System.out.println(jsonObject.getString("message"));
            return "failure";
        }
    }

    /**
     * Withdrawal history
     * @return
     */
    public static String withdrawalHistory(){
        long timestamp = System.currentTimeMillis()/1000;
        String pay_url = Constant.URL_ + "api/recharge/customer/withdrawal/history?timestamp="+timestamp;
        Map<String, String> heads = new HashMap<>();
        heads.put("content-type", "application/json");
        String params = Constant.merchant_no + "&" + timestamp;
        String access_key = HMACSHA1.hamcsha1(params.getBytes(), Constant.key.getBytes());
        heads.put("access_key", access_key);
        heads.put("app_id", Constant.app_id);
        Map<String, Object> param = new HashMap<>();
        //String merchantNo,String customerId,Date startTime,Date endTime,String fiatCurrency,Integer state,Integer type,String accountName
        param.put("merchantNo",Constant.merchant_no);//UID(required)
        //Pagination
        param.put("currPage",1);//Current page (required)
        param.put("pageSize",20);//page Size(required)
//        //Search conditions, all records if not passed
        param.put("merchantOrderNo","TEST1618221736"); //Merchant order number(optional)
//        param.put("customerId","110"); //customerId(optional)
//        param.put("fiatCurrency","CNY");   //fiat Currency(optional)
//        param.put("accountName","测试"); //bank account name(optional)
//        param.put("state",1);//state： 0 Unreviewed  1 Reviewed succeed 2 succeed  -1 Reviewed failed  -2 failed(optional)
//        param.put("type",1);           // Type 1 Normal 2 Urgent (optional)
        for (Map.Entry<String, Object> entry : param.entrySet()) {
            pay_url = pay_url + "&" +entry.getKey() + "=" + entry.getValue();
        }
        System.out.println(pay_url);
        String return_url = HttpUtils.get(pay_url, heads);
        System.out.println(return_url);
        //{"total":1,"code":200,"data":[{"account":"345345245","accountName":"测试","amount":null,"bankAddress":"","bankCode":"","bankName":"中国银行","bankNumber":"535224124242","comeFrom":2,"createTime":"2021-04-12 18:02:17","currencyCount":500.0,"currencyTypeId":1,"customerId":"110","fee":null,"fiatCurrency":"CNY","financialReviewTime":null,"iBank":"344244","id":907,"investorPassword":null,"merchantNo":null,"merchantOrderNo":"TEST1618221736","orderNo":"WF1618221737454","refuseReason":null,"remarks":"","state":0,"subbranch":"北京西二旗分行","swiftCode":"","traderId":null,"traderReviewTime":null,"type":1,"userId":490,"withdrawType":2,"withdrawalRate":null}],"pageSize":20,"message":"success","currentPage":1}
        return return_url;
    }

    /**
     * Delete unreviewed withdrawal records (only application records with state=0 can be deleted)
     * @return
     */
    public static String withdrawalDelete(){
        long timestamp = System.currentTimeMillis()/1000;
        String pay_url = Constant.URL_ + "api/recharge/customer/withdrawal/delete?timestamp="+timestamp;
        Map<String, String> heads = new HashMap<>();
        heads.put("content-type", "application/json");
        String params = Constant.merchant_no + "&" + timestamp;
        String access_key = HMACSHA1.hamcsha1(params.getBytes(), Constant.key.getBytes());
        heads.put("access_key", access_key);
        heads.put("app_id", Constant.app_id);
        Map<String, Object> param = new HashMap<>();
        param.put("merchantNo",Constant.merchant_no);//UID(required)
        param.put("merchantOrderNo","WF1608866506958");//merchant Order No(required)

        for (Map.Entry<String, Object> entry : param.entrySet()) {
            pay_url = pay_url + "&" +entry.getKey() + "=" + entry.getValue();
        }
        System.out.println(pay_url);
        String return_url = HttpUtils.get(pay_url, heads);
        System.out.println(return_url);
        //{"code":200,"message":"success"}
        return return_url;
    }


    /**
     * Get the gold exchange rate (valuation)
     * @param fiatCurrency
     * @return
     */
    public static String withdrawalRate(String fiatCurrency){
        long timestamp = System.currentTimeMillis()/1000;
        String pay_url = Constant.URL_ + "/api/recharge/customer/withdrawal/rate?timestamp="+timestamp;
        Map<String, String> heads = new HashMap<>();
        heads.put("content-type", "application/json");
        String params = fiatCurrency + "&" + timestamp;
        String access_key = HMACSHA1.hamcsha1(params.getBytes(), Constant.key.getBytes());
        heads.put("access_key", access_key);
        heads.put("app_id", Constant.app_id);
        Map<String, Object> param = new HashMap<>();
        param.put("fiatCurrency",fiatCurrency);//法币类型(必传)

        for (Map.Entry<String, Object> entry : param.entrySet()) {
            pay_url = pay_url + "&" +entry.getKey() + "=" + entry.getValue();
        }
        System.out.println(pay_url);
        String return_url = HttpUtils.get(pay_url, heads);
        System.out.println(return_url);
        //{"code":200,"data":{"fiatCurrency":"CNY","withdrawalRate":6.69},"message":"success"}
        return return_url;
    }

    /**
     * Query the withdrawal order according to the Merchant order number
     * @param merchantOrderNo
     * @return
     */
    public static String withdrawalOrderQuery(String merchantOrderNo){
        long timestamp = System.currentTimeMillis()/1000;
        String pay_url = Constant.URL_ + "/api/recharge/customer/withdrawal/order/query?timestamp="+timestamp;
        Map<String, String> heads = new HashMap<>();
        heads.put("content-type", "application/json");
        String params = Constant.merchant_no +"&" + merchantOrderNo + "&" + timestamp;
        String access_key = HMACSHA1.hamcsha1(params.getBytes(), Constant.key.getBytes());
        heads.put("access_key", access_key);
        heads.put("app_id", Constant.app_id);
        Map<String, Object> param = new HashMap<>();
        param.put("merchantNo",Constant.merchant_no);//UID(required)
        param.put("merchantOrderNo",merchantOrderNo);//merchant order number(required)
        for (Map.Entry<String, Object> entry : param.entrySet()) {
            pay_url = pay_url + "&" +entry.getKey() + "=" + entry.getValue();
        }
        System.out.println(pay_url);
        String return_url = HttpUtils.get(pay_url, heads);
        System.out.println(return_url);
        //{"code":200,"data":{"account":"345345245","accountName":"测试","amount":null,"bankAddress":"","bankCode":"","bankName":"中国银行","bankNumber":"535224124242","comeFrom":2,"createTime":"2021-04-12 18:02:17","currencyCount":500.0,"currencyTypeId":1,"customerId":"110","fee":null,"fiatCurrency":"CNY","financialReviewTime":null,"iBank":"344244","id":907,"investorPassword":null,"merchantNo":null,"merchantOrderNo":"TEST1618221736","orderNo":"WF1618221737454","refuseReason":null,"remarks":"","state":0,"subbranch":"北京西二旗分行","swiftCode":"","traderId":null,"traderReviewTime":null,"type":1,"userId":490,"withdrawType":2,"withdrawalRate":null},"message":"success"}
        return return_url;
    }
}
