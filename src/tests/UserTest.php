<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/8
 * Time: 11:26
 */

namespace Finley\authPlugins\tests;


use Finley\authPlugins\controller\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public $control;

    public function __construct(string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->control = new User();
    }


    public function testGetUserById()
    {
        $res = $this->control->getInfoById(1);
        $this->assertTrue(!empty($res["msg"]));
    }

    public function testInsertUser()
    {
        $data = ['username'=>"phpunit_test",'password'=>123456,'role_id_list'=>[3]];
        $res = $this->control->insertUser($data);
        $this->assertTrue(!empty($res["msg"]));
    }

    public function testUpdateUserById()
    {
        $rtn = $this->control->getInfoByUserName("phpunit_test");
        $info = $rtn['data'];
        $data = ['user_id'=>$info['user_id'],'username'=>"phpunit_test222",'password'=>123456,'role_id_list'=>[3,4]];
        $res = $this->control->updateUserById($data);
        $this->assertTrue(!empty($res["msg"]));
    }

    public function testDeleteUserById()
    {
/*      $rtn = $this->control->getInfoByUserName("phpunit_test");
        $info = $rtn['data'];
        $res = $this->control->deleteUserById($info['user_id']);
        $this->assertTrue(!empty($res["msg"]));*/
        $this->assertTrue(true);
    }

}