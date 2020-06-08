<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/8
 * Time: 11:26
 */

namespace Finley\authPlugins\tests;


use Finley\authPlugins\controller\Login;
use Finley\authPlugins\controller\User;
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    public $control;

    public function __construct(string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->control = new Login();
    }

    public function testRegister()
    {
        $data = ['username'=>'phpunit_test','password'=>'123123'];
        $res = $this->control->register($data);
        $this->assertTrue(!empty($res["msg"]));
    }

    public function testLogin()
    {
        $data = ['username'=>'phpunit_test','password'=>'123123'];
        $res = $this->control->login($data);
        $this->assertTrue(!empty($res["msg"]));
    }

    public function testDeleteUserById()
    {
        $rtn = $this->control->getInfoByUserName("phpunit_test");
        $info = $rtn['data'];
        $res = $this->control->deleteUserById($info['user_id']);
        $this->assertTrue(!empty($res["msg"]));
    }



}