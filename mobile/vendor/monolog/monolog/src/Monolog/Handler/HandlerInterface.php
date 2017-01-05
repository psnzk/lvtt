<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

interface HandlerInterface
{
	public function isHandling(array $record);

	public function handle(array $record);

	public function handleBatch(array $records);

	public function pushProcessor($callback);

	public function popProcessor();

	public function setFormatter(\Monolog\Formatter\FormatterInterface $formatter);

	public function getFormatter();
}


?>
