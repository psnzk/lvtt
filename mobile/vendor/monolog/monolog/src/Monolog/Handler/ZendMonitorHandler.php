<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class ZendMonitorHandler extends AbstractProcessingHandler
{
	/**
     * Monolog level / ZendMonitor Custom Event priority map
     *
     * @var array
     */
	protected $levelMap = array(Monolog\Logger::DEBUG => 1, Monolog\Logger::INFO => 2, Monolog\Logger::NOTICE => 3, Monolog\Logger::WARNING => 4, Monolog\Logger::ERROR => 5, Monolog\Logger::CRITICAL => 6, Monolog\Logger::ALERT => 7, Monolog\Logger::EMERGENCY => 0);

	public function __construct($level = Monolog\Logger::DEBUG, $bubble = true)
	{
		if (!function_exists('zend_monitor_custom_event')) {
			throw new MissingExtensionException('You must have Zend Server installed in order to use this handler');
		}

		parent::__construct($level, $bubble);
	}

	protected function write(array $record)
	{
		$this->writeZendMonitorCustomEvent($this->levelMap[$record['level']], $record['message'], $record['formatted']);
	}

	protected function writeZendMonitorCustomEvent($level, $message, $formatted)
	{
		zend_monitor_custom_event($level, $message, $formatted);
	}

	public function getDefaultFormatter()
	{
		return new \Monolog\Formatter\NormalizerFormatter();
	}

	public function getLevelMap()
	{
		return $this->levelMap;
	}
}

?>
