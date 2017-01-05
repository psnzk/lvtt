<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class SyslogHandlerTest extends \PHPUnit_Framework_TestCase
{
	public function testConstruct()
	{
		$handler = new SyslogHandler('test');
		$this->assertInstanceOf('Monolog\\Handler\\SyslogHandler', $handler);
		$handler = new SyslogHandler('test', LOG_USER);
		$this->assertInstanceOf('Monolog\\Handler\\SyslogHandler', $handler);
		$handler = new SyslogHandler('test', 'user');
		$this->assertInstanceOf('Monolog\\Handler\\SyslogHandler', $handler);
		$handler = new SyslogHandler('test', LOG_USER, \Monolog\Logger::DEBUG, true, LOG_PERROR);
		$this->assertInstanceOf('Monolog\\Handler\\SyslogHandler', $handler);
	}

	public function testConstructInvalidFacility()
	{
		$this->setExpectedException('UnexpectedValueException');
		$handler = new SyslogHandler('test', 'unknown');
	}
}

?>
