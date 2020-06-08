<?php
/**
 * Created by PhpStorm.
 * User: fin
 * Date: 2019/11/1
 * Time: 19:58
 */

namespace  Finley\authPlugins\service;

use Finley\authPlugins\lib\Safe;
use Finley\authPlugins\model\Token;
use Finley\authPlugins\model\User;
use think\facade\Db;

class UserService
{
    public $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * 获取管理员信息
     * User: fin
     * Date: 2019/11/1
     * Time: 20:58
     * @param int $id
     * @return mixed
     */
    public function getInfoById($id = 0){
        $data['user'] = $this->userModel->find($id);
        //roles是目前登录用户拥有的角色列表，roleIdList是获取的指定用户所拥有的角色列表
        foreach ($data['user']['roles']->toArray() as $val) {
            $roleIdList[] = $val['role_id'];
        }
        $data['user']['roleIdList'] = isset($roleIdList) ? $roleIdList : [];
        return $data;
    }

    public function getInfoByLogin($username,$password){
        $data = $this->userModel->where(['username' => $username])->find();
        $password = Safe::setpassword($password, $data->salt);
        $admin = $this->userModel->where(['username' => $username,'password'=>$password])->find();
        return $admin;
    }

    public function getInfoByUserName($username){
        $data = $this->userModel->where(['username' => $username])->find();
        return $data;
    }

    /**
     * 批量删除管理员
     * User: fin
     * Date: 2019/11/1
     * Time: 20:58
     * @param $userIds
     */
    public function deleteById($userId){
        $user = $this->userModel->where("user_id","=",$userId)->find();
        $role = $user->roles;
        $user->deleteRole($role);
        $user->delete();
    }

    /**
     * 批量删除管理员
     * User: fin
     * Date: 2019/11/1
     * Time: 20:58
     * @param $userIds
     */
    public function deleteByIds($userIds){
        $users = $this->userModel->where("user_id","in",$userIds)->select();
        foreach ($users as $key => $val) {
            //获取每个用户角色，然后删除
            $roles[$key] = $val->roles;
            $val->deleteRole($roles[$key]);
            $val->delete();
        }
    }

    /**
     * 新增管理员
     * User: fin
     * Date: 2019/11/1
     * Time: 20:58
     * @param $data
     */
    public function save($data){
        $id = $this->userModel->createUser($data);
        $user = $this->userModel->where(["user_id"=>$id])->find();
        //分配权限
        $user->assignRole($data['role_id_list']);
    }


    /**
     * 更新管理员
     * User: fin
     * Date: 2019/11/4
     * Time: 15:33
     * @param $data
     * @throws \think\exception\DbException
     */
    public function update($data){
        //更新用户
        $this->userModel->updateUser($data);
        //手动获取用户，因为save方法返回的不是数据集
        $user = $this->userModel->where(["user_id"=>$data['user_id']])->find();
        //获取目前更新的用户的所有角色
        $userRole = $user->roles;
        //因为上传来的角色列表格式与我们数据库取得不一样，需要转换一下
        if (!$userRole->isEmpty()) {
            foreach ($userRole->toArray() as $role) {
                $userRoles[] = $role['role_id'];
            }
            //将上传来的角色列表和我们转换后的角色列表转换成集合，然后利用集合的差集算出需要增加和删除的权限有哪些
            $userRoles = $this->userModel->collection($userRoles);
            $updateRole = $this->userModel->collection($data['role_id_list']);
            foreach ($userRoles as $role) {
                $user->deleteRole($role);
            }
            foreach ($updateRole as $role) {
                $user->assignRole($role);
            }
        } else {
            $user->assignRole($data['role_id_list']);
        }
        return true;
    }

    public function userPermission($id){
        //获取用户
        $user = $this->userModel->find($id);
        //获取用户所有的角色
        $userRoles = $user->roles;
        //获取用户所有的菜单路由权限
        $userPermission = [];
        //获取用户所有的访问控制器方法的权限
        $userAccess = [];
        //$userRoles是一个二维数组，进行嵌套循环所有每个角色数组下的权限
        foreach ($userRoles as $key => $value) {
            $nextData = $value->permissions->hidden(['pivot'])->toArray();
            foreach ($nextData as $item => $val) {
                $userPermission[] = $val;
                if ($val['perms'] != false) {
                    $userAccess[] = $val['perms'];
                }
            };
        }

        $userPermission = array_unique_fb($userPermission,"menu_id");
        $userAccess = array_unique($userAccess);

        //对权限进行格式化
        foreach ($userAccess as $val) {
            $array = explode(',', $val);
            foreach ($array as $item) {
                $perms[] = $item;
            }
        }
        //去除重复权限并且重新对索引进行排序
        $userAccess = array_values(array_unique($perms));

        $data = [
            'userPermission' => $userPermission,
            'userAccess' => $userAccess
        ];
        return $data;
    }


}
