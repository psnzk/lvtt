<?php
//dezend by  QQ:2172298892
namespace Monolog\Processor;

class TagProcessorTest extends \Monolog\TestCase
{
	public function testProcessor()
	{
		$tags = array(1, 2, 3);
		$processor = new TagProcessor($tags);
		$record = $processor($this->getRecord());
		$this->assertEquals($tags, $record['extra']['tags']);
	}

	public function testProcessorTagModification()
	{
		$tags = array(1, 2, 3);
		$processor = new TagProcessor($tags);
		$record = $processor($this->getRecord());
		$this->assertEquals($tags, $record['extra']['tags']);
		$processor->setTags(array('a', 'b'));
		$record = $processor($this->getRecord());
		$this->assertEquals(array('a', 'b'), $record['extra']['tags']);
		$processor->addTags(array(0 => 'a', 1 => 'c', 'foo' => 'bar'));
		$record = $processor($this->getRecord());
		$this->assertEquals(array(0 => 'a', 1 => 'b', 2 => 'a', 3 => 'c', 'foo' => 'bar'), $record['extra']['tags']);
	}
}

?>
