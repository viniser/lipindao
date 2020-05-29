<?php

namespace app\common\model;

use think\Model;
use think\Db;
/**
 * 会员模型
 */
class User Extends Model
{

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    // 追加属性
    protected $append = [
        'url',
    ];

    /**
     * 获取个人URL
     * @param   string  $value
     * @param   array   $data
     * @return string
     */
    public function getUrlAttr($value, $data)
    {
        return "/u/" . $data['id'];
    }

    /**
     * 获取头像
     * @param   string    $value
     * @param   array     $data
     * @return string
     */
    public function getAvatarAttr($value, $data)
    {
        return $value ? $value : '/assets/img/avatar.png';
    }

    /**
     * 获取会员的组别
     */
    public function getGroupAttr($value, $data)
    {
        return UserGroup::get($data['group_id']);
    }

    /**
     * 获取验证字段数组值
     * @param   string    $value
     * @param   array     $data
     * @return  object
     */
    public function getVerificationAttr($value, $data)
    {
        $value = array_filter((array) json_decode($value, TRUE));
        $value = array_merge(['email' => 0, 'mobile' => 0], $value);
        return (object) $value;
    }

    /**
     * 设置验证字段
     * @param mixed $value
     * @return string
     */
    public function setVerificationAttr($value)
    {
        $value = is_object($value) || is_array($value) ? json_encode($value) : $value;
        return $value;
    }

    /**
     * 变更会员积分
     * @param int $score    积分
     * @param int $user_id  会员ID
     * @param string $memo  备注
     */
    public static function score($score,$money, $user_id, $memo,$s=3,$trade_no=0)
    {
        $user = self::get($user_id);
        if ($user)
        {
            $after = $user->score + $score;
			if($after<0) {
                $after=0;
            }
			if($s==3){
               $before = $money>=0?1:0;//$user->score;
            }else{
                $before = $s;
            }
            $aft_money = $user->money + $money;//输入的金额
            $level = self::nextlevel($after);
            $isstatus = Db::name('user_level')->where('id',$user->level)->value('status');
            //更新会员信息
            if($isstatus == 1){
                $user->save(['money' => $aft_money,'score' => $after, 'level' => $level]);
            }else{
                $user->save(['money' => $aft_money,'score' => $after]);
            }
            //写入日志
            ScoreLog::create(['user_id' => $user_id, 'score' => $money, 'before' => $before, 'after' => $aft_money, 'memo' => $memo,'trade_no'=>$trade_no]);
        }
    }

    /**
     * 根据积分获取等级
     * @param int $score 积分
     * @return int
     */
    public static function nextlevel($score = 0)
    {
       //获取会员等级
        $data = Db::name('user_level')->where('status',1)->field('id,low_price')->select();
        $array = [];
        foreach($data as $key=>$val) {
           $array[$val['id']] = $val['low_price'];
        }
        $level = 1;
        foreach ($array as $key => $value)
        {
            if ($score >= $value)
            {
                $level = $key;
            }
        }
        return $level;
    }

}
