<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class ChromePHPHandler extends AbstractProcessingHandler
{
	const VERSION = '4.0';
	const HEADER_NAME = 'X-ChromeLogger-Data';
	const USER_AGENT_REGEX = '{\\b(?:Chrome/\\d+(?:\\.\\d+)*|Firefox/(?:4[3-9]|[5-9]\\d|\\d{3,})(?:\\.\\d)*)\\b}';

	static protected $initialized = false;
	/**
     * Tracks whether we sent too much data
     *
     * Chrome limits the headers to 256KB, so when we sent 240KB we stop sending
     *
     * @var Boolean
     */
	static protected $overflowed = false;
	static protected $json = array(
		'version' => self::VERSION,
		'columns' => array('label', 'log', 'backtrace', 'type'),
		'rows'    => array()
		);
	static protected $sendHeaders = true;

	public function __construct($level = Monolog\Logger::DEBUG, $bubble = true)
	{
		parent::__construct($level, $bubble);

		if (!function_exists('json_encode')) {
			throw new \RuntimeException('PHP\'s json extension is required to use Monolog\'s ChromePHPHandler');
		}
	}

	public function handleBatch(array $records)
	{
		$messages = array();

		foreach ($records as $record) {
			if ($record['level'] < $this->level) {
				continue;
			}

			$messages[] = $this->processRecord($record);
		}

		if (!empty($messages)) {
			$messages = $this->getFormatter()->formatBatch($messages);
			self::$json['rows'] = array_merge(self::$json['rows'], $messages);
			$this->send();
		}
	}

	protected function getDefaultFormatter()
	{
		return new \Monolog\Formatter\ChromePHPFormatter();
	}

	protected function write(array $record)
	{
		self::$json['rows'][] = $record['formatted'];
		$this->send();
	}

	protected function send()
	{
		if (self::$overflowed || !self::$sendHeaders) {
			return NULL;
		}

		if (!self::$initialized) {
			self::$initialized = true;
			self::$sendHeaders = $this->headersAccepted();

			if (!self::$sendHeaders) {
				return NULL;
			}

			self::$json['request_uri'] = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
		}

		$json = @json_encode(self::$json);
		$data = base64_encode(utf8_encode($json));

		if ((240 * 1024) < strlen($data)) {
			self::$overflowed = true;
			$record = array(
				'message'    => 'Incomplete logs, chrome header size limit reached',
				'context'    => array(),
				'level'      => \Monolog\Logger::WARNING,
				'level_name' => \Monolog\Logger::getLevelName(\Monolog\Logger::WARNING),
				'channel'    => 'monolog',
				'datetime'   => new \DateTime(),
				'extra'      => array()
				);
			self::$json['rows'][count(self::$json['rows']) - 1] = $this->getFormatter()->format($record);
			$json = @json_encode(self::$json);
			$data = base64_encode(utf8_encode($json));
		}

		if (trim($data) !== '') {
			$this->sendHeader(self::HEADER_NAME, $data);
		}
	}

	protected function sendHeader($header, $content)
	{
		if (!headers_sent() && self::$sendHeaders) {
			header(sprintf('%s: %s', $header, $content));
		}
	}

	protected function headersAccepted()
	{
		if (empty($_SERVER['HTTP_USER_AGENT'])) {
			return false;
		}

		return preg_match(self::USER_AGENT_REGEX, $_SERVER['HTTP_USER_AGENT']);
	}

	public function __get($property)
	{
		if ('sendHeaders' !== $property) {
			throw new \InvalidArgumentException('Undefined property ' . $property);
		}

		return static::$sendHeaders;
	}

	public function __set($property, $value)
	{
		if ('sendHeaders' !== $property) {
			throw new \InvalidArgumentException('Undefined property ' . $property);
		}

		static::$sendHeaders = $value;
	}
}

?>
