<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class PHPConsoleHandler extends AbstractProcessingHandler
{
	private $options = array(
		'enabled'                    => true,
		'classesPartialsTraceIgnore' => array('Monolog\\'),
		'debugTagsKeysInContext'     => array(0, 'tag'),
		'useOwnErrorsHandler'        => false,
		'useOwnExceptionsHandler'    => false,
		'sourcesBasePath'            => NULL,
		'registerHelper'             => true,
		'serverEncoding'             => NULL,
		'headersLimit'               => NULL,
		'password'                   => NULL,
		'enableSslOnlyMode'          => false,
		'ipMasks'                    => array(),
		'enableEvalListener'         => false,
		'dumperDetectCallbacks'      => false,
		'dumperLevelLimit'           => 5,
		'dumperItemsCountLimit'      => 100,
		'dumperItemSizeLimit'        => 5000,
		'dumperDumpSizeLimit'        => 500000,
		'detectDumpTraceAndSource'   => false,
		'dataStorage'                => NULL
		);
	/** @var Connector */
	private $connector;

	public function __construct(array $options = array(), \PhpConsole\Connector $connector = NULL, $level = Monolog\Logger::DEBUG, $bubble = true)
	{
		if (!class_exists('PhpConsole\\Connector')) {
			throw new \Exception('PHP Console library not found. See https://github.com/barbushin/php-console#installation');
		}

		parent::__construct($level, $bubble);
		$this->options = $this->initOptions($options);
		$this->connector = $this->initConnector($connector);
	}

	private function initOptions(array $options)
	{
		$wrongOptions = array_diff(array_keys($options), array_keys($this->options));

		if ($wrongOptions) {
			throw new \Exception('Unknown options: ' . implode(', ', $wrongOptions));
		}

		return array_replace($this->options, $options);
	}

	private function initConnector(\PhpConsole\Connector $connector = NULL)
	{
		if (!$connector) {
			if ($this->options['dataStorage']) {
				\PhpConsole\Connector::setPostponeStorage($this->options['dataStorage']);
			}

			$connector = \PhpConsole\Connector::getInstance();
		}

		if ($this->options['registerHelper'] && !\PhpConsole\Helper::isRegistered()) {
			\PhpConsole\Helper::register();
		}

		if ($this->options['enabled'] && $connector->isActiveClient()) {
			if ($this->options['useOwnErrorsHandler'] || $this->options['useOwnExceptionsHandler']) {
				$handler = \PhpConsole\Handler::getInstance();
				$handler->setHandleErrors($this->options['useOwnErrorsHandler']);
				$handler->setHandleExceptions($this->options['useOwnExceptionsHandler']);
				$handler->start();
			}

			if ($this->options['sourcesBasePath']) {
				$connector->setSourcesBasePath($this->options['sourcesBasePath']);
			}

			if ($this->options['serverEncoding']) {
				$connector->setServerEncoding($this->options['serverEncoding']);
			}

			if ($this->options['password']) {
				$connector->setPassword($this->options['password']);
			}

			if ($this->options['enableSslOnlyMode']) {
				$connector->enableSslOnlyMode();
			}

			if ($this->options['ipMasks']) {
				$connector->setAllowedIpMasks($this->options['ipMasks']);
			}

			if ($this->options['headersLimit']) {
				$connector->setHeadersLimit($this->options['headersLimit']);
			}

			if ($this->options['detectDumpTraceAndSource']) {
				$connector->getDebugDispatcher()->detectTraceAndSource = true;
			}

			$dumper = $connector->getDumper();
			$dumper->levelLimit = $this->options['dumperLevelLimit'];
			$dumper->itemsCountLimit = $this->options['dumperItemsCountLimit'];
			$dumper->itemSizeLimit = $this->options['dumperItemSizeLimit'];
			$dumper->dumpSizeLimit = $this->options['dumperDumpSizeLimit'];
			$dumper->detectCallbacks = $this->options['dumperDetectCallbacks'];

			if ($this->options['enableEvalListener']) {
				$connector->startEvalRequestsListener();
			}
		}

		return $connector;
	}

	public function getConnector()
	{
		return $this->connector;
	}

	public function getOptions()
	{
		return $this->options;
	}

	public function handle(array $record)
	{
		if ($this->options['enabled'] && $this->connector->isActiveClient()) {
			return parent::handle($record);
		}

		return !$this->bubble;
	}

	protected function write(array $record)
	{
		if ($record['level'] < \Monolog\Logger::NOTICE) {
			$this->handleDebugRecord($record);
		}
		else {
			if (isset($record['context']['exception']) && $record['context']['exception'] instanceof \Exception) {
				$this->handleExceptionRecord($record);
			}
			else {
				$this->handleErrorRecord($record);
			}
		}
	}

	private function handleDebugRecord(array $record)
	{
		$tags = $this->getRecordTags($record);
		$message = $record['message'];

		if ($record['context']) {
			$message .= ' ' . json_encode($this->connector->getDumper()->dump(array_filter($record['context'])));
		}

		$this->connector->getDebugDispatcher()->dispatchDebug($message, $tags, $this->options['classesPartialsTraceIgnore']);
	}

	private function handleExceptionRecord(array $record)
	{
		$this->connector->getErrorsDispatcher()->dispatchException($record['context']['exception']);
	}

	private function handleErrorRecord(array $record)
	{
		$context = $record['context'];
		$this->connector->getErrorsDispatcher()->dispatchError(isset($context['code']) ? $context['code'] : null, isset($context['message']) ? $context['message'] : $record['message'], isset($context['file']) ? $context['file'] : null, isset($context['line']) ? $context['line'] : null, $this->options['classesPartialsTraceIgnore']);
	}

	private function getRecordTags(array &$record)
	{
		$tags = null;

		if (!empty($record['context'])) {
			$context = &$record['context'];

			foreach ($this->options['debugTagsKeysInContext'] as $key) {
				if (!empty($context[$key])) {
					$tags = $context[$key];

					if ($key === 0) {
						array_shift($context);
					}
					else {
						unset($context[$key]);
					}

					break;
				}
			}
		}

		return $tags ?: strtolower($record['level_name']);
	}

	protected function getDefaultFormatter()
	{
		return new \Monolog\Formatter\LineFormatter('%message%');
	}
}

?>
