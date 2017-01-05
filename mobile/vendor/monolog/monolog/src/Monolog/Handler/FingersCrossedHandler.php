<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class FingersCrossedHandler extends AbstractHandler
{
	protected $handler;
	protected $activationStrategy;
	protected $buffering = true;
	protected $bufferSize;
	protected $buffer = array();
	protected $stopBuffering;
	protected $passthruLevel;

	public function __construct($handler, $activationStrategy = NULL, $bufferSize = 0, $bubble = true, $stopBuffering = true, $passthruLevel = NULL)
	{
		if (null === $activationStrategy) {
			$activationStrategy = new FingersCrossed\ErrorLevelActivationStrategy(\Monolog\Logger::WARNING);
		}

		if (!$activationStrategy instanceof FingersCrossed\ActivationStrategyInterface) {
			$activationStrategy = new FingersCrossed\ErrorLevelActivationStrategy($activationStrategy);
		}

		$this->handler = $handler;
		$this->activationStrategy = $activationStrategy;
		$this->bufferSize = $bufferSize;
		$this->bubble = $bubble;
		$this->stopBuffering = $stopBuffering;

		if ($passthruLevel !== null) {
			$this->passthruLevel = \Monolog\Logger::toMonologLevel($passthruLevel);
		}

		if (!$this->handler instanceof HandlerInterface && !is_callable($this->handler)) {
			throw new \RuntimeException('The given handler (' . json_encode($this->handler) . ') is not a callable nor a Monolog\\Handler\\HandlerInterface object');
		}
	}

	public function isHandling(array $record)
	{
		return true;
	}

	public function activate()
	{
		if ($this->stopBuffering) {
			$this->buffering = false;
		}

		if (!$this->handler instanceof HandlerInterface) {
			$record = end($this->buffer) ?: null;
			$this->handler = call_user_func($this->handler, $record, $this);

			if (!$this->handler instanceof HandlerInterface) {
				throw new \RuntimeException('The factory callable should return a HandlerInterface');
			}
		}

		$this->handler->handleBatch($this->buffer);
		$this->buffer = array();
	}

	public function handle(array $record)
	{
		if ($this->processors) {
			foreach ($this->processors as $processor) {
				$record = call_user_func($processor, $record);
			}
		}

		if ($this->buffering) {
			$this->buffer[] = $record;
			if ((0 < $this->bufferSize) && ($this->bufferSize < count($this->buffer))) {
				array_shift($this->buffer);
			}

			if ($this->activationStrategy->isHandlerActivated($record)) {
				$this->activate();
			}
		}
		else {
			$this->handler->handle($record);
		}

		return false === $this->bubble;
	}

	public function close()
	{
		if (null !== $this->passthruLevel) {
			$level = $this->passthruLevel;
			$this->buffer = array_filter($this->buffer, function($record) use($level) {
				return $level <= $record['level'];
			});

			if (0 < count($this->buffer)) {
				$this->handler->handleBatch($this->buffer);
				$this->buffer = array();
			}
		}
	}

	public function reset()
	{
		$this->buffering = true;
	}

	public function clear()
	{
		$this->buffer = array();
		$this->reset();
	}
}

?>
