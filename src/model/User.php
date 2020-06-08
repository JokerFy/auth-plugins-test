<?php

namespace Finley\authPlugins\model;
use Finley\authPlugins\lib\Safe;

class User extends Base
{
    protected $table = "sys_user";
    protected $autoWriteTimestamp = 'datetime';
    protected $updateTime = false;
    protected $pk = 'user_id';
    public $incrementing = true;
    protected $type = [
        'status'    =>  'integer'
    ];

    //用户有哪些角色(多对多)
    public function roles()
    {
        return $this->belongsToMany('Role','sys_user_role','role_id','user_id');
    }

    //判断是否有哪些角色
    public function isInRoles($roles)
    {
        //判断角色与用户的角色是否有交集，加双感叹号，如果是0则返回false
        return !!$roles->intersect($this->roles)->count();
    }

    //给用户分配角色
    public function assignRole($role)
    {
        return $this->roles()->save($role);
    }

    //取消用户分配的角色
    public function deleteRole($role)
    {
        return $this->roles()->detach($role);

    }

    //判断用户是否有权限
    public function hasPermission($permission)
    {
        return $this->isInRoles($permission->roles);
    }

    //创建一个管理员并保存
    public static function createUser($data){
        $salt = getRandChar(20);
        $res = self::create([
            'username' => $data['username'],
            'password' => Safe::setpassword($data['password'], $salt),
            'salt' => $salt,
            'status'=>$data['status'],
            'mobile'=>$data['mobile'],
            'email'=>$data['email'],
            'create_user_id'=>1
        ]);
        return $res->user_id;
    }

    //更新管理员
    public function updateUser($data){
        $salt = getRandChar(20);
        $res = self::update([
            'username' => $data['username'],
            'password' => Safe::setpassword($data['password'], $salt),
            'salt' => $salt,
            'status'=>$data['status'],
            'mobile'=>$data['mobile'],
            'email'=>$data['email'],
        ],['user_id'=>$data['user_id']]);
        return $res;
    }
}
