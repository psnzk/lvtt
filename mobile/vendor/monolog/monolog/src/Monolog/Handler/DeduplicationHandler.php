<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class DeduplicationHandler extends BufferHandler
{
	/**
     * @var string
     */
	protected $deduplicationStore;
	/**
     * @var int
     */
	protected $deduplicationLevel;
	/**
     * @var int
     */
	protected $time;
	/**
     * @var bool
     */
	private $gc = false;

	public function __construct(HandlerInterface $handler, $deduplicationStore = NULL, $deduplicationLevel = Monolog\Logger::ERROR, $time = 60, $bubble = true)
	{
		parent::__construct($handler, 0, \Monolog\Logger::DEBUG, $bubble, false);
		$this->deduplicationStore = $deduplicationStore === null ? sys_get_temp_dir() . '/monolog-dedup-' . substr(md5(__FILE__), 0, 20) . '.log' : $deduplicationStore;
		$this->deduplicationLevel = \Monolog\Logger::toMonologLevel($deduplicationLevel);
		$this->time = $time;
	}

	public function flush()
	{
		if ($this->bufferSize === 0) {
			return NULL;
		}

		$passthru = null;

		foreach ($this->buffer as $record) {
			if ($this->deduplicationLevel <= $record['level']) {
				$passthru = $passthru || !$this->isDuplicate($record);

				if ($passthru) {
					$this->appendRecord($record);
				}
			}
		}

		if (($passthru === true) || ($passthru === null)) {
			$this->handler->handleBatch($this->buffer);
		}

		$this->clear();

		if ($this->gc) {
			$this->collectLogs();
		}
	}

	private function isDuplicate(array $record)
	{
		if (!file_exists($this->deduplicationStore)) {
			return false;
		}

		$store = file($this->deduplicationStore, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

		if (!is_array($store)) {
			return false;
		}

		$yesterday = time() - 86400;
		$timestampValidity = $record['datetime']->getTimestamp() - $this->time;
		$expectedMessage = preg_replace('{[\\r\\n].*}', '', $record['message']);

		for ($i = count($store) - 1; 0 <= $i; $i--) {
			list($timestamp, $level, $message) = explode(':', $store[$i], 3);
			if (($level === $record['level_name']) && ($message === $expectedMessage) && ($timestampValidity < $timestamp)) {
				return true;
			}

			if ($timestamp < $yesterday) {
				$this->gc = true;
			}
		}

		return false;
	}

	private function collectLogs()
	{
		if (!file_exists($this->deduplicationStore)) {
			return false;
		}

		$handle = fopen($this->deduplicationStore, 'rw+');
		flock($handle, LOCK_EX);
		$validLogs = array();
		$timestampValidity = time() - $this->time;

		while (!feof($handle)) {
			$log = fgets($handle);

			if ($timestampValidity <= substr($log, 0, 10)) {
				$validLogs[] = $log;
			}
		}

		ftruncate($handle, 0);
		rewind($handle);

		foreach ($validLogs as $log) {
			fwrite($handle, $log);
		}

		flock($handle, LOCK_UN);
		fclose($handle);
		$this->gc = false;
	}

	private function appendRecord(array $record)
	{
		file_put_contents($this->deduplicationStore, $record['datetime']->getTimestamp() . ':' . $record['level_name'] . ':' . preg_replace('{[\\r\\n].*}', '', $record['message']) . "\n", FILE_APPEND);
	}
}

?>
