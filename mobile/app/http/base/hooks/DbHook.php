<?php
//dezend by  QQ:2172298892
namespace http\base\hooks;

class DbHook
{
	public function dbQueryBegin($sql, $params)
	{
	}

	public function dbQueryEnd($sql, $data)
	{
	}

	public function dbExecuteBegin($sql, $params)
	{
	}

	public function dbExecuteEnd($sql, $affectedRows)
	{
	}

	public function dbException($sql, $err)
	{
	}
}


?>
