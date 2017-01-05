<?php
//dezend by  QQ:2172298892
namespace Acme;

class Tester
{
	public function test($handler, $record)
	{
		$handler->handle($record);
	}
}

function tester($handler, $record)
{
	$handler->handle($record);
}

namespace Monolog\Processor;

class IntrospectionProcessorTest extends \Monolog\TestCase
{
	public function getHandler()
	{
		$processor = new IntrospectionProcessor();
		$handler = new \Monolog\Handler\TestHandler();
		$handler->pushProcessor($processor);
		return $handler;
	}

	public function testProcessorFromClass()
	{
		$handler = $this->getHandler();
		$tester = new \Acme\Tester();
		$tester->test($handler, $this->getRecord());
		list($record) = $handler->getRecords();
		$this->assertEquals(__FILE__, $record['extra']['file']);
		$this->assertEquals(18, $record['extra']['line']);
		$this->assertEquals('Acme\\Tester', $record['extra']['class']);
		$this->assertEquals('test', $record['extra']['function']);
	}

	public function testProcessorFromFunc()
	{
		$handler = $this->getHandler();
		acme\tester($handler, $this->getRecord());
		list($record) = $handler->getRecords();
		$this->assertEquals(__FILE__, $record['extra']['file']);
		$this->assertEquals(24, $record['extra']['line']);
		$this->assertEquals(null, $record['extra']['class']);
		$this->assertEquals('Acme\\tester', $record['extra']['function']);
	}

	public function testLevelTooLow()
	{
		$input = array(
			'level' => \Monolog\Logger::DEBUG,
			'extra' => array()
			);
		$expected = $input;
		$processor = new IntrospectionProcessor(\Monolog\Logger::CRITICAL);
		$actual = $processor($input);
		$this->assertEquals($expected, $actual);
	}

	public function testLevelEqual()
	{
		$input = array(
			'level' => \Monolog\Logger::CRITICAL,
			'extra' => array()
			);
		$expected = $input;
		$expected['extra'] = array('file' => null, 'line' => null, 'class' => 'ReflectionMethod', 'function' => 'invokeArgs');
		$processor = new IntrospectionProcessor(\Monolog\Logger::CRITICAL);
		$actual = $processor($input);
		$this->assertEquals($expected, $actual);
	}

	public function testLevelHigher()
	{
		$input = array(
			'level' => \Monolog\Logger::EMERGENCY,
			'extra' => array()
			);
		$expected = $input;
		$expected['extra'] = array('file' => null, 'line' => null, 'class' => 'ReflectionMethod', 'function' => 'invokeArgs');
		$processor = new IntrospectionProcessor(\Monolog\Logger::CRITICAL);
		$actual = $processor($input);
		$this->assertEquals($expected, $actual);
	}
}

?>
