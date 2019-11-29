<?php

namespace app\api\controller\v1\order;

use app\api\controller\Api;
use think\Request;
use app\service\service\Zhongtai;

//后台数据接口页
class Order2 extends Api
{
    protected $noAuth = ['orderlist','orderinfo','createOrder','cancel','finish','order_delivery'];
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /*
     * 订单列表
     */
    public function orderlist()
    {
        $params = $this -> postParams;
        $page = !empty($params['this_page']) ? intval($params['this_page']) : 1;
        $size = !empty($params['page_rows']) ? intval($params['page_rows']) : 10;

        $obj = new Zhongtai();
        $openId = $this->userInfo['openid'];
        $token = $params['token'];


        $status = $params['status'] ?? '';
        $start_time = $params['start_time'] ?? '';
        $end_time = $params['end_time'] ?? '';
        $goods_name = $params['goods_name'] ?? '';


        $params = [
            'source' => 'admin',
            'openid' => $openId,
            'page' => $page,
            'page_rows' => $size,
        ];

        if($status){
            $params['status'] = $status;
        }
        if($start_time){
            $params['start_time'] = $start_time;
        }
        if($end_time){
            $params['end_time'] = $end_time;
        }
        if($goods_name){
            $params['goods_name'] = $goods_name;
        }
        $data = $obj -> order_list($params,$token);


        return $data;
    }

    /*
     * 订单详情
     */
    public function orderinfo()
    {
        $params = $this -> postParams;

        $obj = new Zhongtai();
        $openId = $this->userInfo['openid'];
        $token = $params['token'];

        $orderSn = $params['order_sn'] ?? '';
        $getSkus = $params['getskus'] ?? '';
        $getLogs = $params['getlogs'] ?? '';

        $params = [
            'source' => 'admin',
            'openid' => $openId,
            'order_sn' => $orderSn,
            'getskus' => $getSkus,  //是否返回订单商品列表(1是 0否)
            'getlogs' => $getLogs,  //是否返回订单日志列表(1是 0否)
        ];

        $data = $obj -> get_order($params,$token);
        return $data;
    }

    /*
     * 创建订单
     */
    public function createOrder()
    {
        $params = $this -> postParams;

        $obj = new Zhongtai();
        $openId = $this->userInfo['openid'];
        $token = $params['token'];
        $temp = [
            'source'=>'admin',
            'openid'=>$openId,
        ];

        unset($params['token']);
        $params = array_merge($params,$temp);

        $data = $obj -> order_create($params,$token);

        return $data;
    }

    /*
     * 取消订单
     */
    public function cancel()
    {
        $params = $this -> postParams;

        $obj = new Zhongtai();
        $openId = $this->userInfo['openid'];
        $token = $params['token'];

        $orderSn = $params['order_sn'] ?? '';
        $remark = $params['remark'] ?? '';

        $params = [
            'source' => 'admin',
            'openid' => $openId,
            'order_sn' => $orderSn,
            'remark' => $remark,
        ];

        $data = $obj -> order_cancel($params,$token);
        return $data;
    }

    /*
     * 订单发货
     */
    public function order_delivery()
    {
        $params = $this -> postParams;

        $obj = new Zhongtai();
        $openId = $this->userInfo['openid'];
        $token = $params['token'];
        $temp = [
            'source'=>'shop',
            'openid'=>$openId,
        ];

        unset($params['token']);
        $params = array_merge($params,$temp);

        $data = $obj -> order_delivery($params,$token);

        return $data;
    }



}