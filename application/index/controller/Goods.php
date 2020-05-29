<?php
namespace app\index\controller;
use app\common\controller\Frontend;
use app\common\library\Token;
use think\Db;
use think\Request;
/**
 * 礼品管理
 */
class Goods extends Frontend
{

    protected $layout = 'default';
    protected $noNeedLogin = ['lists', 'detail'];
    protected $noNeedRight = ['*'];
    public function _initialize()
    {
        parent::_initialize();
		$auth = $this->auth;
         
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
     *礼品中心                  
     */
    public function lists()
    {  
    	$sort = input('sort');
		if($sort == 'price'){$sorts = 'price asc';$activ = '1';}else{$sorts = 'id desc';$activ = '0';}
		$data = model('goods')->where('is_out',1)->order($sorts)->select();
        $this->assign('goodslist',$data);
		$this->assign('activ',$activ);
    	$this->assign('title','礼品中心');
		$this->assign('keywords','礼品代发,礼物代发,ab单发礼品');
		$this->assign('description','代发礼品中心,汇集精准全面的礼品代发信息,价格低廉，全国多仓发货，一件代发');
    	return $this->view->fetch();
    }
   /**
     *礼品中心                  
     */
    public function detail(Request $request)
    {
        if($request->isPost()){
            $gid = input('gid');
            $gnum = input('num');
            $this->redirect('goods/order', array('gid'=>$gid,'num'=>$gnum));
        }
		$id = input('gid');
        $data = model('goods')->where('id',$id)->find();
		if(!$data){$this->error("您查找的礼品不存在，请核实后操作");}
        $this->assign('data',$data);
    	$this->assign('title',$data['name'].'礼品代发,价格低至'.$data['price'].'元');
		$this->assign('keywords',$data['name'].'礼品代发,ab单发礼品');
		$this->assign('description',$data['name'].'礼品代发,全国多个仓库发货，支持一件代发，价格低至'.$data['price'].'元');
		$rands = model('goods')->orderRaw('rand()')->limit(6)->select();
        $this->assign('rands',$rands);
		return $this->view->fetch();
	}
    /**
     * 商品下单
     */
    public function order(Request $request) 
    {
        if($request->isPost()){
            $num = input('post.num');//获取商品数量
            $id = input('post.gid');//获取商品id
            $issc = input('post.issc');//是否收藏
            $addr_list = input('post.addstext');
            $store_id = input('post.store_id');//获取仓库编号
            $store_info = Db::name('send_store')->where('id',$store_id)->find();//获取仓库记录
            $is_multiple = $store_info['is_multiple'];
            if($is_multiple==0){
                $num = 1;
            }
            $express_id = $store_info['express_id'];//快递编号
            $address_arr = explode("\r\n",$addr_list);//将收件人信息转为数组
            $uid = $this->auth->id;//获取用户id
            $uname = $this->auth->fren?$this->auth->fren:$this->auth->username;
            $uphone = $this->auth->fhao?$this->auth->fhao:$this->auth->mobile;
            $yu_money = $this->auth->money;//获取用户余额
            $level = $this->auth->level;//获取用户会员等级
            $express_price = get_price($level,$express_id);//获取快递价格
            $goods_info = model('goods')->where('id',$id)->where('is_out',1)->find();//获取商品记录
            if(!$goods_info){ $this->error('礼品不存在或已经下架，请核实！');}
            $weight = $goods_info['weight'];
            $goods_price = $goods_info['price'];//获取商品单价
            $total_fee = $num*$goods_price+$express_price;//计算总价
            $send_store = $store_info['send_code'];//获取发货仓编号
            $store_name = $store_info['store_name'];//获取发货仓name
            $father_code = get_father_code($id,$express_id);//获取主站商品编号
            $addr_num = count($address_arr);
            if(!$address_arr[$addr_num-1]){$this->error('不要有空的一行或者最后一行不要为空');}
            $uptime = time();//用于判断插入记录的唯一时间戳
            foreach ($address_arr as $key => $value) {
                if($yu_money<$total_fee){
                    $this->error('只购买了'.$key.'个礼品，其他余额不足无法购买,',url('/Express/exlist/tableid/'.$uptime));
                }
                $lists = str_replace(",","，",$value);
                $list = explode('，',$lists);
                $receiver = $list[0];//获取收件人
                $receiverPhone = $list[1];//获取收件人手机号
                if(!array_key_exists(2, $list)){
                    $this->error('第'.($key+1).'行收货地址不能为空');
                }
                if(empty($list[2])){
                    $this->error('第'.($key+1).'行数据异常,请输入收件地址');
                }
                $tradeNo = md5(date('Ymdh').$uid.$express_id.$receiver.$receiverPhone.$list[2]);//自定义交易单号
                $express_info = model('express')->where('out_order_no',$tradeNo)->find();
                if($express_info){
                    $this->error('第'.($key+1).'行地址已经存在, 请勿重复提交');
                }
                $recive_addr = $list[2];//获取收货地址
                $recive_addr = explode(' ',$recive_addr);
                $recive_addr = array_filter($recive_addr);
                $recive_addr = array_values($recive_addr);
                if(count($recive_addr)<4){
                    $this->error('第'.($key+1).'行收货地址格式中省、市、区或县之间应该用空格隔开，请仔细检查！');
                }
                $recive_addr_num = count($recive_addr);
                $detail_addr = '';
                for($i=3;$i<$recive_addr_num;$i++){
                    $detail_addr = $detail_addr.$recive_addr[$i];
                    unset($recive_addr[$i]);
                }
                $recive_addr[3] = $detail_addr;
            //判断快递类型 选择接口
            switch ($express_id) {
                case 1:
                    //yt接口请求数据
                    $post_info = [
                        'accessToken'=>config('get_yto_config.token'),
                        'storehouseCode'=>$send_store,'goodsCode'=>$father_code,
                        'receiver'=>$receiver,'receiverPhone'=>$receiverPhone,
                        'receiverProvinceName'=>$recive_addr[0],'receiverCityName'=>$recive_addr[1],
                        'receiverAreaName'=>$recive_addr[2],'receiverAddress'=>$recive_addr[3],
                        'thirdOrderNo'=>$tradeNo,'goodsNum'=>$num,'shipperName'=>$uname,'shipperPhone'=>$uphone
                    ];
                    $json = sendRequest(config('get_yto_config.apiurl').'/api/createOrder/merchantCreateOrder',$post_info,'POST');
                    $return = json_decode($json,1);
                    $all_address = $recive_addr[0].' '.$recive_addr[1].' '.$recive_addr[2].' '.$recive_addr[3];
                    $code = $return['code'];
                    if($code==0){
                        $return_data = $return['data'];
                        $taskid = $return_data['recordId'];
                        //处理成功时的业务逻辑
                        $result = [];
                        $result[$key] = [
                            'user_id'=>$uid,
                            'express_no'=>'0',
                            'out_order_no'=>$tradeNo,
                            'expressid'=>$express_id,
                            'weight'=>$weight,
                            'price'=>$total_fee,
                            'num'=>$num,
                            'goods'=>$id,
                            'addressee'=>$receiver,
                            'a_mphone'=>$receiverPhone,
                            'all_address'=>$all_address,
                            'taskid'=>$taskid,
                            'tableid'=>$uptime,
                            'sender'=>$store_name,
                            'cprice'=>$goods_info['cprice']+2.3
                        ];
                        //更新用户信息
                        $retu = model('express')->saveAll($result);
                        $yu_money = $yu_money-$total_fee;
                        \app\common\model\User::score($score=0,'-'.$total_fee,$uid,'购买礼品，编号'.$retu[$key]['id']);
                    }else{
                        $this->error($return['msg']);
                    }
                    break;
                case 4:
                    //韵达接口请求数据
                    $recive_addr = $recive_addr[0].' '.$recive_addr[1].' '.$recive_addr[2].' '.$recive_addr[3];
                    $orderParams = [];
                    $orderParams[0] = [
                        'apiOrderId'=>$tradeNo,//订单唯一标识
                        'buyerName' =>$receiver,//收件人
                        'buyerMobile'=>$receiverPhone,//收件人手机号
                        'buyerAddr'=>$recive_addr,//收件地址
                        'storeType'=>$send_store,//发货仓编号
                        'kuaidiName'=>'yunda'
                    ];
                    $orderParams = json_encode($orderParams);
                    $post_info = [
                        'partnerId'   =>config('get_yunda_config.partnerid'),//合作商ID
                        'itemId'      =>$father_code,//韵达礼品对应商品编号
                        'orderParams' =>$orderParams,//订单信息
                        'validation'   =>md5($father_code.$orderParams.config('get_yunda_config.partnerid').config('get_yunda_config.token'))
                    ];
                    $json = sendRequest(config('get_yunda_config.apiurl').'/ApiOrder/orderGift',$post_info,'post');
                    $return  = json_decode($json,1);
                    if($return['result']===1){
                        $return_info = $return['orders'][0];
                        $out_order_no = $return_info['apiOrderId'];//订单唯一标识
                        $express_no = $return_info['expressNo'];//快递面单号
                        // 处理成功时的业务逻辑
                        $result = [];
                        $result[$key] = [
                            'user_id'=>$uid,
                            'express_no'=>$express_no,
                            'out_order_no'=>$out_order_no,
                            'expressid'=>$express_id,
                            'weight'=>$weight,
                            'price'=>$total_fee,
                            'num'=>$num,
                            'goods'=>$id,
                            'addressee'=>$receiver,
                            'a_mphone'=>$receiverPhone,
                            'all_address'=>$recive_addr,
                            'taskid'=>0,
                            'tableid'=>$uptime,
                            'sender'=>$store_name,
                            'cprice'=>$goods_info['cprice']+2.6
                        ];
                        //更新用户信息
                        $retu = model('express')->saveAll($result);
                        $yu_money = $yu_money-$total_fee;
                        \app\common\model\User::score($score=0,'-'.$total_fee,$uid,'购买礼品，编号'.$retu[$key]['id']);
                    }else{
                        $this->error($return['message']);
                    }
                    break;
                default:
                    $this->error('没有找到对应的快递');
                    break;
                }
            }
            if($issc==1){
                $sc_info = Db::name('favorite')->where('goods_id',$id)->where('user_id',$uid)->where('store_id',$store_id)->find();
                if(!$sc_info){
                    $scdata = ['goods_id' => $id,'user_id' => $uid,'store_id' => $store_id,'goods_num' => $num,'goods_image' => $goods_info['goods_image'],'store_name' => $store_name,'goods_name' => $goods_info['name'],'create_time' => time()];
                    Db::name('favorite')->insert($scdata);
                }
            }
            $this->success("发布成功".($key+1)."条",url('Express/exlist'));
        }
        $id = input('gid');
        $sid = input('sid');
        $num = input('num')?input('num'):1;
        $data = model('goods')->where('id',$id)->where('is_out',1)->find();
        if(!$data){$this->error("礼品不存在或已经下架！");}
        $express_codes = $data['express_codes'];
        $express_codes = json_decode($express_codes,1);
        $expressid = [];
        foreach ($express_codes as $key => $value) {
            $expressid[] = $key;
        }
        $swhere['express_id']=array('in',$expressid);
        if($sid){$swhere['id']=$sid;}
        $stores = Db::name('send_store')->where($swhere)->order('express_id','desc')->select();
        if(!$stores){ $this->error('获取仓库有误！');}
        $this->assign('stores',$stores);
        $this->assign('primary_key',$stores[0]);
        $this->assign('data',$data);
        $this->assign('id',$id);
        $this->assign('sid',$sid);
        $this->assign('num',$num);
        $this->assign('title',$data['name']);
        return $this->view->fetch();
    }
    /**
     * 批量上传
     */
    public function uploads(Request $request)
    {
        set_time_limit(0);
        if($request->isPost()){
            $file =input('post.avatar');
            if(!$file){
                $this->error('请上传需要导入的表格！支持csv,xls,xlsx格式！');
            }
            $filePath = ROOT_PATH . DS . 'public' . DS . $file;
            if (!is_file($filePath)) {
                $this->error('上传的表格不存在，请核实');
            }
            $PHPReader = new \PHPExcel_Reader_Excel2007();
            if (!$PHPReader->canRead($filePath)) {
                $PHPReader = new \PHPExcel_Reader_Excel5();
                if (!$PHPReader->canRead($filePath)) {
                    $PHPReader = new \PHPExcel_Reader_CSV();
                    $PHPReader->setInputEncoding('GBK');
                    if (!$PHPReader->canRead($filePath)) {
                        $this->error(__('Unknown data format'));
                    }
                }
            }
            $uptime = time();//用于判断插入记录的唯一时间戳
            $num = input('post.num');//获取商品数量
            $id = input('post.gid');//获取商品id
            $uid = $this->auth->id;//获取用户id
            $uname = $this->auth->fren?$this->auth->fren:$this->auth->username;
            $uphone = $this->auth->fhao?$this->auth->fhao:$this->auth->mobile;
            $yu_money = $this->auth->money;//获取用户余额
            $level = $this->auth->level;//获取用户会员等级
            $store_id = input('post.store_id');//获取仓库编号
            $store_info = Db::name('send_store')->where('id',$store_id)->find();//获取仓库记录
            $is_multiple = $store_info['is_multiple'];
            if($is_multiple==0){
                $num = 1;
            }
            $express_id = $store_info['express_id'];//快递编号
            $express_price = get_price($level,$express_id);//获取快递价格
            $goods_info = model('goods')->where('id',$id)->find();
            if(!$goods_info){ $this->error('礼品不存在或已经下架，请核实！');}
            $weight = $goods_info['weight'];
            $goods_price = $goods_info['price'];//获取商品单价
            $total_fee = $num*$goods_price+$express_price;//计算总价
            $send_store = $store_info['send_code'];//获取发货仓编号
            $store_name = $store_info['store_name'];//获取发货仓name
            $father_code = get_father_code($id,$express_id);//获取主站商品编号
            $PHPExcel = $PHPReader->load($filePath); //加载文件
            $currentSheet = $PHPExcel->getSheet(0);  //读取文件中的第一个工作表
            $allRow = $currentSheet->getHighestRow(); //取得一共有多少行
            $pttype = $currentSheet->getCell("A1")->getValue();//是什么平台表格
            $leijia = '';
            $continue_num = 0;
            for($i=2;$i<=$allRow;$i++){
                if($yu_money < $total_fee){
                    $upinfo=Db::name('upinfo');
                    $info['user_id'] = $uid;
                    $info['exnum'] = $allRow-1;//要插入数据库的总记录数
                    $info['oknum'] = $i-2;//成功插入的数量
                    $info['tableid'] = $uptime;//此次任务的id
                    $info['expressid'] = $express_id;
                    $info['vars'] = '余额不足';
                    $upinfo->insert($info);
                    $this->error("余额不足，成功发布了".($i-2)."条记录",url('Express/tblist'));
                }
                if($pttype=='订单编号'){//淘宝
                    $out_order_no = $currentSheet->getCell("A".$i)->getValue();//订单号
                    $address_ren =  $currentSheet->getCell("O".$i)->getValue();//收件人
                    $address_array = preg_replace('/(，)|(,)/','',$currentSheet->getCell("P".$i)->getValue());//收货地址
                    $address_hao = preg_replace('/\W/','',$currentSheet->getCell("S".$i)->getValue());//号码
                }else if($pttype=='商品'){//拼多多
                    $out_order_no =  $currentSheet->getCell("B".$i)->getValue();
                    $address_ren =  $currentSheet->getCell("O".$i)->getValue();//收件人
                    $address_hao = preg_replace('/\W/','',$currentSheet->getCell("P".$i)->getValue());//收件号码
                    $add_s =  $currentSheet->getCell("R".$i)->getValue();//省
                    $add_c =  $currentSheet->getCell("S".$i)->getValue();//市
                    $add_a =  $currentSheet->getCell("T".$i)->getValue();//区
                    $add_d =  $currentSheet->getCell("U".$i)->getValue();//地址
                    $address_array = $add_s.' '.$add_c.' '.$add_a.' '.$add_d;//收件地址
                }else if($pttype=='订单号'){//模板
                    $out_order_no =  $currentSheet->getCell("A".$i)->getValue();
                    $address_array = preg_replace('/(，)|(,)/','',$currentSheet->getCell("B".$i)->getValue());//收件地址
                    $address_ren =  $currentSheet->getCell("C".$i)->getValue();//收件人
                    $address_hao = preg_replace('/\W/','',$currentSheet->getCell("D".$i)->getValue());//收件号码
                }else{
                    $this->error('提交的表格格式错误，请按照导入说明操作！');
                }
                $out_order_no = replaceSpecialChar($out_order_no);
                $isorder = \app\common\model\Express::where('out_order_no', $out_order_no)->find();
                if ($isorder)
                {
                    $leijia = $leijia.'第'.$i.'行地址订单号'.$isorder['out_order_no'].'重复,';
                    continue;
                }
                if($address_array=='' || $out_order_no=='')
                {
                    $leijia = $leijia.'第'.$i.'行收件信息或订单号不能为空,';
                    continue;
                }
                $addrlist = explode(" ",$address_array);
                $addrlist = array_filter($addrlist);
                $addrlist = array_values($addrlist);
                if(count($addrlist)<4)
                {
                    $leijia = $leijia.'第'.$i.'行收货地址不合理,';
                    continue;
                }
                switch ($express_id) {
                    case 1:
                        //接口请求参数
                        $post_info = [
                            'accessToken'=>config('get_yto_config.token'),
                            'storehouseCode'=>$send_store,'goodsCode'=>$father_code,
                            'receiver'=>$address_ren,'receiverPhone'=>$address_hao,
                            'receiverProvinceName'=>$addrlist[0],'receiverCityName'=>$addrlist[1],
                            'receiverAreaName'=>$addrlist[2],'receiverAddress'=>$addrlist[3],
                            'thirdOrderNo'=>$out_order_no,'goodsNum'=>$num,'shipperName'=>$uname,'shipperPhone'=>$uphone
                        ];
                        //请求接口
                        $json = sendRequest(config('get_yto_config.apiurl').'/api/createOrder/merchantCreateOrder',$post_info,'POST');
                        $return = json_decode($json,1);
                        $code = $return['code'];
                        if($code==0){
                            $return_data = $return['data'];
                            $taskid = $return_data['recordId'];
                            //处理成功时的业务逻辑
                            $result = [];
                            $result[$i] = [
                                'user_id'=>$uid,
                                'express_no'=>'0',
                                'out_order_no'=>$out_order_no,
                                'expressid'=>$express_id,
                                'weight'=>$weight,
                                'price'=>$total_fee,
                                'num'=>$num,
                                'goods'=>$id,
                                'addressee'=>$address_ren,
                                'a_mphone'=>$address_hao,
                                'all_address'=>$address_array,
                                'taskid'=>$taskid,
                                'sender'=>$store_name,
                                'tableid'  => $uptime,
                                'from'  => 1,
                                'cprice'=>$goods_info['cprice']+2.45
                            ];
                            //更新用户信息
                            $retu = model('express')->saveAll($result);
                            $yu_money = $yu_money-$total_fee;
                            \app\common\model\User::score($score=0,'-'.$total_fee,$uid,'购买礼品，编号'.$retu[$i]['id']);
                            $continue_num = $continue_num+1;
                        }else{
                            $this->error($return['msg']);
                        }
                        break;
                    case 4:
                        //接口请求数据
                        $orderParams = [];
                        $orderParams[0] = [
                            'apiOrderId'=>$out_order_no,//订单唯一标识
                            'buyerName' =>$address_ren,//收件人
                            'buyerMobile'=>$address_hao,//收件人手机号
                            'buyerAddr'=>$address_array,//收件地址
                            'storeType'=>$send_store,//发货仓编号
                            'kuaidiName'=>'yunda'
                        ];
                        $orderParams = json_encode($orderParams);
                        $post_info = [
                            'partnerId'   =>config('get_yunda_config.partnerid'),//合作商ID
                            'itemId'      =>$father_code,//韵达礼品对应商品编号
                            'orderParams' =>$orderParams,//订单信息
                            'validation'   =>md5($father_code.$orderParams.config('get_yunda_config.partnerid').config('get_yunda_config.token'))
                        ];
                        $json = sendRequest(config('get_yunda_config.apiurl').'/ApiOrder/orderGift',$post_info,'post');
                        $return  = json_decode($json,1);
                        if($return['result']===1){
                            $return_info = $return['orders'][0];
                            $out_order_no = $return_info['apiOrderId'];//订单唯一标识
                            $express_no = $return_info['expressNo'];//快递面单号
                            //处理成功时的业务逻辑
                            $result = [];
                            $result[$i] = [
                                'user_id'=>$uid,
                                'express_no'=>$express_no,
                                'out_order_no'=>$out_order_no,
                                'expressid'=>$express_id,
                                'weight'=>$weight,
                                'price'=>$total_fee,
                                'num'=>$num,
                                'goods'=>$id,
                                'addressee'=>$address_ren,
                                'a_mphone'=>$address_hao,
                                'all_address'=>$address_array,
                                'sender'=>$store_name,
                                'tableid'  => $uptime,
                                'from'  => 1,
                                'cprice'=>$goods_info['cprice']+2.6
                            ];
                            //更新用户信息
                            $retu = model('express')->saveAll($result);
                            $yu_money = $yu_money-$total_fee;
                            \app\common\model\User::score($score=0,'-'.$total_fee,$uid,'购买礼品，编号'.$retu[$i]['id']);
                            $continue_num = $continue_num+1;
                        }else{
                            $this->error($return['message']);
                        }
                        break;
                    default:
                        # code...
                        break;
                }
            }
            $upinfo=Db::name('upinfo');
            $info['user_id'] = $uid;
            $info['exnum'] = $allRow-1;//要插入数据库的总记录数
            $info['oknum'] = $continue_num;//成功插入的数量
            $info['tableid'] = $uptime;//此次任务的id
            $info['expressid'] = $express_id;
            $info['vars'] = $leijia=='' ?'全部导入成功':$leijia;
            $upinfo->insert($info);
            $this->success("发布成功".($continue_num)."条",url('Express/exlist'));
        }
        $id = input('gid');
        $sid = input('sid');
        $num = input('num')?input('num'):1;
        $data = model('goods')->where('id',$id)->where('is_out',1)->find();
        if(!$data){$this->error("礼品不存在或已经下架！");}
        $express_codes = $data['express_codes'];
        $express_codes = json_decode($express_codes,1);
        $expressid = [];
        foreach ($express_codes as $key => $value) {
            $expressid[] = $key;
        }
        $swhere['express_id']=array('in',$expressid);
        if($sid){$swhere['id']=$sid;}
        $stores = Db::name('send_store')->where($swhere)->order('express_id','desc')->select();
        if(!$stores){ $this->error('获取仓库有误！');}
        $this->assign('primary_key',$stores[0]);
        $this->assign('stores',$stores);
        $this->assign('data',$data);
        $this->assign('id',$id);
        $this->assign('sid',$sid);
        $this->assign('num',$num);
        $this->assign('title',$data['name']);
        return $this->view->fetch();
    }
    /**
     * 获取动态快递价格
     */
    public function express_price()
    {
        $store_id = input('store_id');
        $store_info = Db::name('send_store')->where('id',$store_id)->find();
        $expressid = $store_info['express_id'];//快递编号
        $is_multiple = $store_info['is_multiple'];//是否支持多数量
        $express_price = get_price($this->auth->level,$expressid);
        $return = ['code'=>1,'express_price'=>$express_price,'expressid'=>$expressid,'is_multiple'=>$is_multiple];
        return $return;
    }
    /**
     * 收藏
     */
    public function favorite()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        $this->relationSearch = true;
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = Db::name('favorite')
                ->where($where)
                ->order($sort, $order)
                ->count();
            $list = Db::name('favorite')
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        $this->assign('title', '商品收藏中心');
        return $this->view->fetch();
    }
}
