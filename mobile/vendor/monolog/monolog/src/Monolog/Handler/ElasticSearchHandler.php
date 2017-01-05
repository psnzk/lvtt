<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class ElasticSearchHandler extends AbstractProcessingHandler
{
	/**
     * @var Client
     */
	protected $client;
	/**
     * @var array Handler config options
     */
	protected $options = array();

	public function __construct(\Elastica\Client $client, array $options = array(), $level = Monolog\Logger::DEBUG, $bubble = true)
	{
		parent::__construct($level, $bubble);
		$this->client = $client;
		$this->options = array_merge(array('index' => 'monolog', 'type' => 'record', 'ignore_error' => false), $options);
	}

	protected function write(array $record)
	{
		$this->bulkSend(array($record['formatted']));
	}

	public function setFormatter(\Monolog\Formatter\FormatterInterface $formatter)
	{
		if ($formatter instanceof \Monolog\Formatter\ElasticaFormatter) {
			return parent::setFormatter($formatter);
		}

		throw new \InvalidArgumentException('ElasticSearchHandler is only compatible with ElasticaFormatter');
	}

	public function getOptions()
	{
		return $this->options;
	}

	protected function getDefaultFormatter()
	{
		return new \Monolog\Formatter\ElasticaFormatter($this->options['index'], $this->options['type']);
	}

	public function handleBatch(array $records)
	{
		$documents = $this->getFormatter()->formatBatch($records);
		$this->bulkSend($documents);
	}

	protected function bulkSend(array $documents)
	{
		try {
			$this->client->addDocuments($documents);
		}
		catch (\Elastica\Exception\ExceptionInterface $e) {
			if (!$this->options['ignore_error']) {
				throw new \RuntimeException('Error sending messages to Elasticsearch', 0, $e);
			}
		}
	}
}

?>
