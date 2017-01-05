<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class MongoDBHandler extends AbstractProcessingHandler
{
	protected $mongoCollection;

	public function __construct($mongo, $database, $collection, $level = Monolog\Logger::DEBUG, $bubble = true)
	{
		if (!($mongo instanceof \MongoClient || $mongo instanceof \Mongo || $mongo instanceof \MongoDB\Client)) {
			throw new \InvalidArgumentException('MongoClient, Mongo or MongoDB\\Client instance required');
		}

		$this->mongoCollection = $mongo->selectCollection($database, $collection);
		parent::__construct($level, $bubble);
	}

	protected function write(array $record)
	{
		if ($this->mongoCollection instanceof \MongoDB\Collection) {
			$this->mongoCollection->insertOne($record['formatted']);
		}
		else {
			$this->mongoCollection->save($record['formatted']);
		}
	}

	protected function getDefaultFormatter()
	{
		return new \Monolog\Formatter\NormalizerFormatter();
	}
}

?>
