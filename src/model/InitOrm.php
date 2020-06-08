<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/8
 * Time: 16:15
 */

namespace Finley\authPlugins\model;


use think\facade\Db;

class InitOrm
{
    protected static $static;

    private function __construct()
    {
    }

    public static function getInstance($params){
        if(!empty(self::$static)){
            return self::$static;
        }else{
            Db::setConfig([
                // 默认数据连接标识
                'default'     => 'mysql',
                // 数据库连接信息
                'connections' => [
                    'mysql' => [
                        // 数据库类型
                        'type'     => 'mysql',
                        // 主机地址
                        'hostname' => $params['hostname'],
                        // 用户名
                        'username' => $params['username'],
                        // 数据库名
                        'database' =>  $params['database'],
                        'password'  => $params['password'],
                        // 数据库编码默认采用utf8
                        'charset'  => $params['charset'],
                        // 数据库表前缀
                        'prefix'   => $params['prefix'],
                        'collation' => $params['collation'],
                        // 数据库调试模式
                        'debug'    => $params['debug'],
                    ],
                ],
            ]);
            return self::$static = new self();
        }
    }
}