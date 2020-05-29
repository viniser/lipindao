<?php

namespace app\admin\controller\user;

use app\common\controller\Backend;

/**
 * 会员管理
 *
 * @icon fa fa-user
 */
class User extends Backend
{

    protected $relationSearch = true;


    /**
     * @var \app\admin\model\User
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('User');
    }

    /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->with(['group','ulevel'])
                    ->where($where)
                    ->order($sort, $order)
                    ->count();
            $list = $this->model
                    ->with(['group','ulevel'])
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            foreach ($list as $k => $v)
            {
                $v->hidden(['password', 'salt']);
				
            }
			 
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = NULL)
    {
        $row = $this->model->get($ids);
        if (!$row)
            $this->error(__('No Results were found'));
        $this->view->assign('groupList', build_select('row[group_id]', \app\admin\model\UserGroup::column('id,name'), $row['group_id'], ['class' => 'form-control selectpicker']));
        return parent::edit($ids);
    }
	
	   /**
     * 积分记录
     */
    public function score($ids = NULL)
    {
       $row= $this->model->get($ids);
	   $s = $this->request->request('s');//积分
	   $m = $this->request->request('m');//积分
		//print_r($row->username);
        if (!$row)
            $this->error(__('No Results were found'));
		$this->view->assign('username',$row->username);
         
		if ($this->request->isAjax()) {
			\app\common\model\User::score($s,$m,$ids,$this->auth->id.'添加金额'.$m.'元,积分'.$s.'分');
            $this->success("请求成功");
        }
		return $this->view->fetch();
		  
    }

}
