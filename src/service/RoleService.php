<?php
/**
 * Created by PhpStorm.
 * User: fin
 * Date: 2019/11/1
 * Time: 19:58
 */

namespace  Finley\authPlugins\service;

use Finley\authPlugins\model\Menu;
use Finley\authPlugins\model\Role;
use Finley\authPlugins\model\User;

class RoleService
{
    public $tokenModel;
    public $userModel;
    public $menuModel;
    public $roleModel;
    public $userService;

    public function __construct()
    {
        $this->userModel = new User();
        $this->menuModel = new Menu();
        $this->roleModel = new Role();
        $this->userService = new UserService();
    }

    /**
     * User: fin
     * Date: 2019/11/4
     * Time: 17:03
     * @param $id
     * @return mixed
     */
    public function getInfoById($id){
        $result = $this->roleModel->find($id);
        $menuList = $result->permissions->toArray();
        $menuIdList = array();
        foreach ($menuList as $val) {
            $menuIdList[] = $val['menu_id'];
        }
        $data['role'] = $result->toArray();
        $data['role']['menuIdList'] = $menuIdList;
//        unset($data['permissions']);
        return $data;
    }

    public function getInfoByRoleName($name){
        $result = $this->roleModel->where(['role_name'=>$name]);
        $menuList = $result->permissions->toArray();
        $menuIdList = array();
        foreach ($menuList as $val) {
            $menuIdList[] = $val['menu_id'];
        }
        $data['role'] = $result->toArray();
        $data['role']['menuIdList'] = $menuIdList;
        return $data;
    }

    /**
     * User: fin
     * Date: 2019/11/4
     * Time: 17:03
     * @param $data
     */
    public function save($data)
    {
        $roleId = $this->roleModel->createRoles($data);
        //查找到当前角色
        $role = $this->roleModel->where(["role_id"=>$roleId])->find();
        if($data['menu_id_list']){
            $data['menu_id_list'] = explode(",",$data['menu_id_list']);
            //保存权限
            $role->grantPermission($data['menu_id_list']);
        }
        return $role->toArray();
    }

    /**
     * User: fin
     * Date: 2019/11/4
     * Time: 17:04
     * @param $roleData
     */
    public function update($roleData)
    {
        //更新角色
        $this->roleModel->updateRoles($roleData);
        $role = $this->roleModel->where("role_id","=",$roleData['role_id'])->find();
        //获取目前更新的角色的所有菜单
        $roleMenu = $role->permissions;
        if($roleMenu){
            //因为上传来的角色列表格式与我们数据库取得不一样，需要转换一下
            foreach ($roleMenu->toArray() as $menu) {
                $roleMenus[] = $menu['menu_id'];
            }

            if($roleMenus) {
                //将上传来的角色列表和我们转换后的角色列表转换成集合，然后利用集合的差集算出需要增加和删除的权限有哪些
                $roleMenus = $this->roleModel->collection($roleMenus);
                foreach ($roleMenus as $menu) {
                    $role->deletePermission($menu);
                }
            }
        }

        if($roleData['menu_id_list']){
            $roleData['menu_id_list'] = explode(",",$roleData['menu_id_list']);
            $updateMenu = $this->roleModel->collection($roleData['menu_id_list']);
            foreach ($updateMenu as $menu) {
                $role->grantPermission($menu);
            }
        }

/*        $addMenu = $updateMenu->diff($roleMenus);
        $deleteMenu = $roleMenus->diff($updateMenu);
        //批量增加菜单权限
        $role->grantPermission($addMenu->toArray());
        //批量删除菜单权限
        $role->deletePermission($deleteMenu->toArray());*/

        return $role->toArray();
    }

    /**
     * 根据用户获取角色列表
     * User: fin
     * Date: 2019/11/4
     * Time: 16:49
     * @param $userid
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function select($userId)
    {
        $data['list'] = $this->userModel->where("user_id","=",$userId)->find()->roles->toArray();
        //如果是该用户创建的角色，该用户具备分配权
        $childrenRole = $this->roleModel->where(['create_user_id' => $userId])->select()->toArray();
        $data['list'] = array_merge($data['list'], $childrenRole);
        return $data;
    }

    /**
     * @param $id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function deleteRoleById($id){
        $role = $this->roleModel->where("role_id","=",$id)->find();
        //获取当前角色的所有权限
        $rolePermission = $role->permissions;
        //删除角色中间表中的权限
        $role->deletePermission($rolePermission);
        //删除角色
        $role->delete();

        return true;
    }

    /**
     * User: fin
     * Date: 2019/11/4
     * Time: 17:05
     * @param $ids
     */
    public function deleteRoleByIds($ids){
        $idArray = explode(",",$ids);
        $roles = $this->roleModel->where("role_id","in",$idArray)->select();
        foreach ($roles as $key => $val) {
            //获取当前角色的所有权限
            $rolePermission = $val->permissions;
            //删除角色中间表中的权限
            $val->deletePermission($rolePermission);
            //删除角色
            $val->delete();
        }
        return true;
    }

}
