<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

function mail($to, $subject, $message, $additional_headers = NULL, $additional_parameters = NULL)
{
	$GLOBALS['mail'][] = func_get_args();
}

class NativeMailerHandlerTest extends \Monolog\TestCase
{
	protected function setUp()
	{
		$GLOBALS['mail'] = array();
	}

	public function testConstructorHeaderInjection()
	{
		$mailer = new NativeMailerHandler('spammer@example.org', 'dear victim', "receiver@example.org\r\nFrom: faked@attacker.org");
	}

	public function testSetterHeaderInjection()
	{
		$mailer = new NativeMailerHandler('spammer@example.org', 'dear victim', 'receiver@example.org');
		$mailer->addHeader("Content-Type: text/html\r\nFrom: faked@attacker.org");
	}

	public function testSetterArrayHeaderInjection()
	{
		$mailer = new NativeMailerHandler('spammer@example.org', 'dear victim', 'receiver@example.org');
		$mailer->addHeader(array("Content-Type: text/html\r\nFrom: faked@attacker.org"));
	}

	public function testSetterContentTypeInjection()
	{
		$mailer = new NativeMailerHandler('spammer@example.org', 'dear victim', 'receiver@example.org');
		$mailer->setContentType("text/html\r\nFrom: faked@attacker.org");
	}

	public function testSetterEncodingInjection()
	{
		$mailer = new NativeMailerHandler('spammer@example.org', 'dear victim', 'receiver@example.org');
		$mailer->setEncoding("utf-8\r\nFrom: faked@attacker.org");
	}

	public function testSend()
	{
		$to = 'spammer@example.org';
		$subject = 'dear victim';
		$from = 'receiver@example.org';
		$mailer = new NativeMailerHandler($to, $subject, $from);
		$mailer->handleBatch(array());
		$this->assertEmpty($GLOBALS['mail']);
		$mailer->handle($this->getRecord(\Monolog\Logger::ERROR, "Foo\nBar\r\n\r\nBaz"));
		$this->assertNotEmpty($GLOBALS['mail']);
		$this->assertInternalType('array', $GLOBALS['mail']);
		$this->assertArrayHasKey('0', $GLOBALS['mail']);
		$params = $GLOBALS['mail'][0];
		$this->assertCount(5, $params);
		$this->assertSame($to, $params[0]);
		$this->assertSame($subject, $params[1]);
		$this->assertStringEndsWith(" test.ERROR: Foo Bar  Baz [] []\n", $params[2]);
		$this->assertSame('From: ' . $from . "\r\nContent-type: text/plain; charset=utf-8\r\n", $params[3]);
		$this->assertSame('', $params[4]);
	}

	public function testMessageSubjectFormatting()
	{
		$mailer = new NativeMailerHandler('to@example.org', 'Alert: %level_name% %message%', 'from@example.org');
		$mailer->handle($this->getRecord(\Monolog\Logger::ERROR, "Foo\nBar\r\n\r\nBaz"));
		$this->assertNotEmpty($GLOBALS['mail']);
		$this->assertInternalType('array', $GLOBALS['mail']);
		$this->assertArrayHasKey('0', $GLOBALS['mail']);
		$params = $GLOBALS['mail'][0];
		$this->assertCount(5, $params);
		$this->assertSame('Alert: ERROR Foo Bar  Baz', $params[1]);
	}
}

?>
