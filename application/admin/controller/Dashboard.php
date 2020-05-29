<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Config;
use fast\Http;

/**
 * 控制台
 *
 * @icon fa fa-dashboard
 * @remark 用于展示当前系统中的统计数据、统计报表及重要实时数据
 */
class Dashboard extends Backend
{

    /**
     * 查看
     */
    public function index()
    {
        $seventtime = \fast\Date::unixtime('day');
        $paylist = [];
        $tid = input('tid');
        if($tid){$where['expressid']=$tid;}
        for ($i = 0; $i < 3; $i++)
        {
			$stime = \fast\Date::unixtime('day', -1*$i);
			$etime = \fast\Date::unixtime('day', -1*$i)+86399;
			$day = date("Y-m-d", $seventtime - ($i * 86400));
			 $where['create_time']=array('between',''.$stime.','.$etime.'');
			 $counts = \app\common\model\Express::where($where)->count();//每天单数
			 $countsum = \app\common\model\Express::where($where)->sum('price');//每天金额
			 $countavg = \app\common\model\Express::where($where)->avg('price');//每天平均
            $totalcb = \app\common\model\Express::where($where)->sum('cprice');//每天支出
            $paylist[] = array("days " => $day, "counts " => $counts, "countsum"=>$countsum, "totalcb"=>$totalcb,"countavg"=>round($countavg,2));
        }
		$moenysum = \app\common\model\Express::sum('price');//总消耗金额
		$usermoenysum = \app\common\model\User::sum('money');//总可用余额
		$usercount = \app\common\model\User::count();//会员数
        //获取圆通余额
        $data = array('accessToken'=> config('get_yto_config.token'));
        $req =Http::post(config('get_yto_config.apiurl').'/api/inquireBalance/getBusinessUserBalance', $data);
        $result=json_decode($req,true);
        //韵达余额
        $kongbao_data=array();
        $kongbao_data['partnerId'] = config('get_yunda_config.partnerid');
        $kongbao_data['md5Secret'] = md5(config('get_yunda_config.token'));
        $ydresult = Http::post(config('get_yunda_config.apiurl').'/ApiOrder/getToken',$kongbao_data);
        $obj = json_decode($ydresult,true);
        $tokens = $obj['token'];
        $yue_data=array();
        $yue_data['token'] = $tokens;
        $results = Http::post(config('get_yunda_config.apiurl').'/ApiOrder/getBalance',$yue_data);
        $objs = json_decode($results,true);
        $ydmomey = $objs['money']/100;
        //获取公告
		$regong =Http::post('http://211.149.135.130:8119/api/express/getgg');
        $resultgong=json_decode($regong,true);

        $hooks = config('addons.hooks');
        $uploadmode = isset($hooks['upload_config_init']) && $hooks['upload_config_init'] ? implode(',', $hooks['upload_config_init']) : 'local';
        $addonComposerCfg = ROOT_PATH . '/vendor/karsonzhang/fastadmin-addons/composer.json';
        Config::parse($addonComposerCfg, "json", "composer");
        $config = Config::get("composer");
        $addonVersion = isset($config['version']) ? $config['version'] : __('Unknown');
        $this->view->assign([
            'totaluser'        => empty($result['data'])?0:$result['data'],
            'totalviews'       => $usermoenysum,
            'totalorder'       => $moenysum,
            'totalorderamount' => $usercount,
            'todayuserlogin'   => 321,
            'todayusersignup'  => 430,
            'todayorder'       => 2324,
            'unsettleorder'    => 132,
            'gonggao'         => $resultgong['data']['info'],
            'ydmomey'         => $ydmomey,
            'paylist'          => $paylist,
            'addonversion'       => $addonVersion,
            'uploadmode'       => $uploadmode
        ]);

        return $this->view->fetch();
    }

}
