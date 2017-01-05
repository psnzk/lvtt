<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class DoctrineCouchDBHandlerTest extends \Monolog\TestCase
{
	protected function setup()
	{
		if (!class_exists('Doctrine\\CouchDB\\CouchDBClient')) {
			$this->markTestSkipped('The "doctrine/couchdb" package is not installed');
		}
	}

	public function testHandle()
	{
		$client = $this->getMockBuilder('Doctrine\\CouchDB\\CouchDBClient')->setMethods(array('postDocument'))->disableOriginalConstructor()->getMock();
		$record = $this->getRecord(\Monolog\Logger::WARNING, 'test', array('data' => new \stdClass(), 'foo' => 34));
		$expected = array(
			'message'    => 'test',
			'context'    => array('data' => '[object] (stdClass: {})', 'foo' => 34),
			'level'      => \Monolog\Logger::WARNING,
			'level_name' => 'WARNING',
			'channel'    => 'test',
			'datetime'   => $record['datetime']->format('Y-m-d H:i:s'),
			'extra'      => array()
			);
		$client->expects($this->once())->method('postDocument')->with($expected);
		$handler = new DoctrineCouchDBHandler($client);
		$handler->handle($record);
	}
}

?>
