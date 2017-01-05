<?php
//dezend by  QQ:2172298892
namespace http\base\controllers;

abstract class BackendController extends BaseController
{
	public function __construct()
	{
		parent::__construct();
		define('__TPL__', __ROOT__ . 'resources/assets/console/');
		require CONF_PATH . 'constant.php';
		$helper_list = array('time', 'base', 'common', 'main', 'insert', 'goods');
		$this->load_helper($helper_list);
		$this->db_config = \base\Config::get('DB.default');
		$this->ecs = $GLOBALS['ecs'] = new \classes\ecshop($this->db_config['DB_NAME'], $this->db_config['DB_PREFIX']);
		$this->db = $GLOBALS['db'] = new \classes\mysql();

		if (!defined('INIT_NO_USERS')) {
			$this->sess = $GLOBALS['sess'] = new \classes\session($this->db, $this->ecs->table('sessions'), $this->ecs->table('sessions_data'));
			define('SESS_ID', $this->sess->get_session_id());
			if (isset($_SESSION['admin_id']) && empty($_SESSION['admin_id']) && isset($_SESSION['admin_name']) && !empty($_SESSION['admin_name'])) {
				$condition['user_name'] = $_SESSION['admin_name'];
				$_SESSION['admin_id'] = $this->model->table('admin_user')->field('user_id')->where($condition)->one();
			}
		}

		$GLOBALS['_CFG'] = load_config();
		$GLOBALS['_CFG']['template'] = 'default';
		c('shop', $GLOBALS['_CFG']);
		$this->checkLogin();
		l(require LANG_PATH . c('shop.lang') . '/common.php');
	}

	public function display($tpl = '', $return = false, $isTpl = true)
	{
		$tpl = $this->getTpl($tpl, $isTpl);
		return parent::display($tpl, $return, $isTpl);
	}

	public function message($msg, $url = NULL, $type = '1', $waitSecond = 3)
	{
		if ($url == NULL) {
			$url = 'javascript:history.back();';
		}

		if ($type == '2') {
			$title = l('error_information');
		}
		else {
			$title = l('prompt_information');
		}

		$data['title'] = $title;
		$data['message'] = $msg;
		$data['type'] = $type;
		$data['url'] = $url;
		$data['second'] = $waitSecond;
		$this->assign('data', $data);
		$this->display('admin/message');
		exit();
	}

	protected function ectouchUpload($key = '', $upload_dir = 'images', $size = 2)
	{
		$config = array('maxSize' => 1024 * 1024 * $size, 'allowExts' => explode(',', 'jpg,jpeg,gif,png,bmp,mp3,amr,mp4'), 'rootPath' => dirname(ROOT_PATH) . '/', 'savePath' => 'data/attached/' . $upload_dir . '/');
		$upload = new \libraries\Upload($config);

		if (!$upload->upload($key)) {
			return array('error' => 1, 'message' => $upload->getError());
		}
		else {
			return array('error' => 0, 'message' => $upload->getUploadFileInfo());
		}
	}

	private function checkLogin()
	{
		$condition['user_id'] = isset($_SESSION['admin_id']) ? intval($_SESSION['admin_id']) : 0;
		$action_list = $this->model->table('admin_user')->field('action_list')->where($condition)->one();
		if (empty($action_list) && (strpos(APP_NAME, $action_list) === FALSE) && ($action_list != 'all')) {
			$this->redirect('../admin/index.php?act=main');
		}
	}

	private function getTpl($tpl = '', $isTpl = false)
	{
		if ($isTpl) {
			$tpl = (empty($tpl) ? strtolower(CONTROLLER_NAME) . c('TPL.TPL_DEPR') . ACTION_NAME : $tpl);
			$base_themes = ROOT_PATH . 'statics/';
			$base_views = ROOT_PATH . 'resources/views/';
			$base_custom = ROOT_PATH . 'app/custom/' . APP_NAME . '/views/' . $tpl . c('TPL.TPL_SUFFIX');
			$extends_tpl = APP_NAME . '/' . $tpl . c('TPL.TPL_SUFFIX');

			if (file_exists($base_custom)) {
				$tpl = 'app/custom/' . APP_NAME . '/views/' . $tpl;
			}
			else if (file_exists($base_themes . $extends_tpl)) {
				$tpl = 'statics/' . APP_NAME . '/' . $tpl;
			}
			else if (file_exists($base_views . 'base/' . $tpl . c('TPL.TPL_SUFFIX'))) {
				$tpl = 'resources/views/base/' . $tpl;
			}
			else if (file_exists($base_views . $extends_tpl)) {
				$tpl = 'resources/views/' . APP_NAME . '/' . $tpl;
			}
			else {
				$tpl = 'app/http/' . APP_NAME . '/views/' . $tpl;
			}
		}

		return strtolower($tpl);
	}

	public function admin_priv($priv_str, $msg_type = '', $msg_output = true)
	{
		$condition['user_id'] = isset($_SESSION['admin_id']) ? intval($_SESSION['admin_id']) : 0;
		$action_list = $this->model->table('admin_user')->field('action_list')->where($condition)->one();
		if (empty($action_list) || ((stripos($action_list, $priv_str) === FALSE) && ($action_list != 'all'))) {
			$this->redirect('../admin/index.php?act=main');
		}
	}
}

?>
