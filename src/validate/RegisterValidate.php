<?php

namespace Finley\authPlugins\validate;

use Finley\authPlugins\excepetion\InvalidArgumentException;
use Finley\authPlugins\lib\Safe;
use Finley\authPlugins\model\User;

class RegisterValidate extends BaseValidate
{
    protected $rule = [
        'username' => 'require|min:4|registerValidation',
        'password' => 'require|min:6',
    ];

    //账号验证
    protected function registerValidation($value,$rule,$data)
    {
        $exist =$this->userService->getInfoByUserName(['username' =>$value]);
        try{
            if ($exist) {
                throw new InvalidArgumentException([
                    'msg' => '该账号已存在'
                ]);
            }
        }catch (InvalidArgumentException $e){
            $e->error();
        }
        return true;
    }

}
