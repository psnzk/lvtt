<?php
//dezend by  QQ:2172298892
namespace Monolog\Processor;

class GitProcessor
{
	private $level;
	static private $cache;

	public function __construct($level = Monolog\Logger::DEBUG)
	{
		$this->level = \Monolog\Logger::toMonologLevel($level);
	}

	public function __invoke(array $record)
	{
		if ($record['level'] < $this->level) {
			return $record;
		}

		$record['extra']['git'] = self::getGitInfo();
		return $record;
	}

	static private function getGitInfo()
	{
		if (self::$cache) {
			return self::$cache;
		}

		$branches = shell_exec('git branch -v --no-abbrev');

		if (preg_match('{^\\* (.+?)\\s+([a-f0-9]{40})(?:\\s|$)}m', $branches, $matches)) {
			return self::$cache = array('branch' => $matches[1], 'commit' => $matches[2]);
		}

		return self::$cache = array();
	}
}


?>
