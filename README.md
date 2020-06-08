# auth-plugins
1.composer require finley/auth-plugin-test，复制sql文件夹下的auth.sql到数据库运行，然后通过如下方法实例化Orm配置
``
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
``  
2.单元测试文件写在tests文件下了，运行通过如 ../../vendor/bin/phpunit LoginTest.php
来执行  
3.模型使用了think-orm，可参考https://github.com/top-think/think-orm  
4.验证器使用了think-validate，可参考https://github.com/top-think/think-validate  
5.自己写了个异常处理，学习一下。  
6.通过在控制器基类中require了common和database文件来初始化，要优化成参数传递的方式。  

