<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class FilterHandler extends AbstractHandler
{
	/**
     * Handler or factory callable($record, $this)
     *
     * @var callable|\Monolog\Handler\HandlerInterface
     */
	protected $handler;
	/**
     * Minimum level for logs that are passed to handler
     *
     * @var int[]
     */
	protected $acceptedLevels;
	/**
     * Whether the messages that are handled can bubble up the stack or not
     *
     * @var Boolean
     */
	protected $bubble;

	public function __construct($handler, $minLevelOrList = Monolog\Logger::DEBUG, $maxLevel = Monolog\Logger::EMERGENCY, $bubble = true)
	{
		$this->handler = $handler;
		$this->bubble = $bubble;
		$this->setAcceptedLevels($minLevelOrList, $maxLevel);
		if (!$this->handler instanceof HandlerInterface && !is_callable($this->handler)) {
			throw new \RuntimeException('The given handler (' . json_encode($this->handler) . ') is not a callable nor a Monolog\\Handler\\HandlerInterface object');
		}
	}

	public function getAcceptedLevels()
	{
		return array_flip($this->acceptedLevels);
	}

	public function setAcceptedLevels($minLevelOrList = Monolog\Logger::DEBUG, $maxLevel = Monolog\Logger::EMERGENCY)
	{
		if (is_array($minLevelOrList)) {
			$acceptedLevels = array_map('Monolog\\Logger::toMonologLevel', $minLevelOrList);
		}
		else {
			$minLevelOrList = \Monolog\Logger::toMonologLevel($minLevelOrList);
			$maxLevel = \Monolog\Logger::toMonologLevel($maxLevel);
			$acceptedLevels = array_values(array_filter(\Monolog\Logger::getLevels(), function($level) use($minLevelOrList, $maxLevel) {
				return ($minLevelOrList <= $level) && ($level <= $maxLevel);
			}));
		}

		$this->acceptedLevels = array_flip($acceptedLevels);
	}

	public function isHandling(array $record)
	{
		return isset($this->acceptedLevels[$record['level']]);
	}

	public function handle(array $record)
	{
		if (!$this->isHandling($record)) {
			return false;
		}

		if (!$this->handler instanceof HandlerInterface) {
			$this->handler = call_user_func($this->handler, $record, $this);

			if (!$this->handler instanceof HandlerInterface) {
				throw new \RuntimeException('The factory callable should return a HandlerInterface');
			}
		}

		if ($this->processors) {
			foreach ($this->processors as $processor) {
				$record = call_user_func($processor, $record);
			}
		}

		$this->handler->handle($record);
		return false === $this->bubble;
	}

	public function handleBatch(array $records)
	{
		$filtered = array();

		foreach ($records as $record) {
			if ($this->isHandling($record)) {
				$filtered[] = $record;
			}
		}

		$this->handler->handleBatch($filtered);
	}
}

?>
