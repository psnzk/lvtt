<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class SwiftMailerHandlerTest extends \Monolog\TestCase
{
	/** @var \Swift_Mailer|\PHPUnit_Framework_MockObject_MockObject */
	private $mailer;

	public function setUp()
	{
		$this->mailer = $this->getMockBuilder('Swift_Mailer')->disableOriginalConstructor()->getMock();
	}

	public function testMessageCreationIsLazyWhenUsingCallback()
	{
		$this->mailer->expects($this->never())->method('send');
		$callback = function() {
			throw new \RuntimeException('Swift_Message creation callback should not have been called in this test');
		};
		$handler = new SwiftMailerHandler($this->mailer, $callback);
		$records = array($this->getRecord(\Monolog\Logger::DEBUG), $this->getRecord(\Monolog\Logger::INFO));
		$handler->handleBatch($records);
	}

	public function testMessageCanBeCustomizedGivenLoggedData()
	{
		$expectedMessage = new \Swift_Message();
		$this->mailer->expects($this->once())->method('send')->with($this->callback(function($value) use($expectedMessage) {
			return $value instanceof \Swift_Message && ($value->getSubject() === 'Emergency') && ($value === $expectedMessage);
		}));
		$callback = function($content, array $records) use($expectedMessage) {
			$subject = (0 < count($records) ? 'Emergency' : 'Normal');
			$expectedMessage->setSubject($subject);
			return $expectedMessage;
		};
		$handler = new SwiftMailerHandler($this->mailer, $callback);
		$records = array($this->getRecord(\Monolog\Logger::EMERGENCY));
		$handler->handleBatch($records);
	}

	public function testMessageSubjectFormatting()
	{
		$messageTemplate = new \Swift_Message();
		$messageTemplate->setSubject('Alert: %level_name% %message%');
		$receivedMessage = null;
		$this->mailer->expects($this->once())->method('send')->with($this->callback(function($value) use(&$receivedMessage) {
			$receivedMessage = $value;
			return true;
		}));
		$handler = new SwiftMailerHandler($this->mailer, $messageTemplate);
		$records = array($this->getRecord(\Monolog\Logger::EMERGENCY));
		$handler->handleBatch($records);
		$this->assertEquals('Alert: EMERGENCY test', $receivedMessage->getSubject());
	}

	public function testMessageHaveUniqueId()
	{
		$messageTemplate = \Swift_Message::newInstance();
		$handler = new SwiftMailerHandler($this->mailer, $messageTemplate);
		$method = new \ReflectionMethod('Monolog\\Handler\\SwiftMailerHandler', 'buildMessage');
		$method->setAccessible(true);
		$method->invokeArgs($handler, array(
	$messageTemplate,
	array()
	));
		$builtMessage1 = $method->invoke($handler, $messageTemplate, array());
		$builtMessage2 = $method->invoke($handler, $messageTemplate, array());
		$this->assertFalse($builtMessage1->getId() === $builtMessage2->getId(), 'Two different messages have the same id');
	}
}

?>
