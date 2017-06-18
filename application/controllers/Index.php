<?php
/*************************************************************************
	File Name: Index.php
	Author: fannner
	Created Time: 2017年04月09日 星期日 17时13分29秒
 ************************************************************************/
class IndexController extends AbstractController {
	public function indexAction() {//默认Action
		$indexService = new Service_IndexModel();
		if (!empty($this->params['word'])) {
			$word = trim($this->params['word']);
			$commentsRet = $indexService->getCommentsByWord($word);
		} else {
			$page = empty($this->params['page']) ? 1 : $this->params['page'];
			$commentsRet = $indexService->getComments($page, $num = 20);
			$totalRet = $indexService->getTotalPage($num);
			$totalPage = empty($totalRet) ? 0 : $totalRet;
		}
		foreach ($commentsRet as $index => &$item) {
			//$tmp = unserialize($item['commenter_content']);
			$tmp = json_decode($item['commenter_content'], true);
			$item['commenter_content'] = $tmp['content'];
		}
		$this->getView()->assign('content', $commentsRet);
		$this->getView()->assign('total', $totalPage);
		$this->getView()->display('index.tpl');
	}
}
