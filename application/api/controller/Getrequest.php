<?php

namespace app\api\controller;

use app\common\controller\Api;
use think\Db;
/**
 * 礼品下单接口
 * @ApiInternal
 */
class Getrequest extends Api
{
	/**
	 * 下单接口
	 */
	//如果$noNeedLogin为空表示所有接口都需要登录才能请求
    //如果$noNeedRight为空表示所有接口都需要验证权限才能请求
    //如果接口已经设置无需登录,那也就无需鉴权了
    //
    // 无需登录的接口,*表示全部
    protected $noNeedLogin = ['getgg'];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = [];

	 /**
     * 获取电子面单
     * @ApiTitle    (获取电子面单)
     * @ApiSummary  (电子单号下单接口)
     * @ApiMethod   (POST)
	 * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token，注：token值请放在headers")
	 * @ApiParams   (name="send_order_no", type="string", required=true, description="订单号，不可重复。")
	 * @ApiParams   (name="platform", type="int", required=true, description="快递类型，1：圆通   3：圆通拼多多专用。")
	 * @ApiParams   (name="weight", type="Double", required=true, description="包裹重量，范围在0.1-40kg")
	 * @ApiParams   (name="goods", type="string", required=true, description="商品名称，30个字内")
	 * @ApiParams   (name="send_name", type="string", required=true, description="发件人")
	 * @ApiParams   (name="send_mphone", type="int", required=true, description="发件手机号码")
	 * @ApiParams   (name="send_province", type="string", required=true, description="发件人省")
	 * @ApiParams   (name="send_city", type="string", required=true, description="发件人市")
	 * @ApiParams   (name="send_district", type="string", required=true, description="发件人区")
	 * @ApiParams   (name="send_address", type="string", required=true, description="发件人详细地址")
	 * @ApiParams   (name="receiver_name", type="string", required=true, description="收货人")
	 * @ApiParams   (name="receiver_mphone", type="string", required=true, description="收货人号码")
	 * @ApiParams   (name="receiver_address", type="string", required=true, description="收货地址。格式：浙江省 杭州市 余杭区 文体路88号")
	 * @ApiReturnParams   (name="code", type="integer", required=true, description="状态值：1 为成功")
     * @ApiReturnParams   (name="msg", type="string", required=true, description="返回说明")
	 * @ApiReturnParams   (name="time", type="string", required=true, description="响应的时间戳")
     * @ApiReturnParams   (name="data", type="object", description="返回业务数据合集")
	 * @ApiReturnParams   (name="express_no", type="string", required=true, description="快递单号")
	 * @ApiReturnParams   (name="taskid", type="int", required=true, description="单号ID，请保存")
	 * @ApiReturnParams   (name="p", type="Double", required=true, description="扣费金额")
     * @ApiReturn   ({"code": 1,"msg": "","time": "1540347545","data": {"express_no": "80081082712","taskid": "98736","p": "3.0"}})
     */
	public function place_an_order()
	{
		$user_id = $this->auth->id;//用户id
		$yu_money = $this->auth->money;//用户余额
		$level = $this->auth->level;//用户等级
		$username = input('post.sendname')?input('post.sendname'):$this->auth->username;//用户名
        $uphone = input('post.sendphone')?input('post.sendphone'):$this->auth->mobile;
		$expressid = (int)input('post.expressid');//获取快递价格
		$express_price = get_price($level,$expressid);//获取快递价格
		$father_id = input('post.father_id');//获取商品编号
		$num = input('post.num');//获取商品数量
		$store_id = input('post.store_code');//获取仓库id
		$receiver = input('post.receiver');//获取收件人
		$a_mphone = input('post.a_mphone');//获取收件人手机号
		$ProvinceName = input('post.ProvinceName');//获取收件人省份
		$CityName = input('post.CityName');//获取收件人市
		$AreaName = input('post.AreaName');//获取收件人区
		$Address = input('post.Address');//获取收件人详细地址
		$out_order_no = input('post.out_order_no');//获取外部单号
		$send_code = input('post.send_code');//发货仓编号
		$store_name = input('post.store_name');//发货仓name
		$form = input('post.form');
		$remark = input('post.remark');
        $goods_info = Db::name('goods')->where(['id'=>$father_id,'is_out'=>1])->find();//商品信息
		if(!$goods_info){
			$this->error('该商品不存在');
		}
		$goods_price = $goods_info['apiprice'];//商品单价
		$total_price = number_format($num*$goods_price+$express_price,2);//商品总价
        $father_code = get_father_code($father_id,$expressid);//获取主站商品编号
        if(!$user_id){
        	$this->error('token错误');
        }
        if($yu_money<$total_price){
			$this->error('系统余额不足,请联系在线客服!',['money'=>$yu_money]);
		}
		$isorder = Db::name('express')->where('out_order_no',$out_order_no)->find();
		if($isorder){
			$this->error('订单号:【'.$out_order_no.'】已经存在,请不要重复提交！');
		}
        //处理圆通礼品接口
		if($expressid==1){
			//组装数据请求圆通接口
			$post_info = [
                'accessToken'=>config('get_yto_config.token'),
                'storehouseCode'=>$send_code,//发货仓编号
                'goodsCode'=>$father_code,//商品编号
                'receiver'=>$receiver,//收件人
                'receiverPhone'=>$a_mphone,//收件人手机号
                'receiverProvinceName'=>$ProvinceName,//收件人省份
                'receiverCityName'=>$CityName,//收件人城市
                'receiverAreaName'=>$AreaName,//收件人区
                'receiverAddress'=>$Address,//收件人详细地址
                'thirdOrderNo'=>$out_order_no,//第三方单号
                'goodsNum'=>$num,//购买数量
                'shipperName'=>$username,//发件人
                'shipperPhone'=>$uphone
            ];
            $json=sendRequest(config('get_yto_config.apiurl').'/api/createOrder/merchantCreateOrder',$post_info,'post');
            $return = json_decode($json,1);
            if($return['code']===0){
            	$recive_addr = $ProvinceName.' '.$CityName.' '.$AreaName.' '.$Address;//收件地址
            	$taskid = $return['data']['recordId'];
            	$express_no = '0';
            	$code = 1;
                $kdcb = 2.3;
            }else{
            	$this->error($return['msg']);
            }
		}
		//处理韵达礼品接口
		if($expressid==4){
			//组装数据请求韵达接口
			$recive_addr = $ProvinceName.' '.$CityName.' '.$AreaName.' '.$Address;//收件地址
			$orderParams = [];
	        $orderParams[0] = [
	            'apiOrderId'=>$out_order_no,//订单唯一标识
	            'buyerName' =>$receiver,//收件人
	            'buyerMobile'=>$a_mphone,//收件人手机号
	            'buyerAddr'=>$recive_addr,//收件地址
	            'storeType'=>$send_code,//发货仓编号
	            'kuaidiName'=>get_express_name($expressid)
	        ];
	        $orderParams = json_encode($orderParams);
	        $post_info = [
	            'partnerId'   =>config('get_yunda_config.partnerid'),//合作商ID
	            'itemId'      =>$father_code,//韵达礼品对应商品编号
	            'orderParams' =>$orderParams,//订单信息
                'validation'   =>md5($father_code.$orderParams.config('get_yunda_config.partnerid').config('get_yunda_config.token'))
	        ];
            $json = sendRequest(config('get_yunda_config.apiurl').'/ApiOrder/orderGift',$post_info,'post');
			$return = json_decode($json,1);
			if($return['result']===1){
                $return_info = $return['orders'][0];
                $out_order_no = $return_info['apiOrderId'];//订单唯一标识
                $express_no = $return_info['expressNo'];//快递面单号
                $taskid = 0;
                $code = 1;
                $kdcb = 2.6;
			}else{
				$this->error($return['message']);
			}
		}
		//处理接口请求成功的业务逻辑
		if($code){
			$result = [
				'user_id'=>$user_id,
				'express_no'=>$express_no,
				'out_order_no'=>$out_order_no,
				'expressid'=>$expressid,
				'price'=>$total_price,
				'num'=>$num,
				'goods'=>$father_id,
				'addressee'=>$receiver,
				'a_mphone'=>$a_mphone,
				'all_address'=>$recive_addr,
				'sender'=>$store_name,
				'from'  => $form,
				'taskid'=>$taskid,
				'remark' =>$remark,
                'cprice'=>$goods_info['cprice']+$kdcb
			];
			$express = model('express');
			$express->save($result);
			\app\common\model\User::score($score=0,'-'.$total_price,$user_id,'购买快递，编号'.$express->id);
			$this->success('提交成功', ['express_no' => $express_no,'taskid' => $express->id,'p' => $total_price]);
		}
	}
	/**
	* 获取单号接口
	*/
	public function get_express_no()
	{
		$taskid = input('post.taskid');
		$expressid = (int)input('post.expressid');
        $exinfo = Db::name('express')->where('id',$taskid)->where('expressid',1)->find();
        if($exinfo['express_no']){$this->success('重新获取单号',['taskid'=>$taskid,'express_no'=>$exinfo['express_no']]);}
        $recordId = $exinfo['taskid'];
		if($expressid==1){
			$post_info['recordId'] = $recordId; 
			$json = sendRequest(config('get_yto_config.apiurl').'/OpenPlatform/findExpressNo',$post_info,'post');
			$return = json_decode($json,1);
			if($return['code']===0 && $return['data'][0]['ExpressNo'] !=''){
				$recordId = $return['data'][0]['recordId'];
				$express_no = $return['data'][0]['ExpressNo'];
				//更新主站单号
				$sql = model('express')->save(['express_no'=>$express_no],['id'=>$taskid]);

					$this->success('获取单号成功',['taskid'=>$taskid,'express_no'=>$express_no]);

			}else{
				$this->error('未获取到单号,请稍后!');
			}
		}
	}
    /**
     * 余额查询
     */
    public function getmoney()
    {
        //获取业务参数
        $this->success('返回成功', ['usermoney' => $this->auth->money]);
    }

    /**
     * 分站公告
     */
    public function getgg()
    {
        //获取业务参数
        $this->success('返回成功', ['info' => config('site.gonggao')]);
    }
}






