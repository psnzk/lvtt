<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class TestHandlerTest extends \Monolog\TestCase
{
	public function testHandler($method, $level)
	{
		$handler = new TestHandler();
		$record = $this->getRecord($level, 'test' . $method);
		$this->assertFalse($handler->{'has' . $method}($record), 'has' . $method);
		$this->assertFalse($handler->{'has' . $method . 'ThatContains'}('test'), 'has' . $method . 'ThatContains');
		$this->assertFalse($handler->{'has' . $method . 'ThatPasses'}(function($rec) {
			return true;
		}), 'has' . $method . 'ThatPasses');
		$this->assertFalse($handler->{'has' . $method . 'ThatMatches'}('/test\\w+/'));
		$this->assertFalse($handler->{'has' . $method . 'Records'}(), 'has' . $method . 'Records');
		$handler->handle($record);
		$this->assertFalse($handler->{'has' . $method}('bar'), 'has' . $method);
		$this->assertTrue($handler->{'has' . $method}($record), 'has' . $method);
		$this->assertTrue($handler->{'has' . $method}('test' . $method), 'has' . $method);
		$this->assertTrue($handler->{'has' . $method . 'ThatContains'}('test'), 'has' . $method . 'ThatContains');
		$this->assertTrue($handler->{'has' . $method . 'ThatPasses'}(function($rec) {
			return true;
		}), 'has' . $method . 'ThatPasses');
		$this->assertTrue($handler->{'has' . $method . 'ThatMatches'}('/test\\w+/'));
		$this->assertTrue($handler->{'has' . $method . 'Records'}(), 'has' . $method . 'Records');
		$records = $handler->getRecords();
		unset($records[0]['formatted']);
		$this->assertEquals(array($record), $records);
	}

	public function methodProvider()
	{
		return array(
	array('Emergency', \Monolog\Logger::EMERGENCY),
	array('Alert', \Monolog\Logger::ALERT),
	array('Critical', \Monolog\Logger::CRITICAL),
	array('Error', \Monolog\Logger::ERROR),
	array('Warning', \Monolog\Logger::WARNING),
	array('Info', \Monolog\Logger::INFO),
	array('Notice', \Monolog\Logger::NOTICE),
	array('Debug', \Monolog\Logger::DEBUG)
	);
	}
}

?>
