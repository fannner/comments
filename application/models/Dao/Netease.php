<?php
/*************************************************************************
	File Name: Netease.php
	Author: fannner
	Created Time: 2017年04月15日 星期六 17时51分28秒
 ************************************************************************/
class Dao_NeteaseModel extends Dao_AbstractModel {

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

	public function getSongById($songId, $singer, $commentId, $desc = true) {
		
		$db = self::getInstance();

		$fileds = array("*");
		$conds = array(
			'song_id=' => $songId,
			'song_singer=' => $singer,
			'commenter_id=' => $commentId,  
		);
		return $db->select(self::$_table, $fileds, $conds);
	}

	public function insertComments($songId, $songName, $singer, $commenterContent, $index) {
		$db = self::getInstance();
		$row = array(
			'song_id' => $songId,
			'song_name' => $songName,
			'song_singer' => $singer,
			'commenter_content' => json_encode($commenterContent),
			'commenter_id' => $index['commentId'],
			'commenter_nickname' => $index['user']['nickname'],
			'commenter_avatar' => $index['user']['avatarUrl'],
			'be_liked_count' => $index['likedCount'],
			'create_time' => time(),
		);
		$db->insert(self::$_table, $row);
	}

}
