<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class ElasticSearchHandlerTest extends \Monolog\TestCase
{
	/**
     * @var Client mock
     */
	protected $client;
	/**
     * @var array Default handler options
     */
	protected $options = array('index' => 'my_index', 'type' => 'doc_type');

	public function setUp()
	{
		if (!class_exists('Elastica\\Client')) {
			$this->markTestSkipped('ruflin/elastica not installed');
		}

		$this->client = $this->getMockBuilder('Elastica\\Client')->setMethods(array('addDocuments'))->disableOriginalConstructor()->getMock();
	}

	public function testHandle()
	{
		$msg = array(
			'level'      => \Monolog\Logger::ERROR,
			'level_name' => 'ERROR',
			'channel'    => 'meh',
			'context'    => array('foo' => 7, 0 => 'bar', 'class' => new \stdClass()),
			'datetime'   => new \DateTime('@0'),
			'extra'      => array(),
			'message'    => 'log'
			);
		$formatter = new \Monolog\Formatter\ElasticaFormatter($this->options['index'], $this->options['type']);
		$expected = array($formatter->format($msg));
		$this->client->expects($this->any())->method('addDocuments')->with($expected);
		$handler = new ElasticSearchHandler($this->client, $this->options);
		$handler->handle($msg);
		$handler->handleBatch(array($msg));
	}

	public function testSetFormatter()
	{
		$handler = new ElasticSearchHandler($this->client);
		$formatter = new \Monolog\Formatter\ElasticaFormatter('index_new', 'type_new');
		$handler->setFormatter($formatter);
		$this->assertInstanceOf('Monolog\\Formatter\\ElasticaFormatter', $handler->getFormatter());
		$this->assertEquals('index_new', $handler->getFormatter()->getIndex());
		$this->assertEquals('type_new', $handler->getFormatter()->getType());
	}

	public function testSetFormatterInvalid()
	{
		$handler = new ElasticSearchHandler($this->client);
		$formatter = new \Monolog\Formatter\NormalizerFormatter();
		$handler->setFormatter($formatter);
	}

	public function testOptions()
	{
		$expected = array('index' => $this->options['index'], 'type' => $this->options['type'], 'ignore_error' => false);
		$handler = new ElasticSearchHandler($this->client, $this->options);
		$this->assertEquals($expected, $handler->getOptions());
	}

	public function testConnectionErrors($ignore, $expectedError)
	{
		$clientOpts = array('host' => '127.0.0.1', 'port' => 1);
		$client = new \Elastica\Client($clientOpts);
		$handlerOpts = array('ignore_error' => $ignore);
		$handler = new ElasticSearchHandler($client, $handlerOpts);

		if ($expectedError) {
			$this->setExpectedException($expectedError[0], $expectedError[1]);
			$handler->handle($this->getRecord());
		}
		else {
			$this->assertFalse($handler->handle($this->getRecord()));
		}
	}

	public function providerTestConnectionErrors()
	{
		return array(
	array(
		false,
		array('RuntimeException', 'Error sending messages to Elasticsearch')
		),
	array(true, false)
	);
	}

	public function testHandleIntegration()
	{
		$msg = array(
			'level'      => \Monolog\Logger::ERROR,
			'level_name' => 'ERROR',
			'channel'    => 'meh',
			'context'    => array('foo' => 7, 0 => 'bar', 'class' => new \stdClass()),
			'datetime'   => new \DateTime('@0'),
			'extra'      => array(),
			'message'    => 'log'
			);
		$expected = $msg;
		$expected['datetime'] = $msg['datetime']->format(\DateTime::ISO8601);
		$expected['context'] = array('class' => '[object] (stdClass: {})', 'foo' => 7, 0 => 'bar');
		$client = new \Elastica\Client();
		$handler = new ElasticSearchHandler($client, $this->options);

		try {
			$handler->handleBatch(array($msg));
		}
		catch (\RuntimeException $e) {
			$this->markTestSkipped('Cannot connect to Elastic Search server on localhost');
		}

		$documentId = $this->getCreatedDocId($client->getLastResponse());
		$this->assertNotEmpty($documentId, 'No elastic document id received');
		$document = $this->getDocSourceFromElastic($client, $this->options['index'], $this->options['type'], $documentId);
		$this->assertEquals($expected, $document);
		$client->request('/' . $this->options['index'], \Elastica\Request::DELETE);
	}

	protected function getCreatedDocId(\Elastica\Response $response)
	{
		$data = $response->getData();

		if (!empty($data['items'][0]['create']['_id'])) {
			return $data['items'][0]['create']['_id'];
		}
	}

	protected function getDocSourceFromElastic(\Elastica\Client $client, $index, $type, $documentId)
	{
		$resp = $client->request('/' . $index . '/' . $type . '/' . $documentId, \Elastica\Request::GET);
		$data = $resp->getData();

		if (!empty($data['_source'])) {
			return $data['_source'];
		}

		return array();
	}
}

?>
