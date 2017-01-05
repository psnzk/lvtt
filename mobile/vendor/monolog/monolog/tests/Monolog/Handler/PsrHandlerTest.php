<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class PsrHandlerTest extends \Monolog\TestCase
{
	public function logLevelProvider()
	{
		$levels = array();
		$monologLogger = new \Monolog\Logger('');

		foreach ($monologLogger->getLevels() as $levelName => $level) {
			$levels[] = array($levelName, $level);
		}

		return $levels;
	}

	public function testHandlesAllLevels($levelName, $level)
	{
		$message = 'Hello, world! ' . $level;
		$context = array('foo' => 'bar', 'level' => $level);
		$psrLogger = $this->getMock('Psr\\Log\\NullLogger');
		$psrLogger->expects($this->once())->method('log')->with(strtolower($levelName), $message, $context);
		$handler = new PsrHandler($psrLogger);
		$handler->handle(array('level' => $level, 'level_name' => $levelName, 'message' => $message, 'context' => $context));
	}
}

?>
