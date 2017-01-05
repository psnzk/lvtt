<?php
//dezend by  QQ:2172298892
namespace Monolog\Processor;

class ProcessIdProcessorTest extends \Monolog\TestCase
{
	public function testProcessor()
	{
		$processor = new ProcessIdProcessor();
		$record = $processor($this->getRecord());
		$this->assertArrayHasKey('process_id', $record['extra']);
		$this->assertInternalType('int', $record['extra']['process_id']);
		$this->assertGreaterThan(0, $record['extra']['process_id']);
		$this->assertEquals(getmypid(), $record['extra']['process_id']);
	}
}

?>
