<?php
//dezend by  QQ:2172298892
namespace base\db;

interface DbInterface
{
	public function __construct($config);

	public function query($sql, array $params);

	public function execute($sql, array $params);

	public function select($table, array $condition, $field, $order, $limit);

	public function insert($table, array $data);

	public function update($table, array $condition, array $data);

	public function delete($table, array $condition);

	public function count($table, array $condition);

	public function getFields($table);

	public function getSql();

	public function beginTransaction();

	public function commit();

	public function rollBack();
}


?>
