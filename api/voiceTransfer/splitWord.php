<?php 
function splitThis($str){
        /*分词-------开始*/
        $host = "http://wbfxfc.market.alicloudapi.com";
        $path = "/rest/160601/text_analysis/aliws.json";
        $method = "POST";
        $appcode = ALI_APP_CODE;
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        //根据API的要求，定义相对应的Content-Type
        array_push($headers, "Content-Type".":"."application/json; charset=UTF-8");
        $querys = "";
        $bodys = array(
            "inputs"=>array(
                0=>array(
                    "text"=>array(
                        "dataType"=>50,
                        "dataValue"=>$str
                    )
                )
            )
        );
        $bodys = json_encode($bodys);
        //$bodys = "{\"inputs\":[{\"text\":{\"dataType\":50,\"dataValue\":\"".$result['result'][0]."\"}}]}";
        $url = $host . $path;
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $bodys);
        $data = json_decode(curl_exec($curl),true);
        $data = json_decode($data['outputs'][0]['outputValue']['dataValue'],true);
        $data = $data['tokens'];
        return $data;
        
        /*分词-------结束*/
        
    }
?>