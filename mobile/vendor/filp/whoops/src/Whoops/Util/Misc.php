<?php
//dezend by  QQ:2172298892
namespace Whoops\Util;

class Misc
{
	static public function canSendHeaders()
	{
		return isset($_SERVER['REQUEST_URI']) && !headers_sent();
	}

	static public function translateErrorCode($error_code)
	{
		$constants = get_defined_constants(true);

		if (array_key_exists('Core', $constants)) {
			foreach ($constants['Core'] as $constant => $value) {
				if ((substr($constant, 0, 2) == 'E_') && ($value == $error_code)) {
					return $constant;
				}
			}
		}

		return 'E_UNKNOWN';
	}
}


?>
