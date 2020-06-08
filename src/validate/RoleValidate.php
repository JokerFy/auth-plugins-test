<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/4
 * Time: 11:55
 */

namespace Finley\authPlugins\validate;

use Finley\authPlugins\excepetion\InvalidArgumentException;
use Finley\authPlugins\model\Role;
use Finley\authPlugins\model\User;

class RoleValidate extends BaseValidate
{
    protected $rule = [
        'role_id'  =>  'require|roleIdValidation',
        'role_name'  =>  'require',
        'remark'  =>  'require',
    ];

    protected $scene = [
        'add'   =>  ['role_name','remark'],
        'edit'  =>  ['role_id','role_name','remark'],
    ];

    //账号验证
    public function roleIdValidation($value)
    {
        $rule = ['role_id'=>'require|number'];
        $data = ['role_id'=>$value];
        $this->checkFieldByRule($data,$rule);
        $user = Role::where(['role_id' =>$value])->find();
        try{
            if (!$user) {
                throw new InvalidArgumentException([
                    'msg' => '角色信息不存在'
                ]);
            }
        }catch (InvalidArgumentException $e){
            $e->error();
        }

        return true;
    }
}