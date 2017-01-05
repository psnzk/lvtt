<?php
//dezend by  QQ:2172298892
namespace http\base\hooks;

class AppHook
{
	/**
	 * 开始时间
	 * @var integer
	 */
	public $startTime = 0;

	public function appBegin()
	{
		$this->startTime = microtime(true);
	}

	public function appEnd()
	{
	}

	public function appError($e)
	{
		if (404 == $e->getCode()) {
			$action = 'error404';
		}
		else {
			$action = 'error';
		}

		a('base/Error', 'controllers')->$action($e);
	}

	public function routeParseUrl($rewriteRule, $rewriteOn)
	{
	}

	public function actionBefore($obj, $action)
	{
	}

	public function actionAfter($obj, $action)
	{
	}
}


?>
