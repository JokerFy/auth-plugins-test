<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/3
 * Time: 17:23
 */

namespace Finley\authPlugins\model;

use think\Model;

class Base extends \think\Model
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    function collection($resultSet)
    {
        $item = current($resultSet);
        if ($item instanceof Model) {
            return \think\model\Collection::make($resultSet);
        } else {
            return \think\Collection::make($resultSet);
        }
    }
}