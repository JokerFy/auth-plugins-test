<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/8
 * Time: 11:26
 */

namespace Finley\authPlugins\tests;


use Finley\authPlugins\controller\Menu;
use PHPUnit\Framework\TestCase;

class MenuTest extends TestCase
{
    public $control;
    public $phpunit_info;
    public function __construct(string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->control = new Menu();
    }

    public function testGetMenuById()
    {
        $res = $this->control->getInfoById(1);
        $this->assertTrue(!empty($res["data"]));
    }

    public function testInsertMenu()
    {
        $data = ['name'=>'phpunit_test','parent_id'=>0,'url'=>'123456','type'=>'0'];
        $res = $this->control->insertMenu($data);
        $this->assertTrue(!empty($res["msg"]));
    }

    public function testUpdateMenuById()
    {
        $rtn = $this->control->getLastInfo();
        $info = $rtn['data'];
        $data = ['menu_id'=>$info['menu_id'],'name'=>"phpunit_test_update","parent_id"=>0,'url'=>"123456",'type'=>"0"];
        $res = $this->control->updateMenuById($data);
        $this->assertTrue(!empty($res["msg"]));
    }

    public function testDeleteMenuById()
    {
        $rtn = $this->control->getLastInfo();
        $menu_id = $rtn['data']['menu_id'];
        $res = $this->control->deleteMenuById($menu_id);
        $this->assertTrue(!empty($res["msg"]));
    }

}