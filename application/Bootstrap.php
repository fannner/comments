<?php
/*************************************************************************
	File Name: Bootstrap.php
	Author: fannner
	Created Time: 2017年04月12日 星期三 20时57分29秒
************************************************************************/
class Bootstrap extends Yaf_Bootstrap_Abstract{
	
	/**
     * 本地类自动加载
     * @param Yaf_Dispatcher $dispatcher
     */
    public function _initLocalClass(Yaf_Dispatcher $dispatcher) {
        $loader = Yaf_Loader::getInstance();
		$loader->registerLocalNamespace(array("Smarty",'Db', 'Third'));
	}	
	
	/**
	 * smarty初始化
	 * @param Yaf_Dispatcher $dispatcher
	 */
	public function _initSmarty(Yaf_Dispatcher $dispatcher) {
		//init smarty view engine 
		$smarty = new Smarty_Adapter(null, Yaf_Application::app()->getConfig()->smarty);
		Yaf_Dispatcher::getInstance()->setView($smarty);
	}


	/**
	 * 注册conf类
	 */
	public function _initConfig(Yaf_Dispatcher $dispatcher) {
		Yaf_Registry::set("config", Yaf_Application::app()->getConfig());
	}
}
