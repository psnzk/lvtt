<?php
//dezend by  QQ:2172298892
namespace Whoops\Provider\Zend;

class RouteNotFoundStrategy extends \Zend\Mvc\View\Http\RouteNotFoundStrategy
{
	protected $run;

	public function __construct(\Whoops\Run $run)
	{
		$this->run = $run;
	}

	public function prepareNotFoundViewModel(\Zend\Mvc\MvcEvent $e)
	{
		$vars = $e->getResult();

		if ($vars instanceof \Zend\Stdlib\ResponseInterface) {
			return NULL;
		}

		$response = $e->getResponse();

		if ($response->getStatusCode() != 404) {
			return NULL;
		}

		if (!$vars instanceof \Zend\View\Model\ViewModel) {
			$model = new \Zend\View\Model\ViewModel();

			if (is_string($vars)) {
				$model->setVariable('message', $vars);
			}
			else {
				$model->setVariable('message', 'Page not found.');
			}
		}
		else {
			$model = $vars;

			if ($model->getVariable('message') === null) {
				$model->setVariable('message', 'Page not found.');
			}
		}

		$this->injectNotFoundReason($model, $e);
		$this->injectException($model, $e);
		$this->injectController($model, $e);
		ob_clean();
		throw new \Exception($model->getVariable('message') . ' ' . $model->getVariable('reason'));
	}
}

?>
