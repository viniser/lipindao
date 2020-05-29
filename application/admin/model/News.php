<?php

namespace app\admin\model;

use think\Model;

class News extends Model
{
    // 表名
    protected $name = 'news';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [
        'category_id_text',
        'create_time_text'
    ];
    

    
    public function getCategoryIdList()
    {
        return ['1' => __('Category_id 1'),'0' => __('Category_id 0')];
    }     


    public function getCategoryIdTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['category_id']) ? $data['category_id'] : '');
        $list = $this->getCategoryIdList();
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


}
