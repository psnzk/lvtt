<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class CouchDBHandler extends AbstractProcessingHandler
{
	private $options;

	public function __construct(array $options = array(), $level = Monolog\Logger::DEBUG, $bubble = true)
	{
		$this->options = array_merge(array('host' => 'localhost', 'port' => 5984, 'dbname' => 'logger', 'username' => null, 'password' => null), $options);
		parent::__construct($level, $bubble);
	}

	protected function write(array $record)
	{
		$basicAuth = null;

		if ($this->options['username']) {
			$basicAuth = sprintf('%s:%s@', $this->options['username'], $this->options['password']);
		}

		$url = 'http://' . $basicAuth . $this->options['host'] . ':' . $this->options['port'] . '/' . $this->options['dbname'];
		$context = stream_context_create(array(
	'http' => array('method' => 'POST', 'content' => $record['formatted'], 'ignore_errors' => true, 'max_redirects' => 0, 'header' => 'Content-type: application/json')
	));

		if (false === @file_get_contents($url, null, $context)) {
			throw new \RuntimeException(sprintf('Could not connect to %s', $url));
		}
	}

	protected function getDefaultFormatter()
	{
		return new \Monolog\Formatter\JsonFormatter(\Monolog\Formatter\JsonFormatter::BATCH_MODE_JSON, false);
	}
}

?>
