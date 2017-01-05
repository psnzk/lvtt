<?php
//dezend by  QQ:2172298892
namespace Whoops\Exception;

class FrameCollection implements \Serializable
{
	/**
     * @var array[]
     */
	private $frames;

	public function __construct(array $frames)
	{
		$this->frames = array_map(function($frame) {
			return new Frame($frame);
		}, $frames);
	}

	public function filter($callable)
	{
		$this->frames = array_filter($this->frames, $callable);
		return $this;
	}

	public function map($callable)
	{
		$this->frames = array_map(function($frame) use($callable) {
			$frame = call_user_func($callable, $frame);

			if (!$frame instanceof Frame) {
				throw new \UnexpectedValueException('Callable to ' . 'Whoops\\Exception\\FrameCollection::Whoops\\Exception\\{closure}' . ' must return a Frame object');
			}

			return $frame;
		}, $this->frames);
		return $this;
	}

	public function getArray()
	{
		return $this->frames;
	}

	public function getIterator()
	{
		return new \ArrayIterator($this->frames);
	}

	public function offsetExists($offset)
	{
		return isset($this->frames[$offset]);
	}

	public function offsetGet($offset)
	{
		return $this->frames[$offset];
	}

	public function offsetSet($offset, $value)
	{
		throw new \Exception('Whoops\\Exception\\FrameCollection' . ' is read only');
	}

	public function offsetUnset($offset)
	{
		throw new \Exception('Whoops\\Exception\\FrameCollection' . ' is read only');
	}

	public function count()
	{
		return count($this->frames);
	}

	public function serialize()
	{
		return serialize($this->frames);
	}

	public function unserialize($serializedFrames)
	{
		$this->frames = unserialize($serializedFrames);
	}

	public function prependFrames(array $frames)
	{
		$this->frames = array_merge($frames, $this->frames);
	}

	public function topDiff(FrameCollection $parentFrames)
	{
		$diff = $this->frames;
		$parentFrames = $parentFrames->getArray();
		$p = count($parentFrames) - 1;

		for ($i = count($diff) - 1; 0 <= $p; $i--) {
			$tailFrame = $diff[$i];

			if ($tailFrame->equals($parentFrames[$p])) {
				unset($diff[$i]);
			}

			$p--;
		}

		return $diff;
	}
}

?>
