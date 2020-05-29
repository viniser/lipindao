<?php

namespace app\api\controller;

use app\common\controller\Api;
/**
 * 电子面单接口
 */
class Express extends Api
{

    //如果$noNeedLogin为空表示所有接口都需要登录才能请求
    //如果$noNeedRight为空表示所有接口都需要验证权限才能请求
    //如果接口已经设置无需登录,那也就无需鉴权了
    //
    // 无需登录的接口,*表示全部
    protected $noNeedLogin = [];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = [];

     /**
     * 查询余额
     * @ApiTitle    (查询余额)
     * @ApiSummary  (获取账号余额)
     * @ApiMethod   (POST)
     * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token，注：token值请放在headers")
	 * @ApiReturnParams   (name="code", type="integer", required=true, description="状态值：1 为成功")
     * @ApiReturnParams   (name="msg", type="string", required=true, description="返回成功")
	 * @ApiReturnParams   (name="time", type="string", required=true, description="响应的时间戳")
     * @ApiReturnParams   (name="data", type="object", description="返回业务数据合集")
	 * @ApiReturnParams   (name="usermoney", type="string", required=true, description="账号所剩余额")
     * @ApiReturn   ({"code": 1,"msg": "","time": "1540347545","data": {"usermoney":88.88}})
     */
    public function getmoney()
    {
		//获取业务参数
        $this->success('返回成功', ['usermoney' => $this->auth->money]);
    }

}
