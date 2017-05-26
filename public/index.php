<?php
/*************************************************************************
	File Name: index.php
	Author: fannner
	Created Time: 2017年04月09日 星期日 17时09分49秒
 ************************************************************************/
static $_db = NULL;//数据库静态变量
define("DS", '/');
define('APPLICATION_PATH', dirname(__FILE__).DS.'..');//指向public上一级的目录 ../  
$application = new Yaf_Application( APPLICATION_PATH . "/conf/application.ini");  
$application->bootstrap()->run();
