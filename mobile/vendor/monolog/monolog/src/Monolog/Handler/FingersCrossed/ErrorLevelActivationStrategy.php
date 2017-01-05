<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler\FingersCrossed;

class ErrorLevelActivationStrategy implements ActivationStrategyInterface
{
	private $actionLevel;

	public function __construct($actionLevel)
	{
		$this->actionLevel = \Monolog\Logger::toMonologLevel($actionLevel);
	}

	public function isHandlerActivated(array $record)
	{
		return $this->actionLevel <= $record['level'];
	}
}

?>
