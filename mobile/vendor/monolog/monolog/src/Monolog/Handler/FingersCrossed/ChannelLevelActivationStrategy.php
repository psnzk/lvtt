<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler\FingersCrossed;

class ChannelLevelActivationStrategy implements ActivationStrategyInterface
{
	private $defaultActionLevel;
	private $channelToActionLevel;

	public function __construct($defaultActionLevel, $channelToActionLevel = array())
	{
		$this->defaultActionLevel = \Monolog\Logger::toMonologLevel($defaultActionLevel);
		$this->channelToActionLevel = array_map('Monolog\\Logger::toMonologLevel', $channelToActionLevel);
	}

	public function isHandlerActivated(array $record)
	{
		if (isset($this->channelToActionLevel[$record['channel']])) {
			return $this->channelToActionLevel[$record['channel']] <= $record['level'];
		}

		return $this->defaultActionLevel <= $record['level'];
	}
}

?>
