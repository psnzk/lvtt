<?php
//dezend by  QQ:2172298892
namespace Whoops;

class Module
{
	protected $run;

	public function onBootstrap(\Zend\EventManager\EventInterface $event)
	{
		$prettyPageHandler = new Handler\PrettyPageHandler();
		$config = $event->getApplication()->getServiceManager()->get('Config');

		if (isset($config['view_manager']['editor'])) {
			$prettyPageHandler->setEditor($config['view_manager']['editor']);
		}

		$this->run = new Run();
		$this->run->register();
		$this->run->pushHandler($prettyPageHandler);
		$this->attachListeners($event);
	}

	public function getAutoloaderConfig()
	{
		return array(
	'Zend\\Loader\\StandardAutoloader' => array(
		'namespaces' => array('Whoops' => __DIR__ . '/src/' . 'Whoops')
		)
	);
	}

	private function attachListeners(\Zend\EventManager\EventInterface $event)
	{
		$request = $event->getRequest();
		$application = $event->getApplication();
		$services = $application->getServiceManager();
		$events = $application->getEventManager();
		$config = $services->get('Config');
		if ($request instanceof \Zend\Console\Request || empty($config['view_manager']['display_exceptions'])) {
			return NULL;
		}

		$jsonHandler = new Handler\JsonResponseHandler();

		if (!empty($config['view_manager']['json_exceptions']['show_trace'])) {
			$jsonHandler->addTraceToOutput(true);
		}

		if (!empty($config['view_manager']['json_exceptions']['ajax_only'])) {
			$jsonHandler->onlyForAjaxRequests(true);
		}

		if (!empty($config['view_manager']['json_exceptions']['display'])) {
			$this->run->pushHandler($jsonHandler);
		}

		$exceptionStrategy = new Provider\Zend\ExceptionStrategy($this->run);
		$exceptionStrategy->attach($events);
		$routeNotFoundStrategy = new Provider\Zend\RouteNotFoundStrategy($this->run);
		$routeNotFoundStrategy->attach($events);
		$services->get('Zend\\Mvc\\View\\Http\\ExceptionStrategy')->detach($events);
		$services->get('Zend\\Mvc\\View\\Http\\RouteNotFoundStrategy')->detach($events);
	}
}


?>
