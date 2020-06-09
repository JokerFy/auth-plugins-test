<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/4
 * Time: 15:54
 */

namespace Finley\authPlugins\validate;

use Finley\authPlugins\excepetion\HttpException;
use Finley\authPlugins\excepetion\InvalidArgumentException;
use Finley\authPlugins\service\UserService;
use think\Validate;

/**
 * Class BaseValidate
 * 验证类的基类
 */
class BaseValidate extends Validate
{

    public $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    /**
     * 检测所有客户端发来的参数是否符合验证类规则
     * 基类定义了很多自定义验证方法
     * 这些自定义验证方法其实，也可以直接调用
     * @throws InvalidArgumentException
     * @return true
     */
    public function goCheck($params)
    {
        if (!$this->check($params)) {
            $exception = new InvalidArgumentException(
                [
                    // $this->error有一个问题，并不是一定返回数组，需要判断
                    'msg' => is_array($this->error) ? implode(
                        ';', $this->error) : $this->error,
                ]);
            throw $exception->error();
        }
        return true;
    }

    /**
     * @param $params
     * @param $sence
     * @return bool
     * @throws InvalidArgumentException
     */
    public function sceneCheck($params,$sence){
        //必须设置contetn-type:application/json
        $result = $this->scene($sence)->check($params);
        if(!$result){
            $exception =  new InvalidArgumentException([
                // $this->error有一个问题，并不是一定返回数组，需要判断
                'msg' => is_array($this->error) ? implode(
                    ';', $this->error) : $this->error,
            ]);
            throw $exception->error();
        }
        return true;
    }


    public function checkFieldByRule($data,$rule)
    {
        if(!$this->check($data,$rule)){
            $exception =  new InvalidArgumentException([
                // $this->error有一个问题，并不是一定返回数组，需要判断
                'msg' => is_array($this->error) ? implode(
                    ';', $this->error) : $this->error,
            ]);
            throw $exception->error();
        }

        return true;
    }

    /**
     * @param array $arrays 通常传入request.post变量数组
     * @return array 按照规则key过滤后的变量数组
     * @throws InvalidArgumentException
     */
    public function getDataByRule($arrays)
    {
        if (array_key_exists('user_id', $arrays) | array_key_exists('uid', $arrays)) {
// 不允许包含user_id或者uid，防止恶意覆盖user_id外键
            throw new InvalidArgumentException([
                'msg' => '参数中包含有非法的参数名user_id或者uid'
            ]);
        }
        $newArray = [];
        if (!$this->check($arrays)) {
            $exception =  new InvalidArgumentException([
                // $this->error有一个问题，并不是一定返回数组，需要判断
                'msg' => is_array($this->error) ? implode(
                    ';', $this->error) : $this->error,
            ]);
            throw $exception->error();
        }
        foreach ($this->rule as $key => $value) {
            $newArray[$key] = $arrays[$key];
        }
        return $newArray;
    }

    protected function isPositiveInteger($value, $rule = '', $data = '', $field = '')
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        }
        return $field . '必须是正整数';
    }

    protected function isNotEmpty($value, $rule = '', $data = '', $field = '')
    {
        if (empty($value)) {
            return $field . '不允许为空';
        } else {
            return true;
        }
    }

//没有使用TP的正则验证，集中在一处方便以后修改
//不推荐使用正则，因为复用性太差
//手机号的验证规则
    protected function isMobile($value)
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

}