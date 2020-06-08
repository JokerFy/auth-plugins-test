<?php

namespace Finley\authPlugins\validate;


class MenuValidate extends BaseValidate
{
    protected $rule = [
        'menu_id' => 'require|number',
        'parent_id' => 'require|number',
        'name' => 'require|min:2',
        'url' => 'require',
        'type' => 'require',
    ];

    protected $scene = [
        'add'   =>  ['parent_id','name','url','type'],
        'edit'  =>  ['menu_id','parent_id','name','url','type'],
    ];
}
