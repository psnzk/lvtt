<?php
//dezend by  QQ:2172298892
namespace Whoops\Exception;

class Formatter
{
	static public function formatExceptionAsDataArray(Inspector $inspector, $shouldAddTrace)
	{
		$exception = $inspector->getException();
		$response = array('type' => get_class($exception), 'message' => $exception->getMessage(), 'file' => $exception->getFile(), 'line' => $exception->getLine());

		if ($shouldAddTrace) {
			$frames = $inspector->getFrames();
			$frameData = array();

			foreach ($frames as $frame) {
				$frameData[] = array('file' => $frame->getFile(), 'line' => $frame->getLine(), 'function' => $frame->getFunction(), 'class' => $frame->getClass(), 'args' => $frame->getArgs());
			}

			$response['trace'] = $frameData;
		}

		return $response;
	}

	static public function formatExceptionPlain(Inspector $inspector)
	{
		$message = $inspector->getException()->getMessage();
		$frames = $inspector->getFrames();
		$plain = $inspector->getExceptionName();
		$plain .= ' thrown with message "';
		$plain .= $message;
		$plain .= '"' . "\n\n";
		$plain .= "Stacktrace:\n";

		foreach ($frames as $i => $frame) {
			$plain .= '#' . (count($frames) - $i - 1) . ' ';
			$plain .= $frame->getClass() ?: '';
			$plain .= ($frame->getClass() && $frame->getFunction() ? ':' : '');
			$plain .= $frame->getFunction() ?: '';
			$plain .= ' in ';
			$plain .= $frame->getFile() ?: '<#unknown>';
			$plain .= ':';
			$plain .= (int) $frame->getLine() . "\n";
		}

		return $plain;
	}
}


?>
