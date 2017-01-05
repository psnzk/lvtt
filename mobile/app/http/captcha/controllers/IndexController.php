<?php
//dezend by  QQ:2172298892
namespace http\captcha\controllers;

class IndexController extends \http\base\controllers\FrontendController
{
	public function actionIndex()
	{
		$params = array(
			'fontSize' => 14,
			'length'   => 4,
			'useNoise' => false,
			'fontttf'  => '4.ttf',
			'bg'       => array(255, 255, 255)
			);
		$verify = new \ectouch\verify\Verify($params);
		$verify->entry();
	}
}

?>
