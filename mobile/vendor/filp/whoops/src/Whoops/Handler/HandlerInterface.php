<?php
//dezend by  QQ:2172298892
namespace Whoops\Handler;

interface HandlerInterface
{
	public function handle();

	public function setRun(\Whoops\Run $run);

	public function setException(\Exception $exception);

	public function setInspector(\Whoops\Exception\Inspector $inspector);
}


?>
