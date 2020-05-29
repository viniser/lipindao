<?php

namespace app\api\controller;

use app\common\controller\Api;

/**
 * Token接口
 */
class Token extends Api
{

    protected $noNeedLogin = ['gettokens'];
    protected $noNeedRight = [];

    public function _initialize()
    {
        parent::_initialize();
    }
/**
     * 获取会员token
     * @ApiTitle    (获取Token)
     * @ApiSummary  (获取token接口，获取到token后，请保持好，其他接口会用到此token)
     * @ApiMethod   (POST)
	 * @ApiParams   (name="account", type="integer", required=true, description="用户名")
     * @ApiParams   (name="password", type="string", required=true, description="登录密码")
	 * @ApiReturnParams   (name="code", type="integer", required=true, description="状态值：1 为成功")
     * @ApiReturnParams   (name="msg", type="string", required=true, description="返回成功")
	 * @ApiReturnParams   (name="time", type="string", required=true, description="响应的时间戳")
     * @ApiReturnParams   (name="data", type="object", description="返回业务数据合集")
	 * @ApiReturnParams   (name="token", type="string", required=true, description="token值")
	 * @ApiReturnParams   (name="expires_in", type="int", required=true, description="token剩余时间，单位秒")
     * @ApiReturn   ({"code": 1,"msg": "","time": "1540347545","data": {"token": "8cxxx-4510-458f-a642-xxx","expires_in": 77759987}})
	 * @ApiInternal
     */
    public function gettokens()
    {
        $account = $this->request->request('account');
        $password = $this->request->request('password');
        if (!$account || !$password)
        {
            $this->error(__('Invalid parameters'));
        }
        $ret = $this->auth->login($account, $password);
        if ($ret)
        {
            $datas = $this->auth->getUserinfo();
            $this->success('操作成功', ['token' => $datas['token'], 'expires_in' => $datas['expires_in']]);
        }
        else
        {
            $this->error($this->auth->getError());
        }
    }
    /**
     * 检测Token是否过期
     * @ApiTitle    (检测Token是否过期)
     * @ApiSummary  (检测Token是否过期)
     * @ApiMethod   (POST)
     * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token，注：token值请放在headers")
	 * @ApiReturnParams   (name="code", type="integer", required=true, description="状态值：1 为成功")
     * @ApiReturnParams   (name="msg", type="string", required=true, description="返回成功")
	 * @ApiReturnParams   (name="time", type="string", required=true, description="响应的时间戳")
     * @ApiReturnParams   (name="data", type="object", description="返回业务数据合集")
	 * @ApiReturnParams   (name="token", type="string", required=true, description="token值")
	 * @ApiReturnParams   (name="expires_in", type="int", required=true, description="token剩余时间，单位秒")
     * @ApiReturn   ({"code": 1,"msg": "","time": "1540347545","data": {"token": "8cxxx-4510-458f-a642-xxx","expires_in": 77759987}})
     */
    public function check()
    {
        $token = $this->auth->getToken();
        $tokenInfo = \app\common\library\Token::get($token);
        $this->success('操作成功', ['token' => $tokenInfo['token'], 'expires_in' => $tokenInfo['expires_in']]);
    }

     /**
     * 刷新Token
     * @ApiTitle    (刷新Token)
     * @ApiSummary  (重新获取token值。刷新后原token将失效)
     * @ApiMethod   (POST)
     * @ApiHeaders  (name=token, type=string, required=true, description="请求的原Token，注：token值请放在headers")
	 * @ApiReturnParams   (name="code", type="integer", required=true, description="状态值：1 为成功")
     * @ApiReturnParams   (name="msg", type="string", required=true, description="返回成功")
	 * @ApiReturnParams   (name="time", type="string", required=true, description="响应的时间戳")
     * @ApiReturnParams   (name="data", type="object", description="返回业务数据合集")
	 * @ApiReturnParams   (name="token", type="string", required=true, description="token值")
	 * @ApiReturnParams   (name="expires_in", type="int", required=true, description="token剩余时间，单位秒")
     * @ApiReturn   ({"code": 1,"msg": "","time": "1540347545","data": {"token": "8cxxx-4510-458f-a642-xxx","expires_in": 77759987}})
     */
    public function refresh()
    {
		$this->auth->refreshToken();
        $token = $this->auth->getToken();
        $tokenInfo = \app\common\library\Token::get($token);
       // $tokenInfo->expiretime = time() + 2592000;
       // $tokenInfo->save();
        $this->success('操作成功', ['token' => $tokenInfo['token'], 'expires_in' => $tokenInfo['expires_in']]);
    }

}
