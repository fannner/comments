<?php
/*************************************************************************
	File Name: Index.php
	Author: fannner
	Created Time: 2017年04月15日 星期六 17时51分28秒
 ************************************************************************/
class Dao_IndexModel extends Dao_AbstractModel {

	//private static $_table = 'song_comment';
	private static $_table = 'song_comment_v1';

	private static $instance = null;

	/**
     * 获取db句柄
     * @access public
     * @param
     * @return void
     */
    public static function getInstance(){
        if(self::$instance === null){
            self::$instance = Db_NewPdo::getInstance();
        }
        return self::$instance;
    }	

	public function getComments($pageIndex, $pageNum, $desc = true) {
		
		$db = self::getInstance();

		$fileds = array("*");
		$conds = null;
		$append = null;

		if (empty($pageIndex) || empty($pageNum)) {
			return array();
		}
		
		if ($desc) {
			$append .= "order by create_time desc";
		}

		$limit = $pageNum;
		$offset = ($pageIndex - 1) * $pageNum;
		$append .= " limit {$limit} offset {$offset}";
		return $db->select(self::$_table, $fileds, $conds, $append);
	}

	public function getTotalPage() {
		
		$db = self::getInstance();
		return $db->selectCount(self::$_table);
	}
}
