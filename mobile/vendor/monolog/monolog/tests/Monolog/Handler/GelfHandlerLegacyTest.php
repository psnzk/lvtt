<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class GelfHandlerLegacyTest extends \Monolog\TestCase
{
	public function setUp()
	{
		if (!class_exists('Gelf\\MessagePublisher') || !class_exists('Gelf\\Message')) {
			$this->markTestSkipped('mlehner/gelf-php not installed');
		}

		require_once __DIR__ . '/GelfMockMessagePublisher.php';
	}

	public function testConstruct()
	{
		$handler = new GelfHandler($this->getMessagePublisher());
		$this->assertInstanceOf('Monolog\\Handler\\GelfHandler', $handler);
	}

	protected function getHandler($messagePublisher)
	{
		$handler = new GelfHandler($messagePublisher);
		return $handler;
	}

	protected function getMessagePublisher()
	{
		return new GelfMockMessagePublisher('localhost');
	}

	public function testDebug()
	{
		$messagePublisher = $this->getMessagePublisher();
		$handler = $this->getHandler($messagePublisher);
		$record = $this->getRecord(\Monolog\Logger::DEBUG, 'A test debug message');
		$handler->handle($record);
		$this->assertEquals(7, $messagePublisher->lastMessage->getLevel());
		$this->assertEquals('test', $messagePublisher->lastMessage->getFacility());
		$this->assertEquals($record['message'], $messagePublisher->lastMessage->getShortMessage());
		$this->assertEquals(null, $messagePublisher->lastMessage->getFullMessage());
	}

	public function testWarning()
	{
		$messagePublisher = $this->getMessagePublisher();
		$handler = $this->getHandler($messagePublisher);
		$record = $this->getRecord(\Monolog\Logger::WARNING, 'A test warning message');
		$handler->handle($record);
		$this->assertEquals(4, $messagePublisher->lastMessage->getLevel());
		$this->assertEquals('test', $messagePublisher->lastMessage->getFacility());
		$this->assertEquals($record['message'], $messagePublisher->lastMessage->getShortMessage());
		$this->assertEquals(null, $messagePublisher->lastMessage->getFullMessage());
	}

	public function testInjectedGelfMessageFormatter()
	{
		$messagePublisher = $this->getMessagePublisher();
		$handler = $this->getHandler($messagePublisher);
		$handler->setFormatter(new \Monolog\Formatter\GelfMessageFormatter('mysystem', 'EXT', 'CTX'));
		$record = $this->getRecord(\Monolog\Logger::WARNING, 'A test warning message');
		$record['extra']['blarg'] = 'yep';
		$record['context']['from'] = 'logger';
		$handler->handle($record);
		$this->assertEquals('mysystem', $messagePublisher->lastMessage->getHost());
		$this->assertArrayHasKey('_EXTblarg', $messagePublisher->lastMessage->toArray());
		$this->assertArrayHasKey('_CTXfrom', $messagePublisher->lastMessage->toArray());
	}
}

?>
