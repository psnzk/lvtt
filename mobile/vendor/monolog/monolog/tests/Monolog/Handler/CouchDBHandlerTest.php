<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class CouchDBHandlerTest extends \Monolog\TestCase
{
	public function testHandle()
	{
		$record = $this->getRecord(\Monolog\Logger::WARNING, 'test', array('data' => new \stdClass(), 'foo' => 34));
		$handler = new CouchDBHandler();

		try {
			$handler->handle($record);
		}
		catch (\RuntimeException $e) {
			$this->markTestSkipped('Could not connect to couchdb server on http://localhost:5984');
		}
	}
}

?>
