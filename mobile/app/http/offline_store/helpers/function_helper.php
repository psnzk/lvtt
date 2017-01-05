<?php
//zend by QQ:2172298892
function getStoreIdByGoodsId($id)
{
	$sql = 'SELECT store_id FROM ' . $GLOBALS['ecs']->table('store_goods') . ' WHERE goods_id = ' . $id;
	$res = $GLOBALS['db']->getRow($sql);
	return $res['store_id'];
}


?>
