<?php
/**
 * Created by huangyihao.
 * User: Administrator
 * Date: 2019/11/26 0026
 * Time: 10:38
 */

namespace app\api\controller;
use app\service\service\Zhongtai;

trait AppZT
{
    //获取商品列表
    protected function get_goods_list()
    {
        $params = $this -> postParams;
        $page = !empty($params['this_page']) ? intval($params['this_page']) : 1;
        $size = !empty($params['page_rows']) ? intval($params['page_rows']) : 10;


        $obj = new Zhongtai();
        $token = $obj -> get_token();
        $openId = $params['zt_openid'] ?? '';
        $shopCode = $params['shop_code'] ?? '';
        $status = $params['status'] ?? '';
        $name = $params['name'] ?? '';
        $cateid = $params['cateid'] ?? '';
        $brandid = $params['brandid'] ?? '';

        $params = [
            'source' => 'user',
            'openid' => $openId,
            'shop_code' => $shopCode,
            'status' => $status,
            'page' => $page,
            'page_rows' => $size,
            'getskus' => 1,
            'getcates' => 1,
            'getbrand' => 1,
            'sortdata' => ['time'=>'asc','shop_sale'=>'desc','shop_price'=>'asc'],
        ];
        if($name){
            $params['name'] = $name;
        }
        if($cateid){
            $params['cateid'] = $cateid;
        }
        if($brandid){
            $params['brandid'] = $brandid;
        }
        $data = $obj -> goods_list($params,$token);

        echo $data;
    }

    /*
     * 获取商品详情
     */
    protected function get_goods_detail()
    {
        $params = $this -> postParams;

        $obj = new Zhongtai();
        $token = $obj -> get_token();
        $openId = $params['zt_openid'] ?? '';
        $shopCode = $params['shop_code'] ?? '';
        $goodsCode = $params['goods_code'] ?? '';
        $getSkus = $params['getskus'] ?? 1;

        $params = [
            'source' => 'user',
            'openid' => $openId,
            'shop_code' => $shopCode,
            'goods_code' => $goodsCode,
            'getskus' => $getSkus,
            'getcates' => 1,
            'getbrand' => 1,
        ];

        $data = $obj -> get_goods($params,$token);

        echo $data;
    }

    /*
     * 根据sn获取sku商品信息
     */
    protected function snget()
    {
        $params = $this -> postParams;

        $obj = new Zhongtai();
        $token = $obj -> get_token();
        $openId = $params['zt_openid'] ?? '';
        $shopCode = $params['shop_code'] ?? '';
        $skuSn = $params['sku_sn'] ?? null;

        $params = [
            'source' => 'user',
            'openid' => $openId,
            'shop_code' => $shopCode,
            'sku_sn' => $skuSn,
        ];

        $data = $obj -> snget($params,$token);

        echo $data;
    }

    /*
     * 创建订单
     */
    protected function order_create()
    {
        $params = $this -> postParams;

        $obj = new Zhongtai();
        $token = $obj -> get_token();
        $openId = $params['zt_openid'] ?? '';
        $shopCode = $params['shop_code'] ?? '';

        $skuList = $params['sku_list'] ?? [];
        $skuList = json_decode($skuList,1);
        $totalAmount = $params['total_amount'] ?? 0;
        $addrId = $params['addr_id'] ?? 0;

        $params = [
            'source' => 'user',
            'openid' => $openId,
            'shop_code' => $shopCode,
            'sku_list' => $skuList,     //下单商品列表数组(商品SKU编号)[必填]
            'total_amount' => $totalAmount,     //总订单金额[必填]
            'addr_id' => $addrId,       //收货地址后提交[必填]
        ];
        //pre($params);
        $data = $obj->order_create($params, $token);
        echo $data;
    }

    /*
     * 订单列表
     */
    protected function order_list()
    {
        $params = $this -> postParams;
        $page = !empty($params['this_page']) ? intval($params['this_page']) : 1;
        $size = !empty($params['page_rows']) ? intval($params['page_rows']) : 10;

        $obj = new Zhongtai();
        $token = $obj -> get_token();
        $openId = $params['zt_openid'] ?? '';
        $shopCode = $params['shop_code'] ?? '';

        $status = $params['status'] ?? '';
        $start_time = $params['start_time'] ?? '';
        $end_time = $params['end_time'] ?? '';
        $goods_name = $params['goods_name'] ?? '';


        $params = [
            'source' => 'user',
            'openid' => $openId,
            'shop_code' => $shopCode,
            'page' => $page,
            'page_rows' => $size,
        ];

        if ($status) {
            $params['status'] = $status;
        }
        if ($start_time) {
            $params['start_time'] = $start_time;
        }
        if ($end_time) {
            $params['end_time'] = $end_time;
        }
        if ($goods_name) {
            $params['goods_name'] = $goods_name;
        }
        $data = $obj->order_list($params, $token);


        return $data;
    }

    /*
     * 订单详情
     */
    protected function order_detail()
    {
        $params = $this -> postParams;

        $obj = new Zhongtai();
        $token = $obj -> get_token();
        $openId = $params['zt_openid'] ?? '';
        $shopCode = $params['shop_code'] ?? '';
        $skuSn = $params['sku_sn'] ?? null;

        $orderSn = $params['order_sn'] ?? '';
        $getSkus = $params['getskus'] ?? '';
        $getLogs = $params['getlogs'] ?? '';

        $params = [
            'source' => 'user',
            'openid' => $openId,
            'shop_code' => $shopCode,
            'order_sn' => $orderSn,
            'getskus' => $getSkus,  //是否返回订单商品列表(1是 0否)
            'getlogs' => $getLogs,  //是否返回订单日志列表(1是 0否)
        ];

        $data = $obj->get_order($params, $token);

        echo $data;
    }

    /*
     * 取消订单
     */
    protected function order_cancel()
    {
        $params = $this -> postParams;

        $obj = new Zhongtai();
        $token = $obj -> get_token();
        $openId = $params['zt_openid'] ?? '';
        $shopCode = $params['shop_code'] ?? '';

        $orderSn = $params['order_sn'] ?? '';
        $remark = $params['remark'] ?? '';

        $params = [
            'source' => 'user',
            'openid' => $openId,
            'shop_code' => $shopCode,
            'order_sn' => $orderSn,
            'remark' => $remark,
        ];

        $data = $obj -> order_cancel($params,$token);

        echo $data;
    }

    /*
     * 订单完成
     */
    protected function order_finish()
    {
        $params = $this -> postParams;

        $obj = new Zhongtai();
        $token = $obj -> get_token();
        $openId = $params['zt_openid'] ?? '';
        $shopCode = $params['shop_code'] ?? '';

        $orderSn = $params['order_sn'] ?? '';
        $remark = $params['remark'] ?? '';

        $params = [
            'source' => 'user',
            'openid' => $openId,
            'shop_code' => $shopCode,
            'order_sn' => $orderSn,
            'remark' => $remark,
        ];

        $data = $obj -> order_finish($params,$token);

        echo $data;
    }


}




















