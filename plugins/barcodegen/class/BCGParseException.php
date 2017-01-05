<?php
//dezend by  QQ:2172298892
class BCGParseException extends Exception
{
	protected $barcode;

	public function __construct($barcode, $message)
	{
		$this->barcode = $barcode;
		parent::__construct($message, 10000);
	}
}

?>
