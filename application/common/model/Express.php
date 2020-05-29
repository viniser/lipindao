<?php

namespace app\common\model;

use think\Model;

/**
 * 电子面单
 */
class Express Extends Model
{
    protected $name = 'express';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    //protected $createTime = 'createtime';
    protected $updateTime = false;
    // 追加属性
    protected $append = [
    ];
    public function goods()
    {
        return $this->belongsTo('Goods', 'goods')->setEagerlyType(0);

    }
}
