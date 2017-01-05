<?php
//dezend by  QQ:2172298892
namespace Whoops;

class Run
{
	const EXCEPTION_HANDLER = 'handleException';
	const ERROR_HANDLER = 'handleError';
	const SHUTDOWN_HANDLER = 'handleShutdown';

	protected $isRegistered;
	protected $allowQuit = true;
	protected $sendOutput = true;
	/**
     * @var integer|false
     */
	protected $sendHttpCode = 500;
	/**
     * @var HandlerInterface[]
     */
	protected $handlerStack = array();
	protected $silencedPatterns = array();
	/**
     * In certain scenarios, like in shutdown handler, we can not throw exceptions
     * @var bool
     */
	private $canThrowExceptions = true;

	public function pushHandler($handler)
	{
		if (is_callable($handler)) {
			$handler = new Handler\CallbackHandler($handler);
		}

		if (!$handler instanceof Handler\HandlerInterface) {
			throw new \InvalidArgumentException('Argument to ' . 'Whoops\\Run::pushHandler' . ' must be a callable, or instance of' . 'Whoops\\Handler\\HandlerInterface');
		}

		$this->handlerStack[] = $handler;
		return $this;
	}

	public function popHandler()
	{
		return array_pop($this->handlerStack);
	}

	public function getHandlers()
	{
		return $this->handlerStack;
	}

	public function clearHandlers()
	{
		$this->handlerStack = array();
		return $this;
	}

	protected function getInspector(\Exception $exception)
	{
		return new Exception\Inspector($exception);
	}

	public function register()
	{
		if (!$this->isRegistered) {
			class_exists('\\Whoops\\Exception\\ErrorException');
			class_exists('\\Whoops\\Exception\\FrameCollection');
			class_exists('\\Whoops\\Exception\\Frame');
			class_exists('\\Whoops\\Exception\\Inspector');
			set_error_handler(array($this, self::ERROR_HANDLER));
			set_exception_handler(array($this, self::EXCEPTION_HANDLER));
			register_shutdown_function(array($this, self::SHUTDOWN_HANDLER));
			$this->isRegistered = true;
		}

		return $this;
	}

	public function unregister()
	{
		if ($this->isRegistered) {
			restore_exception_handler();
			restore_error_handler();
			$this->isRegistered = false;
		}

		return $this;
	}

	public function allowQuit($exit = NULL)
	{
		if (func_num_args() == 0) {
			return $this->allowQuit;
		}

		return $this->allowQuit = (bool) $exit;
	}

	public function silenceErrorsInPaths($patterns, $levels = 10240)
	{
		$this->silencedPatterns = array_merge($this->silencedPatterns, array_map(function($pattern) use($levels) {
			return array('pattern' => $pattern, 'levels' => $levels);
		}, (array) $patterns));
		return $this;
	}

	public function sendHttpCode($code = NULL)
	{
		if (func_num_args() == 0) {
			return $this->sendHttpCode;
		}

		if (!$code) {
			return $this->sendHttpCode = false;
		}

		if ($code === true) {
			$code = 500;
		}

		if (($code < 400) || (600 <= $code)) {
			throw new \InvalidArgumentException('Invalid status code \'' . $code . '\', must be 4xx or 5xx');
		}

		return $this->sendHttpCode = $code;
	}

	public function writeToOutput($send = NULL)
	{
		if (func_num_args() == 0) {
			return $this->sendOutput;
		}

		return $this->sendOutput = (bool) $send;
	}

	public function handleException(\Exception $exception)
	{
		$inspector = $this->getInspector($exception);
		ob_start();
		$handlerResponse = null;

		foreach (array_reverse($this->handlerStack) as $handler) {
			$handler->setRun($this);
			$handler->setInspector($inspector);
			$handler->setException($exception);
			$handlerResponse = $handler->handle($exception);

			if (in_array($handlerResponse, array(Handler\Handler::LAST_HANDLER, Handler\Handler::QUIT))) {
				break;
			}
		}

		$willQuit = ($handlerResponse == Handler\Handler::QUIT) && $this->allowQuit();
		$output = ob_get_clean();

		if ($this->writeToOutput()) {
			if ($willQuit) {
				while (0 < ob_get_level()) {
					ob_end_clean();
				}
			}

			$this->writeToOutputNow($output);
		}

		if ($willQuit) {
			flush();
			exit(1);
		}

		return $output;
	}

	public function handleError($level, $message, $file = NULL, $line = NULL)
	{
		if ($level & error_reporting()) {
			foreach ($this->silencedPatterns as $entry) {
				$pathMatches = (bool) preg_match($entry['pattern'], $file);
				$levelMatches = $level & $entry['levels'];
				if ($pathMatches && $levelMatches) {
					return true;
				}
			}

			$exception = new Exception\ErrorException($message, $level, $level, $file, $line);

			if ($this->canThrowExceptions) {
				throw $exception;
			}
			else {
				$this->handleException($exception);
			}

			return true;
		}

		return false;
	}

	public function handleShutdown()
	{
		$this->canThrowExceptions = false;
		$error = error_get_last();
		if ($error && $this->isLevelFatal($error['type'])) {
			$this->handleError($error['type'], $error['message'], $error['file'], $error['line']);
		}
	}

	private function writeToOutputNow($output)
	{
		if ($this->sendHttpCode() && Util\Misc::canSendHeaders()) {
			$httpCode = $this->sendHttpCode();

			if (function_exists('http_response_code')) {
				http_response_code($httpCode);
			}
			else {
				header('X-Ignore-This: 1', true, $httpCode);
			}
		}

		echo $output;
		return $this;
	}

	static private function isLevelFatal($level)
	{
		$errors = E_ERROR;
		$errors |= E_PARSE;
		$errors |= E_CORE_ERROR;
		$errors |= E_CORE_WARNING;
		$errors |= E_COMPILE_ERROR;
		$errors |= E_COMPILE_WARNING;
		return 0 < ($level & $errors);
	}
}


?>
