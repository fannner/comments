<?php
/*************************************************************************
	File Name: Index.php
	Author: fannner
	Created Time: 2017年04月09日 星期日 17时13分29秒
 ************************************************************************/
class IndexController extends AbstractController {

	const NUM_PAGE = 20;

	/**
	 * 首页入口 index
	 *
	 * @return mixed
	 */
	public function indexAction() {
		$indexService = new Service_IndexModel();
		if (!empty($this->params['word'])) {
			$commentsRet = $indexService->getCommentsByWord(trim($this->params['word']));
		} else {
			$page = empty($this->params['page']) ? 1 : intval($this->params['page']);
			$commentsRet = $indexService->getComments($page, self::NUM_PAGE);
			$totalRet = $indexService->getTotalPage(self::NUM_PAGE);
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
