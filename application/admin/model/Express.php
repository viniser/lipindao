<?php

namespace app\admin\model;

use think\Model;

class Express extends Model
{
    // 表名
    protected $name = 'express';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [
        'expressid_text',
        'from_text',
        'create_time_text'
    ];
    

    
    public function getExpressidList()
    {
        return ['1' => __('Expressid 1'),'2' => __('Expressid 2'),'3' => __('Expressid 3'),'4' => __('Expressid 4'),'5' => __('Expressid 5')];
    }     

    public function getFromList()
    {
        return ['0' => __('From 0'),'1' => __('From 1')];
    }     


    public function getExpressidTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['expressid']) ? $data['expressid'] : '');
        $list = $this->getExpressidList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getFromTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['from']) ? $data['from'] : '');
        $list = $this->getFromList();
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
    public function goods()
    {
        return $this->belongsTo('Goods', 'goods')->setEagerlyType(0);
    }
}
