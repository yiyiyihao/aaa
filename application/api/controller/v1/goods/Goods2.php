<?php
/**
 * Created by huangyihao.
 * User: Administrator
 * Date: 2019/1/31 0031
 * Time: 14:43
 */

namespace app\api\controller\v1\goods;

use app\api\controller\Api;
use think\Request;
use app\service\service\Zhongtai;

//shop
class Goods2 extends Api
{
    protected $noAuth = ['categorylist','factory_goodslist','goodslist','goodsadd','goodsInfo','goodsedit','goodsdel','goods_onsale','goods_offsale','change_stock'];
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /*
     * 分类列表
     */
    public function categorylist()
    {
        $params = $this -> postParams;
        $page = !empty($params['this_page']) ? intval($params['this_page']) : 1;
        $size = !empty($params['page_rows']) ? intval($params['page_rows']) : 10;

        $obj = new Zhongtai();
        $openId = $this->userInfo['openid'];
        $shopCode = $this->userInfo['shop_code'];
        $token = $params['token'];

        $params = [
            'source' => 'admin',
            'openid' => $openId,
            'shop_code' => $shopCode,
            'page' => $page,
            'page_rows' => $size,
        ];
        $data = $obj -> cate_list($params,$token);

        return $data;
    }


    /*
     * 选品中心
     */
    public function factory_goodslist()
    {
        $params = $this -> postParams;
        $page = !empty($params['this_page']) ? intval($params['this_page']) : 1;
        $size = !empty($params['page_rows']) ? intval($params['page_rows']) : 10;

        $obj = new Zhongtai();
        $openId = $this->userInfo['openid'];
        $shopCode = $this->userInfo['shop_code'];
        $token = $params['token'];
        $name = $params['name'] ?? '';
        $cateid = $params['cateid'] ?? '';
        $brandid = $params['brandid'] ?? '';

        $params = [
            'source' => 'admin',
            'openid' => $openId,
            'shop_code' => $shopCode,
            'page' => $page,
            'page_rows' => $size,
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
        $data = $obj -> goodsList($params,$token);

        return $data;
    }

    /*
     * 商品列表
     */
    public function goodslist()
    {
        $params = $this -> postParams;
        $page = !empty($params['this_page']) ? intval($params['this_page']) : 1;
        $size = !empty($params['page_rows']) ? intval($params['page_rows']) : 10;

        $obj = new Zhongtai();
        $openId = $this->userInfo['openid'];
        $shopCode = $this->userInfo['shop_code'];
        $token = $params['token'];
        $status = $params['status'] ?? '';
        $name = $params['name'] ?? '';
        $cateid = $params['cateid'] ?? '';
        $brandid = $params['brandid'] ?? '';

        $params = [
            'source' => 'admin',
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
        //以下都是适应之前bi的前端页面字段，类似的其他方法也有。
        /*$data = json_decode($data,1);
        if(isset($data['code']) && $data['code'] ==0){
            $data['data']['count'] = $data['data']['total'];
            $data['data']['page'] = $data['data']['this_page'];
            foreach($data['data']['list'] as $k => $v){
                $data['data']['list'][$k]['name'] = $v['cate_name'];
            }
        }
        $data = json_encode($data);*/


        return $data;
    }


    /*
     * 商品详情
     */
    public function goodsInfo(){
        $params = $this -> postParams;

        $obj = new Zhongtai();
        $openId = $this->userInfo['openid'];
        $shopCode = $this->userInfo['shop_code'];
        $token = $params['token'];

        $goodsCode = $params['goods_code'] ?? 0;
        $getskus = $params['getskus'] ?? null;
        $params = [
            'source'=>'shop',
            'openid'=>$openId,
            'shop_code' => $shopCode,
            'goods_code'=>$goodsCode,
            'getskus'=>$getskus,
        ];
        $data = $obj -> get_goods($params,$token);
        return $data;
    }



    /*
     * 上架商品
     */
    public function goods_onsale()
    {
        $params = $this -> postParams;

        $obj = new Zhongtai();
        $openId = $this->userInfo['openid'];
        $shopCode = $this->userInfo['shop_code'];
        $token = $params['token'];
        $goodsCode = $params['goods_code'] ?? '';

        $params = [
            'source'=>'shop',
            'openid'=>$openId,
            'shop_code'=>$shopCode,
            'goods_code'=>$goodsCode,
        ];
        $data = $obj -> onsale_goods($params,$token);
        return $data;
    }


    /*
     * 下架商品
     */
    public function goods_offsale()
    {
        $params = $this -> postParams;

        $obj = new Zhongtai();
        $openId = $this->userInfo['openid'];
        $shopCode = $this->userInfo['shop_code'];
        $token = $params['token'];
        $goodsCode = $params['goods_code'] ?? '';

        $params = [
            'source'=>'shop',
            'openid'=>$openId,
            'shop_code'=>$shopCode,
            'goods_code'=>$goodsCode,
        ];
        $data = $obj -> offsale_goods($params,$token);
        return $data;
    }

    /*
     * 修改商品库存
     */
    public function change_stock()
    {
        $params = $this -> postParams;

        $obj = new Zhongtai();
        $openId = $this->userInfo['openid'];
        $shopCode = $this->userInfo['shop_code'];
        $token = $params['token'];
        $goodsCode = $params['goods_code'] ?? null;
        $skus = $params['skus'] ?? null;

        $params = [
            'source'=>'shop',
            'openid'=>$openId,
            'shop_code'=>$shopCode,
            'goods_code'=>$goodsCode,
            'skus'=>$skus,
        ];
        $data = $obj -> change_shop_stock($params,$token);
        return $data;
    }


}