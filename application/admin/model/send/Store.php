<?php

namespace app\admin\model\send;

use think\Model;

class Store extends Model
{
    // 表名
    protected $name = 'send_store';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [
        'express_id_text',
        'is_multiple_text',
        'create_time_text'
    ];
    

    
    public function getExpressIdList()
    {
        return ['1' => __('Express_id 1'),'2' => __('Express_id 2'),'3' => __('Express_id 3'),'4' => __('Express_id 4'),'5' => __('Express_id 5')];
    }     

    public function getIsMultipleList()
    {
        return ['1' => __('Is_multiple 1'),'0' => __('Is_multiple 0')];
    }     


    public function getExpressIdTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['express_id']) ? $data['express_id'] : '');
        $list = $this->getExpressIdList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getIsMultipleTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['is_multiple']) ? $data['is_multiple'] : '');
        $list = $this->getIsMultipleList();
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
