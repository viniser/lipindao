<?php
namespace app\api\controller;

use app\common\controller\Api;
use think\Db;
/**
 * 礼品接口
 */
class Goods extends Api
{
    // 无需登录的接口,*表示全部
    protected $noNeedLogin = [];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = [];

    /**
     * 礼品列表
     * @ApiTitle    (礼品列表)
     * @ApiSummary  (获取在售礼品列表)
     * @ApiMethod   (POST)
     * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token，注：token值请放在headers")
	 * @ApiReturnParams   (name="code", type="integer", required=true, description="状态值：1 为成功")
     * @ApiReturnParams   (name="msg", type="string", required=true, description="返回成功")
	 * @ApiReturnParams   (name="time", type="string", required=true, description="响应的时间戳")
     * @ApiReturnParams   (name="data", type="object", description="返回业务数据合集")
	 * @ApiReturnParams   (name="goodslist", type="string", required=true, description="商品列表。id:商品编码，name:商品名称，price:价格")
     * @ApiReturn   ({"code": 1,"msg": "返回成功","time": "1540347545","data": {"goodslist":[{"id":1,"name":"1.5米裁缝尺","price":"0.45"},{"id":2,"name":"8层加棉抹布","price":"0.90"}]}})
     */
    public function goodslist()
    {
        $goods = Db::name('goods')->where('is_out',1)->field('id,name,apiprice')->order('id','asc')->select();
        $this->success('返回成功', ['goodslist' => $goods]);
    }
    /**
     * 仓库列表
     * @ApiTitle    (仓库列表)
     * @ApiSummary  (获取发货仓库列表)
     * @ApiMethod   (POST)
     * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token，注：token值请放在headers")
     * @ApiReturnParams   (name="code", type="integer", required=true, description="状态值：1 为成功")
     * @ApiReturnParams   (name="msg", type="string", required=true, description="返回成功")
     * @ApiReturnParams   (name="time", type="string", required=true, description="响应的时间戳")
     * @ApiReturnParams   (name="data", type="object", description="返回业务数据合集")
     * @ApiReturnParams   (name="storelist", type="string", required=true, description="仓库列表合集。id:仓库编码，store_name:仓库名称")
     * @ApiReturn   ({"code": 1,"msg": "返回成功","time": "1540347545","data": {"storelist":[{"id":1,"store_name":"圆通北京仓"},{"id":4,"store_name":"圆通广州仓"}]}})
     */
    public function storelist()
    {
        $stores = Db::name('send_store')->where('express_id',1)->field('id,store_name')->order('id','asc')->select();
        $this->success('返回成功', ['storelist' => $stores]);
    }
    /**
     * 礼品下单
     * @ApiTitle    (礼品下单)
     * @ApiSummary  (礼品下单接口)
     * @ApiMethod   (POST)
     * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token，注：token值请放在headers")
     * @ApiParams   (name="send_order_no", type="string", required=flase, description="订单号，不可重复。")
     * @ApiParams   (name="goodsid", type="int", required=true, description="礼品编号，请在礼品列表接口获得具体编号")
     * @ApiParams   (name="storesid", type="int", required=true, description="仓库编号，请在仓库列表接口获得具体编号")
     * @ApiParams   (name="num", type="int", required=flase, description="购买数量.默认 1")
     * @ApiParams   (name="receiver_name", type="int", required=true, description="收货人")
     * @ApiParams   (name="receiver_phone", type="string", required=true, description="收货人号码")
     * @ApiParams   (name="receiver_province", type="string", required=true, description="收货地址所在省")
     * @ApiParams   (name="receiver_city", type="string", required=true, description="收货地址所在市")
     * @ApiParams   (name="receiver_district", type="string", required=true, description="收货地址所在区")
     * @ApiParams   (name="receiver_address", type="string", required=true, description="收货地址")
     * @ApiParams   (name="sendname", type="string", required=true, description="发件人")
     * @ApiParams   (name="sendphone", type="string", required=true, description="发件号码")
     * @ApiReturnParams   (name="code", type="integer", required=true, description="状态值：1 为成功")
     * @ApiReturnParams   (name="msg", type="string", required=true, description="返回说明",sample="提交成功")
     * @ApiReturnParams   (name="time", type="string", required=true, description="响应的时间戳")
     * @ApiReturnParams   (name="data", type="object", description="返回业务数据合集")
     * @ApiReturnParams   (name="express_no", type="string", required=true, description="快递单号。由于人工打单，单号大约10分钟后上传到平台，请用获取单号接口查看")
     * @ApiReturnParams   (name="taskid", type="int", required=true, description="单号ID，请保存")
     * @ApiReturnParams   (name="p", type="Double", required=true, description="扣费金额")
     * @ApiReturn   ({"code": 1,"msg": "提交成功","time": "1540347545","data": {"express_no": "0","taskid": "98736","p": "3.0"}})
     */
    public function order()
    {
        $goodsid = $this->request->request('goodsid');
        $goods_info = model('goods')->where('id',$goodsid)->where('is_out',1)->find();//获取商品记录
        if(!$goods_info){ $this->error('礼品不存在或已经下架');}
        $storesid = $this->request->request('storesid');
        $store_info = Db::name('send_store')->where('id',$storesid)->where('express_id',1)->find();//获取仓库记录
        if(!$store_info){ $this->error('仓库不存在');}

        $num = $this->request->request('num')?$this->request->request('num'):1;
        $sname = $this->request->request('receiver_name');//收货人
        $sphone = $this->request->request('receiver_phone');//收货电话
        $sprovince = $this->request->request('receiver_province');//发省
        $scity = $this->request->request('receiver_city');//发市
        $sdistrict = $this->request->request('receiver_district');//发件区
        $saddress = $this->request->request('receiver_address');//收货地址
        $sendname = $this->request->request('sendname')?$this->request->request('sendname'):$this->auth->username;
        $sendphone = $this->request->request('sendphone')?$this->request->request('sendphone'):$this->auth->mobile;
        $send_order_no = $this->request->request('send_order_no');//订单号
        $out_order_no= $send_order_no ? $send_order_no : 'k'.time().mt_rand(1,100000);//唯一识别号
        $isorder = \app\common\model\Express::where('out_order_no', $out_order_no)->find();
        $userid = $this->auth->id;
        $express_price = get_price($this->auth->level,1);//获取快递价格
        $usermoney = $this->auth->money;//用户余额
        $total_price = number_format($num*$goods_info['apiprice']+$express_price,2);//商品总价
        if($usermoney < $total_price){
            // $ret =  \app\common\library\Sms::notice('15850734565', '15850734565', '360996');
            $this->error('系统余额不足,联系在线客服！',['money'=>$usermoney],110);
        }
        if ($isorder)
        {
            $this->error('订单号:【'.$out_order_no.'】已经存在,请不要重复提交！单号为：'.$isorder['express_no'].'',['express_no'=>$isorder['express_no'],'taskid'=>$isorder['id']],111);
        }
        $father_code = get_father_code($goodsid,1);//获取主站商品编号
        //组装数据请求圆通接口
        $post_info = [
            'accessToken'=>config('get_yto_config.token'),
            'storehouseCode'=>$store_info['send_code'],//发货仓编号
            'goodsCode'=>$father_code,//商品编号
            'receiver'=>$sname,//收件人
            'receiverPhone'=>$sphone,//收件人手机号
            'receiverProvinceName'=>$sprovince,//收件人省份
            'receiverCityName'=>$scity,//收件人城市
            'receiverAreaName'=>$sdistrict,//收件人区
            'receiverAddress'=>$saddress,//收件人详细地址
            'thirdOrderNo'=>$out_order_no,//第三方单号
            'goodsNum'=>$num,//购买数量
            'shipperName'=>$sendname,//发件人
            'shipperPhone'=>$sendphone
        ];
        $json=sendRequest(config('get_yto_config.apiurl').'/api/createOrder/merchantCreateOrder',$post_info,'post');
        $return = json_decode($json,1);
        $express_no = 0;
        if($return['code']===0){
            $recive_addr = $sprovince.' '.$scity.' '.$sdistrict.' '.$saddress;//收件地址
            $taskid = $return['data']['recordId'];
            $kdcb = 2.3;
            $result = [
                'user_id'=>$userid,
                'express_no'=>$express_no,
                'out_order_no'=>$out_order_no,
                'expressid'=>1,
                'price'=>$total_price,
                'num'=>$num,
                'goods'=>$goodsid,
                'addressee'=>$sname,
                'a_mphone'=>$sphone,
                'all_address'=>$recive_addr,
                'sender'=>$store_info['store_name'],
                'from'  => 0,
                'taskid'=>$taskid,
                'cprice'=>$goods_info['cprice']+$kdcb
            ];
            $express = model('express');
            $express->save($result);
            \app\common\model\User::score($score=0,'-'.$total_price,$userid,'api接口下单，编号'.$express->id);
            $this->success('提交成功', ['express_no' => $express_no,'taskid' => $express->id,'p' => $total_price]);
        }else{
            $this->error($return['msg']);
        }
    }
    /**
     * 获取快递单号
     * @ApiTitle    (获取快递单号)
     * @ApiSummary  (获取快递单号)
     * @ApiMethod   (POST)
     * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token，注：token值请放在headers")
     * @ApiParams   (name="taskid", type="int", required=true, description="单号ID，下单时候返回的taskid")
     * @ApiReturnParams   (name="code", type="integer", required=true, description="状态值：1 为成功")
     * @ApiReturnParams   (name="msg", type="string", required=true, description="返回成功")
     * @ApiReturnParams   (name="time", type="string", required=true, description="响应的时间戳")
     * @ApiReturnParams   (name="data", type="object", description="返回业务数据合集")
     * @ApiReturn   ({"code": 1,"msg": "获取单号成功","time": "1540347545","data": {"express_no": "82081082712","taskid": "98736"}})
     */
    public function get_express()
    {
        $taskid = $this->request->request('taskid');
        $exinfo = model('express')->where('id',$taskid)->where('expressid',1)->find();
        $recordId = $exinfo['taskid'];
        if($exinfo['express_no']){$this->success('重新获取单号',['taskid'=>$taskid,'express_no'=>$exinfo['express_no']]);}
        if($recordId){
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
                $this->error('人工打单，10分钟左右更新单号,请稍后!');
            }
        }else{
            $this->error('非法操作!');
        }
    }
    /**
     * 查询余额
     * @ApiTitle    (查询余额)
     * @ApiSummary  (获取账号余额)
     * @ApiMethod   (POST)
     * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token，注：token值请放在headers")
     * @ApiReturnParams   (name="code", type="integer", required=true, description="状态值：1 为成功")
     * @ApiReturnParams   (name="msg", type="string", required=true, description="返回成功")
     * @ApiReturnParams   (name="time", type="string", required=true, description="响应的时间戳")
     * @ApiReturnParams   (name="data", type="object", description="返回业务数据合集")
     * @ApiReturnParams   (name="usermoney", type="string", required=true, description="账号所剩余额")
     * @ApiReturn   ({"code": 1,"msg": "","time": "1540347545","data": {"usermoney":88.88}})
     */
    public function getmoney()
    {
        //获取业务参数
        $this->success('返回成功', ['usermoney' => $this->auth->money]);
    }
}
