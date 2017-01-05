<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class AbstractProcessingHandlerTest extends \Monolog\TestCase
{
	public function testHandleLowerLevelMessage()
	{
		$handler = $this->getMockForAbstractClass('Monolog\\Handler\\AbstractProcessingHandler', array(\Monolog\Logger::WARNING, true));
		$this->assertFalse($handler->handle($this->getRecord(\Monolog\Logger::DEBUG)));
	}

	public function testHandleBubbling()
	{
		$handler = $this->getMockForAbstractClass('Monolog\\Handler\\AbstractProcessingHandler', array(\Monolog\Logger::DEBUG, true));
		$this->assertFalse($handler->handle($this->getRecord()));
	}

	public function testHandleNotBubbling()
	{
		$handler = $this->getMockForAbstractClass('Monolog\\Handler\\AbstractProcessingHandler', array(\Monolog\Logger::DEBUG, false));
		$this->assertTrue($handler->handle($this->getRecord()));
	}

	public function testHandleIsFalseWhenNotHandled()
	{
		$handler = $this->getMockForAbstractClass('Monolog\\Handler\\AbstractProcessingHandler', array(\Monolog\Logger::WARNING, false));
		$this->assertTrue($handler->handle($this->getRecord()));
		$this->assertFalse($handler->handle($this->getRecord(\Monolog\Logger::DEBUG)));
	}

	public function testProcessRecord()
	{
		$handler = $this->getMockForAbstractClass('Monolog\\Handler\\AbstractProcessingHandler');
		$handler->pushProcessor(new \Monolog\Processor\WebProcessor(array('REQUEST_URI' => '', 'REQUEST_METHOD' => '', 'REMOTE_ADDR' => '', 'SERVER_NAME' => '', 'UNIQUE_ID' => '')));
		$handledRecord = null;
		$handler->expects($this->once())->method('write')->will($this->returnCallback(function($record) use(&$handledRecord) {
			$handledRecord = $record;
		}));
		$handler->handle($this->getRecord());
		$this->assertEquals(6, count($handledRecord['extra']));
	}
}

?>
