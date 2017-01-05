<?php
//dezend by  QQ:2172298892
namespace Whoops\Handler;

class SoapResponseHandler extends Handler
{
	public function handle()
	{
		$exception = $this->getException();
		echo $this->toXml($exception);
		return Handler::QUIT;
	}

	private function toXml(\Exception $exception)
	{
		$xml = '';
		$xml .= '<?xml version="1.0" encoding="UTF-8"?>';
		$xml .= '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">';
		$xml .= '  <SOAP-ENV:Body>';
		$xml .= '    <SOAP-ENV:Fault>';
		$xml .= '      <faultcode>' . htmlspecialchars($exception->getCode()) . '</faultcode>';
		$xml .= '      <faultstring>' . htmlspecialchars($exception->getMessage()) . '</faultstring>';
		$xml .= '      <detail><trace>' . htmlspecialchars($exception->getTraceAsString()) . '</trace></detail>';
		$xml .= '    </SOAP-ENV:Fault>';
		$xml .= '  </SOAP-ENV:Body>';
		$xml .= '</SOAP-ENV:Envelope>';
		return $xml;
	}
}

?>
