<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class GroupHandlerTest extends \Monolog\TestCase
{
	public function testConstructorOnlyTakesHandler()
	{
		new GroupHandler(array(new TestHandler(), 'foo'));
	}

	public function testHandle()
	{
		$testHandlers = array(new TestHandler(), new TestHandler());
		$handler = new GroupHandler($testHandlers);
		$handler->handle($this->getRecord(\Monolog\Logger::DEBUG));
		$handler->handle($this->getRecord(\Monolog\Logger::INFO));

		foreach ($testHandlers as $test) {
			$this->assertTrue($test->hasDebugRecords());
			$this->assertTrue($test->hasInfoRecords());
			$this->assertTrue(count($test->getRecords()) === 2);
		}
	}

	public function testHandleBatch()
	{
		$testHandlers = array(new TestHandler(), new TestHandler());
		$handler = new GroupHandler($testHandlers);
		$handler->handleBatch(array($this->getRecord(\Monolog\Logger::DEBUG), $this->getRecord(\Monolog\Logger::INFO)));

		foreach ($testHandlers as $test) {
			$this->assertTrue($test->hasDebugRecords());
			$this->assertTrue($test->hasInfoRecords());
			$this->assertTrue(count($test->getRecords()) === 2);
		}
	}

	public function testIsHandling()
	{
		$testHandlers = array(new TestHandler(\Monolog\Logger::ERROR), new TestHandler(\Monolog\Logger::WARNING));
		$handler = new GroupHandler($testHandlers);
		$this->assertTrue($handler->isHandling($this->getRecord(\Monolog\Logger::ERROR)));
		$this->assertTrue($handler->isHandling($this->getRecord(\Monolog\Logger::WARNING)));
		$this->assertFalse($handler->isHandling($this->getRecord(\Monolog\Logger::DEBUG)));
	}

	public function testHandleUsesProcessors()
	{
		$test = new TestHandler();
		$handler = new GroupHandler(array($test));
		$handler->pushProcessor(function($record) {
			$record['extra']['foo'] = true;
			return $record;
		});
		$handler->handle($this->getRecord(\Monolog\Logger::WARNING));
		$this->assertTrue($test->hasWarningRecords());
		$records = $test->getRecords();
		$this->assertTrue($records[0]['extra']['foo']);
	}

	public function testHandleBatchUsesProcessors()
	{
		$testHandlers = array(new TestHandler(), new TestHandler());
		$handler = new GroupHandler($testHandlers);
		$handler->pushProcessor(function($record) {
			$record['extra']['foo'] = true;
			return $record;
		});
		$handler->handleBatch(array($this->getRecord(\Monolog\Logger::DEBUG), $this->getRecord(\Monolog\Logger::INFO)));

		foreach ($testHandlers as $test) {
			$this->assertTrue($test->hasDebugRecords());
			$this->assertTrue($test->hasInfoRecords());
			$this->assertTrue(count($test->getRecords()) === 2);
			$records = $test->getRecords();
			$this->assertTrue($records[0]['extra']['foo']);
			$this->assertTrue($records[1]['extra']['foo']);
		}
	}
}

?>
