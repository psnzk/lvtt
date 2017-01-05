<?php
//dezend by  QQ:2172298892
namespace Psr\Log\Test;

class DummyTest
{
	public function __toString()
	{
	}
}

abstract class LoggerInterfaceTest extends \PHPUnit_Framework_TestCase
{
	abstract public function getLogger();

	abstract public function getLogs();

	public function testImplements()
	{
		$this->assertInstanceOf('Psr\\Log\\LoggerInterface', $this->getLogger());
	}

	public function testLogsAtAllLevels($level, $message)
	{
		$logger = $this->getLogger();
		$logger->$level($message, array('user' => 'Bob'));
		$logger->log($level, $message, array('user' => 'Bob'));
		$expected = array($level . ' message of level ' . $level . ' with context: Bob', $level . ' message of level ' . $level . ' with context: Bob');
		$this->assertEquals($expected, $this->getLogs());
	}

	public function provideLevelsAndMessages()
	{
		return array(
	\Psr\Log\LogLevel::EMERGENCY => array(\Psr\Log\LogLevel::EMERGENCY, 'message of level emergency with context: {user}'),
	\Psr\Log\LogLevel::ALERT     => array(\Psr\Log\LogLevel::ALERT, 'message of level alert with context: {user}'),
	\Psr\Log\LogLevel::CRITICAL  => array(\Psr\Log\LogLevel::CRITICAL, 'message of level critical with context: {user}'),
	\Psr\Log\LogLevel::ERROR     => array(\Psr\Log\LogLevel::ERROR, 'message of level error with context: {user}'),
	\Psr\Log\LogLevel::WARNING   => array(\Psr\Log\LogLevel::WARNING, 'message of level warning with context: {user}'),
	\Psr\Log\LogLevel::NOTICE    => array(\Psr\Log\LogLevel::NOTICE, 'message of level notice with context: {user}'),
	\Psr\Log\LogLevel::INFO      => array(\Psr\Log\LogLevel::INFO, 'message of level info with context: {user}'),
	\Psr\Log\LogLevel::DEBUG     => array(\Psr\Log\LogLevel::DEBUG, 'message of level debug with context: {user}')
	);
	}

	public function testThrowsOnInvalidLevel()
	{
		$logger = $this->getLogger();
		$logger->log('invalid level', 'Foo');
	}

	public function testContextReplacement()
	{
		$logger = $this->getLogger();
		$logger->info('{Message {nothing} {user} {foo.bar} a}', array('user' => 'Bob', 'foo.bar' => 'Bar'));
		$expected = array('info {Message {nothing} Bob Bar a}');
		$this->assertEquals($expected, $this->getLogs());
	}

	public function testObjectCastToString()
	{
		if (method_exists($this, 'createPartialMock')) {
			$dummy = $this->createPartialMock('Psr\\Log\\Test\\DummyTest', array('__toString'));
		}
		else {
			$dummy = $this->getMock('Psr\\Log\\Test\\DummyTest', array('__toString'));
		}

		$dummy->expects($this->once())->method('__toString')->will($this->returnValue('DUMMY'));
		$this->getLogger()->warning($dummy);
		$expected = array('warning DUMMY');
		$this->assertEquals($expected, $this->getLogs());
	}

	public function testContextCanContainAnything()
	{
		$context = array(
			'bool'     => true,
			'null'     => null,
			'string'   => 'Foo',
			'int'      => 0,
			'float'    => 0.5,
			'nested'   => array('with object' => new DummyTest()),
			'object'   => new \DateTime(),
			'resource' => fopen('php://memory', 'r')
			);
		$this->getLogger()->warning('Crazy context data', $context);
		$expected = array('warning Crazy context data');
		$this->assertEquals($expected, $this->getLogs());
	}

	public function testContextExceptionKeyCanBeExceptionOrOtherValues()
	{
		$logger = $this->getLogger();
		$logger->warning('Random message', array('exception' => 'oops'));
		$logger->critical('Uncaught Exception!', array('exception' => new \LogicException('Fail')));
		$expected = array('warning Random message', 'critical Uncaught Exception!');
		$this->assertEquals($expected, $this->getLogs());
	}
}

?>
