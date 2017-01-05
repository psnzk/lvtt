<?php
//dezend by  QQ:2172298892
namespace Whoops\Exception;

class Inspector
{
	/**
     * @var Exception
     */
	private $exception;
	/**
     * @var \Whoops\Exception\FrameCollection
     */
	private $frames;
	/**
     * @var \Whoops\Exception\Inspector
     */
	private $previousExceptionInspector;

	public function __construct(\Exception $exception)
	{
		$this->exception = $exception;
	}

	public function getException()
	{
		return $this->exception;
	}

	public function getExceptionName()
	{
		return get_class($this->exception);
	}

	public function getExceptionMessage()
	{
		return $this->exception->getMessage();
	}

	public function hasPreviousException()
	{
		return $this->previousExceptionInspector || $this->exception->getPrevious();
	}

	public function getPreviousExceptionInspector()
	{
		if ($this->previousExceptionInspector === null) {
			$previousException = $this->exception->getPrevious();

			if ($previousException) {
				$this->previousExceptionInspector = new Inspector($previousException);
			}
		}

		return $this->previousExceptionInspector;
	}

	public function getFrames()
	{
		if ($this->frames === null) {
			$frames = $this->exception->getTrace();
			if ($this->exception instanceof ErrorException && empty($frames[1]['line'])) {
				$frames = array($this->getFrameFromError($this->exception));
			}
			else {
				$firstFrame = $this->getFrameFromException($this->exception);
				array_unshift($frames, $firstFrame);
			}

			$this->frames = new FrameCollection($frames);

			if ($previousInspector = $this->getPreviousExceptionInspector()) {
				$outerFrames = $this->frames;
				$newFrames = clone $previousInspector->getFrames();

				if (isset($newFrames[0])) {
					$newFrames[0]->addComment($previousInspector->getExceptionMessage(), 'Exception message:');
				}

				$newFrames->prependFrames($outerFrames->topDiff($newFrames));
				$this->frames = $newFrames;
			}
		}

		return $this->frames;
	}

	protected function getFrameFromException(\Exception $exception)
	{
		return array(
	'file'  => $exception->getFile(),
	'line'  => $exception->getLine(),
	'class' => get_class($exception),
	'args'  => array($exception->getMessage())
	);
	}

	protected function getFrameFromError(ErrorException $exception)
	{
		return array(
	'file'  => $exception->getFile(),
	'line'  => $exception->getLine(),
	'class' => null,
	'args'  => array()
	);
	}
}


?>
