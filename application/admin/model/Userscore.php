<?php

namespace app\admin\model;

use think\Model;

class Userscore extends Model
{
     // 表名
    protected $name = 'user_score_log';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = '';
    //自定义日志标题
    protected static $title = '';
    //自定义日志内容
    protected static $content = '';

 
    public function getBeforeList()
    {
        return ['0' => __('Before 0'),'1' => __('Before 1')];
    }   
    public function getBeforeTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['before']) ? $data['before'] : '');
        $list = $this->getBeforeList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

}
