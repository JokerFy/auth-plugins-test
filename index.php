<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/3
 * Time: 15:59
 */
require_once './vendor/autoload.php';

$config = [
    'type'     => 'mysql',
    'hostname' => '127.0.0.1',
    'username' => 'root',
    'database' => 'mydb',
    'password'  => '123456789',
    'charset'  => 'utf8mb4',
    'collation' => 'utf8mb4_general_ci',
];
\Finley\authPlugins\model\InitOrm::getInstance($config);
$controller = new \Finley\authPlugins\controller\Role();
print_r($controller->getInfoById(1));
//1
//print_r($controller->insertRole(['role_name'=>"fangyi2",'remark'=>123456,'menu_id_list'=>[1,2,3]]));
//print_r($controller->updateRole(['role_id'=>12,'role_name'=>"fangyi3",'remark'=>123456,'menu_id_list'=>[1,2,3]]));
//print_r($controller->deleteRoleById(12));
//print_r($controller->updateRoleById(['role_id'=>16,'role_name'=>"phpunit_test_update","remark"=>"987654321",'menu_id_list'=>"1,2,3"]));
//print_r($controller->login(['username'=>'admin','password'=>'123123']));
//print_r($controller->updateUser(['user_id'=>7,'username'=>"fangyi2",'password'=>123456,'role_id_list'=>[3]]));
