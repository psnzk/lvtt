<?php
//dezend by  QQ:2172298892
namespace Monolog;

class Registry
{
	/**
     * List of all loggers in the registry (by named indexes)
     *
     * @var Logger[]
     */
	static private $loggers = array();

	static public function addLogger(Logger $logger, $name = NULL, $overwrite = false)
	{
		$name = $name ?: $logger->getName();
		if (isset(self::$loggers[$name]) && !$overwrite) {
			throw new \InvalidArgumentException('Logger with the given name already exists');
		}

		self::$loggers[$name] = $logger;
	}

	static public function hasLogger($logger)
	{
		if ($logger instanceof Logger) {
			$index = array_search($logger, self::$loggers, true);
			return false !== $index;
		}
		else {
			return isset(self::$loggers[$logger]);
		}
	}

	static public function removeLogger($logger)
	{
		if ($logger instanceof Logger) {
			if (false !== ($idx = array_search($logger, self::$loggers, true))) {
				unset(self::$loggers[$idx]);
			}
		}
		else {
			unset(self::$loggers[$logger]);
		}
	}

	static public function clear()
	{
		self::$loggers = array();
	}

	static public function getInstance($name)
	{
		if (!isset(self::$loggers[$name])) {
			throw new \InvalidArgumentException(sprintf('Requested "%s" logger instance is not in the registry', $name));
		}

		return self::$loggers[$name];
	}

	static public function __callStatic($name, $arguments)
	{
		return self::getInstance($name);
	}
}


?>
