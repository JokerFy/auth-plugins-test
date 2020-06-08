<?php

namespace Finley\authPlugins\lib;

class Safe
{

    // 密码加密
    public static function setPassword($pwd,$salt)
    {
        $pwd = md5(md5($pwd).$salt);
        return $pwd;
    }

    // 生成令牌
    public static function generateToken()
    {
        $str = md5(uniqid(md5(microtime(true)), true)); //uniqid第二个参数加true会带上一个额外的内容避免多机部署token重复
        $randChar = getRandChar(32);
        $token = sha1($str . $randChar);
        $data = [
            'token'=>$token,
            'expire'=>time()+3600
        ];
        return $data;
    }

    //生成随机字符串
    function getRandChar($length)
    {
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;
        for ($i = 0;
             $i < $length;
             $i++) {
            $str .= $strPol[rand(0, $max)];
        }
        return $str;
    }
}
