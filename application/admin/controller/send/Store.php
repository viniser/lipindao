<?php

namespace app\admin\controller\send;

use app\common\controller\Backend;
use think\Db;
/**
 * 发货仓
 *
 * @icon fa fa-circle-o
 */
class Store extends Backend
{
    
    /**
     * Store模型对象
     * @var \app\admin\model\send\Store
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\send\Store;
        $this->view->assign("expressIdList", $this->model->getExpressIdList());
        $this->view->assign("isMultipleList", $this->model->getIsMultipleList());
    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    /**
     * 仓库同步
     */
    public function data_sync()
    {
        $store_info = Db::name('send_store')->select();
        //获取分站url
        $urls = Db::name('substation_user')->where('is_push',1)->column('push_url');
        foreach($urls as $key=>$vo){
            $urls[$key] = $vo.'/api/update/update_store';
        }
        $count = count($urls);
        //循环请求接口
        $return = [];
        for($i=0;$i<$count;$i++){
            $json = sendRequest($urls[$i],$store_info,'post');
            $return[$i]= json_decode($json,1);
        }
        // dump($return);
        $column = array_column($return,'code');
        $sum = array_sum($column);
        if($sum>1){
            return ['code'=>1,'msg'=>'数据同步成功'];
        }else{
            $msg = array_column($return,'msg');
            $string = implode(';',$msg);
            return ['code'=>0,'msg'=>$string];
        }
    }
}
