<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class FleepHookHandlerTest extends \Monolog\TestCase
{
	const TOKEN = '123abc';

	/**
     * @var FleepHookHandler
     */
	private $handler;

	public function setUp()
	{
		parent::setUp();

		if (!extension_loaded('openssl')) {
			$this->markTestSkipped('This test requires openssl extension to run');
		}

		$this->handler = new FleepHookHandler(self::TOKEN);
	}

	public function testConstructorSetsExpectedDefaults()
	{
		$this->assertEquals(\Monolog\Logger::DEBUG, $this->handler->getLevel());
		$this->assertEquals(true, $this->handler->getBubble());
	}

	public function testHandlerUsesLineFormatterWhichIgnoresEmptyArrays()
	{
		$record = array(
			'message'    => 'msg',
			'context'    => array(),
			'level'      => \Monolog\Logger::DEBUG,
			'level_name' => \Monolog\Logger::getLevelName(\Monolog\Logger::DEBUG),
			'channel'    => 'channel',
			'datetime'   => new \DateTime(),
			'extra'      => array()
			);
		$expectedFormatter = new \Monolog\Formatter\LineFormatter(null, null, true, true);
		$expected = $expectedFormatter->format($record);
		$handlerFormatter = $this->handler->getFormatter();
		$actual = $handlerFormatter->format($record);
		$this->assertEquals($expected, $actual, 'Empty context and extra arrays should not be rendered');
	}

	public function testConnectionStringisConstructedCorrectly()
	{
		$this->assertEquals('ssl://' . FleepHookHandler::FLEEP_HOST . ':443', $this->handler->getConnectionString());
	}
}

?>
