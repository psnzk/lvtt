<?php
//dezend by  QQ:2172298892
namespace Monolog\Processor;

class GitProcessorTest extends \Monolog\TestCase
{
	public function testProcessor()
	{
		$processor = new GitProcessor();
		$record = $processor($this->getRecord());
		$this->assertArrayHasKey('git', $record['extra']);
		$this->assertTrue(!is_array($record['extra']['git']['branch']));
	}
}

?>
