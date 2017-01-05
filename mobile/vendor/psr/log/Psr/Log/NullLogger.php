<?php
//dezend by  QQ:2172298892
namespace Psr\Log;

class NullLogger extends AbstractLogger
{
	public function log($level, $message, array $context = array())
	{
	}
}

?>
