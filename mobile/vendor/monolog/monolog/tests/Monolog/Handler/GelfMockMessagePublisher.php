<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class GelfMockMessagePublisher extends \Gelf\MessagePublisher
{
	public $lastMessage;

	public function publish(\Gelf\Message $message)
	{
		$this->lastMessage = $message;
	}
}

?>
