<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/8
 * Time: 11:26
 */

namespace Finley\authPlugins\tests;


use Finley\authPlugins\controller\Role;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    public $control;
    public $phpunit_info;
    public function __construct(string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->control = new Role();
    }

    public function testGetRoleById()
    {
        $res = $this->control->getInfoById(1);
        $this->assertTrue(!empty($res["data"]));
    }

    public function testInsertRole()
    {
        $data = ['role_name'=>"phpunit_test",'remark'=>123456,'menu_id_list'=>"1,2,3"];
        $res = $this->control->insertRole($data);
        $this->assertTrue(!empty($res["msg"]));
    }

    public function testUpdateRoleById()
    {
        $rtn = $this->control->getInfoByRoleName("phpunit_test");
        $info = $rtn['data'];
        $data = ['role_id'=>$info['role_id'],'role_name'=>"phpunit_test_update",'remark'=>123456,'menu_id_list'=>"1,2,3"];
        $res = $this->control->updateRoleById($data);
        $this->assertTrue(!empty($res["msg"]));
    }

    public function testDeleteRoleById()
    {
        $rtn = $this->control->getInfoByRoleName("phpunit_test");
        $info = $rtn['data'];
        $res = $this->control->deleteRoleById($info['role_id']);
        $this->assertTrue(!empty($res["msg"]));
    }

}