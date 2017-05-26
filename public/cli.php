<?php
/*************************************************************************
	File Name: cli.php
	Author: fanner
	Created Time: 2017年05月16日 星期二 16时38分20秒
 ************************************************************************/
if (php_sapi_name()!='cli') {
    exit('this is cli interface');
}

defined('DS')        || define('DS', '/');
//define('APPLICATION_PATH', dirname(__FILE__).DS.'..');//指向public上一级的目录 ../  
defined('APPLICATION_PATH') || define('APPLICATION_PATH', dirname(__FILE__) . DS.'..');
//defined('USER_NEED_CHECK') || define ('USER_NEED_CHECK',0);
//defined('IS_CLI_INTERFACE') || define('IS_CLI_INTERFACE',1);

$config = array(
    'ap' => array(
        'directory' => APPLICATION_PATH,
    ),
); 

// set cli request params like "query_str=a=c&b=d"
// request uri params auto from "request_uri=/controller/action"
$queryKeyStr = 'query_str=';
$simpleRequest = new Yaf_Request_Simple();
foreach ($argv as $cliParams) {
	if (stripos($cliParams, $queryKeyStr) === 0) {
		parse_str(substr($cliParams, strlen($queryKeyStr)), $requestParams);
		foreach ($requestParams as $key => $val) {
			$simpleRequest->setParam($key, $val);
        }
        break;
    }
}
$application = new Yaf_Application( APPLICATION_PATH . "/conf/application.ini");  
$application->bootstrap()->getDispatcher()->dispatch($simpleRequest);
