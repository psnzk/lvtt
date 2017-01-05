<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class SlackHandler extends SocketHandler
{
	/**
     * Slack API token
     * @var string
     */
	private $token;
	/**
     * Slack channel (encoded ID or name)
     * @var string
     */
	private $channel;
	/**
     * Name of a bot
     * @var string
     */
	private $username;
	/**
     * Emoji icon name
     * @var string
     */
	private $iconEmoji;
	/**
     * Whether the message should be added to Slack as attachment (plain text otherwise)
     * @var bool
     */
	private $useAttachment;
	/**
     * Whether the the context/extra messages added to Slack as attachments are in a short style
     * @var bool
     */
	private $useShortAttachment;
	/**
     * Whether the attachment should include context and extra data
     * @var bool
     */
	private $includeContextAndExtra;
	/**
     * @var LineFormatter
     */
	private $lineFormatter;

	public function __construct($token, $channel, $username = 'Monolog', $useAttachment = true, $iconEmoji = NULL, $level = Monolog\Logger::CRITICAL, $bubble = true, $useShortAttachment = false, $includeContextAndExtra = false)
	{
		if (!extension_loaded('openssl')) {
			throw new MissingExtensionException('The OpenSSL PHP extension is required to use the SlackHandler');
		}

		parent::__construct('ssl://slack.com:443', $level, $bubble);
		$this->token = $token;
		$this->channel = $channel;
		$this->username = $username;
		$this->iconEmoji = trim($iconEmoji, ':');
		$this->useAttachment = $useAttachment;
		$this->useShortAttachment = $useShortAttachment;
		$this->includeContextAndExtra = $includeContextAndExtra;
		if ($this->includeContextAndExtra && $this->useShortAttachment) {
			$this->lineFormatter = new \Monolog\Formatter\LineFormatter();
		}
	}

	protected function generateDataStream($record)
	{
		$content = $this->buildContent($record);
		return $this->buildHeader($content) . $content;
	}

	private function buildContent($record)
	{
		$dataArray = $this->prepareContentData($record);
		return http_build_query($dataArray);
	}

	protected function prepareContentData($record)
	{
		$dataArray = array(
			'token'       => $this->token,
			'channel'     => $this->channel,
			'username'    => $this->username,
			'text'        => '',
			'attachments' => array()
			);

		if ($this->formatter) {
			$message = $this->formatter->format($record);
		}
		else {
			$message = $record['message'];
		}

		if ($this->useAttachment) {
			$attachment = array(
				'fallback' => $message,
				'color'    => $this->getAttachmentColor($record['level']),
				'fields'   => array()
				);

			if ($this->useShortAttachment) {
				$attachment['title'] = $record['level_name'];
				$attachment['text'] = $message;
			}
			else {
				$attachment['title'] = 'Message';
				$attachment['text'] = $message;
				$attachment['fields'][] = array('title' => 'Level', 'value' => $record['level_name'], 'short' => true);
			}

			if ($this->includeContextAndExtra) {
				if (!empty($record['extra'])) {
					if ($this->useShortAttachment) {
						$attachment['fields'][] = array('title' => 'Extra', 'value' => $this->stringify($record['extra']), 'short' => $this->useShortAttachment);
					}
					else {
						foreach ($record['extra'] as $var => $val) {
							$attachment['fields'][] = array('title' => $var, 'value' => $val, 'short' => $this->useShortAttachment);
						}
					}
				}

				if (!empty($record['context'])) {
					if ($this->useShortAttachment) {
						$attachment['fields'][] = array('title' => 'Context', 'value' => $this->stringify($record['context']), 'short' => $this->useShortAttachment);
					}
					else {
						foreach ($record['context'] as $var => $val) {
							$attachment['fields'][] = array('title' => $var, 'value' => $val, 'short' => $this->useShortAttachment);
						}
					}
				}
			}

			$dataArray['attachments'] = json_encode(array($attachment));
		}
		else {
			$dataArray['text'] = $message;
		}

		if ($this->iconEmoji) {
			$dataArray['icon_emoji'] = ':' . $this->iconEmoji . ':';
		}

		return $dataArray;
	}

	private function buildHeader($content)
	{
		$header = "POST /api/chat.postMessage HTTP/1.1\r\n";
		$header .= "Host: slack.com\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= 'Content-Length: ' . strlen($content) . "\r\n";
		$header .= "\r\n";
		return $header;
	}

	protected function write(array $record)
	{
		parent::write($record);
		$res = $this->getResource();

		if (is_resource($res)) {
			@fread($res, 2048);
		}

		$this->closeSocket();
	}

	protected function getAttachmentColor($level)
	{
		switch (true) {
		case \Monolog\Logger::ERROR <= $level:
			return 'danger';
		case \Monolog\Logger::WARNING <= $level:
			return 'warning';
		case \Monolog\Logger::INFO <= $level:
			return 'good';
		default:
			return '#e3e4e6';
		}
	}

	protected function stringify($fields)
	{
		$string = '';

		foreach ($fields as $var => $val) {
			$string .= $var . ': ' . $this->lineFormatter->stringify($val) . ' | ';
		}

		$string = rtrim($string, ' |');
		return $string;
	}
}

?>
