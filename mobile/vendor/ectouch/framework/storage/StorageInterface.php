<?php
//dezend by  QQ:2172298892
namespace base\storage;

interface StorageInterface
{
	public function read($name);

	public function write($name, $content, $option);

	public function append($name, $content);

	public function delete($name);

	public function isExists($name);

	public function move($oldName, $newName);
}


?>
