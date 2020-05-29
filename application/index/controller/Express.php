<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use think\Config;
use think\Cookie;
use think\Hook;
use think\Session;
use think\Validate;
use think\Db;


/**
 * 单号管理
 */
class Express extends Frontend
{

    protected $layout = 'default';
    protected $noNeedLogin = ['gettoken'];
    protected $noNeedRight = ['*'];
    protected $searchFields = 'express_no,out_order_no,a_mphone';
    protected $model = null;
    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Express');
         
    }

    /**
     * 空的请求
     * @param $name
     * @return mixed
     */
    public function _empty($name)
    {
        Hook::listen("user_request_empty", $name);
        return $this->view->fetch('user/' . $name);
    }

    /**
     * 订单列表
     */
   public function exlist()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        $this->relationSearch = true;
        $this->get_expressno();
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = model('Express')
                ->with(['goods'])
                ->where($where)
                ->order($sort, $order)
                ->count();
            $list = model('Express')
                ->with(['goods'])
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        $this->assign('title', '订单记录');
        return $this->view->fetch();
    }

    //导入记录
    public function ulist()
    {

		//设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = Db::name('upinfo')
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = Db::name('upinfo')
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
		$this->assign('title', '导入记录');
        return $this->view->fetch();
    }
    /**
     *自动发货授权
     */
    public function aoto()
    {
        $this->redirect('http://acs.agiso.com/Authorize.aspx?appId=20190409426234&state=1'.rand('10000000','99999999'));
    }
    /**
     *获取token
     */
    public function gettoken()
    {
        $code= $this->request->request('code');
        $state= $this->request->request('state');
        $ispt = substr($state,0,-8);
        if($ispt == 2){
            $this->redirect('http://www.danhaoku.com/user/gettoken?code='.$code.'&state='.$state.'');
            exit;
        }
        $json = sendRequest('http://acs.agiso.com/Authorize.aspx?code='.$code.'&appId=20190409426234&secret=86ak83wyu8ss5ydc2bgcws5r3bzt95t4&state='.$state.'','','GET');
        $return  = json_decode($json,1);
        $data['user_id']=$this->auth->id;
        $data['taobaonick']=$return['TaobaoUserNick'];
        $data['expires_in']=time()+floor($return['ExpiresIn']);
        $data['token']=$return['Token'];
        $data['createtime']=time();
        Db::name('taobao')->insert($data);
        $this->success("授权成功",url('Express/exlist'));
    }
    /**
     *自动发货
     */
    public function autosend()
    {
        $express=model('express');
        $tableid = input('tableid');
        $map['user_id']= $this->auth->id;
        $map['tableid'] = $tableid;
        $map['express_no'] = ['neq',0];
        $map['from'] = 1;
        $list=$express->where($map)->order('create_time desc')->select();
        $mytaobao =Db::name('taobao')->where('expires_in','>', time())->where('user_id', $this->auth->id)->order('id desc')->select();
        $this->assign("mytaobao",$mytaobao);
        $this->assign('empty','<a href="/express/aoto">还没有店铺授权，请授权需要发货的店铺！</a>');
        $this->assign('title', '自动发货');
        $this->assign('list', $list);
        return $this->view->fetch();
    }
    /**
     *自动发货
     */
    public function sendchek()
    {
        if ($this->request->isPost()) {
            $tbid = $this->request->post('tbid');
            $addstext = $this->request->post('addstext');
            $userid = $this->auth->id;
            $address_arr = explode("\r\n",$addstext);
            $jil =  count($address_arr);
            $sendadd =Db::name('taobao')->where('expires_in','>', time())->where('user_id',$userid)->where('id',$tbid)->find();
            if(!$sendadd){$this->error('店铺不存在或者授权已过期！');}
            $appKey = "20190409426234";
            $appSecret = "86ak83wyu8ss5ydc2bgcws5r3bzt95t4";
            $accessToken = $sendadd['token'];
            $leijia = '';
            foreach($address_arr as $key => $value){
                $v1 = str_replace("，",",",$value);
                $shouarr = explode(",",$v1);
               // print_r($resa);
                //业务参数
                $params['tid'] = $shouarr[0];
                $params['timestamp'] = time();
                $params['outSid'] = $shouarr[1];
                $params['companyCode'] = $shouarr[2];
                $datas[] = array('tid'=>$shouarr[0],'timestamp'=>time(),'outSid'=>$shouarr[1],'companyCode'=>$shouarr[2],'sign'=>taiSign($params,$appSecret));
             //   $params['sign'] = taiSign($params,$appSecret);
            }//forend
            $req = postMulti('http://gw.api.agiso.com/acs/Trade/LogisticsOfflineSend', $datas,$accessToken);
            foreach($req as $k => $val){
                $resa=json_decode($val,true);
                if(!$resa['IsSuccess']){
                    $msginfo[] = array ('order_no' => $datas[$k]['tid'],'ex_no' => $datas[$k]['outSid'],'ErrMsg' => $resa['Data']['ErrMsg']);
                    //$leijia = $leijia.'订单号'.$shouarr[0].'发货失败：'.$resa['Data']['ErrMsg'].'。<br>';
                    continue;
                }else{
                    $msginfo[] = array ('order_no' => $datas[$k]['tid'],'ex_no' => $datas[$k]['outSid'],'ErrMsg' => '发货成功');
                    continue;
                }
            }
           $this->success("提交成功",null,$msginfo);
           // echo $leijia=='' ?'全部发货成功':$leijia;
        }
    }
    /**
     *导出记录
     */
 public function export()
   {
	    $filenames = date("Y-m-d H-i",time());
        header( "Content-type:   application/octet-stream "); 
        header( "Accept-Ranges:   bytes "); 
        header( "Content-Disposition:   attachment;   filename=".$filenames."发货单号.txt "); 
        header( "Expires:   0 "); 
        header( "Cache-Control:   must-revalidate,   post-check=0,   pre-check=0 "); 
        header( "Pragma:   public "); 
        $express=model('express');
        $tableid = input('tableid');
        $map['user_id']= $this->auth->id;
        $map['tableid'] = $tableid;
        $map['express_no'] = ['neq',0];
        $map['from'] = 1;
        $list=$express->where($map)->order('create_time desc')->select();
        if(empty($list)){
            echo "数据为空，目前只能导出表格批量导入的订单";
        }else{
            foreach($list as $val){
                if($val['expressid']==1){
                    $kname = '圆通';
                }elseif($val['expressid']==2){
                    $kname = '中通';
                }elseif($val['expressid']==3){
                    $kname = '申通';
                }elseif($val['expressid']==4){
                    $kname = '韵达';
                }elseif($val['expressid']==5){
                    $kname = '汇通';
                }else{
                    $kname = '未知';
                }
                echo trim($val['out_order_no']).",".$val['express_no'].",".$kname."\r\n";
            }
        }
   }
    /**
     * 获取单号
     */
    public function get_expressno()
    {
        $express = model('express');
        $lists = $express->where(['user_id'=>$this->auth->id,'express_no'=>'0','expressid'=>'1'])->field('taskid')->select();
        foreach ($lists as $key => $value) {
            $post_info['accessToken'] = config('get_yto_config.token');
            $post_info['recordId'] = $value['taskid'];
            //请求接口
            $json = sendRequest(config('get_yto_config.apiurl').'/OpenPlatform/findExpressNo',$post_info,'POST');
            $return = json_decode($json,1);
            if($return['code']==0 && $return['data'][0]['ExpressNo'] !=''){
                $res = $express->save(['express_no'=> $return['data'][0]['ExpressNo']],['taskid' => $return['data'][0]['recordId']]);
            }
        }

    }
    /**
     * 订单详情弹窗
     */
    public function order_detail()
    {
        $eid = input('get.eid');
        $express_info = model('express')->where('id',$eid)->find();//获取订单记录
        $goods_num = $express_info['num'];//订单商品数量
        $goods_id = $express_info['goods'];//订单商品id
        $express_id = $express_info['expressid'];//获取快递编号
        $express_name = get_express_name($express_id);//快递名称
        $goods_info = Db::name('goods')->where('id',$goods_id)->find();//获取该商品记录
        $goods_price = $goods_info['price'];//获取商品的单价
        $ulevel = $this->auth->level;//获取用户等级
        $express_price = get_price($ulevel,$express_id);//获取快递价格
        $order_detail = [
            'goods_num'=>$goods_num,'goods_price'=>$goods_price,'express_price'=>$express_price,
            'goods_name'=>$goods_info['name'],'store_name'=>$express_info['sender'],'total_price'=>$express_info['price'],'express_name'=>$express_name
        ];
        $this->assign('order_info',$order_detail);
          $this->view->engine->layout(false);
          return $this->view->fetch();
    }
    /**
     * 地址列表
     */
   public function adds()
    {
		//设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = Db::name('address')
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = Db::name('address')
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
		$this->assign('title', '发货地址');
        return $this->view->fetch();
    }
    public function newadds(){
	        $fajianren = $this->request->request('fajianren');
			$dianpu = $this->request->request('dianpu');
			$shouji = $this->request->request('shouji');
			$ismr = $this->request->request('ismr');
			$rowqu = $this->request->request('rowqu');
			$address = $this->request->request('address');
		    $alladds = explode('/',$rowqu);
	
	$ismrList = ['0' => __('Ismr 0'),'1' => __('Ismr 1')];
	$this->view->assign("ismrList", $ismrList);
	$this->view->engine->layout(false);
	if ($this->request->isAjax()) {
		        $data['user_id']=$this->auth->id;
		        $data['fajianren']=$fajianren;
				$data['dianpu']=$dianpu;
				$data['shouji']=$shouji;
				$data['ismr']=$ismr;
				$data['a_province']=$alladds[0];
				$data['city']=$alladds[1];
				$data['area']=$alladds[2];
				$data['address']=$address;
		        $data['create_time']=time();
               Db::name('address')->insert($data);
            $this->success("添加成功");
        }
	return $this->view->fetch();
}	
    public function editdds($ids = NULL){
		    $fajianren = $this->request->request('fajianren');
			$ismr = $this->request->request('ismr');
			$rowqu = $this->request->request('rowqu');
			$address = $this->request->request('address');
		    $alladds = explode("/",$rowqu);
        $row= Db::name('address')->where('id', $ids)->where('user_id', $this->auth->id)->find();
		if (!$row)
            $this->error(__('No Results were found'));
	    $ismrList = ['0' => __('Ismr 0'),'1' => __('Ismr 1')];
		
	    $this->view->assign("row", $row);
		$this->view->assign("ismrList", $ismrList);
		$this->view->engine->layout(false);
		if ($this->request->isAjax()) {
			 
			Db::name('address')->where('id', $ids)->update(['fajianren' => $fajianren,'address' => $address,'ismr' => $ismr,'a_province' =>$alladds[0],'city' => $alladds[1],'area' => $alladds[2]]);
            $this->success("修改成功");
        }
		return $this->view->fetch();
		
		  
    } 
 
}
