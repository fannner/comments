<?php
/*************************************************************************
	File Name: Netease.php
	Author: fannner
	Created Time: 2017年04月15日 星期六 17时51分28秒
************************************************************************/
class Dao_NeteaseModel extends Dao_AbstractModel {

	/*
	 Create Table: CREATE TABLE `song_comment` (
		 `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
		 `song_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '歌曲id',
		 `song_name` varchar(100) NOT NULL DEFAULT '' COMMENT '歌曲名',
		 `song_singer` varchar(100) NOT NULL DEFAULT '' COMMENT '歌手',
		 `commenter_content` text COMMENT '评论',
		 `commenter_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论人id',
		 `commenter_nickname` varchar(100) NOT NULL DEFAULT '' COMMENT '评论人昵称',
		 `commenter_avatar` varchar(100) NOT NULL DEFAULT '' COMMENT '评论人图像',
		 `be_liked_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论被点赞次数',
		 `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
		 `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
		 PRIMARY KEY (`id`),
		 KEY `idx_song_name` (`song_name`) USING BTREE,
		 KEY `idx_song_singer` (`song_singer`) USING BTREE,
		 KEY `idx_song_singer_song_id_commenter_id` (`song_singer`,`song_id`,`commenter_id`) USING BTREE
	 ) ENGINE=InnoDB AUTO_INCREMENT=21742 DEFAULT CHARSET=utf8 COMMENT='歌曲评论表'
	 */

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
