<?php
//dezend by  QQ:2172298892
namespace Monolog\Formatter;

class JsonFormatterTest extends \Monolog\TestCase
{
	public function testConstruct()
	{
		$formatter = new JsonFormatter();
		$this->assertEquals(JsonFormatter::BATCH_MODE_JSON, $formatter->getBatchMode());
		$this->assertEquals(true, $formatter->isAppendingNewlines());
		$formatter = new JsonFormatter(JsonFormatter::BATCH_MODE_NEWLINES, false);
		$this->assertEquals(JsonFormatter::BATCH_MODE_NEWLINES, $formatter->getBatchMode());
		$this->assertEquals(false, $formatter->isAppendingNewlines());
	}

	public function testFormat()
	{
		$formatter = new JsonFormatter();
		$record = $this->getRecord();
		$this->assertEquals(json_encode($record) . "\n", $formatter->format($record));
		$formatter = new JsonFormatter(JsonFormatter::BATCH_MODE_JSON, false);
		$record = $this->getRecord();
		$this->assertEquals(json_encode($record), $formatter->format($record));
	}

	public function testFormatBatch()
	{
		$formatter = new JsonFormatter();
		$records = array($this->getRecord(\Monolog\Logger::WARNING), $this->getRecord(\Monolog\Logger::DEBUG));
		$this->assertEquals(json_encode($records), $formatter->formatBatch($records));
	}

	public function testFormatBatchNewlines()
	{
		$formatter = new JsonFormatter(JsonFormatter::BATCH_MODE_NEWLINES);
		$records = $expected = array($this->getRecord(\Monolog\Logger::WARNING), $this->getRecord(\Monolog\Logger::DEBUG));
		array_walk($expected, function(&$value, $key) {
			$value = json_encode($value);
		});
		$this->assertEquals(implode("\n", $expected), $formatter->formatBatch($records));
	}

	public function testDefFormatWithException()
	{
		$formatter = new JsonFormatter();
		$exception = new \RuntimeException('Foo');
		$message = $formatter->format(array(
	'level_name' => 'CRITICAL',
	'channel'    => 'core',
	'context'    => array('exception' => $exception),
	'datetime'   => new \DateTime(),
	'extra'      => array(),
	'message'    => 'foobar'
	));

		if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
			$path = substr(json_encode($exception->getFile(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), 1, -1);
		}
		else {
			$path = substr(json_encode($exception->getFile()), 1, -1);
		}

		$this->assertEquals('{"level_name":"CRITICAL","channel":"core","context":{"exception":{"class":"RuntimeException","message":"' . $exception->getMessage() . '","code":' . $exception->getCode() . ',"file":"' . $path . ':' . $exception->getLine() . '"}},"datetime":' . json_encode(new \DateTime()) . ',"extra":[],"message":"foobar"}' . "\n", $message);
	}

	public function testDefFormatWithPreviousException()
	{
		$formatter = new JsonFormatter();
		$exception = new \RuntimeException('Foo', 0, new \LogicException('Wut?'));
		$message = $formatter->format(array(
	'level_name' => 'CRITICAL',
	'channel'    => 'core',
	'context'    => array('exception' => $exception),
	'datetime'   => new \DateTime(),
	'extra'      => array(),
	'message'    => 'foobar'
	));

		if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
			$pathPrevious = substr(json_encode($exception->getPrevious()->getFile(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), 1, -1);
			$pathException = substr(json_encode($exception->getFile(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), 1, -1);
		}
		else {
			$pathPrevious = substr(json_encode($exception->getPrevious()->getFile()), 1, -1);
			$pathException = substr(json_encode($exception->getFile()), 1, -1);
		}

		$this->assertEquals('{"level_name":"CRITICAL","channel":"core","context":{"exception":{"class":"RuntimeException","message":"' . $exception->getMessage() . '","code":' . $exception->getCode() . ',"file":"' . $pathException . ':' . $exception->getLine() . '","previous":{"class":"LogicException","message":"' . $exception->getPrevious()->getMessage() . '","code":' . $exception->getPrevious()->getCode() . ',"file":"' . $pathPrevious . ':' . $exception->getPrevious()->getLine() . '"}}},"datetime":' . json_encode(new \DateTime()) . ',"extra":[],"message":"foobar"}' . "\n", $message);
	}
}

?>
