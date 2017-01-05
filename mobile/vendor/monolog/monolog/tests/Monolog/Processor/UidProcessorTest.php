<?php
//dezend by  QQ:2172298892
namespace Monolog\Processor;

class UidProcessorTest extends \Monolog\TestCase
{
	public function testProcessor()
	{
		$processor = new UidProcessor();
		$record = $processor($this->getRecord());
		$this->assertArrayHasKey('uid', $record['extra']);
	}

	public function testGetUid()
	{
		$processor = new UidProcessor(10);
		$this->assertEquals(10, strlen($processor->getUid()));
	}
}

?>
