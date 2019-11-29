<?php
/**
 * Created by huangyihao.
 * User: Administrator
 * Date: 2019/11/28 0028
 * Time: 18:10
 */
namespace app\api\controller;
class Websocket
{
    public function index()
    {
        $data = ['websocketIP'=>config('websocket.ip'),'port'=>config('websocket.port')];
        $data = ['code'=>0,'msg'=>'ok','data'=>$data];
        return json_encode($data);
    }
}