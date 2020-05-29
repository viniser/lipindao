<?php

namespace app\admin\model;

use think\Model;

class Address extends Model
{
    // 表名
    protected $name = 'address';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [
        'ismr_text',
        'create_time_text'
    ];
    

    
    public function getIsmrList()
    {
        return ['0' => __('Ismr 0'),'1' => __('Ismr 1')];
    }     


    public function getIsmrTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['ismr']) ? $data['ismr'] : '');
        $list = $this->getIsmrList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getCreateTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['create_time']) ? $data['create_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setCreateTimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }


    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
