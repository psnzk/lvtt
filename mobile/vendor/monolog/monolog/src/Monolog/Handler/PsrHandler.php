<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class PsrHandler extends AbstractHandler
{
	/**
     * PSR-3 compliant logger
     *
     * @var LoggerInterface
     */
	protected $logger;

	public function __construct(\Psr\Log\LoggerInterface $logger, $level = Monolog\Logger::DEBUG, $bubble = true)
	{
		parent::__construct($level, $bubble);
		$this->logger = $logger;
	}

	public function handle(array $record)
	{
		if (!$this->isHandling($record)) {
			return false;
		}

		$this->logger->log(strtolower($record['level_name']), $record['message'], $record['context']);
		return false === $this->bubble;
	}
}

?>
