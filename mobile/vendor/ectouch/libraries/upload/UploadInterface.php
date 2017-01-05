<?php
//dezend by  QQ:2172298892
namespace libraries\upload;

interface UploadInterface
{
	public function __construct($config);

	public function rootPath($path);

	public function checkPath($path);

	public function saveFile($file);

	public function getError();
}


?>
