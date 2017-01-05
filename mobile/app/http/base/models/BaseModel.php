<?php
//dezend by  QQ:2172298892
namespace http\base\models;

class BaseModel extends \base\Model
{
	public function one()
	{
		$field = $this->options['field'];
		$data = $this->find();
		return isset($data[$field]) ? $data[$field] : array();
	}
}

?>
