<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class MongoDBHandlerTest extends \Monolog\TestCase
{
	public function testConstructorShouldThrowExceptionForInvalidMongo()
	{
		new MongoDBHandler(new \stdClass(), 'DB', 'Collection');
	}

	public function testHandle()
	{
		$mongo = $this->getMock('Mongo', array('selectCollection'), array(), '', false);
		$collection = $this->getMock('stdClass', array('save'));
		$mongo->expects($this->once())->method('selectCollection')->with('DB', 'Collection')->will($this->returnValue($collection));
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
		$collection->expects($this->once())->method('save')->with($expected);
		$handler = new MongoDBHandler($mongo, 'DB', 'Collection');
		$handler->handle($record);
	}
}

if (!class_exists('Mongo')) {
	class Mongo
	{
		public function selectCollection()
		{
		}
	}
}

?>
