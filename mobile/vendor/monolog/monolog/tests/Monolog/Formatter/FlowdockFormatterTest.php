<?php
//dezend by  QQ:2172298892
namespace Monolog\Formatter;

class FlowdockFormatterTest extends \Monolog\TestCase
{
	public function testFormat()
	{
		$formatter = new FlowdockFormatter('test_source', 'source@test.com');
		$record = $this->getRecord();
		$expected = array(
			'source'       => 'test_source',
			'from_address' => 'source@test.com',
			'subject'      => 'in test_source: WARNING - test',
			'content'      => 'test',
			'tags'         => array('#logs', '#warning', '#test'),
			'project'      => 'test_source'
			);
		$formatted = $formatter->format($record);
		$this->assertEquals($expected, $formatted['flowdock']);
	}

	public function testFormatBatch()
	{
		$formatter = new FlowdockFormatter('test_source', 'source@test.com');
		$records = array($this->getRecord(\Monolog\Logger::WARNING), $this->getRecord(\Monolog\Logger::DEBUG));
		$formatted = $formatter->formatBatch($records);
		$this->assertArrayHasKey('flowdock', $formatted[0]);
		$this->assertArrayHasKey('flowdock', $formatted[1]);
	}
}

?>
