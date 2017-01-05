<?php
//dezend by  QQ:2172298892
namespace http\respond\controllers;

class IndexController extends \http\base\controllers\FrontendController
{
	private $data = array();

	public function __construct()
	{
		parent::__construct();
		$this->data['code'] = i('get.code');
		$this->data['type'] = i('get.type');

		if (isset($_GET['code'])) {
			unset($_GET['code']);
		}

		if (isset($_GET['type'])) {
			unset($_GET['type']);
		}
	}

	public function actionIndex()
	{
		$condition['pay_code'] = $this->data['code'];
		$condition['enabled'] = 1;
		$enabled = $this->db->table('payment')->where($condition)->count();
		$msg_type = 2;

		if ($enabled == 0) {
			$msg = l('pay_disabled');
		}
		else {
			$plugin_file = ADDONS_PATH . 'payment/' . $this->data['code'] . '.php';

			if (file_exists($plugin_file)) {
				include_once $plugin_file;
				$payobj = new $this->data['code']();

				if ($this->data['type'] == 'notify') {
					@$payobj->notify($this->data);
				}

				if (@$payobj->callback($this->data)) {
					$msg = l('pay_success');
					$msg_type = 0;
				}
				else {
					$msg = l('pay_fail');
					$msg_type = 1;
				}
			}
			else {
				$msg = l('pay_not_exist');
			}
		}

		$this->assign('message', $msg);
		$this->assign('msg_type', $msg_type);
		$this->assign('shop_url', __URL__);
		$this->display('index');
	}
}

?>
