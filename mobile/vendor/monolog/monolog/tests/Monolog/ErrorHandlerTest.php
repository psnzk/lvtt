<?php
//dezend by  QQ:2172298892
namespace Monolog;

class ErrorHandlerTest extends \PHPUnit_Framework_TestCase
{
	public function testHandleError()
	{
		$logger = new Logger('test', array($handler = new Handler\TestHandler()));
		$errHandler = new ErrorHandler($logger);
		$errHandler->registerErrorHandler(array(E_USER_NOTICE => Logger::EMERGENCY), false);
		trigger_error('Foo', E_USER_ERROR);
		$this->assertCount(1, $handler->getRecords());
		$this->assertTrue($handler->hasErrorRecords());
		trigger_error('Foo', E_USER_NOTICE);
		$this->assertCount(2, $handler->getRecords());
		$this->assertTrue($handler->hasEmergencyRecords());
	}
}

?>
