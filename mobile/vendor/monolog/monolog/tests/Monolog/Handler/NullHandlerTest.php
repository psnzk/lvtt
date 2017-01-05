<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class NullHandlerTest extends \Monolog\TestCase
{
	public function testHandle()
	{
		$handler = new NullHandler();
		$this->assertTrue($handler->handle($this->getRecord()));
	}

	public function testHandleLowerLevelRecord()
	{
		$handler = new NullHandler(\Monolog\Logger::WARNING);
		$this->assertFalse($handler->handle($this->getRecord(\Monolog\Logger::DEBUG)));
	}
}

?>
