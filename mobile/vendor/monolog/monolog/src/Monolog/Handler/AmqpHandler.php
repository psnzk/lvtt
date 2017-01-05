<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class AmqpHandler extends AbstractProcessingHandler
{
	/**
     * @var AMQPExchange|AMQPChannel $exchange
     */
	protected $exchange;
	/**
     * @var string
     */
	protected $exchangeName;

	public function __construct($exchange, $exchangeName = 'log', $level = Monolog\Logger::DEBUG, $bubble = true)
	{
		if ($exchange instanceof \AMQPExchange) {
			$exchange->setName($exchangeName);
		}
		else if ($exchange instanceof \PhpAmqpLib\Channel\AMQPChannel) {
			$this->exchangeName = $exchangeName;
		}
		else {
			throw new \InvalidArgumentException('PhpAmqpLib\\Channel\\AMQPChannel or AMQPExchange instance required');
		}

		$this->exchange = $exchange;
		parent::__construct($level, $bubble);
	}

	protected function write(array $record)
	{
		$data = $record['formatted'];
		$routingKey = $this->getRoutingKey($record);

		if ($this->exchange instanceof \AMQPExchange) {
			$this->exchange->publish($data, $routingKey, 0, array('delivery_mode' => 2, 'content_type' => 'application/json'));
		}
		else {
			$this->exchange->basic_publish($this->createAmqpMessage($data), $this->exchangeName, $routingKey);
		}
	}

	public function handleBatch(array $records)
	{
		if ($this->exchange instanceof \AMQPExchange) {
			parent::handleBatch($records);
			return NULL;
		}

		foreach ($records as $record) {
			if (!$this->isHandling($record)) {
				continue;
			}

			$record = $this->processRecord($record);
			$data = $this->getFormatter()->format($record);
			$this->exchange->batch_basic_publish($this->createAmqpMessage($data), $this->exchangeName, $this->getRoutingKey($record));
		}

		$this->exchange->publish_batch();
	}

	private function getRoutingKey(array $record)
	{
		$routingKey = sprintf('%s.%s', substr($record['level_name'], 0, 4), $record['channel']);
		return strtolower($routingKey);
	}

	private function createAmqpMessage($data)
	{
		return new \PhpAmqpLib\Message\AMQPMessage((string) $data, array('delivery_mode' => 2, 'content_type' => 'application/json'));
	}

	protected function getDefaultFormatter()
	{
		return new \Monolog\Formatter\JsonFormatter(\Monolog\Formatter\JsonFormatter::BATCH_MODE_JSON, false);
	}
}

?>
