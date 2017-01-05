<?php
//dezend by  QQ:2172298892
namespace Whoops\Provider\Silex;

class WhoopsServiceProvider implements \Silex\ServiceProviderInterface
{
	public function register(\Silex\Application $app)
	{
		$app['whoops.error_page_handler'] = $app->share(function() {
			if (PHP_SAPI === 'cli') {
				return new \Whoops\Handler\PlainTextHandler();
			}
			else {
				return new \Whoops\Handler\PrettyPageHandler();
			}
		});
		$app['whoops.silex_info_handler'] = $app->protect(function() use($app) {
			try {
				$request = $app['request'];
			}
			catch (\RuntimeException $e) {
				return NULL;
			}

			$errorPageHandler = $app['whoops.error_page_handler'];

			if ($errorPageHandler instanceof \Whoops\Handler\PrettyPageHandler) {
				$errorPageHandler->addDataTable('Silex Application', array('Charset' => $app['charset'], 'Locale' => $app['locale'], 'Route Class' => $app['route_class'], 'Dispatcher Class' => $app['dispatcher_class'], 'Application Class' => get_class($app)));
				$errorPageHandler->addDataTable('Silex Application (Request)', array('URI' => $request->getUri(), 'Request URI' => $request->getRequestUri(), 'Path Info' => $request->getPathInfo(), 'Query String' => $request->getQueryString() ?: '<none>', 'HTTP Method' => $request->getMethod(), 'Script Name' => $request->getScriptName(), 'Base Path' => $request->getBasePath(), 'Base URL' => $request->getBaseUrl(), 'Scheme' => $request->getScheme(), 'Port' => $request->getPort(), 'Host' => $request->getHost()));
			}
		});
		$app['whoops'] = $app->share(function() use($app) {
			$run = new \Whoops\Run();
			$run->allowQuit(false);
			$run->pushHandler($app['whoops.error_page_handler']);
			$run->pushHandler($app['whoops.silex_info_handler']);
			return $run;
		});
		$app->error(function($e) use($app) {
			$method = \Whoops\Run::EXCEPTION_HANDLER;
			ob_start();
			$app['whoops']->$method($e);
			$response = ob_get_clean();
			$code = ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException ? $e->getStatusCode() : 500);
			return new \Symfony\Component\HttpFoundation\Response($response, $code);
		});
		$app['whoops']->register();
	}

	public function boot(\Silex\Application $app)
	{
	}
}

?>
