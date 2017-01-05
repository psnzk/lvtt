<?php
//dezend by  QQ:2172298892
namespace Monolog;

class PsrLogCompatTest extends \Psr\Log\Test\LoggerInterfaceTest
{
	private $handler;

	public function getLogger()
	{
		$logger = new Logger('foo');
		$logger->pushHandler($handler = new Handler\TestHandler());
		$logger->pushProcessor(new Processor\PsrLogMessageProcessor());
		$handler->setFormatter(new Formatter\LineFormatter('%level_name% %message%'));
		$this->handler = $handler;
		return $logger;
	}

	public function getLogs()
	{
		$convert = function($record) {
			$lower = function($match) {
				return strtolower($match[0]);
			};
			return preg_replace_callback('{^[A-Z]+}', $lower, $record['formatted']);
		};
		return array_map($convert, $this->handler->getRecords());
	}
}

?>
