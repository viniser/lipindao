<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Db;
/**
 * 商品管理
 *
 * @icon fa fa-circle-o
 */
class Goods extends Backend
{
    
    /**
     * Goods模型对象
     * @var \app\admin\model\Goods
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Goods;

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    /**
     * 编辑
     */
    public function edit($ids = NULL)
    {
        $row = $this->model->get($ids);
        if (!$row)
            $this->error(__('No Results were found'));
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            $express_code = $params['express_code'];
            $father_code = $params['father_code'];
            $express_codes = [];
            foreach($express_code as $key=>$value){
                $express_codes[$value] = $father_code[$key];
            }
            $express_codes = array_filter($express_codes);
            $express_codes = json_encode($express_codes);
            unset($params['father_code']);
            unset($params['express_code']);
            $params['express_codes'] = $express_codes;
            $url =  'https://'.$_SERVER['HTTP_HOST'];
            if(strpos($params['goods_image'],$url) === false){
                $params['goods_image'] = $url.$params['goods_image'];
            }
            if ($params) {
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = basename(str_replace('\\', '/', get_class($this->model)));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : true) : $this->modelValidate;
                        $row->validate($validate);
                    }
                    $result = $row->allowField(true)->save($params);
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error($row->getError());
                    }
                } catch (\think\exception\PDOException $e) {
                    $this->error($e->getMessage());
                } catch (\think\Exception $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        $express_codes = json_decode($row['express_codes'],1);
        $this->assign('express_codes',$express_codes);
        return $this->view->fetch();
    }
    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            $express_code = $params['express_code'];
            $father_code = $params['father_code'];
            $express_codes = [];
            foreach($express_code as $key=>$value){
                $express_codes[$value] = $father_code[$key];
            }
            $express_codes = array_filter($express_codes);
            $express_codes = json_encode($express_codes);
            unset($params['father_code']);
            unset($params['express_code']);
            $params['express_codes'] = $express_codes;
            $url =  'https://'.$_SERVER['HTTP_HOST'];
            $params['goods_image'] = $url.$params['goods_image'];
            if ($params) {
                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
                }
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : true) : $this->modelValidate;
                        $this->model->validate($validate);
                    }
                    $result = $this->model->allowField(true)->save($params);
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error($this->model->getError());
                    }
                } catch (\think\exception\PDOException $e) {
                    $this->error($e->getMessage());
                } catch (\think\Exception $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }
    /**
     * 数据同步
     */
    public function data_sync()
    {
        //获取商品表的所有数据
        $goods_data = Db::name('goods')->select();
        foreach($goods_data as $key=>$val){
            $goods_data[$key]['fid']=$val['id'];
            unset($goods_data[$key]['id']);
            unset($goods_data[$key]['cprice']);
          //  unset($goods_data[$key]['apiprice']);
        }
        //获取分站url
        $urls = Db::name('substation_user')->where('is_push',1)->column('push_url');
        foreach($urls as $key=>$vo){
            $urls[$key] = $vo.'/api/update/update_goods';
        }
        $count = count($urls);
        //循环请求接口
        $return = [];

        for($i=0;$i<$count;$i++){
            $json = sendRequest($urls[$i],$goods_data,'post');
            $return[$i]= json_decode($json,1);
        }
        $column = array_column($return,'code');
        $sum = array_sum($column);
        if($sum>=1){
            return ['code'=>1,'msg'=>'数据同步成功'];
        }else{
            $msg = array_column($return,'msg');
            $string = implode(';',$msg);
            return ['code'=>0,'msg'=>$string];
        }
    }
    /**
     * 商品下架
     */
    public function sold_out(){
        $gid = (int)input('post.gid');
        $is_out = 0;
        //更新数据库
        $sql = $this->model->where('id',$gid)->setField('is_out',$is_out);
        if($sql){
            return ['code'=>1,'msg'=>'商品下架成功'];
        }
    }
    /**
     * 商品上架
     */
    public function sold_up(){
        $gid = (int)input('post.gid');
        $is_out = 1;
        //更新数据库
        $sql =$this->model->where('id',$gid)->setField('is_out',$is_out);
        if($sql){
            return ['code'=>1,'msg'=>'商品上架成功'];
        }
    }

}
