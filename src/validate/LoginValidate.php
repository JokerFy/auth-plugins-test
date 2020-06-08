<?php

namespace Finley\authPlugins\validate;

use Finley\authPlugins\excepetion\InvalidArgumentException;
use Finley\authPlugins\lib\Safe;
use Finley\authPlugins\model\User;

class LoginValidate extends BaseValidate
{


    protected $rule = [
        'username' => 'require|min:4|accountValidation',
        'password' => 'require|min:6',
//        'captcha'  =>  'require|captchaValidation'
    ];

    //验证码验证
/*    protected function captchaValidation($value,$rule,$data)
    {
        $res = $this->check_verify($value,$data['uuid']);
        if(!$res)
            throw new ParameterException([
                'msg'=>'验证码错误'
            ]);
        return true;
    }*/

    //账号验证
    protected function accountValidation($value,$rule,$data)
    {
        $admin = $this->userService->getInfoByUserName(['username' => $data['username'],'password' => $data['password']]);
        try{
            if (!$admin) {
                throw new InvalidArgumentException([
                    'msg' => '账号或密码错误'
                ]);
            }
        }catch (InvalidArgumentException $e){
            $e->error();
        }

        return true;
    }

/*    protected  function check_verify($code, $id = '')
    {
        $captcha = new Captcha();
        return $captcha->check($code, $id);
    }*/
}
