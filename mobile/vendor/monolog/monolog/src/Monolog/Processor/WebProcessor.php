<?php
//dezend by  QQ:2172298892
namespace Monolog\Processor;

class WebProcessor
{
	/**
     * @var array|\ArrayAccess
     */
	protected $serverData;
	/**
     * Default fields
     *
     * Array is structured as [key in record.extra => key in $serverData]
     *
     * @var array
     */
	protected $extraFields = array('url' => 'REQUEST_URI', 'ip' => 'REMOTE_ADDR', 'http_method' => 'REQUEST_METHOD', 'server' => 'SERVER_NAME', 'referrer' => 'HTTP_REFERER');

	public function __construct($serverData = NULL, array $extraFields = NULL)
	{
		if (null === $serverData) {
			$this->serverData = &$_SERVER;
		}
		else {
			if (is_array($serverData) || $serverData instanceof \ArrayAccess) {
				$this->serverData = $serverData;
			}
			else {
				throw new \UnexpectedValueException('$serverData must be an array or object implementing ArrayAccess.');
			}
		}

		if (null !== $extraFields) {
			if (isset($extraFields[0])) {
				foreach (array_keys($this->extraFields) as $fieldName) {
					if (!in_array($fieldName, $extraFields)) {
						unset($this->extraFields[$fieldName]);
					}
				}
			}
			else {
				$this->extraFields = $extraFields;
			}
		}
	}

	public function __invoke(array $record)
	{
		if (!isset($this->serverData['REQUEST_URI'])) {
			return $record;
		}

		$record['extra'] = $this->appendExtraFields($record['extra']);
		return $record;
	}

	public function addExtraField($extraName, $serverName)
	{
		$this->extraFields[$extraName] = $serverName;
		return $this;
	}

	private function appendExtraFields(array $extra)
	{
		foreach ($this->extraFields as $extraName => $serverName) {
			$extra[$extraName] = isset($this->serverData[$serverName]) ? $this->serverData[$serverName] : null;
		}

		if (isset($this->serverData['UNIQUE_ID'])) {
			$extra['unique_id'] = $this->serverData['UNIQUE_ID'];
		}

		return $extra;
	}
}


?>
