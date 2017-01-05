<?php
//dezend by  QQ:2172298892
namespace Whoops\Handler;

class PlainTextHandler extends Handler
{
	const VAR_DUMP_PREFIX = '   | ';

	/**
     * @var \Psr\Log\LoggerInterface
     */
	protected $logger;
	/**
     * @var bool
     */
	private $addTraceToOutput = true;
	/**
     * @var bool|integer
     */
	private $addTraceFunctionArgsToOutput = false;
	/**
     * @var integer
     */
	private $traceFunctionArgsOutputLimit = 1024;
	/**
     * @var bool
     */
	private $onlyForCommandLine = false;
	/**
     * @var bool
     */
	private $outputOnlyIfCommandLine = true;
	/**
     * @var bool
     */
	private $loggerOnly = false;

	public function __construct($logger = NULL)
	{
		$this->setLogger($logger);
	}

	public function setLogger($logger = NULL)
	{
		if (!(is_null($logger) || $logger instanceof \Psr\Log\LoggerInterface)) {
			throw new \InvalidArgumentException('Argument to ' . 'Whoops\\Handler\\PlainTextHandler::setLogger' . ' must be a valid Logger Interface (aka. Monolog), ' . get_class($logger) . ' given.');
		}

		$this->logger = $logger;
	}

	public function getLogger()
	{
		return $this->logger;
	}

	public function addTraceToOutput($addTraceToOutput = NULL)
	{
		if (func_num_args() == 0) {
			return $this->addTraceToOutput;
		}

		$this->addTraceToOutput = (bool) $addTraceToOutput;
		return $this;
	}

	public function addTraceFunctionArgsToOutput($addTraceFunctionArgsToOutput = NULL)
	{
		if (func_num_args() == 0) {
			return $this->addTraceFunctionArgsToOutput;
		}

		if (!is_integer($addTraceFunctionArgsToOutput)) {
			$this->addTraceFunctionArgsToOutput = (bool) $addTraceFunctionArgsToOutput;
		}
		else {
			$this->addTraceFunctionArgsToOutput = $addTraceFunctionArgsToOutput;
		}
	}

	public function setTraceFunctionArgsOutputLimit($traceFunctionArgsOutputLimit)
	{
		$this->traceFunctionArgsOutputLimit = (int) $traceFunctionArgsOutputLimit;
	}

	public function getTraceFunctionArgsOutputLimit()
	{
		return $this->traceFunctionArgsOutputLimit;
	}

	public function onlyForCommandLine($onlyForCommandLine = NULL)
	{
		if (func_num_args() == 0) {
			return $this->onlyForCommandLine;
		}

		$this->onlyForCommandLine = (bool) $onlyForCommandLine;
	}

	public function outputOnlyIfCommandLine($outputOnlyIfCommandLine = NULL)
	{
		if (func_num_args() == 0) {
			return $this->outputOnlyIfCommandLine;
		}

		$this->outputOnlyIfCommandLine = (bool) $outputOnlyIfCommandLine;
	}

	public function loggerOnly($loggerOnly = NULL)
	{
		if (func_num_args() == 0) {
			return $this->loggerOnly;
		}

		$this->loggerOnly = (bool) $loggerOnly;
	}

	private function isCommandLine()
	{
		return PHP_SAPI == 'cli';
	}

	private function canProcess()
	{
		return $this->isCommandLine() || !$this->onlyForCommandLine();
	}

	private function canOutput()
	{
		return ($this->isCommandLine() || !$this->outputOnlyIfCommandLine()) && !$this->loggerOnly();
	}

	private function getFrameArgsOutput(\Whoops\Exception\Frame $frame, $line)
	{
		if (($this->addTraceFunctionArgsToOutput() === false) || ($this->addTraceFunctionArgsToOutput() < $line)) {
			return '';
		}

		ob_start();
		var_dump($frame->getArgs());

		if ($this->getTraceFunctionArgsOutputLimit() < ob_get_length()) {
			ob_clean();
			return sprintf("\n%sArguments dump length greater than %d Bytes. Discarded.", self::VAR_DUMP_PREFIX, $this->getTraceFunctionArgsOutputLimit());
		}

		return sprintf("\n%s", preg_replace('/^/m', self::VAR_DUMP_PREFIX, ob_get_clean()));
	}

	private function getTraceOutput()
	{
		if (!$this->addTraceToOutput()) {
			return '';
		}

		$inspector = $this->getInspector();
		$frames = $inspector->getFrames();
		$response = "\nStack trace:";
		$line = 1;

		foreach ($frames as $frame) {
			$class = $frame->getClass();
			$template = "\n%3d. %s->%s() %s:%d%s";

			if (!$class) {
				$template = "\n%3d. %s%s() %s:%d%s";
			}

			$response .= sprintf($template, $line, $class, $frame->getFunction(), $frame->getFile(), $frame->getLine(), $this->getFrameArgsOutput($frame, $line));
			$line++;
		}

		return $response;
	}

	public function handle()
	{
		if (!$this->canProcess()) {
			return Handler::DONE;
		}

		$exception = $this->getException();
		$response = sprintf("%s: %s in file %s on line %d%s\n", get_class($exception), $exception->getMessage(), $exception->getFile(), $exception->getLine(), $this->getTraceOutput());

		if ($this->getLogger()) {
			$this->getLogger()->error($response);
		}

		if (!$this->canOutput()) {
			return Handler::DONE;
		}

		if (class_exists('\\Whoops\\Util\\Misc') && \Whoops\Util\Misc::canSendHeaders()) {
			header('Content-Type: text/plain');
		}

		echo $response;
		return Handler::QUIT;
	}
}

?>
