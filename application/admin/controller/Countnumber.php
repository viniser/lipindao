<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Config;
use fast\Http;
use think\Db;
/**
 * 控制台
 *
 * @icon fa fa-dashboard
 * @remark 用于展示当前系统中的统计数据、统计报表及重要实时数据
 */
class Countnumber extends Backend
{

    /**
     * 用户下单数量统计
     */
    public function statistics_user()
    {
        unset($_GET['addtabs']);
        $current_time = strtotime(date("Y/m/d 00:00:00",time()-24*60*60));
        $yesterday_time = strtotime(date("Y/m/d 23:59:59",time()-24*60*60));
        //时间搜索
        $start = input('get.start_time');
        $end = input('get.end_time');
        $start_time = $start?strtotime($start):$current_time;
        $end_time = $end?strtotime($end):$yesterday_time;
        //搜索关键字
        $keywords = input('get.keywords');
        $expressid = input('get.expressid');
        $where = [];
        if($expressid !=0){
           $where['expressid'] = ['eq',$expressid];
        }
        if($keywords){
           $where['user_id'] = ['eq',get_uid($keywords)];
        }
        $data = Db::name('express')
                            ->field('user_id,avg(price) as avgprice,count(out_order_no) as count,create_time')
                            ->where('create_time', 'between', [$start_time, $end_time])
                            ->where($where)
                            ->group('user_id')
                            ->order('count desc')
                            ->paginate(50, '');

        $this->assign('express_info',$data);
        $this->assign('start',$start);
        $this->assign('end',$end);
        $this->assign('keywords',$keywords);
        $this->assign('expressid',$expressid);
        return $this->view->fetch();
    }
    /**
     *商品销量
     */
    public function goods_count()
    {
        unset($_GET['addtabs']);
        //默认7天时间
        $current_time = strtotime(date("Y/m/d 00:00:00",time()-24*60*60*7));
        $yesterday_time = strtotime(date("Y/m/d 23:59:59",time()-24*60*60));
        //时间搜索
        $start = input('get.start_time');
        $end = input('get.end_time');
        $start_time = $start?strtotime($start):$current_time;
        $end_time = $end?strtotime($end):$yesterday_time;
        $where = [];
        //搜索关键字
        $keywords = input('get.keywords');
        if($keywords){
            $where['goods'] = ['eq',$keywords];
        }
        $goods_info = Db::name('express')
            ->field('goods,count(out_order_no) as count')
            ->where('create_time', 'between', [$start_time, $end_time])
            ->where($where)
            ->group('goods')
            ->order('count desc')
            ->select();

        $this->assign('start',$start);
        $this->assign('end',$end);
        $this->assign('keywords',$keywords);
        $this->assign('goods_info',$goods_info);

        return $this->view->fetch();
    }
}

