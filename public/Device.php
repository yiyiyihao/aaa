<?php
/**
 * Created by huangyihao.
 * User: Administrator
 * Date: 2019/3/25 0025
 * Time: 15:15
 */
namespace app\api\controller;

class Device extends ApiBase
{

    public function __construct(){
        parent::__construct();
    }

    public function StatusChange()
    {
        $params = $this -> postParams;

        $this -> returnAndContinue(['code' => 0, 'msg' => 'ok']);
        $str = var_export($params,TRUE);
        $date = date('Ym');
        file_put_contents('../runtime/log/bibibi' . $date,$str . PHP_EOL, FILE_APPEND);
        #TODO:在设备表中添加is_online字段，修改设备列表页，根据这个字段显示实时直播
//        $data = json_decode($params,true);
        $data = $params;
        if(isset($data['type']) && $data['type'] == 'STATE'){
            if($data['data']['state'] == 'OFFLINE'){
                $update = ['status' => 0];
            }else{ //ONLINE
                $update = ['status' => 1];
                $deviceApi = new \app\common\api\DeviceApi();
                //开启实时录像
                $result = $deviceApi -> openvideo($data['mac_id']);
            }
            $info = db('device') -> where('device_code','=',$data['mac_id']) ->where('is_del','=',0) -> update($update);


        }
        $results = @curl_post('http://bi.micyi.com/api/device/StatusChange',$this->postParams);
    }

    /* 中断并返回数据,后面程序继续执行,避免用户等待(immediate)
 * 可用于返回值后,继续执行程序,但程序占得所以资源没有释放,一直占用,务必注意,最好给单独脚本执行
 * @param   string|array      $data 字符串或数组,数组将被转换成json字符串
 * @param   intval      $set_time_limit 设置后面程序最大执行时间,0不限制,但web页面设置最大执行时间不一定靠谱,可改用脚本或单独开子进程
 * @return
 */
    function returnAndContinue($data ='',$set_time_limit=10)
    {
        $str=is_string($data)  ? $data : json_encode($data , JSON_UNESCAPED_UNICODE);
        header('Content-Type:application/json');
        echo $str;
        if(function_exists('fastcgi_finish_request')){			//Nginx使用
            fastcgi_finish_request();		//后面输出客户端获取不到
        }else {			//apache 使用
            $size = ob_get_length();
            header("Content-length: $size");
            header('Connection:close');
            ob_end_flush();
            //ob_flush();       //加了没效果
            flush();
        }
        ignore_user_abort(true);
        set_time_limit($set_time_limit);
        return true;
    }



}
