<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class AbstractHandlerTest extends \Monolog\TestCase
{
	public function testConstructAndGetSet()
	{
		$handler = $this->getMockForAbstractClass('Monolog\\Handler\\AbstractHandler', array(\Monolog\Logger::WARNING, false));
		$this->assertEquals(\Monolog\Logger::WARNING, $handler->getLevel());
		$this->assertEquals(false, $handler->getBubble());
		$handler->setLevel(\Monolog\Logger::ERROR);
		$handler->setBubble(true);
		$handler->setFormatter($formatter = new \Monolog\Formatter\LineFormatter());
		$this->assertEquals(\Monolog\Logger::ERROR, $handler->getLevel());
		$this->assertEquals(true, $handler->getBubble());
		$this->assertSame($formatter, $handler->getFormatter());
	}

	public function testHandleBatch()
	{
		$handler = $this->getMockForAbstractClass('Monolog\\Handler\\AbstractHandler');
		$handler->expects($this->exactly(2))->method('handle');
		$handler->handleBatch(array($this->getRecord(), $this->getRecord()));
	}

	public function testIsHandling()
	{
		$handler = $this->getMockForAbstractClass('Monolog\\Handler\\AbstractHandler', array(\Monolog\Logger::WARNING, false));
		$this->assertTrue($handler->isHandling($this->getRecord()));
		$this->assertFalse($handler->isHandling($this->getRecord(\Monolog\Logger::DEBUG)));
	}

	public function testHandlesPsrStyleLevels()
	{
		$handler = $this->getMockForAbstractClass('Monolog\\Handler\\AbstractHandler', array('warning', false));
		$this->assertFalse($handler->isHandling($this->getRecord(\Monolog\Logger::DEBUG)));
		$handler->setLevel('debug');
		$this->assertTrue($handler->isHandling($this->getRecord(\Monolog\Logger::DEBUG)));
	}

	public function testGetFormatterInitializesDefault()
	{
		$handler = $this->getMockForAbstractClass('Monolog\\Handler\\AbstractHandler');
		$this->assertInstanceOf('Monolog\\Formatter\\LineFormatter', $handler->getFormatter());
	}

	public function testPushPopProcessor()
	{
		$logger = $this->getMockForAbstractClass('Monolog\\Handler\\AbstractHandler');
		$processor1 = new \Monolog\Processor\WebProcessor();
		$processor2 = new \Monolog\Processor\WebProcessor();
		$logger->pushProcessor($processor1);
		$logger->pushProcessor($processor2);
		$this->assertEquals($processor2, $logger->popProcessor());
		$this->assertEquals($processor1, $logger->popProcessor());
		$logger->popProcessor();
	}

	public function testPushProcessorWithNonCallable()
	{
		$handler = $this->getMockForAbstractClass('Monolog\\Handler\\AbstractHandler');
		$handler->pushProcessor(new \stdClass());
	}
}

?>
