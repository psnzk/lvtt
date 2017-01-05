<?php
//dezend by  QQ:2172298892
namespace Whoops\Provider\Phalcon;

class WhoopsServiceProvider
{
	public function __construct(\Phalcon\DI $di = NULL)
	{
		if (!$di) {
			$di = \Phalcon\DI::getDefault();
		}

		$di->setShared('whoops.pretty_page_handler', function() {
			return new \Whoops\Handler\PrettyPageHandler();
		});
		$di->setShared('whoops.json_response_handler', function() {
			$jsonHandler = new \Whoops\Handler\JsonResponseHandler();
			$jsonHandler->onlyForAjaxRequests(true);
			return $jsonHandler;
		});
		$phalcon_info_handler = function() use($di) {
			try {
				$request = $di['request'];
			}
			catch (\Phalcon\DI\Exception $e) {
				return NULL;
			}

			$di['whoops.pretty_page_handler']->addDataTable('Phalcon Application (Request)', array('URI' => $request->getScheme() . '://' . $request->getServer('HTTP_HOST') . $request->getServer('REQUEST_URI'), 'Request URI' => $request->getServer('REQUEST_URI'), 'Path Info' => $request->getServer('PATH_INFO'), 'Query String' => $request->getServer('QUERY_STRING') ?: '<none>', 'HTTP Method' => $request->getMethod(), 'Script Name' => $request->getServer('SCRIPT_NAME'), 'Scheme' => $request->getScheme(), 'Port' => $request->getServer('SERVER_PORT'), 'Host' => $request->getServerName()));
		};
		$di->setShared('whoops', function() use($di, $phalcon_info_handler) {
			$run = new \Whoops\Run();
			$run->pushHandler($di['whoops.pretty_page_handler']);
			$run->pushHandler($phalcon_info_handler);
			$run->pushHandler($di['whoops.json_response_handler']);
			return $run;
		});
		$di['whoops']->register();
	}
}


?>
