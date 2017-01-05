<?php
//dezend by  QQ:2172298892
namespace custom\guestbook\controllers;

class MY_IndexController extends \http\base\controllers\FrontendController
{
	public function MY_Index()
	{
		echo 'this guestbook list. ';
		echo '<a href="' . u('add') . '">Goto Add</a>';
	}

	public function MY_Add()
	{
		$this->display();
	}

	public function MY_Save()
	{
		$post = array('title' => i('title'), 'content' => i('content'));
		$this->redirect(u('index'));
	}
}

?>
