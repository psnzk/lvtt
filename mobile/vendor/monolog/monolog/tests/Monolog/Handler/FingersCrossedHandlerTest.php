<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class FingersCrossedHandlerTest extends \Monolog\TestCase
{
	public function testHandleBuffers()
	{
		$test = new TestHandler();
		$handler = new FingersCrossedHandler($test);
		$handler->handle($this->getRecord(\Monolog\Logger::DEBUG));
		$handler->handle($this->getRecord(\Monolog\Logger::INFO));
		$this->assertFalse($test->hasDebugRecords());
		$this->assertFalse($test->hasInfoRecords());
		$handler->handle($this->getRecord(\Monolog\Logger::WARNING));
		$handler->close();
		$this->assertTrue($test->hasInfoRecords());
		$this->assertTrue(count($test->getRecords()) === 3);
	}

	public function testHandleStopsBufferingAfterTrigger()
	{
		$test = new TestHandler();
		$handler = new FingersCrossedHandler($test);
		$handler->handle($this->getRecord(\Monolog\Logger::WARNING));
		$handler->handle($this->getRecord(\Monolog\Logger::DEBUG));
		$handler->close();
		$this->assertTrue($test->hasWarningRecords());
		$this->assertTrue($test->hasDebugRecords());
	}

	public function testHandleRestartBufferingAfterReset()
	{
		$test = new TestHandler();
		$handler = new FingersCrossedHandler($test);
		$handler->handle($this->getRecord(\Monolog\Logger::WARNING));
		$handler->handle($this->getRecord(\Monolog\Logger::DEBUG));
		$handler->reset();
		$handler->handle($this->getRecord(\Monolog\Logger::INFO));
		$handler->close();
		$this->assertTrue($test->hasWarningRecords());
		$this->assertTrue($test->hasDebugRecords());
		$this->assertFalse($test->hasInfoRecords());
	}

	public function testHandleRestartBufferingAfterBeingTriggeredWhenStopBufferingIsDisabled()
	{
		$test = new TestHandler();
		$handler = new FingersCrossedHandler($test, \Monolog\Logger::WARNING, 0, false, false);
		$handler->handle($this->getRecord(\Monolog\Logger::DEBUG));
		$handler->handle($this->getRecord(\Monolog\Logger::WARNING));
		$handler->handle($this->getRecord(\Monolog\Logger::INFO));
		$handler->close();
		$this->assertTrue($test->hasWarningRecords());
		$this->assertTrue($test->hasDebugRecords());
		$this->assertFalse($test->hasInfoRecords());
	}

	public function testHandleBufferLimit()
	{
		$test = new TestHandler();
		$handler = new FingersCrossedHandler($test, \Monolog\Logger::WARNING, 2);
		$handler->handle($this->getRecord(\Monolog\Logger::DEBUG));
		$handler->handle($this->getRecord(\Monolog\Logger::DEBUG));
		$handler->handle($this->getRecord(\Monolog\Logger::INFO));
		$handler->handle($this->getRecord(\Monolog\Logger::WARNING));
		$this->assertTrue($test->hasWarningRecords());
		$this->assertTrue($test->hasInfoRecords());
		$this->assertFalse($test->hasDebugRecords());
	}

	public function testHandleWithCallback()
	{
		$test = new TestHandler();
		$handler = new FingersCrossedHandler(function($record, $handler) use($test) {
			return $test;
		});
		$handler->handle($this->getRecord(\Monolog\Logger::DEBUG));
		$handler->handle($this->getRecord(\Monolog\Logger::INFO));
		$this->assertFalse($test->hasDebugRecords());
		$this->assertFalse($test->hasInfoRecords());
		$handler->handle($this->getRecord(\Monolog\Logger::WARNING));
		$this->assertTrue($test->hasInfoRecords());
		$this->assertTrue(count($test->getRecords()) === 3);
	}

	public function testHandleWithBadCallbackThrowsException()
	{
		$handler = new FingersCrossedHandler(function($record, $handler) {
			return 'foo';
		});
		$handler->handle($this->getRecord(\Monolog\Logger::WARNING));
	}

	public function testIsHandlingAlways()
	{
		$test = new TestHandler();
		$handler = new FingersCrossedHandler($test, \Monolog\Logger::ERROR);
		$this->assertTrue($handler->isHandling($this->getRecord(\Monolog\Logger::DEBUG)));
	}

	public function testErrorLevelActivationStrategy()
	{
		$test = new TestHandler();
		$handler = new FingersCrossedHandler($test, new FingersCrossed\ErrorLevelActivationStrategy(\Monolog\Logger::WARNING));
		$handler->handle($this->getRecord(\Monolog\Logger::DEBUG));
		$this->assertFalse($test->hasDebugRecords());
		$handler->handle($this->getRecord(\Monolog\Logger::WARNING));
		$this->assertTrue($test->hasDebugRecords());
		$this->assertTrue($test->hasWarningRecords());
	}

	public function testErrorLevelActivationStrategyWithPsrLevel()
	{
		$test = new TestHandler();
		$handler = new FingersCrossedHandler($test, new FingersCrossed\ErrorLevelActivationStrategy('warning'));
		$handler->handle($this->getRecord(\Monolog\Logger::DEBUG));
		$this->assertFalse($test->hasDebugRecords());
		$handler->handle($this->getRecord(\Monolog\Logger::WARNING));
		$this->assertTrue($test->hasDebugRecords());
		$this->assertTrue($test->hasWarningRecords());
	}

	public function testOverrideActivationStrategy()
	{
		$test = new TestHandler();
		$handler = new FingersCrossedHandler($test, new FingersCrossed\ErrorLevelActivationStrategy('warning'));
		$handler->handle($this->getRecord(\Monolog\Logger::DEBUG));
		$this->assertFalse($test->hasDebugRecords());
		$handler->activate();
		$this->assertTrue($test->hasDebugRecords());
		$handler->handle($this->getRecord(\Monolog\Logger::INFO));
		$this->assertTrue($test->hasInfoRecords());
	}

	public function testChannelLevelActivationStrategy()
	{
		$test = new TestHandler();
		$handler = new FingersCrossedHandler($test, new FingersCrossed\ChannelLevelActivationStrategy(\Monolog\Logger::ERROR, array('othertest' => \Monolog\Logger::DEBUG)));
		$handler->handle($this->getRecord(\Monolog\Logger::WARNING));
		$this->assertFalse($test->hasWarningRecords());
		$record = $this->getRecord(\Monolog\Logger::DEBUG);
		$record['channel'] = 'othertest';
		$handler->handle($record);
		$this->assertTrue($test->hasDebugRecords());
		$this->assertTrue($test->hasWarningRecords());
	}

	public function testChannelLevelActivationStrategyWithPsrLevels()
	{
		$test = new TestHandler();
		$handler = new FingersCrossedHandler($test, new FingersCrossed\ChannelLevelActivationStrategy('error', array('othertest' => 'debug')));
		$handler->handle($this->getRecord(\Monolog\Logger::WARNING));
		$this->assertFalse($test->hasWarningRecords());
		$record = $this->getRecord(\Monolog\Logger::DEBUG);
		$record['channel'] = 'othertest';
		$handler->handle($record);
		$this->assertTrue($test->hasDebugRecords());
		$this->assertTrue($test->hasWarningRecords());
	}

	public function testHandleUsesProcessors()
	{
		$test = new TestHandler();
		$handler = new FingersCrossedHandler($test, \Monolog\Logger::INFO);
		$handler->pushProcessor(function($record) {
			$record['extra']['foo'] = true;
			return $record;
		});
		$handler->handle($this->getRecord(\Monolog\Logger::WARNING));
		$this->assertTrue($test->hasWarningRecords());
		$records = $test->getRecords();
		$this->assertTrue($records[0]['extra']['foo']);
	}

	public function testPassthruOnClose()
	{
		$test = new TestHandler();
		$handler = new FingersCrossedHandler($test, new FingersCrossed\ErrorLevelActivationStrategy(\Monolog\Logger::WARNING), 0, true, true, \Monolog\Logger::INFO);
		$handler->handle($this->getRecord(\Monolog\Logger::DEBUG));
		$handler->handle($this->getRecord(\Monolog\Logger::INFO));
		$handler->close();
		$this->assertFalse($test->hasDebugRecords());
		$this->assertTrue($test->hasInfoRecords());
	}

	public function testPsrLevelPassthruOnClose()
	{
		$test = new TestHandler();
		$handler = new FingersCrossedHandler($test, new FingersCrossed\ErrorLevelActivationStrategy(\Monolog\Logger::WARNING), 0, true, true, \Psr\Log\LogLevel::INFO);
		$handler->handle($this->getRecord(\Monolog\Logger::DEBUG));
		$handler->handle($this->getRecord(\Monolog\Logger::INFO));
		$handler->close();
		$this->assertFalse($test->hasDebugRecords());
		$this->assertTrue($test->hasInfoRecords());
	}
}

?>
