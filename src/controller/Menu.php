<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/4
 * Time: 10:29
 */

namespace Finley\authPlugins\controller;

use Finley\authPlugins\service\MenuService;
use Finley\authPlugins\validate\IDMustBePositiveInt;
use Finley\authPlugins\validate\MenuValidate;

/**
 * 初始数据格式：
 * var dataList = [
 * {
 * 'menuId': 1,
 * 'parentId': 0,
 * 'parentName': null,
* 'name': '系统管理',
* 'url': null,
* 'perms': null,
* 'type': 0,
* 'icon': 'system',
* 'orderNum': 0,
* 'open': null,
* 'list': null
* }...
 *菜单化格式：
 * var navDataList = [
* {
* 'menuId': 1,
* 'parentId': 0,
* 'parentName': null,
* 'name': '系统管理',
* 'url': null,
* 'perms': null,
* 'type': 0,
* 'icon': 'system',
* 'orderNum': 0,
* 'open': null,
* 'list': [
* {
* 'menuId': 2,
* 'parentId': 1,
* 'parentName': null,
* 'name': '管理员列表',
* 'url': 'sys/user',
* 'perms': null,
* 'type': 1,
* 'icon': 'admin',
* 'orderNum': 1,
* 'open': null,
* 'list': null
* },...
 * Class AuthMenu
 * @package app\sys\controller\auth
 */
class Menu extends Base
{
    public $menuSevice;
    public $menuValidate;

    public function __construct()
    {
        $this->menuSevice = new MenuService();
    }

    public function getMenuList()
    {
        $data = $this->menuSevice->menuList();
        return SuccessNotify($data);
    }

    //获得菜单信息
    public function getInfoById($id)
    {
        (new IDMustBePositiveInt())->goCheck(["id"=>$id]);
        $data = $this->menuSevice->info($id);
        return SuccessNotify($data);
    }

    public function getLastInfo()
    {
        $data = $this->menuSevice->getLastInfo();
        return SuccessNotify($data);
    }


    //获取上级菜单
    public function getSelectData()
    {
        $data['menu_list'] = $this->menuSevice->select();
        return SuccessNotify($data);
    }

    //添加菜单
    public function insertMenu($data)
    {
        (new MenuValidate())->sceneCheck($data,'add');
        $this->menuSevice->save($data);
        return SuccessNotify();
    }

    //修改菜单
    public function updateMenuById($data)
    {
        (new MenuValidate())->sceneCheck($data,'edit');
        $this->menuSevice->update($data);
        return SuccessNotify();
    }

    //删除菜单（如果有子菜单则一起删除，包括中间表和菜单表）
    public function deleteMenuById($id)
    {
        (new IDMustBePositiveInt())->goCheck(["id"=>$id]);
        $this->menuSevice->delete($id);
        return SuccessNotify();
    }

    /**
     * 根据用户的权限生成菜单树（因为前后端分离，所以这里相当于是获取了给前端显示菜单的路由）
     * menulist是用户的路由菜单权限，前端根据该数值动态显示菜单
     * permissions是用户的访问权限，前端根据该值判断用户能访问后端的哪些路由，并且根据权限判断是否显示增加删除等按钮
     *  data: {
    'msg': 'success',
    'code': 0,
    'menuList': navDataList(上面可以查看格式)
    'permissions': [
    'sys:schedule:info',
    'sys:menu:update',
    'sys:menu:delete',
    'sys:config:info',
    'sys:menu:list',
    'sys:config:save'......
     */
    public function nav($userId)
    {
        (new IDMustBePositiveInt())->goCheck(["id"=>$userId]);
        $data = $this->menuSevice->nav($userId);
        return SuccessNotify($data);
    }
}