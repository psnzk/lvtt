<?php
//dezend by  QQ:2172298892
function get_goodstype($ru_id)
{
	$where = ' WHERE 1 ';

	if ($GLOBALS['_CFG']['attr_set_up'] == 0) {
		if (0 < $ru_id) {
			$where .= ' AND t.user_id = 0 ';
		}
	}
	else if ($GLOBALS['_CFG']['attr_set_up'] == 1) {
		if (0 < $ru_id) {
			$where .= ' AND t.user_id = \'' . $ru_id . '\'';
		}
	}

	$result = get_filter();

	if ($result === false) {
		if (!empty($_GET['is_ajax']) && ($_GET['is_ajax'] == 1)) {
			$_REQUEST['keyword'] = json_str_iconv($_REQUEST['keyword']);
		}

		$filter['keyword'] = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
		$filter['merchant_id'] = isset($_REQUEST['merchant_id']) ? intval($_REQUEST['merchant_id']) : -1;

		if ($filter['keyword']) {
			$where .= ' AND t.cat_name LIKE \'%' . mysql_like_quote($filter['keyword']) . '%\' ';
		}

		if (-1 < $filter['merchant_id']) {
			$where .= ' AND t.user_id = \'' . $filter['merchant_id'] . '\' ';
		}

		$sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('goods_type') . ' AS t ' . 'LEFT JOIN ' . $GLOBALS['ecs']->table('attribute') . ' AS a ON a.cat_id=t.cat_id ' . $where . 'GROUP BY t.cat_id ';
		$filter['record_count'] = count($GLOBALS['db']->getAll($sql));
		$filter = page_and_size($filter);
		$sql = 'SELECT t.*, COUNT(a.cat_id) AS attr_count ' . 'FROM ' . $GLOBALS['ecs']->table('goods_type') . ' AS t ' . 'LEFT JOIN ' . $GLOBALS['ecs']->table('attribute') . ' AS a ON a.cat_id=t.cat_id ' . $where . 'GROUP BY t.cat_id ' . 'LIMIT ' . $filter['start'] . ',' . $filter['page_size'];
		set_filter($filter, $sql);
	}
	else {
		$sql = $result['sql'];
		$filter = $result['filter'];
	}

	$all = $GLOBALS['db']->getAll($sql);

	foreach ($all as $key => $val) {
		$all[$key]['attr_group'] = strtr($val['attr_group'], array("\r" => '', "\n" => ', '));
		$all[$key]['user_name'] = get_shop_name($val['user_id'], 1);
	}

	return array('type' => $all, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}

function get_goodstype_info($cat_id)
{
	$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('goods_type') . ' WHERE cat_id=\'' . $cat_id . '\'';
	return $GLOBALS['db']->getRow($sql);
}

function update_attribute_group($cat_id, $old_group, $new_group)
{
	$sql = 'UPDATE ' . $GLOBALS['ecs']->table('attribute') . ' SET attr_group=\'' . $new_group . '\' WHERE cat_id=\'' . $cat_id . '\' AND attr_group=\'' . $old_group . '\'';
	$GLOBALS['db']->query($sql);
}

define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';
$exc = new exchange($ecs->table('goods_type'), $db, 'cat_id', 'cat_name');
$adminru = get_admin_ru_id();

if ($adminru['ru_id'] == 0) {
	$smarty->assign('priv_ru', 1);
}
else {
	$smarty->assign('priv_ru', 0);
}

if ($_REQUEST['act'] == 'manage') {
	assign_query_info();
	$smarty->assign('ur_here', $_LANG['08_goods_type']);
	$smarty->assign('full_page', 1);
	$good_type_list = get_goodstype($adminru['ru_id']);
	$good_in_type = '';
	$smarty->assign('goods_type_arr', $good_type_list['type']);
	$smarty->assign('filter', $good_type_list['filter']);
	$smarty->assign('record_count', $good_type_list['record_count']);
	$smarty->assign('page_count', $good_type_list['page_count']);
	$query = $db->query('SELECT a.cat_id FROM ' . $ecs->table('attribute') . ' AS a RIGHT JOIN ' . $ecs->table('goods_attr') . ' AS g ON g.attr_id = a.attr_id GROUP BY a.cat_id');

	while ($row = $db->fetchRow($query)) {
		$good_in_type[$row['cat_id']] = 1;
	}

	$smarty->assign('good_in_type', $good_in_type);

	if ($GLOBALS['_CFG']['attr_set_up'] == 0) {
		if ($adminru['ru_id'] == 0) {
			$smarty->assign('action_link', array('text' => $_LANG['new_goods_type'], 'href' => 'goods_type.php?act=add'));
			$smarty->assign('attr_set_up', 1);
		}
		else {
			$smarty->assign('attr_set_up', 0);
		}
	}
	else if ($GLOBALS['_CFG']['attr_set_up'] == 1) {
		$smarty->assign('action_link', array('text' => $_LANG['new_goods_type'], 'href' => 'goods_type.php?act=add'));
		$smarty->assign('attr_set_up', 1);
	}

	$store_list = get_common_store_list();
	$smarty->assign('store_list', $store_list);
	$smarty->display('goods_type.dwt');
}
else if ($_REQUEST['act'] == 'query') {
	$good_type_list = get_goodstype($adminru['ru_id']);

	if ($GLOBALS['_CFG']['attr_set_up'] == 0) {
		if ($adminru['ru_id'] == 0) {
			$smarty->assign('attr_set_up', 1);
		}
		else {
			$smarty->assign('attr_set_up', 0);
		}
	}
	else if ($GLOBALS['_CFG']['attr_set_up'] == 1) {
		$smarty->assign('attr_set_up', 1);
	}

	$smarty->assign('goods_type_arr', $good_type_list['type']);
	$smarty->assign('filter', $good_type_list['filter']);
	$smarty->assign('record_count', $good_type_list['record_count']);
	$smarty->assign('page_count', $good_type_list['page_count']);
	make_json_result($smarty->fetch('goods_type.dwt'), '', array('filter' => $good_type_list['filter'], 'page_count' => $good_type_list['page_count']));
}
else if ($_REQUEST['act'] == 'edit_type_name') {
	check_authz_json('goods_type');
	$type_id = (!empty($_POST['id']) ? intval($_POST['id']) : 0);
	$type_name = (!empty($_POST['val']) ? json_str_iconv(trim($_POST['val'])) : '');
	$is_only = $exc->is_only('cat_name', $type_name, $type_id);

	if ($is_only) {
		$exc->edit('cat_name=\'' . $type_name . '\'', $type_id);
		admin_log($type_name, 'edit', 'goods_type');
		make_json_result(stripslashes($type_name));
	}
	else {
		make_json_error($_LANG['repeat_type_name']);
	}
}
else if ($_REQUEST['act'] == 'toggle_enabled') {
	check_authz_json('goods_type');
	$id = intval($_POST['id']);
	$val = intval($_POST['val']);
	$exc->edit('enabled=\'' . $val . '\'', $id);
	make_json_result($val);
}
else if ($_REQUEST['act'] == 'add') {
	admin_priv('goods_type');

	if ($GLOBALS['_CFG']['attr_set_up'] == 0) {
		if (0 < $adminru['ru_id']) {
			$links = array(
				array('href' => 'goods_type.php?act=manage', 'text' => $_LANG['back_list'])
				);
			sys_msg('暂时没有添加属性权限', 0, $links);
			exit();
		}
	}

	$smarty->assign('ur_here', $_LANG['new_goods_type']);
	$smarty->assign('action_link', array('href' => 'goods_type.php?act=manage', 'text' => $_LANG['goods_type_list']));
	$smarty->assign('action', 'add');
	$smarty->assign('form_act', 'insert');
	$smarty->assign('goods_type', array('enabled' => 1));
	assign_query_info();
	$smarty->display('goods_type_info.dwt');
}
else if ($_REQUEST['act'] == 'insert') {
	$goods_type['cat_name'] = sub_str($_POST['cat_name'], 60);
	$goods_type['attr_group'] = sub_str($_POST['attr_group'], 255);
	$goods_type['enabled'] = intval($_POST['enabled']);
	$goods_type['user_id'] = $adminru['ru_id'];

	if ($db->autoExecute($ecs->table('goods_type'), $goods_type) !== false) {
		$links = array(
			array('href' => 'goods_type.php?act=manage', 'text' => $_LANG['back_list'])
			);
		sys_msg($_LANG['add_goodstype_success'], 0, $links);
	}
	else {
		sys_msg($_LANG['add_goodstype_failed'], 1);
	}
}
else if ($_REQUEST['act'] == 'edit') {
	$goods_type = get_goodstype_info(intval($_GET['cat_id']));

	if (empty($goods_type)) {
		sys_msg($_LANG['cannot_found_goodstype'], 1);
	}

	admin_priv('goods_type');

	if ($GLOBALS['_CFG']['attr_set_up'] == 0) {
		if (0 < $adminru['ru_id']) {
			$links = array(
				array('href' => 'goods_type.php?act=manage', 'text' => $_LANG['back_list'])
				);
			sys_msg('暂时没有添加属性权限', 0, $links);
			exit();
		}
	}

	$smarty->assign('ur_here', $_LANG['edit_goods_type']);
	$smarty->assign('action_link', array('href' => 'goods_type.php?act=manage', 'text' => $_LANG['goods_type_list']));
	$smarty->assign('action', 'add');
	$smarty->assign('form_act', 'update');
	$smarty->assign('goods_type', $goods_type);
	assign_query_info();
	$smarty->display('goods_type_info.dwt');
}
else if ($_REQUEST['act'] == 'update') {
	$goods_type['cat_name'] = sub_str($_POST['cat_name'], 60);
	$goods_type['attr_group'] = sub_str($_POST['attr_group'], 255);
	$goods_type['enabled'] = intval($_POST['enabled']);
	$cat_id = intval($_POST['cat_id']);
	$old_groups = get_attr_groups($cat_id);

	if ($db->autoExecute($ecs->table('goods_type'), $goods_type, 'UPDATE', 'cat_id=\'' . $cat_id . '\'') !== false) {
		$new_groups = explode("\n", str_replace("\r", '', $goods_type['attr_group']));

		foreach ($old_groups as $key => $val) {
			$found = array_search($val, $new_groups);
			if (($found === NULL) || ($found === false)) {
				update_attribute_group($cat_id, $key, 0);
			}
			else if ($key != $found) {
				update_attribute_group($cat_id, $key, $found);
			}
		}

		$links = array(
			array('href' => 'goods_type.php?act=manage', 'text' => $_LANG['back_list'])
			);
		sys_msg($_LANG['edit_goodstype_success'], 0, $links);
	}
	else {
		sys_msg($_LANG['edit_goodstype_failed'], 1);
	}
}
else if ($_REQUEST['act'] == 'remove') {
	check_authz_json('goods_type');
	$id = intval($_GET['id']);
	$name = $exc->get_name($id);

	if ($exc->drop($id)) {
		admin_log(addslashes($name), 'remove', 'goods_type');
		$sql = 'SELECT attr_id FROM ' . $ecs->table('attribute') . ' WHERE cat_id = \'' . $id . '\'';
		$arr = $db->getCol($sql);
		$GLOBALS['db']->query('DELETE FROM ' . $ecs->table('attribute') . ' WHERE attr_id ' . db_create_in($arr));
		$GLOBALS['db']->query('DELETE FROM ' . $ecs->table('goods_attr') . ' WHERE attr_id ' . db_create_in($arr));
		$url = 'goods_type.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
		ecs_header('Location: ' . $url . "\n");
		exit();
	}
	else {
		make_json_error($_LANG['remove_failed']);
	}
}

?>
