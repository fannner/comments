<?php
/*************************************************************************
	File Name: Index.php
	Author: fannner
	Created Time: 2017年04月15日 星期六 17时28分19秒
 ************************************************************************/
class Service_IndexModel extends Service_AbstractModel {
	
	public function getComments($page, $num) {
		$indexDao = new Dao_IndexModel();
		return $indexDao->getComments($page ,$num);
	}

	public function getTotalPage($pageNum) {
		$pageDao = new Dao_IndexModel();
		$total = $pageDao->getTotalPage();
		if ($total != false) {
			return ceil($total/$pageNum);
		}
		return;
	}
}
