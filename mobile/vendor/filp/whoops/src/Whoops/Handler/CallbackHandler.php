<?php
//dezend by  QQ:2172298892
namespace Whoops\Handler;

class CallbackHandler extends Handler
{
	/**
     * @var callable
     */
	protected $callable;

	public function __construct($callable)
	{
		if (!is_callable($callable)) {
			throw new \InvalidArgumentException('Argument to ' . 'Whoops\\Handler\\CallbackHandler::__construct' . ' must be valid callable');
		}

		$this->callable = $callable;
	}

	public function handle()
	{
		$exception = $this->getException();
		$inspector = $this->getInspector();
		$run = $this->getRun();
		$callable = $this->callable;
		return $callable($exception, $inspector, $run);
	}
}

?>
