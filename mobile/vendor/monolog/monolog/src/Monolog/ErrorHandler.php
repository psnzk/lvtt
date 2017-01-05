<?php
//dezend by  QQ:2172298892
namespace Monolog;

class ErrorHandler
{
	private $logger;
	private $previousExceptionHandler;
	private $uncaughtExceptionLevel;
	private $previousErrorHandler;
	private $errorLevelMap;
	private $handleOnlyReportedErrors;
	private $hasFatalErrorHandler;
	private $fatalLevel;
	private $reservedMemory;
	static private $fatalErrors = array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR);

	public function __construct(\Psr\Log\LoggerInterface $logger)
	{
		$this->logger = $logger;
	}

	static public function register(\Psr\Log\LoggerInterface $logger, $errorLevelMap = array(), $exceptionLevel = NULL, $fatalLevel = NULL)
	{
		$handler = new static($logger);

		if ($errorLevelMap !== false) {
			$handler->registerErrorHandler($errorLevelMap);
		}

		if ($exceptionLevel !== false) {
			$handler->registerExceptionHandler($exceptionLevel);
		}

		if ($fatalLevel !== false) {
			$handler->registerFatalHandler($fatalLevel);
		}

		return $handler;
	}

	public function registerExceptionHandler($level = NULL, $callPrevious = true)
	{
		$prev = set_exception_handler(array($this, 'handleException'));
		$this->uncaughtExceptionLevel = $level;
		if ($callPrevious && $prev) {
			$this->previousExceptionHandler = $prev;
		}
	}

	public function registerErrorHandler(array $levelMap = array(), $callPrevious = true, $errorTypes = -1, $handleOnlyReportedErrors = true)
	{
		$prev = set_error_handler(array($this, 'handleError'), $errorTypes);
		$this->errorLevelMap = array_replace($this->defaultErrorLevelMap(), $levelMap);

		if ($callPrevious) {
			$this->previousErrorHandler = $prev ?: true;
		}

		$this->handleOnlyReportedErrors = $handleOnlyReportedErrors;
	}

	public function registerFatalHandler($level = NULL, $reservedMemorySize = 20)
	{
		register_shutdown_function(array($this, 'handleFatalError'));
		$this->reservedMemory = str_repeat(' ', 1024 * $reservedMemorySize);
		$this->fatalLevel = $level;
		$this->hasFatalErrorHandler = true;
	}

	protected function defaultErrorLevelMap()
	{
		return array(E_ERROR => \Psr\Log\LogLevel::CRITICAL, E_WARNING => \Psr\Log\LogLevel::WARNING, E_PARSE => \Psr\Log\LogLevel::ALERT, E_NOTICE => \Psr\Log\LogLevel::NOTICE, E_CORE_ERROR => \Psr\Log\LogLevel::CRITICAL, E_CORE_WARNING => \Psr\Log\LogLevel::WARNING, E_COMPILE_ERROR => \Psr\Log\LogLevel::ALERT, E_COMPILE_WARNING => \Psr\Log\LogLevel::WARNING, E_USER_ERROR => \Psr\Log\LogLevel::ERROR, E_USER_WARNING => \Psr\Log\LogLevel::WARNING, E_USER_NOTICE => \Psr\Log\LogLevel::NOTICE, E_STRICT => \Psr\Log\LogLevel::NOTICE, E_RECOVERABLE_ERROR => \Psr\Log\LogLevel::ERROR, E_DEPRECATED => \Psr\Log\LogLevel::NOTICE, E_USER_DEPRECATED => \Psr\Log\LogLevel::NOTICE);
	}

	public function handleException($e)
	{
		$this->logger->log($this->uncaughtExceptionLevel === null ? \Psr\Log\LogLevel::ERROR : $this->uncaughtExceptionLevel, sprintf('Uncaught Exception %s: "%s" at %s line %s', get_class($e), $e->getMessage(), $e->getFile(), $e->getLine()), array('exception' => $e));

		if ($this->previousExceptionHandler) {
			call_user_func($this->previousExceptionHandler, $e);
		}

		exit(255);
	}

	public function handleError($code, $message, $file = '', $line = 0, $context = array())
	{
		if ($this->handleOnlyReportedErrors && !(error_reporting() & $code)) {
			return NULL;
		}

		if (!$this->hasFatalErrorHandler || !in_array($code, self::$fatalErrors, true)) {
			$level = (isset($this->errorLevelMap[$code]) ? $this->errorLevelMap[$code] : \Psr\Log\LogLevel::CRITICAL);
			$this->logger->log($level, self::codeToString($code) . ': ' . $message, array('code' => $code, 'message' => $message, 'file' => $file, 'line' => $line));
		}

		if ($this->previousErrorHandler === true) {
			return false;
		}
		else if ($this->previousErrorHandler) {
			return call_user_func($this->previousErrorHandler, $code, $message, $file, $line, $context);
		}
	}

	public function handleFatalError()
	{
		$this->reservedMemory = null;
		$lastError = error_get_last();
		if ($lastError && in_array($lastError['type'], self::$fatalErrors, true)) {
			$this->logger->log($this->fatalLevel === null ? \Psr\Log\LogLevel::ALERT : $this->fatalLevel, 'Fatal Error (' . self::codeToString($lastError['type']) . '): ' . $lastError['message'], array('code' => $lastError['type'], 'message' => $lastError['message'], 'file' => $lastError['file'], 'line' => $lastError['line']));

			if ($this->logger instanceof Logger) {
				foreach ($this->logger->getHandlers() as $handler) {
					if ($handler instanceof Handler\AbstractHandler) {
						$handler->close();
					}
				}
			}
		}
	}

	static private function codeToString($code)
	{
		switch ($code) {
		case E_ERROR:
			return 'E_ERROR';
		case E_WARNING:
			return 'E_WARNING';
		case E_PARSE:
			return 'E_PARSE';
		case E_NOTICE:
			return 'E_NOTICE';
		case E_CORE_ERROR:
			return 'E_CORE_ERROR';
		case E_CORE_WARNING:
			return 'E_CORE_WARNING';
		case E_COMPILE_ERROR:
			return 'E_COMPILE_ERROR';
		case E_COMPILE_WARNING:
			return 'E_COMPILE_WARNING';
		case E_USER_ERROR:
			return 'E_USER_ERROR';
		case E_USER_WARNING:
			return 'E_USER_WARNING';
		case E_USER_NOTICE:
			return 'E_USER_NOTICE';
		case E_STRICT:
			return 'E_STRICT';
		case E_RECOVERABLE_ERROR:
			return 'E_RECOVERABLE_ERROR';
		case E_DEPRECATED:
			return 'E_DEPRECATED';
		case E_USER_DEPRECATED:
			return 'E_USER_DEPRECATED';
		}

		return 'Unknown PHP error';
	}
}


?>
