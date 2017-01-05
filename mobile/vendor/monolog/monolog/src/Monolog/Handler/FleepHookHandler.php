<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class FleepHookHandler extends SocketHandler
{
	const FLEEP_HOST = 'fleep.io';
	const FLEEP_HOOK_URI = '/hook/';

	/**
     * @var string Webhook token (specifies the conversation where logs are sent)
     */
	protected $token;

	public function __construct($token, $level = Monolog\Logger::DEBUG, $bubble = true)
	{
		if (!extension_loaded('openssl')) {
			throw new MissingExtensionException('The OpenSSL PHP extension is required to use the FleepHookHandler');
		}

		$this->token = $token;
		$connectionString = 'ssl://' . self::FLEEP_HOST . ':443';
		parent::__construct($connectionString, $level, $bubble);
	}

	protected function getDefaultFormatter()
	{
		return new \Monolog\Formatter\LineFormatter(null, null, true, true);
	}

	public function write(array $record)
	{
		parent::write($record);
		$this->closeSocket();
	}

	protected function generateDataStream($record)
	{
		$content = $this->buildContent($record);
		return $this->buildHeader($content) . $content;
	}

	private function buildHeader($content)
	{
		$header = 'POST ' . self::FLEEP_HOOK_URI . $this->token . " HTTP/1.1\r\n";
		$header .= 'Host: ' . self::FLEEP_HOST . "\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= 'Content-Length: ' . strlen($content) . "\r\n";
		$header .= "\r\n";
		return $header;
	}

	private function buildContent($record)
	{
		$dataArray = array('message' => $record['formatted']);
		return http_build_query($dataArray);
	}
}

?>
