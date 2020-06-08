<?php

namespace Finley\authPlugins\controller;

use Finley\authPlugins\validate\IDCollection;
use Finley\authPlugins\validate\IDMustBePositiveInt;
use Finley\authPlugins\validate\StringFieldMustBeHaveValue;

class User extends Base
{
    public $userSevice;
    public $userValidate;

    public function __construct()
    {
        $this->userSevice = new \Finley\authPlugins\service\UserService();
        $this->userValidate = new \Finley\authPlugins\validate\UserValidate();
        $this->userModel = new \Finley\authPlugins\model\User();
    }

    /**
     * 获取管理员列表
     * @auth: finley
     * @date: 2018/11/7 下午3:44
     * @param int $page //当前请求页面
     * @param int $limit //每页显示数量
     */
    public function getUserList($condition=[])
    {
        $data = $this->pageList($this->userModel,$condition);
        return SuccessNotify($data);
    }

    /**
     * 根据参数获取管理员信息
     * 管理员信息格式：
     * data: {
     * 'msg': 'success',
     * 'code': 0,
     * 'user':{
     * 'userId': '@increment',
     * 'username': '@name',
     * 'email': '@email',
     * 'mobile': /^1[0-9]{10}$/,
     * 'status': 1,
     * 'roleIdList': (1,2,3,4),
     * 'createUserId': 1,
     * 'createTime': 'datetime'
     * }}
     */
    public function getInfoById($id)
    {
        (new IDMustBePositiveInt())->goCheck(["id"=>$id]);
        $data = $this->userSevice->getInfoById($id);
        return SuccessNotify($data);
    }

    public function getInfoByUserName($name)
    {
        (new StringFieldMustBeHaveValue())->goCheck(["checkField"=>$name]);
        $data = $this->userSevice->getInfoByUserName($name);
        return SuccessNotify($data);
    }

    //删除管理员
    public function deleteUserById($id)
    {
        (new IDMustBePositiveInt())->goCheck(["id"=>$id]);
        $this->userSevice->deleteById($id);
        return SuccessNotify();
    }

    //删除管理员(批量)
    public function deleteUserByIds($ids)
    {
        (new IDCollection())->goCheck(["ids"=>$ids]);
        $this->userSevice->deleteByIds($ids);
        return SuccessNotify();
    }

    //增加管理员
    public function insertUser($data)
    {
        $this->userValidate->sceneCheck($data,'add');
        $this->userSevice->save($data);
        return SuccessNotify();
    }

    //修改管理员
    public function updateUserById($data)
    {
        $this->userValidate->sceneCheck($data,'edit');
        $this->userSevice->update($data);
        return SuccessNotify();
    }

    public function getUserPermission($id){
        $data = $this->userSevice->userPermission($id);
        return SuccessNotify($data);
    }

}
