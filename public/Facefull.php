<?php
namespace app\api\controller;

/**
 * 相机原图处理接口
 * @author xiaojun
 */
class Facefull extends ApiBase
{
    protected $imgFile;
    protected $imgPixel;
    protected $captureTime;
    protected $deviceTime;
    protected $deviceCode;
    
    public function __construct(){
        parent::__construct();
    }
    /**
     * 请求参数处理
     */
    protected function _checkPostParams()
    {
        parent::_checkPostParams();
        $this->imgFile = $this->postParams && isset($this->postParams['img_url']) ? trim($this->postParams['img_url']) : '';        //人脸图片
        $this->deviceCode = $this->postParams && isset($this->postParams['mac_id']) ? trim($this->postParams['mac_id']): '';        //设备串码
        $this->imgPixel = $this->postParams && isset($this->postParams['img_pixel']) ? trim($this->postParams['img_pixel']): 0;     //抓拍时间戳
        $this->angle = $this->postParams && isset($this->postParams['angle']) ? trim($this->postParams['angle']): 0;                //抓拍角度
        $this->captureTime = $this->postParams && isset($this->postParams['timestamp']) ? trim($this->postParams['timestamp']): 0;  //抓拍时间戳
        if (!$this->imgFile) {
            $this->_returnMsg(['code' => 1, 'msg' => 'img_url: 参数缺失']);
        }
        if (!$this->deviceCode) {
            $this->_returnMsg(['code' => 1, 'msg' => 'mac_id: 参数缺失']);
        }
        if (!$this->captureTime) {
            $this->_returnMsg(['code' => 1, 'msg' => 'timestamp: 参数缺失']);
        }
        if (strlen($this->captureTime) != 10) {
            $this->_returnMsg(['code' => 1, 'msg' => 'timestamp: 时间戳为10位有效数字']);
        }
        //判断时间戳格式是否正确
        if(strtotime(date('Y-m-d H:i:s',$this->captureTime)) != $this->captureTime) {
            $this->_returnMsg(['code' => 1, 'msg' => 'timestamp: 参数格式错误']);
        }
        $this->deviceTime = $this->captureTime - 8*3600;
        $this->captureTime = time();
    }
    public function index()
    {
        $deviceModel = db('device');
        //判断设备是否存在
        $deviceExist = $deviceModel->where(['device_code' => $this->deviceCode, 'is_del' => 0])->find();
        if (!$deviceExist) {
        }
        $storeId = $deviceExist ? intval($deviceExist['store_id']) : 0;         //设备授权门店ID
        $deviceId = $deviceExist ?  intval($deviceExist['device_id']) : 0;      //设备ID
        $blockId = $deviceExist ? intval($deviceExist['block_id']): 0;          //设备所属区域ID
        $positionType = $deviceExist ? intval($deviceExist['position_type']) :3;//1为进店 2为离店 3为店内其它
        $clerkId = 0;
        $deviceImgModel = db('device_img');
        $this -> returnAndContinue(['code' => 0, 'msg' => 'ok']);
        //判断当前图片是否已经存在
        $imgData = [
            'store_id'      => $storeId,
            'block_id'      => $blockId,
            'device_id'     => $deviceId,
            'device_code'   => $this->deviceCode,
            'position_type' => $positionType,
            'img_url'       => $this->imgFile,
            'angle'         => $this->angle,
            'is_del'        => 0,
        ];
        $dimgExist = $deviceImgModel->where($imgData)->find();
        if($dimgExist){die;
            $this->_returnMsg(['code' => 1, 'msg' => 'img_url:图片+角度重复']);
        }
        $imgData['add_time'] = $imgData['update_time'] = time();
        $imgData['capture_time']= $this->captureTime;
        $imgData['img_pixel']= $this->imgPixel;
        $imgArr = $this->imgPixel ? explode('*', $this->imgPixel) : [];
        $imgData['image_width'] = isset($imgArr[0]) ? $imgArr[0] : '';
        $imgData['image_height'] = isset($imgArr[1]) ? $imgArr[1] : '';
        $dimgId = $deviceImgModel->insertGetId($imgData);
        if (!$dimgId) {
            $this->_returnMsg(['code' => 1, 'msg' => 'img_url:数据库处理失败']);
        }
        $this->_returnMsg(['code' => 0, 'msg' => 'img_url: 图片上报成功', 'img_url' => $this->imgFile]);
    }
    /**
     * 处理接口返回信息
     */
    protected function _returnMsg($data, $echo = TRUE){
        $str=is_string($data)  ? $data : json_encode($data , JSON_UNESCAPED_UNICODE);
        $responseTime = $this->_getMillisecond() - $this->visitMicroTime;//响应时间(毫秒)
        $addData = [
            'type'          => 'facefull',
            'request_time'  => $this->requestTime,
            'capture_time'  => $this->deviceTime ? $this->deviceTime: 0,
            'return_time'   => time(),
            'capture_img'   => $this->imgFile ? $this->imgFile : '',
            'device_code'   => $this->deviceCode ? $this->deviceCode : '',
            'img_x'         => 0,
            'img_y'         => 0,
            'img_pixel'     => $this->imgPixel ? $this->imgPixel : '',
            'request_params'=> $this->postParams ? json_encode($this->postParams) : '',
            'return_params' => $str,
            'response_time' => $responseTime,
            'error'         => isset($data['code']) ? intval($data['code']) : 0,
        ];
        $apiLogId = db('apilog_device')->insertGetId($addData);
        exit();
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