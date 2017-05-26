<?php
/*************************************************************************
	File Name: Abstract.php
	Author: fannner
	Created Time: 2017年05月25日 星期四 16时58分34秒
 ************************************************************************/
abstract class AbstractController extends Yaf_Controller_Abstract {
	
	public $params = array();

    public function init() {
        $this->params = $this->getParams(); 
    }

    /**
     * 封装的获取query post param中的参数,
     * method=all是cgi模式下获取顺序为 
     * query post param 直到获取到为止,不存在返回null
     *
     *@param $key string
     *@param $method all post param query
     *@return mixed | null 
     */
    public function getParam($key,$method='all') {
        if ($this->getRequest()->isCli()) {
            return $this->getRequest()->getParam($key);
        } else {
            $method = strtolower($method);
            $ret    = $this->getParams();
            $ret    = isset($ret[$key])?$ret[$key]:null;
        }
        return $ret;
    }

    /**
     * 获取所有的参数
     *
     * @param $method all post query param
     * @return array
     */
    public function getParams($method='all') {
        $method= strtolower($method);
        switch ($method) {
            case 'get' :
                $ret = $this->getRequest()->getQuery();
                break;
            case 'post':
                $ret = $this->getRequest()->getPost();
                break;
            case 'param':
                $ret = $this->getRequest()->getParams();
                break;
            case 'all':
                $ret = array_merge($this->getRequest()->getQuery(),$this->getRequest()->getPost(),$this->getRequest()->getParams());
                break;
            default:
                $ret = array();
                break;
        }
        return $ret;
    }

    /**
     * ajax Return data
     * 
     * @param int $errno error no 
     * @param mixed $data data
     * @param string $errmsg  error msg
     * @access public
     * @return boolean true
     */
    public function ajaxReturn($errno = 0, $data = null, $errmsg = null) {
        $ret = array(
            'error' => array(
                'code' => intval($errno),
            ),
        );
        if (!is_null($data)) {
            $ret['data'] = $data;  
        }
        if (!is_null($errmsg)) {
            $errmsg = htmlspecialchars($errmsg); 
            $ret['error']['msg'] = $errmsg;
        }
        $jsonRet = json_encode($ret);
        if (isset($_GET['callback']) && preg_match("/^[a-zA-Z0-9_]+$/i", $_GET['callback'])) {
            $jsonRet = "/**/" . "{$_GET['callback']}($jsonRet)";
        }
        echo $jsonRet;
        return true;
    }
}
