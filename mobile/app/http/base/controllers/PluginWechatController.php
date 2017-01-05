<?php
//dezend by  QQ:2172298892
namespace http\base\controllers;

abstract class PluginWechatController extends BaseController
{
	protected $_data = array();

	abstract protected function show($fromusername, $info);

	abstract protected function give_point($fromusername, $info);

	abstract protected function action();

	public function do_point($fromusername, $info, $rank_points = 0, $pay_points = 0)
	{
		$time = gmtime();
		$user_id = model()->table('wechat_user')->field('ect_uid')->where(array('openid' => $fromusername))->one();

		if ($user_id) {
			$sql = 'UPDATE {pre}users SET rank_points = rank_points + ' . intval($rank_points) . ' WHERE user_id = ' . $user_id;
			model()->query($sql);
			$sql = 'UPDATE {pre}users SET pay_points = pay_points + ' . intval($pay_points) . ' WHERE user_id = ' . $user_id;
			model()->query($sql);
			$data['user_id'] = $user_id;
			$data['user_money'] = 0;
			$data['frozen_money'] = 0;
			$data['rank_points'] = intval($rank_points);
			$data['pay_points'] = intval($pay_points);
			$data['change_time'] = $time;
			$data['change_desc'] = $info['name'] . '积分赠送';
			$data['change_type'] = ACT_OTHER;
			$log_id = model()->table('account_log')->data($data)->insert();
			$data1['log_id'] = $log_id;
			$data1['openid'] = $fromusername;
			$data1['keywords'] = $info['command'];
			$data1['createtime'] = $time;
			$log_id = model()->table('wechat_point')->data($data1)->insert();
		}
	}

	public function do_takeout_point($fromusername, $info, $point_value)
	{
		$time = gmtime();
		$user_id = model()->table('wechat_user')->field('ect_uid')->where(array('openid' => $fromusername))->one();

		if ($user_id) {
			$usable_points = model()->table('users')->field('pay_points')->where(array('user_id' => $user_id))->one();

			if (intval($point_value) <= intval($usable_points)) {
				$sql = 'UPDATE {pre}users SET pay_points = pay_points - ' . intval($point_value) . ' WHERE user_id = ' . $user_id;
				model()->query($sql);
				$data['user_id'] = $user_id;
				$data['user_money'] = 0;
				$data['frozen_money'] = 0;
				$data['rank_points'] = 0;
				$data['pay_points'] = $point_value;
				$data['change_time'] = $time;
				$data['change_desc'] = $info['name'] . '积分扣除';
				$data['change_type'] = ACT_OTHER;
				$log_id = model()->table('account_log')->data($data)->insert();
				$data1['log_id'] = $log_id;
				$data1['openid'] = $fromusername;
				$data1['keywords'] = $info['command'];
				$data1['createtime'] = $time;
				$log_id = model()->table('wechat_point')->data($data1)->insert();
				return true;
			}
			else {
				return false;
			}
		}
	}

	public function plugin_display($tpl = '', $config = array())
	{
		$this->_data['config'] = $config;
		l(require LANG_PATH . c('shop.lang') . '/wechat.php');
		$this->_data['lang'] = array_change_key_case(l());
		$this->assign($this->_data);
		$tpl = 'app/modules/wechat/' . $this->plugin_name . '/view/' . $tpl . c('TPL.TPL_SUFFIX');
		$content = file_get_contents(ROOT_PATH . $tpl);
		$content = str_replace('\\', '', $content);
		$this->template_content = $this->display($content, true, false);
		$tpl_l = 'app/http/' . APP_NAME . '/views/wechat_layout';
		$this->assign($this->_data);
		return parent::display($tpl_l);
	}

	public function get_rand($proArr)
	{
		$result = '';
		$proSum = array_sum($proArr);

		foreach ($proArr as $key => $proCur) {
			$randNum = mt_rand(1, $proSum);

			if ($randNum <= $proCur) {
				$result = $key;
				break;
			}
			else {
				$proSum -= $proCur;
			}
		}

		unset($proArr);
		return $result;
	}

	public function __get($name)
	{
		return isset($this->_data[$name]) ? $this->_data[$name] : NULL;
	}

	public function __set($name, $value)
	{
		$this->_data[$name] = $value;
	}
}

?>
