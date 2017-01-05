<?php
//dezend by  QQ:2172298892
namespace Whoops\Handler;

class JsonResponseHandler extends Handler
{
	/**
     * @var bool
     */
	private $returnFrames = false;
	/**
     * @var bool
     */
	private $onlyForAjaxRequests = false;

	public function addTraceToOutput($returnFrames = NULL)
	{
		if (func_num_args() == 0) {
			return $this->returnFrames;
		}

		$this->returnFrames = (bool) $returnFrames;
		return $this;
	}

	public function onlyForAjaxRequests($onlyForAjaxRequests = NULL)
	{
		if (func_num_args() == 0) {
			return $this->onlyForAjaxRequests;
		}

		$this->onlyForAjaxRequests = (bool) $onlyForAjaxRequests;
	}

	private function isAjaxRequest()
	{
		return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
	}

	public function handle()
	{
		if ($this->onlyForAjaxRequests() && !$this->isAjaxRequest()) {
			return Handler::DONE;
		}

		$response = array('error' => \Whoops\Exception\Formatter::formatExceptionAsDataArray($this->getInspector(), $this->addTraceToOutput()));

		if (\Whoops\Util\Misc::canSendHeaders()) {
			header('Content-Type: application/json');
		}

		echo json_encode($response);
		return Handler::QUIT;
	}
}

?>
