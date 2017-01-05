<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class MockRavenClient extends \Raven_Client
{
	public $lastData;
	public $lastStack;

	public function capture($data, $stack, $vars = NULL)
	{
		$data = array_merge($this->get_user_data(), $data);
		$this->lastData = $data;
		$this->lastStack = $stack;
	}
}

?>
