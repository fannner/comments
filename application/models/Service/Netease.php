<?php
/*************************************************************************
	File Name: Netease.php
	Author: fannner
	Created Time: 2017年05月16日 星期二 18时28分19秒
 ************************************************************************/
class Service_NeteaseModel extends Service_AbstractModel {

	const COMMENTS_NUM = 5000;

	const COMMENTS_LIKED = 5000;

	public function insertComments($data, $songId, $songName, $singer) {
		if (!$data) {
			return;
		}
		$arrParseRet = json_decode($data, true);
		$neteaseDao = new Dao_NeteaseModel();

		//筛选,准入门槛-总评论数不小于5000
		if (isset($arrParseRet['total']) && $arrParseRet['total'] >=self::COMMENTS_NUM) {
			foreach($arrParseRet['hotComments'] as $index){
				//筛选,准入门槛-总点赞次数不小于5000
				if (isset($index['likedCount']) && isset($index['user']) && $index['likedCount'] >= self::COMMENTS_LIKED) {
					//检查此条评论是否在库中,不在则insert
					$songInfo = $neteaseDao->getSongById($songId, $singer, $index['commentId']);
					if (empty($songInfo)) {
						$commenterContent = array();
						$commenterContent['content'] = $index['content'];
						if (!empty($index['beReplied'])) {                                              
							foreach ($index['beReplied'] as $beReplied) {
								$commenterContent['bereplied']['nickname'] = $beReplied['user']['nickname'];
								$commenterContent['bereplied']['id'] = $beReplied['user']['userId'];
								$commenterContent['bereplied']['avatar'] = $beReplied['user']['avatarUrl'];
								$commenterContent['bereplied']['content'] = $beReplied['content'];
							}
							//$index['beReplied'] = $commenterContent;
						}
						unset($index['beReplied']);
						unset($index['content']);
						$neteaseDao->insertComments($songId, $songName, $singer, $commenterContent, $index);
					}
				}
			}
		}
	}

}
