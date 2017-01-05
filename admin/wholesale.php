<?php
//dezend by  QQ:2172298892
function wholesale_list($ru_id)
{
	$rank_list = array();
	$sql = 'SELECT rank_id, rank_name FROM ' . $GLOBALS['ecs']->table('user_rank');
	$res = $GLOBALS['db']->query($sql);

	while ($row = $GLOBALS['db']->fetchRow($res)) {
		$rank_list[$row['rank_id']] = $row['rank_name'];
	}

	$result = get_filter();

	if ($result === false) {
		$filter['keyword'] = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
		if (isset($_REQUEST['is_ajax']) && ($_REQUEST['is_ajax'] == 1)) {
			$filter['keyword'] = json_str_iconv($filter['keyword']);
		}

		$filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'w.act_id' : trim($_REQUEST['sort_by']);
		$filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
		$where = '';

		if (!empty($filter['keyword'])) {
			$where .= ' AND goods_name LIKE \'%' . mysql_like_quote($filter['keyword']) . '%\'';
		}

		if (0 < $ru_id) {
			$where .= ' AND w.user_id = \'' . $ru_id . '\' ';
		}

		$filter['store_search'] = !isset($_REQUEST['store_search']) ? -1 : intval($_REQUEST['store_search']);
		$filter['merchant_id'] = isset($_REQUEST['merchant_id']) ? intval($_REQUEST['merchant_id']) : 0;
		$filter['store_keyword'] = isset($_REQUEST['store_keyword']) ? trim($_REQUEST['store_keyword']) : '';
		$store_where = '';
		$store_search_where = '';

		if (-1 < $filter['store_search']) {
			if ($ru_id == 0) {
				if (0 < $filter['store_search']) {
					if ($_REQUEST['store_type']) {
						$store_search_where = 'AND msi.shopNameSuffix = \'' . $_REQUEST['store_type'] . '\'';
					}

					if ($filter['store_search'] == 1) {
						$where .= ' AND w.user_id = \'' . $filter['merchant_id'] . '\' ';
					}
					else if ($filter['store_search'] == 2) {
						$store_where .= ' AND msi.rz_shopName LIKE \'%' . mysql_like_quote($filter['store_keyword']) . '%\'';
					}
					else if ($filter['store_search'] == 3) {
						$store_where .= ' AND msi.shoprz_brandName LIKE \'%' . mysql_like_quote($filter['store_keyword']) . '%\' ' . $store_search_where;
					}

					if (1 < $filter['store_search']) {
						$where .= ' AND (SELECT msi.user_id FROM ' . $GLOBALS['ecs']->table('merchants_shop_information') . ' as msi ' . ' WHERE msi.user_id = w.user_id ' . $store_where . ') > 0 ';
					}
				}
				else {
					$where .= ' AND w.user_id = 0';
				}
			}
		}

		$sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('wholesale') . ' AS w ' . ' WHERE 1 ' . $where;
		$filter['record_count'] = $GLOBALS['db']->getOne($sql);
		$filter = page_and_size($filter);
		$sql = 'SELECT w.* ' . 'FROM ' . $GLOBALS['ecs']->table('wholesale') . ' AS w ' . ' WHERE 1 ' . $where . ' ' . ' ORDER BY ' . $filter['sort_by'] . ' ' . $filter['sort_order'] . ' ' . ' LIMIT ' . $filter['start'] . ', ' . $filter['page_size'];
		$filter['keyword'] = stripslashes($filter['keyword']);
		set_filter($filter, $sql);
	}
	else {
		$sql = $result['sql'];
		$filter = $result['filter'];
	}

	$res = $GLOBALS['db']->query($sql);
	$list = array();

	while ($row = $GLOBALS['db']->fetchRow($res)) {
		$rank_name_list = array();

		if ($row['rank_ids']) {
			$rank_id_list = explode(',', $row['rank_ids']);

			foreach ($rank_id_list as $id) {
				if (isset($rank_list[$id])) {
					$rank_name_list[] = $rank_list[$id];
				}
			}
		}

		$row['rank_names'] = join(',', $rank_name_list);
		$row['price_list'] = unserialize($row['prices']);
		$row['ru_name'] = get_shop_name($row['user_id'], 1);
		$list[] = $row;
	}

	return array('item' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}

define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';
include_once '../includes/lib_goods.php';
$adminru = get_admin_ru_id();

if ($adminru['ru_id'] == 0) {
	$smarty->assign('priv_ru', 1);
}
else {
	$smarty->assign('priv_ru', 0);
}

if ($_REQUEST['act'] == 'list') {
	admin_priv('whole_sale');
	$smarty->assign('full_page', 1);
	$smarty->assign('ur_here', $_LANG['wholesale_list']);
	$smarty->assign('action_link', array('href' => 'wholesale.php?act=add', 'text' => $_LANG['add_wholesale']));
	$smarty->assign('action_link2', array('href' => 'wholesale.php?act=batch_add', 'text' => $_LANG['add_batch_wholesale']));
	$list = wholesale_list($adminru['ru_id']);
	$smarty->assign('wholesale_list', $list['item']);
	$smarty->assign('filter', $list['filter']);
	$smarty->assign('record_count', $list['record_count']);
	$smarty->assign('page_count', $list['page_count']);
	$store_list = get_common_store_list();
	$smarty->assign('store_list', $store_list);
	$sort_flag = sort_flag($list['filter']);
	$smarty->assign($sort_flag['tag'], $sort_flag['img']);
	assign_query_info();
	$smarty->display('wholesale_list.dwt');
}
else if ($_REQUEST['act'] == 'query') {
	$list = wholesale_list($adminru['ru_id']);
	$smarty->assign('wholesale_list', $list['item']);
	$smarty->assign('filter', $list['filter']);
	$smarty->assign('record_count', $list['record_count']);
	$smarty->assign('page_count', $list['page_count']);
	$store_list = get_common_store_list();
	$smarty->assign('store_list', $store_list);
	$sort_flag = sort_flag($list['filter']);
	$smarty->assign($sort_flag['tag'], $sort_flag['img']);
	make_json_result($smarty->fetch('wholesale_list.dwt'), '', array('filter' => $list['filter'], 'page_count' => $list['page_count']));
}
else if ($_REQUEST['act'] == 'remove') {
	check_authz_json('whole_sale');
	$id = intval($_GET['id']);
	$wholesale = wholesale_info($id);

	if (empty($wholesale)) {
		make_json_error($_LANG['wholesale_not_exist']);
	}

	$name = $wholesale['goods_name'];
	$sql = 'DELETE FROM ' . $ecs->table('wholesale') . ' WHERE act_id = \'' . $id . '\' LIMIT 1';
	$db->query($sql);
	admin_log($name, 'remove', 'wholesale');
	clear_cache_files();
	$url = 'wholesale.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
	ecs_header('Location: ' . $url . "\n");
	exit();
}
else if ($_REQUEST['act'] == 'batch') {
	if (empty($_POST['checkboxes'])) {
		sys_msg($_LANG['no_record_selected']);
	}
	else {
		admin_priv('whole_sale');
		$ids = $_POST['checkboxes'];

		if (isset($_POST['drop'])) {
			$sql = 'DELETE FROM ' . $ecs->table('wholesale') . ' WHERE act_id ' . db_create_in($ids);
			$db->query($sql);
			admin_log('', 'batch_remove', 'wholesale');
			clear_cache_files();
			$links[] = array('text' => $_LANG['back_wholesale_list'], 'href' => 'wholesale.php?act=list&' . list_link_postfix());
			sys_msg($_LANG['batch_drop_ok'], 0, $links);
		}
	}
}
else if ($_REQUEST['act'] == 'toggle_enabled') {
	check_authz_json('whole_sale');
	$id = intval($_POST['id']);
	$val = intval($_POST['val']);
	$sql = 'UPDATE ' . $ecs->table('wholesale') . ' SET enabled = \'' . $val . '\'' . ' WHERE act_id = \'' . $id . '\' LIMIT 1';
	$db->query($sql);
	make_json_result($val);
}
else if ($_REQUEST['act'] == 'batch_add') {
	admin_priv('whole_sale');
	$smarty->assign('form_action', 'batch_add_insert');
	$wholesale = array(
		'act_id'     => 0,
		'goods_id'   => 0,
		'goods_name' => $_LANG['pls_search_goods'],
		'enabled'    => '1',
		'price_list' => array()
		);
	$wholesale['price_list'] = array(
	array(
		'attr'    => array(),
		'qp_list' => array(
			array('quantity' => 0, 'price' => 0)
			)
		)
	);
	$smarty->assign('wholesale', $wholesale);
	$user_rank_list = array();
	$sql = 'SELECT rank_id, rank_name FROM ' . $ecs->table('user_rank') . ' ORDER BY special_rank, min_points';
	$res = $db->query($sql);

	while ($rank = $db->fetchRow($res)) {
		if (!empty($wholesale['rank_ids']) && (strpos($wholesale['rank_ids'], $rank['rank_id']) !== false)) {
			$rank['checked'] = 1;
		}

		$user_rank_list[] = $rank;
	}

	$smarty->assign('user_rank_list', $user_rank_list);
	set_default_filter();
	$smarty->assign('ur_here', $_LANG['add_wholesale']);
	$smarty->assign('ru_id', $adminru['ru_id']);
	$href = 'wholesale.php?act=list';
	$smarty->assign('action_link', array('href' => $href, 'text' => $_LANG['wholesale_list']));
	assign_query_info();
	$smarty->display('wholesale_batch_info.dwt');
}
else if ($_REQUEST['act'] == 'batch_add_insert') {
	admin_priv('whole_sale');
	$_POST['dst_goods_lists'] = array();

	if (!empty($_POST['goods_ids'])) {
		$_POST['dst_goods_lists'] = $_POST['goods_ids'];
	}

	if (!empty($_POST['dst_goods_lists']) && is_array($_POST['dst_goods_lists'])) {
		foreach ($_POST['dst_goods_lists'] as $dst_key => $dst_goods) {
			$dst_goods = intval($dst_goods);

			if ($dst_goods == 0) {
				unset($_POST['dst_goods_lists'][$dst_key]);
			}
		}
	}
	else if (!empty($_POST['dst_goods_lists'])) {
		$_POST['dst_goods_lists'] = array(intval($_POST['dst_goods_lists']));
	}
	else {
		sys_msg($_LANG['pls_search_goods']);
	}

	$dst_goods = implode(',', $_POST['dst_goods_lists']);
	$sql = 'SELECT goods_name, goods_id FROM ' . $ecs->table('goods') . ' WHERE goods_id IN (' . $dst_goods . ')';
	$goods_name = $db->getAll($sql);

	if (!empty($goods_name)) {
		$goods_rebulid = array();

		foreach ($goods_name as $goods_value) {
			$goods_rebulid[$goods_value['goods_id']] = addslashes($goods_value['goods_name']);
		}
	}

	if (empty($goods_rebulid)) {
		sys_msg('invalid goods id: All');
	}

	if (!isset($_POST['rank_id'])) {
		sys_msg($_LANG['pls_set_user_rank']);
	}

	if (isset($_POST['rank_id'])) {
		$dst_res = array();

		foreach ($_POST['rank_id'] as $rank_id) {
			$sql = 'SELECT COUNT(act_id) AS num, goods_id FROM ' . $ecs->table('wholesale') . ' WHERE goods_id IN (' . $dst_goods . ') ' . ' AND CONCAT(\',\', rank_ids, \',\') LIKE CONCAT(\'%,\', \'' . $rank_id . "', ',%')\r\n                      GROUP BY goods_id";

			if ($dst_res = $db->getAll($sql)) {
				foreach ($dst_res as $dst) {
					$key = array_search($dst['goods_id'], $_POST['dst_goods_lists']);
					if (($key != NULL) && ($key !== false)) {
						unset($_POST['dst_goods_lists'][$key]);
					}
				}
			}
		}
	}

	if (empty($_POST['dst_goods_lists'])) {
		sys_msg($_LANG['pls_search_goods']);
	}

	$wholesale = array('rank_ids' => isset($_POST['rank_id']) ? join(',', $_POST['rank_id']) : '', 'prices' => '', 'enabled' => empty($_POST['enabled']) ? 0 : 1, 'user_id' => $adminru['ru_id']);

	foreach ($_POST['dst_goods_lists'] as $goods_value) {
		$_wholesale = $wholesale;
		$_wholesale['goods_id'] = $goods_value;
		$_wholesale['goods_name'] = $goods_rebulid[$goods_value];
		$db->autoExecute($ecs->table('wholesale'), $_wholesale, 'INSERT');
		admin_log($goods_rebulid[$goods_value], 'add', 'wholesale');
	}

	clear_cache_files();
	$links = array(
		array('href' => 'wholesale.php?act=list', 'text' => $_LANG['back_wholesale_list']),
		array('href' => 'wholesale.php?act=add', 'text' => $_LANG['continue_add_wholesale'])
		);
	sys_msg($_LANG['add_wholesale_ok'], 0, $links);
}
else {
	if (($_REQUEST['act'] == 'add') || ($_REQUEST['act'] == 'edit')) {
		admin_priv('whole_sale');
		$is_add = $_REQUEST['act'] == 'add';
		$smarty->assign('form_action', $is_add ? 'insert' : 'update');

		if ($is_add) {
			$wholesale = array(
				'act_id'     => 0,
				'goods_id'   => 0,
				'goods_name' => $_LANG['pls_search_goods'],
				'enabled'    => '1',
				'price_list' => array()
				);
		}
		else {
			if (empty($_GET['id'])) {
				sys_msg('invalid param');
			}

			$id = intval($_GET['id']);
			$wholesale = wholesale_info($id);

			if (empty($wholesale)) {
				sys_msg($_LANG['wholesale_not_exist']);
			}

			$smarty->assign('attr_list', get_goods_attr($wholesale['goods_id']));
		}

		if (empty($wholesale['price_list'])) {
			$wholesale['price_list'] = array(
	array(
		'attr'    => array(),
		'qp_list' => array(
			array('quantity' => 0, 'price' => 0)
			)
		)
	);
		}

		$smarty->assign('wholesale', $wholesale);
		$user_rank_list = array();
		$sql = 'SELECT rank_id, rank_name FROM ' . $ecs->table('user_rank') . ' ORDER BY special_rank, min_points';
		$res = $db->query($sql);

		while ($rank = $db->fetchRow($res)) {
			if (!empty($wholesale['rank_ids']) && (strpos($wholesale['rank_ids'], $rank['rank_id']) !== false)) {
				$rank['checked'] = 1;
			}

			$user_rank_list[] = $rank;
		}

		$smarty->assign('user_rank_list', $user_rank_list);
		set_default_filter();

		if ($is_add) {
			$smarty->assign('ur_here', $_LANG['add_wholesale']);
		}
		else {
			$smarty->assign('ur_here', $_LANG['edit_wholesale']);
		}

		$href = 'wholesale.php?act=list';

		if (!$is_add) {
			$href .= '&' . list_link_postfix();
		}

		$smarty->assign('action_link', array('href' => $href, 'text' => $_LANG['wholesale_list']));
		$smarty->assign('ru_id', $adminru['ru_id']);
		assign_query_info();
		$smarty->display('wholesale_info.dwt');
	}
	else {
		if (($_REQUEST['act'] == 'insert') || ($_REQUEST['act'] == 'update')) {
			admin_priv('whole_sale');
			$is_add = $_REQUEST['act'] == 'insert';
			$goods_id = intval($_POST['goods_id']);

			if ($goods_id <= 0) {
				sys_msg($_LANG['pls_search_goods']);
			}

			$sql = 'SELECT goods_name FROM ' . $ecs->table('goods') . ' WHERE goods_id = \'' . $goods_id . '\'';
			$goods_name = $db->getOne($sql);
			$goods_name = addslashes($goods_name);

			if (is_null($goods_name)) {
				sys_msg('invalid goods id: ' . $goods_id);
			}

			if (!isset($_POST['rank_id'])) {
				sys_msg($_LANG['pls_set_user_rank']);
			}

			if (isset($_POST['rank_id'])) {
				foreach ($_POST['rank_id'] as $rank_id) {
					$sql = 'SELECT COUNT(*) FROM ' . $ecs->table('wholesale') . ' WHERE goods_id = \'' . $goods_id . '\' ' . ' AND CONCAT(\',\', rank_ids, \',\') LIKE CONCAT(\'%,\', \'' . $rank_id . '\', \',%\')';

					if (!$is_add) {
						$sql .= ' AND act_id <> \'' . $_POST['id'] . '\'';
					}

					if (0 < $db->getOne($sql)) {
						sys_msg($_LANG['user_rank_exist']);
					}
				}
			}

			$sql = 'SELECT a.attr_id ' . 'FROM ' . $ecs->table('goods') . ' AS g, ' . $ecs->table('attribute') . ' AS a ' . 'WHERE g.goods_id = \'' . $goods_id . '\' ' . 'AND g.goods_type = a.cat_id ' . 'AND a.attr_type = 1';
			$attr_id_list = $db->getCol($sql);
			$prices = array();
			$key_list = array_keys($_POST['quantity']);

			foreach ($key_list as $key) {
				$attr = array();

				foreach ($attr_id_list as $attr_id) {
					if ($_POST['attr_' . $attr_id][$key] != 0) {
						$attr[$attr_id] = $_POST['attr_' . $attr_id][$key];
					}
				}

				$attr_error = false;

				if (!empty($attr)) {
					$_attr = $attr;
					ksort($_attr);
					$goods_attr = implode('|', $_attr);
					$sql = 'SELECT product_id FROM ' . $ecs->table('products') . ' WHERE goods_attr = \'' . $goods_attr . '\' AND goods_id = \'' . $goods_id . '\'';

					if (!$db->getOne($sql)) {
						$attr_error = true;
						continue;
					}
				}

				$qp_list = array();

				foreach ($_POST['quantity'][$key] as $index => $quantity) {
					$quantity = intval($quantity);
					$price = floatval($_POST['price'][$key][$index]);
					if (($quantity <= 0) || ($price <= 0) || isset($qp_list[$quantity])) {
						continue;
					}

					$qp_list[$quantity] = $price;
				}

				ksort($qp_list);
				$arranged_qp_list = array();

				foreach ($qp_list as $quantity => $price) {
					$arranged_qp_list[] = array('quantity' => $quantity, 'price' => $price);
				}

				if ($arranged_qp_list) {
					$prices[] = array('attr' => $attr, 'qp_list' => $arranged_qp_list);
				}
			}

			$wholesale = array('act_id' => intval($_POST['id']), 'goods_id' => $goods_id, 'goods_name' => $goods_name, 'rank_ids' => isset($_POST['rank_id']) ? join(',', $_POST['rank_id']) : '', 'prices' => serialize($prices), 'enabled' => empty($_POST['enabled']) ? 0 : 1);

			if ($is_add) {
				$wholesale['user_id'] = $adminru['ru_id'];
				$db->autoExecute($ecs->table('wholesale'), $wholesale, 'INSERT');
				$wholesale['act_id'] = $db->insert_id();
			}
			else {
				$db->autoExecute($ecs->table('wholesale'), $wholesale, 'UPDATE', 'act_id = \'' . $wholesale['act_id'] . '\'');
			}

			if ($is_add) {
				admin_log($wholesale['goods_name'], 'add', 'wholesale');
			}
			else {
				admin_log($wholesale['goods_name'], 'edit', 'wholesale');
			}

			clear_cache_files();

			if ($attr_error) {
				$links = array(
					array('href' => 'wholesale.php?act=list', 'text' => $_LANG['back_wholesale_list'])
					);
				sys_msg(sprintf($_LANG['save_wholesale_falid'], $wholesale['goods_name']), 1, $links);
			}

			if ($is_add) {
				$links = array(
					array('href' => 'wholesale.php?act=add', 'text' => $_LANG['continue_add_wholesale']),
					array('href' => 'wholesale.php?act=list', 'text' => $_LANG['back_wholesale_list'])
					);
				sys_msg($_LANG['add_wholesale_ok'], 0, $links);
			}
			else {
				$links = array(
					array('href' => 'wholesale.php?act=list&' . list_link_postfix(), 'text' => $_LANG['back_wholesale_list'])
					);
				sys_msg($_LANG['edit_wholesale_ok'], 0, $links);
			}
		}
		else if ($_REQUEST['act'] == 'search_goods') {
			check_authz_json('whole_sale');
			include_once ROOT_PATH . 'includes/cls_json.php';
			$json = new JSON();
			$filter = $json->decode($_GET['JSON']);
			$arr = get_goods_list($filter);

			if (empty($arr)) {
				$arr[0] = array('goods_id' => 0, 'goods_name' => $_LANG['search_result_empty']);
			}

			make_json_result($arr);
		}
		else if ($_REQUEST['act'] == 'get_goods_info') {
			include_once ROOT_PATH . 'includes/cls_json.php';
			$json = new JSON();
			$goods_id = intval($_REQUEST['goods_id']);
			$goods_attr_list = array_values(get_goods_attr($goods_id));

			if (!empty($goods_attr_list)) {
				foreach ($goods_attr_list as $goods_attr_key => $goods_attr_value) {
					if (isset($goods_attr_value['goods_attr_list']) && !empty($goods_attr_value['goods_attr_list'])) {
						foreach ($goods_attr_value['goods_attr_list'] as $key => $value) {
							$goods_attr_list[$goods_attr_key]['goods_attr_list']['c' . $key] = $value;
							unset($goods_attr_list[$goods_attr_key]['goods_attr_list'][$key]);
						}
					}
				}
			}

			echo $json->encode($goods_attr_list);
		}
	}
}

?>
