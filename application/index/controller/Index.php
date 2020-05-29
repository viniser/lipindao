<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use app\common\library\Token;
use think\Db;
use fast\Http;
class Index extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {  
		$goodslist = Db::name('goods')->where('is_out',1)->order('id desc')->limit(8)->select();
        $newslist = Db::name('news')->where('category_id',1)->order('id desc')->limit(8)->select();
        $glist = Db::name('news')->where('category_id',0)->order('id desc')->limit(8)->select();
        $this->view->assign('newslist', $newslist);
        $this->view->assign('glist', $glist);
		$this->view->assign('goodslist', $goodslist);
		return $this->view->fetch();	
    }
  public function demo()
    {
		 $token = '664033ae9f41dc4955849c5c';//初始化一个token
	     $t = time();//getMillisecond();
		 $g = '12574478';
		 $data = '{"q":"鞋字","sst":"1","n":20,"buying":"buyitnow","m":"api4h5","token4h5":"","abtest":"15","wlsort":"15","sort":"_sale","page":1}';
		 $sign = md5($token.'&'.$t.'&'.$g.'&'.$data);  
 		 $ourl = 'https://acs.m.taobao.com/h5/mtop.taobao.wsearch.h5search/1.0/?jsv=2.3.16&appKey=12574478&t='.$t.'&sign='.$sign.'&api=mtop.taobao.wsearch.h5search&v=1.0&H5Request=true&ecode=1&type=jsonp&dataType=jsonp&callback=mtopjsonp1&data='.UrlEncode($data).'';	
		//请求ourl 这次请求主要是为了获取淘宝返回head里的cookie信息。
		$ch = curl_init($ourl); //初始化 
		curl_setopt($ch,CURLOPT_HEADER,1); //将头文件的信息作为数据流输出 
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); //返回获取的输出文本流 
		curl_setopt($ch,CURLOPT_COOKIE,'cookie2=3362c77b37f90f4ba7f3e5964fcfa025;_m_h5_tk=c8da7c6531079f647241184f3d2b7e80_1545906313609; _m_h5_tk_enc=236d970100c8da9eeb2ee61d6ad3502a');
		$string = curl_exec($ch); 
		preg_match_all('/FAIL_SYS_TOKEN_EMPTY::(.*)"]/i', $string, $bodys); 
		echo $string;
		exit();
		if($bodys[1][0]=='令牌为空'){//出现令牌为空，说明请求没有cookie。然后我们在刚才请求返回的结果里面获取cookie
		preg_match_all('/Set-Cookie: _m_h5_tk=(.*)_/i', $string, $results); //获取新token，就是返回结果里面的_m_h5_tk=79e96b776b6edb9cae135321ff67c45a_1545905212345;这个字段。注意：这个token要去除_1545905212345这个字段
		preg_match_all('/Set-Cookie: (.*);Path=/i', $string, $allcook); //获取cookie，用于下一次请求用
	 
		 $token1 = $results[1][0];//得到最新的token
		 $sign1 = md5($token1.'&'.$t.'&'.$g.'&'.$data);//重新计算签名
		 $params = [];
		 $url = 'https://acs.m.taobao.com/h5/mtop.taobao.wsearch.h5search/1.0/?jsv=2.3.16&appKey=12574478&t='.$t.'&sign='.$sign1.'&api=mtop.taobao.wsearch.h5search&v=1.0&H5Request=true&ecode=1&type=jsonp&dataType=jsonp&callback=mtopjsonp1&data='.UrlEncode($data).'';	
		$cok = $allcook[1][0].';'.$allcook[1][1].';'.$allcook[1][2];//组装cookie，最终请求格式：cookie2=3362c77b37f90f4ba7f3e5964fcfa025; _m_h5_tk=c8da7c6531079f647241184f3d2b7e80_1545906313609; _m_h5_tk_enc=236d970100c8da9eeb2ee61d6ad3502a
       //再次请求淘宝url。这次请求要带上cookie
			$ch =curl_init();
		   curl_setopt($ch,CURLOPT_URL,$url);
		   $header = array();
		//curl_setopt($ch,CURLOPT_POST,true);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		  curl_setopt($ch,CURLOPT_RETURNTRANSFER,0);
		  curl_setopt($ch,CURLOPT_HEADER,0);
	   	  curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
		  curl_setopt($ch,CURLOPT_COOKIE,$cok);//把第一次请求获取到的cookie放在这里
		  $content = curl_exec($ch); 
		  echo $content;
			
			}else{
			echo '未知错误';
		}
    }
public function sendco()
{

}
    public function news($ids = NULL)
    {
		$data = Db::name('news')->where('id',$ids)->find();
		if(!$data){$this->error("请求数据有误");}
		$newslist = Db::name('news')->order('id desc')->limit(10)->select();
		
		$this->view->assign('data', $data);
		$this->view->assign('newslist', $newslist);
        return $this->view->fetch();
    }

}
