<?php

header("Content-Type:text/html;charset=utf-8");

 error_reporting(E_ALL);
 ini_set( 'display_errors', 'On' );

 // 定义应用目录
 $app = 'Application';
 define('APP_PATH','./Application/');

 define('APP_DEBUG',True);
 define('TMPL','./Template/');

 //define('ROOT_PATH',dirname(__FILE__));

 //define('ROOT_APP',ROOT_PATH.'/'.$app);
