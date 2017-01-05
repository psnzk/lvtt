<?php
//dezend by  QQ:2172298892
namespace Whoops\Provider\Zend;

class ExceptionStrategy extends \Zend\Mvc\View\Http\ExceptionStrategy
{
	protected $run;

	public function __construct(\Whoops\Run $run)
	{
		$this->run = $run;
		return $this;
	}

	public function prepareExceptionViewModel(\Zend\Mvc\MvcEvent $event)
	{
		$error = $event->getError();

		if (empty($error)) {
			return NULL;
		}

		$result = $event->getResult();

		if ($result instanceof \Zend\Http\Response) {
			return NULL;
		}

		switch ($error) {
		case \Zend\Mvc\Application::ERROR_CONTROLLER_NOT_FOUND:
		case \Zend\Mvc\Application::ERROR_CONTROLLER_INVALID:
		case \Zend\Mvc\Application::ERROR_ROUTER_NO_MATCH:
			return NULL;
		case \Zend\Mvc\Application::ERROR_EXCEPTION:
		default:
			$exception = $event->getParam('exception');

			if ($exception) {
				$response = $event->getResponse();
				if (!$response || ($response->getStatusCode() === 200)) {
					header('HTTP/1.0 500 Internal Server Error', true, 500);
				}

				ob_clean();
				$this->run->handleException($event->getParam('exception'));
			}

			break;
		}
	}
}

?>
