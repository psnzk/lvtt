<?php
//dezend by  QQ:2172298892
namespace http\base\controllers;

abstract class FrontendController extends BaseController
{
	public $province_id = 0;
	public $city_id = 0;
	public $district_id = 0;
	public $caching = false;
	public $custom = '';
	public $customs = '';

	public function __construct()
	{
		parent::__construct();
		$this->start();
		$this->geocoding();
	}

	private function geocoding()
	{
		$current_city_id = cookie('city');
		$current_city_info = get_region_name(intval($current_city_id));

		if (empty($current_city_info)) {
			$res_ip_info = \libraries\Http::doGet('https://pv.sohu.com/cityjson?ie=utf-8');
			preg_match('/\\{(.*)\\}/', $res_ip_info, $match);
			$res_city = json_decode('{' . $match[1] . '}', true);
			$res_city_name = rtrim($res_city['cname'], '市');
			$sql = 'select `region_id`, `region_name`, `parent_id` from ' . $GLOBALS['ecs']->table('region') . ' where region_type = 2 and region_name = \'' . $res_city_name . '\'';
			$current_city_info = $GLOBALS['db']->getRow($sql);
			if (empty($current_city_info) && in_array(APP_NAME, array('site'))) {
				$this->redirect(u('location/index/index'));
			}

			setcookie('province', $current_city_info['parent_id'], gmtime() + (3600 * 24 * 30));
			setcookie('city', $current_city_info['region_id'], gmtime() + (3600 * 24 * 30));
			setcookie('district', 0, gmtime() + (3600 * 24 * 30));
		}

		$this->assign('current_city', $current_city_info);
	}

	public function fetch($tpl = '', $return = true, $isTpl = false)
	{
		return $this->display($tpl, $return, $isTpl);
	}

	public function display($tpl = '', $return = false, $isTpl = true)
	{
		$tpl = $this->getTpl($tpl, $isTpl);
		if ($this->caching && $isTpl) {
			$return = true;
			$cacheKey = md5($tpl . 'display');
			$html = $this->cache->get($cacheKey);

			if (empty($html)) {
				$html = parent::display($tpl, $return, $isTpl);
				$expire = c('CACHE_EXPIRE');
				$expire = (!empty($expire) ? $expire : 86400);
				$this->cache->set($cacheKey, $html, $expire);
			}

			echo $html;
		}
		else {
			return parent::display($tpl, $return, $isTpl);
		}
	}

	protected function response($data = array(), $block = ACTION_NAME)
	{
		$result = array();
		$tpl = $this->getTpl('widget', true) . c('TPL.TPL_SUFFIX');
		$widget = file_get_contents(ROOT_PATH . $tpl);
		preg_replace('/<block\\sname="(.+?)"\\s*?>(.*?)<\\/block>/eis', '$this->parseBlock(\'\\1\',\'\\2\')', $widget);
		$content = str_replace('\\', '', $this->block[$block]);

		foreach ($data as $vo) {
			$this->assign($vo);
			$result[] = $this->fetch($content);
		}

		exit(json_encode($result));
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
			else if (file_exists($base_views . $extends_tpl)) {
				$tpl = 'resources/views/' . APP_NAME . '/' . $tpl;
			}
			else {
				$tpl = 'app/http/' . APP_NAME . '/views/' . $tpl;
			}
		}

		return $tpl;
	}

	private function parseBlock($name, $content)
	{
		$this->block[$name] = $content;
	}

	private function replaceBlock($name, $content)
	{
		$replace = (isset($this->block[$name]) ? $this->block[$name] : $content);
		return stripslashes($replace);
	}

	private function start()
	{
		$this->set_ini();
		$this->init();
		$this->init_first();
		$this->init_user();
		$this->init_gzip();
		$this->init_wxpay_jspay();
		$this->init_assign();
		$this->init_area();
		$this->init_oauth();
		$this->replace_lang();
		$this->assign('lang', array_change_key_case(l()));
		$this->assign('charset', CHARSET);
	}

	private function set_ini()
	{
		@ini_set('memory_limit', '512M');
		@ini_set('session.cache_expire', 180);
		@ini_set('session.use_trans_sid', 0);
		@ini_set('session.use_cookies', 1);
		@ini_set('session.auto_start', 0);
		@ini_set('display_errors', 1);
	}

	private function init()
	{
		require CONF_PATH . 'constant.php';
		$helper_list = array('time', 'base', 'common', 'main', 'insert', 'goods');
		$this->load_helper($helper_list);
		$this->db_config = \base\Config::get('DB.default');
		$this->ecs = $GLOBALS['ecs'] = new \classes\ecshop($this->db_config['DB_NAME'], $this->db_config['DB_PREFIX']);
		$this->db = $GLOBALS['db'] = new \classes\mysql();
		$this->err = $GLOBALS['err'] = new \classes\error('message');
		$GLOBALS['_CFG'] = load_config();
		$GLOBALS['_CFG']['template'] = 'default';
		c('shop', $GLOBALS['_CFG']);
		$app_config = APP_PATH . 'config/web.php';
		c('app', file_exists($app_config) ? require $app_config : array());
		l(require LANG_PATH . c('shop.lang') . '/common.php');
		$app_lang = APP_PATH . 'language/' . c('shop.lang') . '/' . strtolower(CONTROLLER_NAME) . '.php';
		l(file_exists($app_lang) ? require $app_lang : array());
		$this->load_helper('function', 'app');

		if (c('shop_closed') == 1) {
			exit('<p>' . l('shop_closed') . '</p><p>' . c('close_comment') . '</p>');
		}

		if (!defined('INIT_NO_USERS')) {
			if (($this->cache->cache == 'memcached') && (c('CACHE.memcached.CACHE_TYPE') == 'Memcached')) {
				$this->sess = $GLOBALS['sess'] = new \classes\session_memcached($this->db, $this->ecs->table('sessions'), $this->ecs->table('sessions_data'));
			}
			else {
				$this->sess = $GLOBALS['sess'] = new \classes\session($this->db, $this->ecs->table('sessions'), $this->ecs->table('sessions_data'));
			}

			define('SESS_ID', $this->sess->get_session_id());
		}

		$schelper_list = array('scecmoban', 'scfunction');
		$this->load_helper($schelper_list);
	}

	private function init_user()
	{
		if (!defined('INIT_NO_USERS')) {
			$GLOBALS['user'] = $this->users = &init_users();

			if (!isset($_SESSION['user_id'])) {
				$site_name = (isset($_GET['from']) ? htmlspecialchars($_GET['from']) : addslashes(l('self_site')));
				$from_ad = (!empty($_GET['ad_id']) ? intval($_GET['ad_id']) : 0);
				$wechat_from = array('timeline', 'groupmessage', 'singlemessage');

				if (in_array($site_name, $wechat_from)) {
					$site_name = addslashes(l('self_site'));
				}

				$_SESSION['from_ad'] = $from_ad;
				$_SESSION['referer'] = stripslashes($site_name);
				unset($site_name);

				if (!defined('INGORE_VISIT_STATS')) {
					visit_stats();
				}
			}

			if (empty($_SESSION['user_id'])) {
				if ($this->users->get_cookie()) {
					if (0 < $_SESSION['user_id']) {
						update_user_info();
					}
				}
				else {
					$_SESSION['user_id'] = 0;
					$_SESSION['user_name'] = '';
					$_SESSION['email'] = '';
					$_SESSION['user_rank'] = 0;
					$_SESSION['discount'] = 1;

					if (!isset($_SESSION['login_fail'])) {
						$_SESSION['login_fail'] = 0;
					}
				}
			}

			if (isset($_GET['u'])) {
				set_affiliate();
			}

			if (!empty($_COOKIE['ECS']['user_id']) && !empty($_COOKIE['ECS']['password'])) {
				$condition = array('user_id' => intval($_COOKIE['ECS']['user_id']), 'password' => $_COOKIE['ECS']['password']);
				$row = $this->db->table('users')->where($condition)->find();

				if (!$row) {
					$time = time() - 3600;
					setcookie('ECS[user_id]', '', $time, '/');
					setcookie('ECS[password]', '', $time, '/');
				}
				else {
					$_SESSION['user_id'] = $row['user_id'];
					$_SESSION['user_name'] = $row['user_name'];
					update_user_info();
				}
			}

			if (isset($this->tpl)) {
				$this->tpl->assign('ecs_session', $_SESSION);
			}
		}
	}

	private function init_assign()
	{
		$search_keywords = c('shop.search_keywords');
		$hot_keywords = array();

		if ($search_keywords) {
			$hot_keywords = explode(',', $search_keywords);
		}

		$this->assign('hot_keywords', $hot_keywords);
		$history = '';

		if (!empty($_COOKIE['ECS']['keywords'])) {
			$history = explode(',', $_COOKIE['ECS']['keywords']);
			$history = array_unique($history);
		}

		$this->assign('history_keywords', $history);
		if (is_wechat_browser() && is_dir(APP_WECHAT_PATH)) {
			$is_wechat = 1;
		}

		$this->assign('is_wechat', $is_wechat);
	}

	public function init_area()
	{
		$city_district_list = get_ishas_area($_COOKIE['type_city']);

		if (!$city_district_list) {
			setcookie('type_district', 0, gmtime() + (3600 * 24 * 30));
			$_COOKIE['type_district'] = 0;
		}

		$provinceT_list = get_ishas_area($_COOKIE['type_province']);
		$cityT_list = get_ishas_area($_COOKIE['type_city'], 1);
		$districtT_list = get_ishas_area($_COOKIE['type_district'], 1);
		if ((0 < $_COOKIE['type_province']) && $provinceT_list) {
			if ($city_district_list) {
				if (($cityT_list['parent_id'] == $_COOKIE['type_province']) && ($_COOKIE['type_city'] == $districtT_list['parent_id'])) {
					$_COOKIE['province'] = $_COOKIE['type_province'];

					if (0 < $_COOKIE['type_city']) {
						$_COOKIE['city'] = $_COOKIE['type_city'];
					}

					if (0 < $_COOKIE['type_district']) {
						$_COOKIE['district'] = $_COOKIE['type_district'];
					}
				}
			}
			else if ($cityT_list['parent_id'] == $_COOKIE['type_province']) {
				$_COOKIE['province'] = $_COOKIE['type_province'];

				if (0 < $_COOKIE['type_city']) {
					$_COOKIE['city'] = $_COOKIE['type_city'];
				}

				if (0 < $_COOKIE['type_district']) {
					$_COOKIE['district'] = $_COOKIE['type_district'];
				}
			}
		}

		$this->province_id = isset($_COOKIE['province']) ? $_COOKIE['province'] : 0;
		$this->city_id = isset($_COOKIE['city']) ? $_COOKIE['city'] : 0;
		$this->district_id = isset($_COOKIE['district']) ? $_COOKIE['district'] : 0;
		$warehouse_date = array('region_id', 'region_name');
		$warehouse_where = 'regionId = \'' . $this->province_id . '\'';
		$warehouse_province = get_table_date('region_warehouse', $warehouse_where, $warehouse_date);
		$sellerInfo = get_seller_info_area();

		if (!$warehouse_province) {
			$this->province_id = $sellerInfo['province'];
			$this->city_id = $sellerInfo['city'];
			$this->district_id = $sellerInfo['district'];
		}

		setcookie('province', $this->province_id, gmtime() + (3600 * 24 * 30));
		setcookie('city', $this->city_id, gmtime() + (3600 * 24 * 30));
		setcookie('district', $this->district_id, gmtime() + (3600 * 24 * 30));
	}

	private function init_gzip()
	{
		if (!defined('INIT_NO_SMARTY') && gzip_enabled()) {
			ob_start('ob_gzhandler');
		}
		else {
			ob_start();
		}
	}

	private function init_first()
	{
		$init_path = APP_PATH . 'config/';
		$init_cache = CACHE_PATH . 'app/' . APP_NAME . '/';

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
				$this->init_execute($init_path . 'db.sql', '{pre}', $this->db_config['DB_PREFIX']);
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

	private function init_oauth()
	{
		if (is_wechat_browser() && empty($_SESSION['openid']) && (APP_NAME != 'oauth')) {
			$sql = ' select `auth_config` from ' . $GLOBALS['ecs']->table('touch_auth') . ' where `type` = \'wechat\' ';
			$auth_config = $GLOBALS['db']->getOne($sql);

			if ($auth_config) {
				$back_url = (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
				$this->redirect(u('oauth/index/index', array('type' => 'wechat', 'back_url' => $back_url)));
			}
		}
	}

	private function replace_lang()
	{
		$condition['code'] = 'custom_distribution';
		$condition2['code'] = 'custom_distributor';
		$this->custom = $this->model->table('drp_config')->field('value')->where($condition)->One();
		$this->customs = $this->model->table('drp_config')->field('value')->where($condition2)->One();
		$coustomes = l();

		if (is_array($coustomes)) {
			foreach ($coustomes as $key => $val) {
				l($key, str_replace('分销', $this->custom, str_replace('分销商', $this->customs, $val)));
			}
		}

		return l();
	}
	private function init_wxpay_jspay(){
		if( ! preg_match('/micromessenger/', strtolower($_SERVER['HTTP_USER_AGENT']))){
			return false;
		}
		if ( !empty($_POST) ){
			return false;
		}

		//error_reporting(E_ERROR | E_WARNING | E_PARSE);
		//error_reporting(E_ALL | E_NOTICE);	//Notice 以上的错误会显示
		//error_reporting(0);	
		if ( empty($_SESSION['wxpay_jspay_openid'])  ){
			if(isset($_COOKIE["wxpay_jspay_openid"]) && !empty($_COOKIE["wxpay_jspay_openid"]))
			{
				//$_SESSION["wxpay_jspay_openid"]= $_COOKIE["wxpay_jspay_openid"];
				//return true;
			}
			//获取openid
			include_once(BASE_PATH.'helpers/payment_helper.php');
			$plugin_file = ADDONS_PATH.'payment/wxpay_jspay.php';
			require_once( $plugin_file );
			$payment  = get_payment('wxpay_jspay');
			if( empty($payment)  && $payment['enabled']  != 1 ){
				return false;
			}
			$wxpay_jspay = new \wxpay_jspay();
			$wxpay_jspay->_config( $payment );
			$tools = new \JsApiPay();
			$data = $tools->GetOpenid();

			$openid = $data['openid'];
			$_SESSION['wxpay_jspay_openid'] = $openid;
			setcookie("wxpay_jspay_openid", $openid, time()+3600*24*7);
			unset($_GET['code']);
		}
	}
}

?>
