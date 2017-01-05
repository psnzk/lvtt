<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class NewRelicHandler extends AbstractProcessingHandler
{
	/**
     * Name of the New Relic application that will receive logs from this handler.
     *
     * @var string
     */
	protected $appName;
	/**
     * Name of the current transaction
     *
     * @var string
     */
	protected $transactionName;
	/**
     * Some context and extra data is passed into the handler as arrays of values. Do we send them as is
     * (useful if we are using the API), or explode them for display on the NewRelic RPM website?
     *
     * @var bool
     */
	protected $explodeArrays;

	public function __construct($level = Monolog\Logger::ERROR, $bubble = true, $appName = NULL, $explodeArrays = false, $transactionName = NULL)
	{
		parent::__construct($level, $bubble);
		$this->appName = $appName;
		$this->explodeArrays = $explodeArrays;
		$this->transactionName = $transactionName;
	}

	protected function write(array $record)
	{
		if (!$this->isNewRelicEnabled()) {
			throw new MissingExtensionException('The newrelic PHP extension is required to use the NewRelicHandler');
		}

		if ($appName = $this->getAppName($record['context'])) {
			$this->setNewRelicAppName($appName);
		}

		if ($transactionName = $this->getTransactionName($record['context'])) {
			$this->setNewRelicTransactionName($transactionName);
			unset($record['formatted']['context']['transaction_name']);
		}

		if (isset($record['context']['exception']) && $record['context']['exception'] instanceof \Exception) {
			newrelic_notice_error($record['message'], $record['context']['exception']);
			unset($record['formatted']['context']['exception']);
		}
		else {
			newrelic_notice_error($record['message']);
		}

		if (isset($record['formatted']['context']) && is_array($record['formatted']['context'])) {
			foreach ($record['formatted']['context'] as $key => $parameter) {
				if (is_array($parameter) && $this->explodeArrays) {
					foreach ($parameter as $paramKey => $paramValue) {
						$this->setNewRelicParameter('context_' . $key . '_' . $paramKey, $paramValue);
					}
				}
				else {
					$this->setNewRelicParameter('context_' . $key, $parameter);
				}
			}
		}

		if (isset($record['formatted']['extra']) && is_array($record['formatted']['extra'])) {
			foreach ($record['formatted']['extra'] as $key => $parameter) {
				if (is_array($parameter) && $this->explodeArrays) {
					foreach ($parameter as $paramKey => $paramValue) {
						$this->setNewRelicParameter('extra_' . $key . '_' . $paramKey, $paramValue);
					}
				}
				else {
					$this->setNewRelicParameter('extra_' . $key, $parameter);
				}
			}
		}
	}

	protected function isNewRelicEnabled()
	{
		return extension_loaded('newrelic');
	}

	protected function getAppName(array $context)
	{
		if (isset($context['appname'])) {
			return $context['appname'];
		}

		return $this->appName;
	}

	protected function getTransactionName(array $context)
	{
		if (isset($context['transaction_name'])) {
			return $context['transaction_name'];
		}

		return $this->transactionName;
	}

	protected function setNewRelicAppName($appName)
	{
		newrelic_set_appname($appName);
	}

	protected function setNewRelicTransactionName($transactionName)
	{
		newrelic_name_transaction($transactionName);
	}

	protected function setNewRelicParameter($key, $value)
	{
		if ((null === $value) || is_scalar($value)) {
			newrelic_add_custom_parameter($key, $value);
		}
		else {
			newrelic_add_custom_parameter($key, @json_encode($value));
		}
	}

	protected function getDefaultFormatter()
	{
		return new \Monolog\Formatter\NormalizerFormatter();
	}
}

?>
