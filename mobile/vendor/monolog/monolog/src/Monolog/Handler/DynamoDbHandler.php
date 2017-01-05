<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class DynamoDbHandler extends AbstractProcessingHandler
{
	const DATE_FORMAT = 'Y-m-d\\TH:i:s.uO';

	/**
     * @var DynamoDbClient
     */
	protected $client;
	/**
     * @var string
     */
	protected $table;

	public function __construct(\Aws\DynamoDb\DynamoDbClient $client, $table, $level = Monolog\Logger::DEBUG, $bubble = true)
	{
		if (!defined('Aws\\Common\\Aws::VERSION') || version_compare('3.0', \Aws\Common\Aws::VERSION, '<=')) {
			throw new \RuntimeException('The DynamoDbHandler is only known to work with the AWS SDK 2.x releases');
		}

		$this->client = $client;
		$this->table = $table;
		parent::__construct($level, $bubble);
	}

	protected function write(array $record)
	{
		$filtered = $this->filterEmptyFields($record['formatted']);
		$formatted = $this->client->formatAttributes($filtered);
		$this->client->putItem(array('TableName' => $this->table, 'Item' => $formatted));
	}

	protected function filterEmptyFields(array $record)
	{
		return array_filter($record, function($value) {
			return !empty($value) || (false === $value) || (0 === $value);
		});
	}

	protected function getDefaultFormatter()
	{
		return new \Monolog\Formatter\ScalarFormatter(self::DATE_FORMAT);
	}
}

?>
