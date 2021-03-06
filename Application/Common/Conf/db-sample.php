<?php
//项目配置文件
return array(
//数据库配置信息
// MYSQL数据库类型
'DB_TYPE'   =>  'mysqli',
// MYSQL服务器地址
'DB_HOST'   =>  'DBHOST',
// MYSQL数据库名
'DB_NAME'   => 'DBNAME',
// MYSQL用户名
'DB_USER'   => 'DBUSER',
// MYSQL密码
'DB_PWD'    => 'DBPWD',
// MYSQL端口
'DB_PORT'   => 3306,
// MYSQL数据库表前缀
'DB_PREFIX' => '',
//REDIS服务主机IP
'REDIS_HOST' => 'REDISHOST',
//REDIS服务端口
'REDIS_PORT' => '6379',
//REDIS连接时长 默认为0 不限制时长
'REDIS_TIMEOUT' => '30',
//REDIS数据库名
'REDIS_DBNAME' => '0',
//REDIS连接类型 1普通连接 2长连接
'REDIS_CTYPE' => '1',
//REDIS密码
'REDIS_PWD' => '',
);
