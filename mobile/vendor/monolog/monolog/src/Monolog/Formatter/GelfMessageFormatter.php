<?php
//dezend by  QQ:2172298892
namespace Monolog\Formatter;

class GelfMessageFormatter extends NormalizerFormatter
{
	const MAX_LENGTH = 32766;

	/**
     * @var string the name of the system for the Gelf log message
     */
	protected $systemName;
	/**
     * @var string a prefix for 'extra' fields from the Monolog record (optional)
     */
	protected $extraPrefix;
	/**
     * @var string a prefix for 'context' fields from the Monolog record (optional)
     */
	protected $contextPrefix;
	/**
     * Translates Monolog log levels to Graylog2 log priorities.
     */
	private $logLevels = array(Monolog\Logger::DEBUG => 7, Monolog\Logger::INFO => 6, Monolog\Logger::NOTICE => 5, Monolog\Logger::WARNING => 4, Monolog\Logger::ERROR => 3, Monolog\Logger::CRITICAL => 2, Monolog\Logger::ALERT => 1, Monolog\Logger::EMERGENCY => 0);

	public function __construct($systemName = NULL, $extraPrefix = NULL, $contextPrefix = 'ctxt_')
	{
		parent::__construct('U.u');
		$this->systemName = $systemName ?: gethostname();
		$this->extraPrefix = $extraPrefix;
		$this->contextPrefix = $contextPrefix;
	}

	public function format(array $record)
	{
		$record = parent::format($record);
		if (!(isset($record['datetime']) && isset($record['message']) && isset($record['level']))) {
			throw new \InvalidArgumentException('The record should at least contain datetime, message and level keys, ' . var_export($record, true) . ' given');
		}

		$message = new \Gelf\Message();
		$message->setTimestamp($record['datetime'])->setShortMessage((string) $record['message'])->setHost($this->systemName)->setLevel($this->logLevels[$record['level']]);
		$len = 200 + strlen((string) $record['message']) + strlen($this->systemName);

		if (self::MAX_LENGTH < $len) {
			$message->setShortMessage(substr($record['message'], 0, self::MAX_LENGTH - 200));
			return $message;
		}

		if (isset($record['channel'])) {
			$message->setFacility($record['channel']);
			$len += strlen($record['channel']);
		}

		if (isset($record['extra']['line'])) {
			$message->setLine($record['extra']['line']);
			$len += 10;
			unset($record['extra']['line']);
		}

		if (isset($record['extra']['file'])) {
			$message->setFile($record['extra']['file']);
			$len += strlen($record['extra']['file']);
			unset($record['extra']['file']);
		}

		foreach ($record['extra'] as $key => $val) {
			$val = (is_scalar($val) || (null === $val) ? $val : $this->toJson($val));
			$len += strlen($this->extraPrefix . $key . $val);

			if (self::MAX_LENGTH < $len) {
				$message->setAdditional($this->extraPrefix . $key, substr($val, 0, self::MAX_LENGTH - $len));
				break;
			}

			$message->setAdditional($this->extraPrefix . $key, $val);
		}

		foreach ($record['context'] as $key => $val) {
			$val = (is_scalar($val) || (null === $val) ? $val : $this->toJson($val));
			$len += strlen($this->contextPrefix . $key . $val);

			if (self::MAX_LENGTH < $len) {
				$message->setAdditional($this->contextPrefix . $key, substr($val, 0, self::MAX_LENGTH - $len));
				break;
			}

			$message->setAdditional($this->contextPrefix . $key, $val);
		}

		if ((null === $message->getFile()) && isset($record['context']['exception']['file'])) {
			if (preg_match('/^(.+):([0-9]+)$/', $record['context']['exception']['file'], $matches)) {
				$message->setFile($matches[1]);
				$message->setLine($matches[2]);
			}
		}

		return $message;
	}
}

?>
