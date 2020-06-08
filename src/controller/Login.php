<?php

namespace Finley\authPlugins\controller;

use Finley\authPlugins\lib\Safe;
use Finley\authPlugins\service\UserService;
use Finley\authPlugins\validate\LoginValidate;
use Finley\authPlugins\validate\RegisterValidate;
use think\facade\Db;
use Finley\authPlugins\model\User;

class Login extends Base
{

    public $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    /**
     * 后台管理员用户在后台添加，
     * 无需注册
     */
    public function login($params)
    {
        (new LoginValidate())->goCheck($params);
        $admin = $this->userService->getInfoByUserName(['username' => $params['username']]);
        return SuccessNotify($admin->toArray());
    }

    /**
     * @return mixed
     */
    public function register($params)
    {
        (new RegisterValidate())->goCheck($params);
        //生成管理员
        $user = User::createUser($params);
        return SuccessNotify($user);
    }

    public function logout()
    {
        return SuccessNotify();
    }
}
