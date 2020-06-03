<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/3
 * Time: 15:39
 */
namespace Finley\authPlugins\Controller;

use Finley\authPlugins\Model\Test;

class TestController
{
    public function index(){
        $model = new Test();
        print_r($model->getOne(48));
    }
}