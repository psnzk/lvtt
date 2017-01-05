<?php
//dezend by  QQ:2172298892
class wechat
{
	private $wechat = '';

	public function __construct($config)
	{
		$options = array('appid' => $config['app_id'], 'appsecret' => $config['app_secret']);
		$this->wechat = new \ectouch\wechat\Wechat($options);
	}

	public function redirect($callback_url)
	{
		return $this->wechat->getOauthRedirect($callback_url, 'wechat_oauth');
	}

	public function callback($callback_url, $code)
	{
		if (!empty($code)) {
			$token = $this->wechat->getOauthAccessToken();
			$userinfo = $this->wechat->getOauthUserinfo($token['access_token'], $token['openid']);

			if (!empty($userinfo)) {
				include 'emoji.php';
				$userinfo['nickname'] = emoji_unified_to_html($userinfo['nickname']);
				$userinfo['nickname'] = stripslashes(htmlspecialchars_decode($userinfo['nickname']));
				$_SESSION['openid'] = $userinfo['openid'];
				$_SESSION['nickname'] = $userinfo['nickname'];
				$_SESSION['avatar'] = $userinfo['headimgurl'];
				$identify = (isset($userinfo['unionid']) && !empty($userinfo['unionid']) ? $userinfo['unionid'] : $userinfo['openid']);
				$data = array('openid' => basename(__FILE__, '.php') . '_' . $identify, 'name' => $userinfo['nickname'], 'sex' => $userinfo['sex'], 'avatar' => $userinfo['headimgurl']);
				$controller = '\\http\\wechat\\controllers\\IndexController';

				if (class_exists($controller)) {
					$this->updateInfo($userinfo);
				}

				return $data;
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}

	private function updateInfo($res = array())
	{
		if (empty($res)) {
			return false;
		}

		$res['privilege'] = serialize($res['privilege']);
		$unionid = false;
		if (isset($res['unionid']) && !empty($res['unionid'])) {
			$userinfo = model()->table('wechat_user')->where(array('unionid' => $res['unionid']))->find();
			$unionid = true;
		}
		else {
			$userinfo = model()->table('wechat_user')->where(array('openid' => $res['openid']))->find();
		}

		if (empty($userinfo)) {
			$res['ect_uid'] = 0;
			$res['wechat_id'] = 1;
			model()->table('wechat_user')->data($res)->insert();
		}
		else {
			if ($unionid) {
				$condition = array('unionid' => $res['unionid']);
			}
			else {
				$condition = array('openid' => $res['openid']);
			}

			model()->table('wechat_user')->data($res)->where($condition)->update();
		}
	}
}

defined('BASE_PATH') || exit('No direct script access allowed');

?>
