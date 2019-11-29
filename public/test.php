<?php

/*echo 1111111111111;*/
/***获取token*********/
//get_token();
//die;
$userId = '444444401';
$token = 'b5016897ea07913fd31caf0d76c5ea6d';
/********添加用户*********/
//register($userId,$token);

$deviceId = '2000201080029815';
$deviceId = '2000560470005612';
/*******绑定设备*******/
//device_bind($deviceId,$userId,$token);die;
/*********设备列表**************/
//device_list($userId,$token);

/************解绑设备***************/
//device_unbind($deviceId,$userId,$token);

/*************开启云录像****************/
//cloud_open($deviceId,$saveDay=10,$token);


/*************关闭云录像****************/
//cloud_close($deviceId,$token);

/**************获取直播地址*****************/
//play_realtime($deviceId,$token);

$startTime = '1571211870';
$timeOut = '4222';
/***************获取云回放时间轴***********************/
//play_list($deviceId,$startTime,$timeOut,$token);

$fields = ''; //要获取的字段，用,隔开，例如”device_type,device_ip” 如果不传则获取所有字段 -----------现在在方法里不使用这个字段
/******************获取设备所有状态**********************/
//device_info($deviceId,$fields,$token);























//模拟回调传过来的数据
function huidiao(){
    $url = 'http://bi.api.worthcloud.net/api/face/face';
    $data = [
        "device_id"=>"2000570610024400",
        "command"=> "300019",
        "event_time"=> "1536396985",
        "msg_id"=>"1q22231312dd",
        "type"=> "SNAPCALLBACK",
        "data" => [
            "face_list"=>[
                [
                    "face"=>"1.jpg",
                    "body"=>"",
                    "face_id"=>"",
                    "face_rectangle"=>[
                        "top"=>"0",
                        "left"=>"0",
                        "width"=>"100",
                        "height"=>"100"
                    ]
                ]
            ]
        ]
    ];


    $result = curl_request($url,$data);

    print_r($result);
}

/*
 * 获取设备所有状态
 */
function device_info($deviceId,$fields,$token)
{
    $uri = 'http://iot.worthcloud.net';
    $url = $uri . "/v1/devices/info?device_id=$deviceId";
    $headers = ['Authorization:'.$token];
    $result = curl_request($url,'',$headers);
    print_r($result);
}

/*
 * 获取云回放时间轴
 * $startTime 开始时间戳，结束时间为当天24点  ，可选
 * $timeOut 不传生成的链接一直生效，传入则视频只生效传入时间，单位秒   可选
 */
function play_list($deviceId,$startTime,$timeOut = '48000',$token)
{
    $uri = 'http://iot.worthcloud.net';
    $url = $uri . "/v1/cloud/playlist?device_id=$deviceId&start_time=$startTime&timeout=$timeOut";
    $headers = ['Authorization:'.$token];
    $result = curl_request($url,'',$headers);
    print_r($result);
}


/*
 * 获取直播地址
 */
function play_realtime($deviceId,$token)
{
    $uri = 'http://iot.worthcloud.net';
    $url = $uri . "/v1/cloud/play_realtime?device_id=$deviceId";
    $headers = ['Authorization:'.$token];
    $result = curl_request($url,'',$headers);
    print_r($result);
}


/*
 * 关闭云录像
 */
function cloud_close($deviceId,$token)
{
    $uri = 'http://iot.worthcloud.net';
    $url = $uri . "/v1/cloud/close?device_id=$deviceId";
    $headers = ['Authorization:'.$token];
    $result = curl_request($url,'',$headers);
    print_r($result);
}


/*
 * 开启云录像
 */
function cloud_open($deviceId,$saveDay=10,$token)
{
    $uri = 'http://iot.worthcloud.net';
    $url = $uri . "/v1/cloud/open?device_id=$deviceId&save_day=$saveDay";
    $headers = ['Authorization:'.$token];
    $result = curl_request($url,'',$headers);
    print_r($result);
}

/*
 * 解绑设备
 */
function device_unbind($deviceId,$userId,$token)
{
    $uri = 'http://iot.worthcloud.net';
    $url = $uri . "/v1/devices/unbind";
    $data = ['user_id'=>$userId,'device_id'=>$deviceId];
    $headers = ['Authorization:'.$token];
    $result = curl_request($url,$data,$headers);
    print_r($result);
}


/*
 * 设备列表
 */
function device_list($userId,$token)
{
    $uri = 'http://iot.worthcloud.net';
    $url = $uri . "/v1/devices/list/user_id/$userId";
    $headers = ['Authorization:'.$token];
    $result = curl_request($url,'',$headers);
    print_r($result);
}


/*
 * 绑定设备
 */
function device_bind($deviceId,$userId,$token)
{
    $uri = 'http://iot.worthcloud.net';
    $url = $uri . "/v1/devices/add";
    $data = ['user_id'=>$userId,'device_id'=>$deviceId];
    $headers = ['Authorization:'.$token];
    $result = curl_request($url,$data,$headers);
    print_r($result);
}


/*注册user_id
 * $userId：bigint：用户id
 */
function register($userId,$token)
{
    $uri = 'http://iot.worthcloud.net';
    $url = $uri . "/v1/users";
    $data = ['user_id'=>$userId];
    $headers = ['Authorization:'.$token];
    $result = curl_request($url,$data,$headers);
    print_r($result);
}

//生成token ,7200秒有效
function get_token()
{

    $access_key = '4ab74c64f6dd47bb1a4f8939c52ffb31';
    $secret_key = '29bdbc822df2e6c13dcf4afe6913525f';
    $uri = 'http://iot.worthcloud.net';
    $url = $uri . "/v1/authenticator/$access_key/$secret_key";
    $result = curl_request($url);
    print_r($result);

/*    $headers = ['Authorization:'.$this -> token];
    $apiUrl = $this->apiUrl.'/v3/cameras';
    $params = ['mac_id' => $macId,'user_id' => $userId,'mac_name' => $macName];
    $paramStr = http_build_query($params);
    $apiUrl = $apiUrl. "?" . $paramStr;
    $result = curl_post($apiUrl, $params,$headers);*/
}


//参数1：访问的URL，参数2：post数据(不填则为GET)，参数3：提交的$cookies,参数4：是否返回$cookies
function curl_request($url,$post='',$headers=[],$cookie='', $returnCookie=0,$delete = ''){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
    curl_setopt($curl, CURLOPT_REFERER, "http://XXX");
    //设置headers
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    if($post) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
    }else if($delete){
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }
    if($cookie) {
        curl_setopt($curl, CURLOPT_COOKIE, $cookie);
    }
    curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);
    if (curl_errno($curl)) {
        return curl_error($curl);
    }
    curl_close($curl);
    if($returnCookie){
        list($header, $body) = explode("\r\n\r\n", $data, 2);
        preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
        $info['cookie']  = substr($matches[1][0], 1);
        $info['content'] = $body;
        return $info;
    }else{
        return $data;
    }
}
?>