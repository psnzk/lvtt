<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class DoctrineCouchDBHandler extends AbstractProcessingHandler
{
	private $client;

	public function __construct(\Doctrine\CouchDB\CouchDBClient $client, $level = Monolog\Logger::DEBUG, $bubble = true)
	{
		$this->client = $client;
		parent::__construct($level, $bubble);
	}

	protected function write(array $record)
	{
		$this->client->postDocument($record['formatted']);
	}

	protected function getDefaultFormatter()
	{
		return new \Monolog\Formatter\NormalizerFormatter();
	}
}

?>
