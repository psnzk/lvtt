<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class PushoverHandler extends SocketHandler
{
	private $token;
	private $users;
	private $title;
	private $user;
	private $retry;
	private $expire;
	private $highPriorityLevel;
	private $emergencyLevel;
	private $useFormattedMessage = false;
	/**
     * All parameters that can be sent to Pushover
     * @see https://pushover.net/api
     * @var array
     */
	private $parameterNames = array('token' => true, 'user' => true, 'message' => true, 'device' => true, 'title' => true, 'url' => true, 'url_title' => true, 'priority' => true, 'timestamp' => true, 'sound' => true, 'retry' => true, 'expire' => true, 'callback' => true);
	/**
     * Sounds the api supports by default
     * @see https://pushover.net/api#sounds
     * @var array
     */
	private $sounds = array('pushover', 'bike', 'bugle', 'cashregister', 'classical', 'cosmic', 'falling', 'gamelan', 'incoming', 'intermission', 'magic', 'mechanical', 'pianobar', 'siren', 'spacealarm', 'tugboat', 'alien', 'climb', 'persistent', 'echo', 'updown', 'none');

	public function __construct($token, $users, $title = NULL, $level = Monolog\Logger::CRITICAL, $bubble = true, $useSSL = true, $highPriorityLevel = Monolog\Logger::CRITICAL, $emergencyLevel = Monolog\Logger::EMERGENCY, $retry = 30, $expire = 25200)
	{
		$connectionString = ($useSSL ? 'ssl://api.pushover.net:443' : 'api.pushover.net:80');
		parent::__construct($connectionString, $level, $bubble);
		$this->token = $token;
		$this->users = (array) $users;
		$this->title = $title ?: gethostname();
		$this->highPriorityLevel = \Monolog\Logger::toMonologLevel($highPriorityLevel);
		$this->emergencyLevel = \Monolog\Logger::toMonologLevel($emergencyLevel);
		$this->retry = $retry;
		$this->expire = $expire;
	}

	protected function generateDataStream($record)
	{
		$content = $this->buildContent($record);
		return $this->buildHeader($content) . $content;
	}

	private function buildContent($record)
	{
		$maxMessageLength = 512 - strlen($this->title);
		$message = ($this->useFormattedMessage ? $record['formatted'] : $record['message']);
		$message = substr($message, 0, $maxMessageLength);
		$timestamp = $record['datetime']->getTimestamp();
		$dataArray = array('token' => $this->token, 'user' => $this->user, 'message' => $message, 'title' => $this->title, 'timestamp' => $timestamp);
		if (isset($record['level']) && ($this->emergencyLevel <= $record['level'])) {
			$dataArray['priority'] = 2;
			$dataArray['retry'] = $this->retry;
			$dataArray['expire'] = $this->expire;
		}
		else {
			if (isset($record['level']) && ($this->highPriorityLevel <= $record['level'])) {
				$dataArray['priority'] = 1;
			}
		}

		$context = array_intersect_key($record['context'], $this->parameterNames);
		$extra = array_intersect_key($record['extra'], $this->parameterNames);
		$dataArray = array_merge($extra, $context, $dataArray);
		if (isset($dataArray['sound']) && !in_array($dataArray['sound'], $this->sounds)) {
			unset($dataArray['sound']);
		}

		return http_build_query($dataArray);
	}

	private function buildHeader($content)
	{
		$header = "POST /1/messages.json HTTP/1.1\r\n";
		$header .= "Host: api.pushover.net\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= 'Content-Length: ' . strlen($content) . "\r\n";
		$header .= "\r\n";
		return $header;
	}

	protected function write(array $record)
	{
		foreach ($this->users as $user) {
			$this->user = $user;
			parent::write($record);
			$this->closeSocket();
		}

		$this->user = null;
	}

	public function setHighPriorityLevel($value)
	{
		$this->highPriorityLevel = $value;
	}

	public function setEmergencyLevel($value)
	{
		$this->emergencyLevel = $value;
	}

	public function useFormattedMessage($value)
	{
		$this->useFormattedMessage = (bool) $value;
	}
}

?>
