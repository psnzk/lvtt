<?php
//dezend by  QQ:2172298892
namespace http\base\controllers;

abstract class BaseController extends \base\Controller
{
	protected $model;
	protected $cache;
	protected $pager = '';

	public function __construct()
	{
		define('APP_PATH', BASE_PATH . 'http/' . APP_NAME . '/');
		define('__ROOT__', rtrim(dirname($_SERVER['SCRIPT_NAME']), '\\/') . '/');
		define('__PUBLIC__', __ROOT__ . 'resources/assets/');
		define('__HOST__', get_domain());
		define('__URL__', __HOST__ . __ROOT__);
		define('__TPL__', __ROOT__ . 'statics/');
		define('__JS__', __ROOT__ . 'app/http/base/js/');
		$this->model = new \http\base\models\BaseModel();
		$GLOBALS['cache'] = $this->cache = new \base\Cache('memcached');
		$GLOBALS['smarty'] = $this->_getView();
		$this->init_first();
		$this->SaaS();
	}

	protected function getApiCityName($ip = '')
	{
		$ip = (empty($ip) ? get_client_ip() : $ip);
		$url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=' . $ip;
		$data = \libraries\Http::doGet($url);
		$str = json_decode($data, true);

		if ($str['city'] != '') {
			$region = $str['city'];
		}
		else {
			$region = $str['province'];
		}

		return $region;
	}

	private function init_first()
	{
		$init_path = APP_PATH . 'config/';
		$init_cache = ROOT_PATH . 'storage/app/' . APP_NAME . '/';
		$db_config = \base\Config::get('DB.default');

		if (!file_exists($init_cache)) {
			if (!@mkdir($init_cache, 511, true)) {
				throw new \Exception('Can not create dir \'' . $init_cache . '\'', 500);
			}
		}

		if (!is_writable($init_cache)) {
			@chmod($init_cache, 511);
		}

		if (!file_exists($init_cache . 'installed.lock')) {
			if (file_exists($init_path . 'db.sql')) {
				$this->init_execute($init_path . 'db.sql', '{pre}', $db_config['DB_PREFIX']);
			}

			if (file_exists($init_path . 'init.php')) {
				require $init_path . 'init.php';
			}

			file_put_contents($init_cache . 'installed.lock', 'lock');
		}
	}

	private function init_execute($sql_path, $old_prefix = '', $new_prefix = '', $separator = ";\n")
	{
		$commenter = array('#', '--');

		if (!file_exists($sql_path)) {
			return false;
		}

		$content = file_get_contents($sql_path);
		$content = str_replace(array($old_prefix, "\r"), array($new_prefix, "\n"), $content);
		$segment = explode($separator, trim($content));
		$data = array();

		foreach ($segment as $statement) {
			$sentence = explode("\n", $statement);
			$newStatement = array();

			foreach ($sentence as $subSentence) {
				if ('' != trim($subSentence)) {
					$isComment = false;

					foreach ($commenter as $comer) {
						if (preg_match('/^(' . $comer . ')/is', trim($subSentence))) {
							$isComment = true;
							break;
						}
					}

					if (!$isComment) {
						$newStatement[] = $subSentence;
					}
				}
			}

			$data[] = $newStatement;
		}

		foreach ($data as $statement) {
			$newStmt = '';

			foreach ($statement as $sentence) {
				$newStmt = $newStmt . trim($sentence) . "\n";
			}

			if (!empty($newStmt)) {
				$result[] = $newStmt;
			}
		}

		$db = new \base\Model();

		foreach ($result as $value) {
			$value = trim($value);

			if (empty($value)) {
				continue;
			}

			$db->query($value);
		}
	}

	protected function SaaS()
	{
		$wechat_path = BASE_PATH . 'http/wechat';
		$drp_path = BASE_PATH . 'http/drp';

		if (file_exists(ROOT_PATH . 'storage/saas_mode.txt')) {
			$db_config = \base\Config::get('DB.default');
			$site_url = 'aHR0cDovL2Nsb3VkLmRzY21hbGwuY24vaW5kZXgucGhwP2M9c2l0ZSZhPWxldmVsJm1hbGxfZG9tYWluPQ==';
			$site_rsp = \libraries\Http::curlGet(base64_decode($site_url) . substr($db_config['DB_NAME'], 3));
			$site_rsp = json_decode($site_rsp, true);

			if ($site_rsp['code'] == -1) {
				$mall_level = 0;
			}
			else {
				$mall_level = $site_rsp['data']['mall_level'];
			}

			if ($mall_level <= 0) {
				$wechat_path .= time();
				$drp_path .= time();
			}
			else if ($mall_level == 1) {
				$drp_path .= time();
			}
		}

		define('APP_WECHAT_PATH', $wechat_path);
		define('APP_DRP_PATH', $drp_path);
	}

	protected function load_helper($files = array(), $type = 'base')
	{
		if (!is_array($files)) {
			$files = array($files);
		}

		$base_path = ($type == 'app' ? APP_PATH : BASE_PATH);

		foreach ($files as $vo) {
			$helper = $base_path . 'helpers/' . $vo . '_helper.php';

			if (file_exists($helper)) {
				require_once $helper;
			}
		}
	}

	protected function pageLimit($url, $num = 10)
	{
		$url = str_replace(urlencode('{page}'), '{page}', $url);
		$page = (isset($this->pager['obj']) && is_object($this->pager['obj']) ? $this->pager['obj'] : new \libraries\Page());
		$cur_page = $page->getCurPage($url);
		$limit_start = ($cur_page - 1) * $num;
		$limit = $limit_start . ',' . $num;
		$this->pager = array('obj' => $page, 'url' => $url, 'num' => $num, 'cur_page' => $cur_page, 'limit' => $limit);
		return $limit;
	}

	protected function pageShow($count)
	{
		return $this->pager['obj']->show($this->pager['url'], $count, $this->pager['num']);
	}

	protected function ectouchUpload($key = '', $upload_dir = 'images')
	{
		$config = array('maxSize' => 1024 * 1024 * 2, 'allowExts' => explode(',', 'jpg,jpeg,gif,png,bmp,mp3,amr,mp4'), 'rootPath' => dirname(ROOT_PATH) . '/', 'savePath' => 'data/attached/' . $upload_dir . '/');
		$upload = new \libraries\Upload($config);

		if (!$upload->upload($key)) {
			return array('error' => 1, 'message' => $upload->getError());
		}
		else {
			return array('error' => 0, 'message' => $upload->getUploadFileInfo());
		}
	}

	protected function sentry($e, $type = 0)
	{
		$client = new \Raven_Client('https://ae2118aa1c3149c5bba492ed9abaf43f:2e4b9be6f4d9495eb3f0a44f28484893@sentry.io/106949');
		$error_handler = new \Raven_ErrorHandler($client);
		$error_handler->registerExceptionHandler();
		$error_handler->registerErrorHandler();
		$error_handler->registerShutdownFunction();

		if ($type) {
			$client->captureMessage($e);
		}
		else {
			$client->captureException($e);
		}
	}
}

?>
