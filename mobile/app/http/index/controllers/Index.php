<?php
//zend by QQ:2172298892
namespace app\http\index\controllers;

class Index extends \app\http\base\controllers\Frontend
{
	public function actionIndex()
	{
		$this->assign('page_title', config('shop.shop_name'));
		$this->assign('description', config('shop.shop_desc'));
		$this->assign('keywords', config('shop.shop_keywords'));
		$this->display();
	}

	public function actionDashboard()
	{
		$this->display();
	}
}

?>
