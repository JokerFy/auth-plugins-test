<?php
/**
 * Created by PhpStorm.
 * User: fin
 * Date: 2019/11/1
 * Time: 19:58
 */

namespace  Finley\authPlugins\service;


use Finley\authPlugins\model\Menu;
use Finley\authPlugins\model\User;

class MenuService
{
    public $userModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->menuModel = new Menu();
        $this->userService = new UserService();
    }


    public function menuList(){
        $data = $this->menuModel->select();
        //给菜单增加一个上级菜单的属性
        foreach ($data as $key => $val) {
            foreach ($data as $item) {
                if ($val['parent_id'] == $item['menu_id']) {
                    $data[$key]['parent_name'] = $item['name'];
                } elseif ($val['parent_id'] == 0) {
                    $data[$key]['parent_name'] = '';
                }
            }
        }
        return $data;
    }

    public function info($id){
        return $this->menuModel->where("menu_id",$id)->find()->toArray();
    }

    public function getLastInfo(){
        return $this->menuModel->order("menu_id","desc")->find()->toArray();
    }

    public function select()
    {
        $data = $this->menuModel->select()->toArray();
        //给菜单增加一个上级菜单的属性
        foreach ($data as $key => $val) {
            foreach ($data as $item) {
                if ($val['parent_id'] == $item['menu_id']) {
                    $data[$key]['parent_name'] = $item['name'];
                } elseif ($val['parent_id'] == 0) {
                    $data[$key]['parent_name'] = '';
                }
            }
        }
        return $data;
    }

    public function save($data){
        return $this->menuModel->insertMenu($data);
    }

    public function update($data){
        $menuId = $data['menu_id'];
        unset($data['menu_id']);
        return $this->menuModel::update($data,["menu_id"=>$menuId]);
    }

    public function delete($id){
        $menu = $this->menuModel->where(['menu_id'=>$id])->find();
        $childMenu = $this->menuModel->where(['parent_id'=>$id])->select();
        //获取菜单在中间表匹配的角色
        $menusRole = $menu->roles;
        //删除中间表和菜单表数据
        $menu->deleteMenu($menusRole);
        $menu->delete();
        foreach ($childMenu as $val){
            $childMenuRole = $val->roles;
            $val->deleteMenu($childMenuRole);
            $val->delete();
        }
    }


    public function nav($userid)
    {
        $userPermisson = $this->userService->userPermission($userid);
        //对菜单进行二级递归排序
        $menuList = treeData($userPermisson['userPermission']);
        $data = ['menuList' => $menuList, 'permissions' => $userPermisson['userAccess']];
        return $data;
    }
}
