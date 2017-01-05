<?php
//dezend by  QQ:2172298892
namespace Monolog\Formatter;

class ChromePHPFormatter implements FormatterInterface
{
	/**
     * Translates Monolog log levels to Wildfire levels.
     */
	private $logLevels = array(Monolog\Logger::DEBUG => 'log', Monolog\Logger::INFO => 'info', Monolog\Logger::NOTICE => 'info', Monolog\Logger::WARNING => 'warn', Monolog\Logger::ERROR => 'error', Monolog\Logger::CRITICAL => 'error', Monolog\Logger::ALERT => 'error', Monolog\Logger::EMERGENCY => 'error');

	public function format(array $record)
	{
		$backtrace = 'unknown';
		if (isset($record['extra']['file']) && isset($record['extra']['line'])) {
			$backtrace = $record['extra']['file'] . ' : ' . $record['extra']['line'];
			unset($record['extra']['file']);
			unset($record['extra']['line']);
		}

		$message = array('message' => $record['message']);

		if ($record['context']) {
			$message['context'] = $record['context'];
		}

		if ($record['extra']) {
			$message['extra'] = $record['extra'];
		}

		if (count($message) === 1) {
			$message = reset($message);
		}

		return array($record['channel'], $message, $backtrace, $this->logLevels[$record['level']]);
	}

	public function formatBatch(array $records)
	{
		$formatted = array();

		foreach ($records as $record) {
			$formatted[] = $this->format($record);
		}

		return $formatted;
	}
}

?>
