<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class FilterHandlerTest extends \Monolog\TestCase
{
	public function testIsHandling()
	{
		$test = new TestHandler();
		$handler = new FilterHandler($test, \Monolog\Logger::INFO, \Monolog\Logger::NOTICE);
		$this->assertFalse($handler->isHandling($this->getRecord(\Monolog\Logger::DEBUG)));
		$this->assertTrue($handler->isHandling($this->getRecord(\Monolog\Logger::INFO)));
		$this->assertTrue($handler->isHandling($this->getRecord(\Monolog\Logger::NOTICE)));
		$this->assertFalse($handler->isHandling($this->getRecord(\Monolog\Logger::WARNING)));
		$this->assertFalse($handler->isHandling($this->getRecord(\Monolog\Logger::ERROR)));
		$this->assertFalse($handler->isHandling($this->getRecord(\Monolog\Logger::CRITICAL)));
		$this->assertFalse($handler->isHandling($this->getRecord(\Monolog\Logger::ALERT)));
		$this->assertFalse($handler->isHandling($this->getRecord(\Monolog\Logger::EMERGENCY)));
	}

	public function testHandleProcessOnlyNeededLevels()
	{
		$test = new TestHandler();
		$handler = new FilterHandler($test, \Monolog\Logger::INFO, \Monolog\Logger::NOTICE);
		$handler->handle($this->getRecord(\Monolog\Logger::DEBUG));
		$this->assertFalse($test->hasDebugRecords());
		$handler->handle($this->getRecord(\Monolog\Logger::INFO));
		$this->assertTrue($test->hasInfoRecords());
		$handler->handle($this->getRecord(\Monolog\Logger::NOTICE));
		$this->assertTrue($test->hasNoticeRecords());
		$handler->handle($this->getRecord(\Monolog\Logger::WARNING));
		$this->assertFalse($test->hasWarningRecords());
		$handler->handle($this->getRecord(\Monolog\Logger::ERROR));
		$this->assertFalse($test->hasErrorRecords());
		$handler->handle($this->getRecord(\Monolog\Logger::CRITICAL));
		$this->assertFalse($test->hasCriticalRecords());
		$handler->handle($this->getRecord(\Monolog\Logger::ALERT));
		$this->assertFalse($test->hasAlertRecords());
		$handler->handle($this->getRecord(\Monolog\Logger::EMERGENCY));
		$this->assertFalse($test->hasEmergencyRecords());
		$test = new TestHandler();
		$handler = new FilterHandler($test, array(\Monolog\Logger::INFO, \Monolog\Logger::ERROR));
		$handler->handle($this->getRecord(\Monolog\Logger::DEBUG));
		$this->assertFalse($test->hasDebugRecords());
		$handler->handle($this->getRecord(\Monolog\Logger::INFO));
		$this->assertTrue($test->hasInfoRecords());
		$handler->handle($this->getRecord(\Monolog\Logger::NOTICE));
		$this->assertFalse($test->hasNoticeRecords());
		$handler->handle($this->getRecord(\Monolog\Logger::ERROR));
		$this->assertTrue($test->hasErrorRecords());
		$handler->handle($this->getRecord(\Monolog\Logger::CRITICAL));
		$this->assertFalse($test->hasCriticalRecords());
	}

	public function testAcceptedLevelApi()
	{
		$test = new TestHandler();
		$handler = new FilterHandler($test);
		$levels = array(\Monolog\Logger::INFO, \Monolog\Logger::ERROR);
		$handler->setAcceptedLevels($levels);
		$this->assertSame($levels, $handler->getAcceptedLevels());
		$handler->setAcceptedLevels(array('info', 'error'));
		$this->assertSame($levels, $handler->getAcceptedLevels());
		$levels = array(\Monolog\Logger::CRITICAL, \Monolog\Logger::ALERT, \Monolog\Logger::EMERGENCY);
		$handler->setAcceptedLevels(\Monolog\Logger::CRITICAL, \Monolog\Logger::EMERGENCY);
		$this->assertSame($levels, $handler->getAcceptedLevels());
		$handler->setAcceptedLevels('critical', 'emergency');
		$this->assertSame($levels, $handler->getAcceptedLevels());
	}

	public function testHandleUsesProcessors()
	{
		$test = new TestHandler();
		$handler = new FilterHandler($test, \Monolog\Logger::DEBUG, \Monolog\Logger::EMERGENCY);
		$handler->pushProcessor(function($record) {
			$record['extra']['foo'] = true;
			return $record;
		});
		$handler->handle($this->getRecord(\Monolog\Logger::WARNING));
		$this->assertTrue($test->hasWarningRecords());
		$records = $test->getRecords();
		$this->assertTrue($records[0]['extra']['foo']);
	}

	public function testHandleRespectsBubble()
	{
		$test = new TestHandler();
		$handler = new FilterHandler($test, \Monolog\Logger::INFO, \Monolog\Logger::NOTICE, false);
		$this->assertTrue($handler->handle($this->getRecord(\Monolog\Logger::INFO)));
		$this->assertFalse($handler->handle($this->getRecord(\Monolog\Logger::WARNING)));
		$handler = new FilterHandler($test, \Monolog\Logger::INFO, \Monolog\Logger::NOTICE, true);
		$this->assertFalse($handler->handle($this->getRecord(\Monolog\Logger::INFO)));
		$this->assertFalse($handler->handle($this->getRecord(\Monolog\Logger::WARNING)));
	}

	public function testHandleWithCallback()
	{
		$test = new TestHandler();
		$handler = new FilterHandler(function($record, $handler) use($test) {
			return $test;
		}, \Monolog\Logger::INFO, \Monolog\Logger::NOTICE, false);
		$handler->handle($this->getRecord(\Monolog\Logger::DEBUG));
		$handler->handle($this->getRecord(\Monolog\Logger::INFO));
		$this->assertFalse($test->hasDebugRecords());
		$this->assertTrue($test->hasInfoRecords());
	}

	public function testHandleWithBadCallbackThrowsException()
	{
		$handler = new FilterHandler(function($record, $handler) {
			return 'foo';
		});
		$handler->handle($this->getRecord(\Monolog\Logger::WARNING));
	}
}

?>
