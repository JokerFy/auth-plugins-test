<?php

namespace Finley\authPlugins\model;

class Menu extends Base
{
    protected $table = 'sys_menu';
    protected $pk = "menu_id";
    protected $type = [
        'type' => 'integer'
    ];

    //菜单拥有哪些角色
    public function roles()
    {
        return $this->belongsToMany('Role','sys_role_menu','role_id', 'menu_id');
    }

    //删除中间表中的menu权限
    public function deleteMenu($menu)
    {
        return $this->roles()->detach($menu);
    }

    public static function insertMenu($data)
    {
        return self::create($data);
    }
}
