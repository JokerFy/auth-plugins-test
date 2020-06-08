<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/4
 * Time: 11:55
 */

namespace Finley\authPlugins\validate;

use Finley\authPlugins\excepetion\InvalidArgumentException;
use Finley\authPlugins\model\User;

class UserValidate extends BaseValidate
{
    protected $rule = [
        'user_id'  =>  'require',
        'username'  =>  'require',
        'role_id_list'  =>  'require',
        'email' =>  'email',
        'password' =>  'require',
        'mobile' =>  'require',
    ];

    protected $message = [
        'username'  =>  '用户名必须',
        'email' =>  '邮箱格式错误',
        'password' =>  '密码格式错误',
        'mobile' =>  '手机号格式错误',
        'role_id_list' =>  '角色ID不可以为空',
    ];

    protected $scene = [
        'add'   =>  ['username','password','role_id_list'],
        'edit'  =>  ['user_id','role_id_list'],
    ];

    //账号验证
    public function userIdValidation($value)
    {
        $rule = ['id'=>'require|number'];
        $data = ['id'=>$value];
        $this->checkFieldByRule($data,$rule);
        $user = User::where(['user_id' =>$value])->find();
        try{
            if (!$user) {
                throw new InvalidArgumentException([
                    'msg' => '账号信息不存在'
                ]);
            }
        }catch (InvalidArgumentException $e){
            $e->error();
        }

        return true;
    }
}