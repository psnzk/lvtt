<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class NullHandler extends AbstractHandler
{
	public function __construct($level = Monolog\Logger::DEBUG)
	{
		parent::__construct($level, false);
	}

	public function handle(array $record)
	{
		if ($record['level'] < $this->level) {
			return false;
		}

		return true;
	}
}

?>
