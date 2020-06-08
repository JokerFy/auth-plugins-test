<?php

namespace Finley\authPlugins\controller;

use Finley\authPlugins\service\CommonService;
use Finley\authPlugins\service\RoleService;
use Finley\authPlugins\validate\IDCollection;
use Finley\authPlugins\validate\IDMustBePositiveInt;
use Finley\authPlugins\validate\RoleValidate;
use Finley\authPlugins\validate\StringFieldMustBeHaveValue;

/**
 * Class AuthRole
 * @package app\sys\controller\auth
 * 角色信息格式：
 * data: {
 * 'msg': 'success',
 * 'code': 0,
 * 'role':{
 * 'roleId': '@increment',
 * 'roleName': '@name',
 * 'remark': '@csentence',
 * 'createUserId': 1,
 * 'menuIdList': '(1, 2, 3，4...)',
 * 'createTime': '@datetime'
 * }
 * }
 *角色列表格式：
 * data: {
 * 'msg': 'success',
 * 'code': 0,
 * 'page': {
 * 'totalCount': dataList.length,
 * 'pageSize': 10,
 * 'totalPage': 1,
 * 'currPage': 1,
 * 'list': dataList
 * }
 * }
 */
class Role extends Base
{
    public $roleSevice;
    public $roleModel;

    public function __construct()
    {
        $this->roleSevice = new RoleService();
        $this->roleModel = new \Finley\authPlugins\model\Role();
        $this->roleValidate = new RoleValidate();
    }

    public function getRoleList($condition=[])
    {
        $data = $this->pageList($this->roleModel,$condition);
        return SuccessNotify($data);
    }

    //根据用户id获取角色列表
    public function getSelectData($userId)
    {
        (new IDMustBePositiveInt())->goCheck(["id"=>$userId]);
        $data = $this->roleSevice->select($userId);
        return SuccessNotify($data);
    }

    //根据角色id获取角色信息
    public function getInfoById($id)
    {
        (new IDMustBePositiveInt())->goCheck(["id"=>$id]);
        $data = $this->roleSevice->getInfoById($id);
        return SuccessNotify($data);
    }


    public function getInfoByRoleName($name)
    {
        (new StringFieldMustBeHaveValue())->goCheck(["checkField"=>$name]);
        $data = $this->roleSevice->getInfoByRoleName($name);
        return SuccessNotify($data);
    }


    //创建角色
    public function insertRole($roleData)
    {
        $this->roleValidate->sceneCheck($roleData,'add');
        if($roleData['menu_id_list']){
            (new IDCollection())->goCheck(["ids"=>$roleData['menu_id_list']]);
        }
        $res = $this->roleSevice->save($roleData);
        return SuccessNotify($res);
    }

    //修改角色
    public function updateRoleById($roleData)
    {
        $this->roleValidate->sceneCheck($roleData,'edit');
        $res = $this->roleSevice->update($roleData);
        return SuccessNotify($res);
    }

    //可批量删除角色
    public function deleteRoleByIds($roleIds)
    {
        (new IDCollection())->goCheck(["ids"=>$roleIds]);
        $res = $this->roleSevice->deleteRoleByIds($roleIds);
        return SuccessNotify($res);
    }

    //删除角色
    public function deleteRoleById($roleId)
    {
        (new IDMustBePositiveInt())->goCheck(["id"=>$roleId]);
        $res = $this->roleSevice->deleteRoleById($roleId);
        return SuccessNotify($res);
    }

    //检查角色有哪些权限
    public function rolePermission($roleId)
    {
        (new IDMustBePositiveInt())->goCheck(["id"=>$roleId]);
        $role = $this->roleSevice->info($roleId);
        return SuccessNotify($role->permissions);
    }


}
