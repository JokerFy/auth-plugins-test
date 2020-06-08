<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/4
 * Time: 17:15
 */
use think\facade\Db;
function initOrm($params){
    // 数据库配置信息设置（全局有效）
    Db::setConfig([
        // 默认数据连接标识
        'default'     => 'mysql',
        // 数据库连接信息
        'connections' => [
            'mysql' => [
                // 数据库类型
                'type'     => 'mysql',
                // 主机地址
                'hostname' => '127.0.0.1',
                // 用户名
                'username' => 'root',
                // 数据库名
                'database' => 'mydb',
                'password'  => '123456789',
                // 数据库编码默认采用utf8
                'charset'  => 'utf8mb4',
                // 数据库表前缀
                'prefix'   => '',
                'collation' => 'utf8mb4_general_ci',
                // 数据库调试模式
                'debug'    => true,
            ],
        ],
    ]);
}
