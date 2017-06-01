<?php
/*************************************************************************
	File Name: Netease.php
	Author: fannner
	Created Time: 2017年05月16日 星期日 17时13分29秒
************************************************************************/

/**
 * cli模式类，脚本运行入口
 * cli模式使用方式：php cli.php "request_uri=/Netease/getSongInfoById" "query_str=a=c&b=d"
 * 其中：request_uri表示路由path；query_str表示请求参数query
 */
class NeteaseController extends AbstractController {

	/**
	 * 根据id歌曲解析评论
	 *
	 * cli 模式运行,command:php cli.php "request_uri=/Netease/parseIdToComment"
	 */
	public function parseIdToCommentAction() {//默认Action
		//获取文件中的歌曲id信息
		//$musicFile = APPLICATION_PATH."/application/data/music_info";
		$musicFile = APPLICATION_PATH."/application/data/music_info.".date("Y-m-d");
		$handle = fopen($musicFile, "r");
		if (!$handle) {
			//@to do
		}
		$neteaseService = new Service_NeteaseModel();
		while ($line = fgets($handle, 4096)) {
			$expRet = explode("\t", trim($line));
			$url = "http://music.163.com/api/v1/resource/comments/R_SO_4_".$expRet[0]."/?rid=R_SO_4_".$expRet[0];
			if ($parseRet = $this->curlNeteaseApi($url)) {
				$neteaseService->insertComments($parseRet, $expRet[0], $expRet[1], $expRet[2]);
			}
		}
	}

	/**
	 * 根据id获取音乐信息
	 *
	 * cli 模式运行,command:php cli.php "request_uri=/Netease/getSongInfoById"
	 * 可以手动测试输入id:php cli.php "request_uri=/Netease/getSongInfoById" "query_str=id=432506345"
	 */
	public function getSongInfoByIdAction() {
		$musicFile = APPLICATION_PATH."/application/data/music_info.".date("Y-m-d");
		if ($this->getParam('id')) {
			$musicId = $this->getParam('id');
		} else {
			$music = 35447132;
			//get id from file or others
		}
		$url = 'http://music.163.com/api/search/get/web?csrf_token=';
		$postFiled = 'hlpretag=&hlposttag=&s='. $musicId . '&type=1&offset=0&total=true&limit=1';
		$musicInfo = $this->curlNeteaseApi($url, 'POST', $postFiled);
		if ($musicInfo && $formatData = json_decode($musicInfo, true)) {
			if (isset($formatData['result']['songs'][0]) && 
				isset($formatData['result']['songs'][0]['id']) && 
				isset($formatData['result']['songs'][0]['name']) &&
				isset($formatData['result']['songs'][0]['artists'][0]['name'])) {
					$music = $formatData['result']['songs'][0]['id'] ."\t" .$formatData['result']['songs'][0]['name'] ."\t" .$formatData['result']['songs'][0]['artists'][0]['name'] ."\n";
					file_put_contents($musicFile, $music, FILE_APPEND);
			}
		}
	}

	/**
	 * 获取新歌、飙升歌、热歌、原创歌
	 *
	 * cli模式，command：php cli.php "request_uri=/Netease/getHotMuscicInfo"
	 */
	public function getHotMuscicInfoAction() {
		$musicFile = APPLICATION_PATH."/application/data/music_info.".date("Y-m-d");
		$instance = Third_MusicAPI::getInstance();
		$topic = array(
			'19723756' => '云音乐飙升榜',
			'3779629'  => '云音乐新歌榜',
			'2884035'  => '网易原创歌曲榜',
			'3778678'  => '云音乐热歌榜',
		);
		foreach($topic as $key => $item) {
			$musicInfo = $instance->playlist($key);
			if ($decodeMusic = json_decode($musicInfo, true)) {
				if (isset($decodeMusic['playlist']['trackIds'])) {
					foreach ($decodeMusic['playlist']['tracks'] as $detail) {
						$music = $detail['id'] ."\t" .$detail['name'] ."\t" .$detail['ar'][0]['name']. "\n";
						file_put_contents($musicFile, $music, FILE_APPEND);
					}
				}
			}
		}
	}

	/**
	 * curl 解析接口数据
	 *
	 * @paran $musicId int
	 * @return mixed
	 */
	public function curlNeteaseApi($url, $method = 'GET', $postFiled = '') {
		$header =array(
            'Host: music.163.com',
            'Origin: http://music.163.com',
            'User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36',
            'Content-Type: application/x-www-form-urlencoded',
            'Referer: http://music.163.com/search/',
        );
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		if ($method == 'POST') {
			curl_setopt($curl, CURLOPT_POST, 1); 
			curl_setopt($curl, CURLOPT_POSTFIELDS, $postFiled);
		}
        $src = curl_exec($curl);
        $errno = curl_errno( $curl);
        $info  = curl_getinfo($curl);
        if ($errno != 0 && $info['http_code'] != 200) {
            $time = date("Y-m-d H:i:s"); 
            //write_log
			//file_put_contents("parse.log".date("Y-m-d"), var_export($time." curl errno info:    " .json_decode($info)."\n", true), FILE_APPEND);
        }   
        curl_close($curl);
        return $src;
    }
}
