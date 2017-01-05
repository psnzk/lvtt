<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class GelfHandler extends AbstractProcessingHandler
{
	/**
     * @var Publisher the publisher object that sends the message to the server
     */
	protected $publisher;

	public function __construct($publisher, $level = Monolog\Logger::DEBUG, $bubble = true)
	{
		parent::__construct($level, $bubble);
		if (!$publisher instanceof \Gelf\Publisher && !$publisher instanceof \Gelf\IMessagePublisher && !$publisher instanceof \Gelf\PublisherInterface) {
			throw new \InvalidArgumentException('Invalid publisher, expected a Gelf\\Publisher, Gelf\\IMessagePublisher or Gelf\\PublisherInterface instance');
		}

		$this->publisher = $publisher;
	}

	public function close()
	{
		$this->publisher = null;
	}

	protected function write(array $record)
	{
		$this->publisher->publish($record['formatted']);
	}

	protected function getDefaultFormatter()
	{
		return new \Monolog\Formatter\GelfMessageFormatter();
	}
}

?>
