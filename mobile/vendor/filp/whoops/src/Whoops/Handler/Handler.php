<?php
//dezend by  QQ:2172298892
namespace Whoops\Handler;

abstract class Handler implements HandlerInterface
{
	const DONE = 16;
	const LAST_HANDLER = 32;
	const QUIT = 48;

	/**
     * @var Run
     */
	private $run;
	/**
     * @var Inspector $inspector
     */
	private $inspector;
	/**
     * @var Exception $exception
     */
	private $exception;

	public function setRun(\Whoops\Run $run)
	{
		$this->run = $run;
	}

	protected function getRun()
	{
		return $this->run;
	}

	public function setInspector(\Whoops\Exception\Inspector $inspector)
	{
		$this->inspector = $inspector;
	}

	protected function getInspector()
	{
		return $this->inspector;
	}

	public function setException(\Exception $exception)
	{
		$this->exception = $exception;
	}

	protected function getException()
	{
		return $this->exception;
	}
}

?>
