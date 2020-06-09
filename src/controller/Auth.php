<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/9
 * Time: 10:35
 */

namespace Finley\authPlugins\controller;
use Finley\authPlugins\service\MenuService;
use Finley\authPlugins\validate\IDMustBePositiveInt;

class Auth extends Base
{
    public $menuService;
    public function __construct()
    {
        $this->menuSevice = new MenuService();
    }

    //获取用户的菜单路由以及api访问权限
    public function getUserPermission($userId){
        (new IDMustBePositiveInt())->goCheck(["id"=>$userId]);
        $data = $this->menuSevice->nav($userId);
        return SuccessNotify($data);
    }

    public function checkUserPermission($userId,$perms){
        (new IDMustBePositiveInt())->goCheck(["id"=>$userId]);
        $data = $this->menuSevice->nav($userId);
        if(!in_array($perms,$data['permissions'])){
            return false;
        }else{
            return true;
        }
    }

}