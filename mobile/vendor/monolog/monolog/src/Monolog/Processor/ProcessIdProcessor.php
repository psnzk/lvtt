<?php
//dezend by  QQ:2172298892
namespace Monolog\Processor;

class ProcessIdProcessor
{
	public function __invoke(array $record)
	{
		$record['extra']['process_id'] = getmypid();
		return $record;
	}
}


?>
