<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/3
 * Time: 15:59
 */
require_once './vendor/autoload.php';

$capsule = new \Illuminate\Database\Capsule\Manager;
// 创建链接
$capsule->addConnection(require './conf/database.php');
// 设置全局静态可访问DB
$capsule->setAsGlobal();
// 启动Eloquent （如果只使用查询构造器，这个可以注释）
$capsule->bootEloquent();

$controller = new \Finley\authPlugins\Controller\TestController();
$controller->index();