<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class RavenHandler extends AbstractProcessingHandler
{
	/**
     * Translates Monolog log levels to Raven log levels.
     */
	private $logLevels = array(Monolog\Logger::DEBUG => Raven_Client::DEBUG, Monolog\Logger::INFO => Raven_Client::INFO, Monolog\Logger::NOTICE => Raven_Client::INFO, Monolog\Logger::WARNING => Raven_Client::WARNING, Monolog\Logger::ERROR => Raven_Client::ERROR, Monolog\Logger::CRITICAL => Raven_Client::FATAL, Monolog\Logger::ALERT => Raven_Client::FATAL, Monolog\Logger::EMERGENCY => Raven_Client::FATAL);
	/**
     * @var string should represent the current version of the calling
     *             software. Can be any string (git commit, version number)
     */
	private $release;
	/**
     * @var Raven_Client the client object that sends the message to the server
     */
	protected $ravenClient;
	/**
     * @var LineFormatter The formatter to use for the logs generated via handleBatch()
     */
	protected $batchFormatter;

	public function __construct(\Raven_Client $ravenClient, $level = Monolog\Logger::DEBUG, $bubble = true)
	{
		parent::__construct($level, $bubble);
		$this->ravenClient = $ravenClient;
	}

	public function handleBatch(array $records)
	{
		$level = $this->level;
		$records = array_filter($records, function($record) use($level) {
			return $level <= $record['level'];
		});

		if (!$records) {
			return NULL;
		}

		$record = array_reduce($records, function($highest, $record) {
			if ($highest['level'] <= $record['level']) {
				return $record;
			}

			return $highest;
		});
		$logs = array();

		foreach ($records as $r) {
			$logs[] = $this->processRecord($r);
		}

		if ($logs) {
			$record['context']['logs'] = (string) $this->getBatchFormatter()->formatBatch($logs);
		}

		$this->handle($record);
	}

	public function setBatchFormatter(\Monolog\Formatter\FormatterInterface $formatter)
	{
		$this->batchFormatter = $formatter;
	}

	public function getBatchFormatter()
	{
		if (!$this->batchFormatter) {
			$this->batchFormatter = $this->getDefaultBatchFormatter();
		}

		return $this->batchFormatter;
	}

	protected function write(array $record)
	{
		$previousUserContext = false;
		$options = array();
		$options['level'] = $this->logLevels[$record['level']];
		$options['tags'] = array();

		if (!empty($record['extra']['tags'])) {
			$options['tags'] = array_merge($options['tags'], $record['extra']['tags']);
			unset($record['extra']['tags']);
		}

		if (!empty($record['context']['tags'])) {
			$options['tags'] = array_merge($options['tags'], $record['context']['tags']);
			unset($record['context']['tags']);
		}

		if (!empty($record['context']['fingerprint'])) {
			$options['fingerprint'] = $record['context']['fingerprint'];
			unset($record['context']['fingerprint']);
		}

		if (!empty($record['context']['logger'])) {
			$options['logger'] = $record['context']['logger'];
			unset($record['context']['logger']);
		}
		else {
			$options['logger'] = $record['channel'];
		}

		foreach ($this->getExtraParameters() as $key) {
			foreach (array('extra', 'context') as $source) {
				if (!empty($record[$source][$key])) {
					$options[$key] = $record[$source][$key];
					unset($record[$source][$key]);
				}
			}
		}

		if (!empty($record['context'])) {
			$options['extra']['context'] = $record['context'];

			if (!empty($record['context']['user'])) {
				$previousUserContext = $this->ravenClient->context->user;
				$this->ravenClient->user_context($record['context']['user']);
				unset($options['extra']['context']['user']);
			}
		}

		if (!empty($record['extra'])) {
			$options['extra']['extra'] = $record['extra'];
		}

		if (!empty($this->release) && !isset($options['release'])) {
			$options['release'] = $this->release;
		}

		if (isset($record['context']['exception']) && $record['context']['exception'] instanceof \Exception) {
			$options['extra']['message'] = $record['formatted'];
			$this->ravenClient->captureException($record['context']['exception'], $options);
		}
		else {
			$this->ravenClient->captureMessage($record['formatted'], array(), $options);
		}

		if ($previousUserContext !== false) {
			$this->ravenClient->user_context($previousUserContext);
		}
	}

	protected function getDefaultFormatter()
	{
		return new \Monolog\Formatter\LineFormatter('[%channel%] %message%');
	}

	protected function getDefaultBatchFormatter()
	{
		return new \Monolog\Formatter\LineFormatter();
	}

	protected function getExtraParameters()
	{
		return array('checksum', 'release');
	}

	public function setRelease($value)
	{
		$this->release = $value;
		return $this;
	}
}

?>
