<?php
//dezend by  QQ:2172298892
namespace Whoops\Handler;

class XmlResponseHandler extends Handler
{
	/**
     * @var bool
     */
	private $returnFrames = false;

	public function addTraceToOutput($returnFrames = NULL)
	{
		if (func_num_args() == 0) {
			return $this->returnFrames;
		}

		$this->returnFrames = (bool) $returnFrames;
		return $this;
	}

	public function handle()
	{
		$response = array('error' => \Whoops\Exception\Formatter::formatExceptionAsDataArray($this->getInspector(), $this->addTraceToOutput()));
		echo $this->toXml($response);
		return Handler::QUIT;
	}

	static private function addDataToNode(\SimpleXMLElement $node, $data)
	{
		assert('is_array($data) || $node instanceof Traversable');

		foreach ($data as $key => $value) {
			if (is_numeric($key)) {
				$key = 'unknownNode_' . (string) $key;
			}

			$key = preg_replace('/[^a-z0-9\\-\\_\\.\\:]/i', '', $key);

			if (is_array($value)) {
				$child = $node->addChild($key);
				self::addDataToNode($child, $value);
			}
			else {
				$value = str_replace('&', '&amp;', print_r($value, true));
				$node->addChild($key, $value);
			}
		}

		return $node;
	}

	static private function toXml($data)
	{
		assert('is_array($data) || $node instanceof Traversable');
		$node = simplexml_load_string('<?xml version=\'1.0\' encoding=\'utf-8\'?><root />');
		return self::addDataToNode($node, $data)->asXML();
	}
}

?>
