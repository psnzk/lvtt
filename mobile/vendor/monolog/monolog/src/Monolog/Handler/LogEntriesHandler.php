<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class LogEntriesHandler extends SocketHandler
{
	/**
     * @var string
     */
	protected $logToken;

	public function __construct($token, $useSSL = true, $level = Monolog\Logger::DEBUG, $bubble = true)
	{
		if ($useSSL && !extension_loaded('openssl')) {
			throw new MissingExtensionException('The OpenSSL PHP plugin is required to use SSL encrypted connection for LogEntriesHandler');
		}

		$endpoint = ($useSSL ? 'ssl://data.logentries.com:443' : 'data.logentries.com:80');
		parent::__construct($endpoint, $level, $bubble);
		$this->logToken = $token;
	}

	protected function generateDataStream($record)
	{
		return $this->logToken . ' ' . $record['formatted'];
	}
}

?>
