<?php
/*************************************************************************
	File Name: Index.php
	Author: fannner
	Created Time: 2017年04月09日 星期日 17时13分29秒
 ************************************************************************/
class IndexController extends AbstractController {
	public function indexAction() {//默认Action
		$indexService = new Service_IndexModel();
		//$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		//$num = isset($_GET['num']) ? intval($_GET['num']) :16;
		$page = empty($this->params['page']) ? 1 : $this->params['page'];
		
		$commentsRet = $indexService->getComments($page, $num = 20);
		foreach ($commentsRet as $index => &$item) {
			//$tmp = unserialize($item['commenter_content']);
			$tmp = json_decode($item['commenter_content'], true);
			$item['commenter_content'] = $tmp['content'];
		}
		$totalRet = $indexService->getTotalPage($num);
		$totalPage = empty($totalRet) ? 0 : $totalRet;
		$this->getView()->assign('content', $commentsRet);
		$this->getView()->assign('total', $totalPage);
		$this->getView()->display('index.tpl');
	}
}
