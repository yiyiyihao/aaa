<?php
/**
 * Created by huangyihao.
 * User: Administrator
 * Date: 2019/11/15 0015
 * Time: 16:22
 */
class test
{

    /*
     * 获取token
     */
    function get_token()
    {
        $appid = 'echopjtgkutoevivr0';
        $secret = 'fCBt1NzQNS2QX1wzBPOqo9wIV5e0JaeI';
        $url = 'http://api.weiput.com/v1/token/get';
        if(cache('gettoken')){
            return cache('gettoken');
        }
        $result = $this->curl_request($url, ['appid' => $appid, 'secret' => $secret]);
        if(isset($result['code']) && $result['code'] == 0){
            cache('gettoken',$result['token'],60);
            return $result['token'];
        }
        return false;

    }

    /*
     * 注册为管理员用户
     */
    function register()
    {
        $appid = 'echopjtgkutoevivr0';
        $secret = 'fCBt1NzQNS2QX1wzBPOqo9wIV5e0JaeI';
        $url = 'http://api.weiput.com/v1/user/register/register';
        $result = $this->curl_request($url, ['appid' => $appid, 'secret' => $secret]);
        return $result;
    }


//参数1：访问的URL，参数2：post数据(不填则为GET)，参数3：提交的$cookies,参数4：是否返回$cookies
    function curl_request($url, $post = '', $headers = [], $cookie = '', $returnCookie = 0, $delete = '')
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_REFERER, "http://XXX");
        //设置headers
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        if ($post) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
        } else if ($delete) {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }
        if ($cookie) {
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
        if ($returnCookie) {
            list($header, $body) = explode("\r\n\r\n", $data, 2);
            preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
            $info['cookie'] = substr($matches[1][0], 1);
            $info['content'] = $body;
            return $info;
        } else {
            return $data;
        }
    }
}
echo '<pre />';
$obj = new test();
print_r($obj ->get_token());