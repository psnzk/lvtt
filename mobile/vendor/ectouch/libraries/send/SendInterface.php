<?php
//dezend by  QQ:2172298892
namespace libraries\send;

interface SendInterface
{
	public function __construct($config);

	public function push($to, $title, $content, $time = '', $data = array());

	public function getError();
}


?>
