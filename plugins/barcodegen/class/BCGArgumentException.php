<?php
//dezend by  QQ:2172298892
class BCGArgumentException extends Exception
{
	protected $param;

	public function __construct($message, $param)
	{
		$this->param = $param;
		parent::__construct($message, 20000);
	}
}

?>
