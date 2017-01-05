<?php
//dezend by  QQ:2172298892
namespace Monolog\Processor;

abstract class MemoryProcessor
{
	/**
     * @var bool If true, get the real size of memory allocated from system. Else, only the memory used by emalloc() is reported.
     */
	protected $realUsage;
	/**
     * @var bool If true, then format memory size to human readable string (MB, KB, B depending on size)
     */
	protected $useFormatting;

	public function __construct($realUsage = true, $useFormatting = true)
	{
		$this->realUsage = (bool) $realUsage;
		$this->useFormatting = (bool) $useFormatting;
	}

	protected function formatBytes($bytes)
	{
		$bytes = (int) $bytes;

		if (!$this->useFormatting) {
			return $bytes;
		}

		if ((1024 * 1024) < $bytes) {
			return round($bytes / 1024 / 1024, 2) . ' MB';
		}
		else if (1024 < $bytes) {
			return round($bytes / 1024, 2) . ' KB';
		}

		return $bytes . ' B';
	}
}


?>
