<?php
//zend  QQ:2172298892
function fromat_coupons($cou_data)
{
	$time = gmtime();

	foreach ($cou_data as $k => $v) {
		if (!isset($v['cou_surplus'])) {
			$cou_data[$k]['cou_surplus'] = 100;
		}

		if (!empty($v['cou_goods'])) {
			$cou_data[$k]['cou_goods_name'] = $GLOBALS['db']->getAll('SELECT goods_id,goods_name,goods_thumb FROM ' . $GLOBALS['ecs']->table('goods') . ' WHERE goods_id IN(' . $v['cou_goods'] . ')');
		}

		if (!empty($v['cou_ok_user'])) {
			$cou_data[$k]['cou_ok_user_name'] = $GLOBALS['db']->getOne('SELECT group_concat(rank_name)  FROM ' . $GLOBALS['ecs']->table('user_rank') . ' WHERE rank_id IN(' . $v['cou_ok_user'] . ')');
		}

		if ($v['ru_id']) {
			$store_info = get_shop_name($v['ru_id']);
			$cou_data[$k]['store_name'] = '限' . $store_info['shop_name'] . '可用';
		}
		else {
			$cou_data[$k]['store_name'] = '全平台可用';
		}

		$cou_data[$k]['cou_start_time_format'] = local_date('Y/m/d', $v['cou_start_time']);
		$cou_data[$k]['cou_end_time_format'] = local_date('Y/m/d', $v['cou_end_time']);

		if ($v['cou_end_time'] < $time) {
			$cou_data[$k]['is_overdue'] = 1;
		}
		else {
			$cou_data[$k]['is_overdue'] = 0;
		}

		$cou_data[$k]['cou_type_name'] = $v['cou_type'] == 3 ? '全场券' : ($v['cou_type'] == 4 ? '会员券' : '未知');

		if ($_SESSION['user_id']) {
			$r = $GLOBALS['db']->getOne('SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('coupons_user') . ' WHERE cou_id=\'' . $v['cou_id'] . '\' AND user_id =\'' . $_SESSION['user_id'] . '\'');
			$cou_data[$k]['cou_is_receive'] = $r ? 1 : 0;
		}
	}

	return $cou_data;
}

define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';
require ROOT_PATH . 'includes/cls_json.php';
require ROOT_PATH . '/includes/lib_area.php';
$area_info = get_area_info($province_id);
$area_id = $area_info['region_id'];
$where = 'regionId = \'' . $province_id . '\'';
$date = array('parent_id');
$region_id = get_table_date('region_warehouse', $where, $date, 2);
$user_id = (!empty($_SESSION['user_id']) ? $_SESSION['user_id'] : 0);
assign_template();
$smarty->assign('helps', get_shop_help());
$categories_pro = get_category_tree_leve_one();
$smarty->assign('categories_pro', $categories_pro);
$smarty->assign('navigator_list', get_navigator($ctype, $catlist));
$time = gmtime();

if ($_REQUEST['act'] == 'coupons_index') {
	for ($i = 1; $i <= $_CFG['auction_ad']; $i++) {
		$coupons_index .= '\'coupons_index' . $i . ',';
	}

	$smarty->assign('coupons_index', $coupons_index);
	$time = gmtime();
	$sql = 'SELECT c.cou_id,FLOOR((c.cou_total-COUNT(cu.cou_id))/c.cou_total*100) cou_surplus FROM ' . $ecs->table('coupons_user') . ' cu LEFT JOIN ' . $ecs->table('coupons') . ' c ON c.cou_id=cu.cou_id  WHERE c.cou_type NOT IN(1,2) AND c.cou_end_time>' . $time . ' GROUP BY c.cou_id ORDER BY c.cou_id DESC limit 6';
	$cou_surplus = $db->getAll($sql);
	$sql = 'SELECT c.*,cu.user_id,cu.is_use FROM ' . $ecs->table('coupons') . ' c LEFT JOIN ' . $ecs->table('coupons_user') . ' cu ON c.cou_id=cu.cou_id WHERE c.cou_type  NOT IN(1,2) AND c.cou_end_time>' . $time . ' GROUP BY c.cou_id ORDER BY c.cou_id DESC limit 6';
	$cou_data = $db->getAll($sql);

	foreach ($cou_data as $k => $v) {
		foreach ($cou_surplus as $m => $n) {
			if ($v['cou_id'] == $n['cou_id']) {
				$cou_data[$k]['cou_surplus'] = $n['cou_surplus'];
			}
		}
	}

	$cou_data = fromat_coupons($cou_data);
	$seckill = $cou_data;

	foreach ($seckill as $k => $v) {
		if ($v['cou_goods']) {
			$sort_arr[] = $v['cou_order'];
		}
		else {
			$seckill[$k]['cou_goods_name'][0]['goods_thumb'] = 'images/coupons_default.png';
		}
	}

	array_multisort($sort_arr, SORT_DESC, $seckill);
	$seckill = array_slice($seckill, 0, 4);
	$sql = 'SELECT * FROM ' . $ecs->table('coupons') . ' where cou_type  IN(2) AND cou_end_time>' . $time . ' limit 4';
	$cou_goods = $db->getAll($sql);

	foreach ($cou_goods as $k => $v) {
		if ($v['cou_ok_goods']) {
			$cou_goods[$k]['cou_ok_goods_name'] = $db->getAll('SELECT goods_id,goods_name,goods_thumb FROM ' . $ecs->table('goods') . ' WHERE goods_id IN(' . $v['cou_ok_goods'] . ')');
		}
		else {
			$cou_goods[$k]['cou_ok_goods_name'][0]['goods_thumb'] = 'images/coupons_default.png';
		}

		$cou_goods[$k]['cou_end_time_format'] = local_date('Y-m-d H:i:s', $v['cou_end_time']);
	}

	if ($_SESSION['user_id']) {
		foreach ($cou_data as $k => $v) {
			$cou_data[$k]['is_use'] = $db->getOne('SELECT is_use FROM' . $ecs->table('coupons_user') . 'WHERE cou_id=\'' . $v['cou_id'] . '\' AND user_id=\'' . $_SESSION['user_id'] . '\' ORDER BY uc_id DESC LIMIT 1');
		}
	}

	$smarty->assign('seckill', $seckill);
	$smarty->assign('cou_goods', $cou_goods);
	$smarty->assign('cou_data', $cou_data);
	$smarty->assign('page_title', '首页-领券中心');
	$smarty->display('coupons_index.dwt');
}

if ($_REQUEST['act'] == 'coupons_list') {
	$order_field = (empty($_REQUEST['field']) ? 'c.cou_id' : 'c.' . $_REQUEST['field']);

	if (!empty($_REQUEST['type'])) {
		if ($_REQUEST['type'] == 'all') {
			$where = ' AND cou_type = 3 ';
		}
		else if ($_REQUEST['type'] == 'member') {
			$where = ' AND cou_type = 4 ';
		}
		else {
			$where = ' ';
		}
	}
	else {
		$where = ' ';
	}

	$time = gmtime();
	$sql = 'SELECT c.cou_id,FLOOR((c.cou_total-COUNT(cu.cou_id))/c.cou_total*100) cou_surplus FROM ' . $ecs->table('coupons_user') . ' cu LEFT JOIN ' . $ecs->table('coupons') . ' c ON c.cou_id=cu.cou_id  WHERE c.cou_type NOT IN(1,2) AND c.cou_end_time>' . $time . ' GROUP BY c.cou_id limit 6';
	$cou_surplus = $db->getAll($sql);
	$sql = 'SELECT COUNT(c.cou_id) FROM ' . $ecs->table('coupons') . ' c  where c.cou_type  NOT IN(1,2) AND c.cou_end_time>' . $time . ' ' . $where . ' ';
	$cou_row_total = $db->getOne($sql);
	$row_num = 12;
	$page_total = ceil($cou_row_total / $row_num);
	$page = (empty($_REQUEST['p']) || ($page_total < $_REQUEST['p']) ? 1 : $_REQUEST['p']);
	$offset = ($page - 1) * $row_num;
	$sql = 'SELECT c.*,cu.user_id,cu.is_use FROM ' . $ecs->table('coupons') . ' c LEFT JOIN ' . $ecs->table('coupons_user') . ' cu ON c.cou_id=cu.cou_id WHERE c.cou_type  NOT IN(1,2) AND c.cou_end_time>' . $time . ' ' . $where . '  GROUP BY c.cou_id  ORDER BY ' . $order_field . ' DESC limit ' . $offset . ' , ' . $row_num . '';
	$cou_data = $db->getAll($sql);

	foreach ($cou_data as $k => $v) {
		foreach ($cou_surplus as $m => $n) {
			if ($v['cou_id'] == $n['cou_id']) {
				$cou_data[$k]['cou_surplus'] = $n['cou_surplus'];
			}
		}
	}

	$cou_data = fromat_coupons($cou_data);

	if ($_SESSION['user_id']) {
		foreach ($cou_data as $k => $v) {
			$cou_data[$k]['is_use'] = $db->getOne('SELECT is_use FROM' . $ecs->table('coupons_user') . 'WHERE cou_id=\'' . $v['cou_id'] . '\' AND user_id=\'' . $_SESSION['user_id'] . '\' ORDER BY uc_id DESC LIMIT 1');
		}
	}

	for ($i = 1; $i <= $page_total; $i++) {
		$page_total2[] = $i;
	}

	$page_url = (strstr($_SERVER['QUERY_STRING'], '&p', true) ? strstr($_SERVER['QUERY_STRING'], '&p', true) : $_SERVER['QUERY_STRING']);
	$smarty->assign('page_total2', $page_total2);
	$smarty->assign('page_total', $page_total);
	$smarty->assign('page', $page);
	$smarty->assign('prev_page', $page == 1 ? 1 : $page - 1);
	$smarty->assign('next_page', $page == $page_total ? $page_total : $page + 1);
	$smarty->assign('page_url', $page_url);
	$smarty->assign('cou_data', $cou_data);
	$smarty->assign('page_title', '领券中心-好券集市');
	$smarty->display('coupons_list.dwt');
}

if ($_REQUEST['act'] == 'coupons_goods') {
	$time = gmtime();
	$sql = 'SELECT COUNT(*) FROM ' . $ecs->table('coupons') . ' where cou_type IN(2) AND cou_end_time>' . $time;
	$cou_row_total = $db->getoNE($sql);
	$row_num = 10;
	$page_total = ceil($cou_row_total / $row_num);
	$page = (empty($_REQUEST['p']) || ($page_total < $_REQUEST['p']) ? 1 : $_REQUEST['p']);
	$offset = ($page - 1) * $row_num;
	$sql = 'SELECT * FROM ' . $ecs->table('coupons') . ' where cou_type IN(2) AND cou_end_time>' . $time . ' limit ' . $offset . ' , ' . $row_num . '';
	$cou_goods = $db->getAll($sql);

	foreach ($cou_goods as $k => $v) {
		if ($v['cou_ok_goods']) {
			$cou_goods[$k]['cou_ok_goods_name'] = $db->getAll('SELECT goods_id,goods_name,goods_thumb FROM ' . $ecs->table('goods') . ' WHERE goods_id IN(' . $v['cou_ok_goods'] . ')');
		}
		else {
			$cou_goods[$k]['cou_ok_goods_name'][0]['goods_thumb'] = 'images/coupons_default.png';
		}

		$cou_goods[$k]['cou_end_time_format'] = local_date('Y-m-d H:i:s', $v['cou_end_time']);
	}

	for ($i = 1; $i <= $page_total; $i++) {
		$page_total2[] = $i;
	}

	$page_url = (strstr($_SERVER['QUERY_STRING'], '&p', true) ? strstr($_SERVER['QUERY_STRING'], '&p', true) : $_SERVER['QUERY_STRING']);
	$smarty->assign('page_total2', $page_total2);
	$smarty->assign('page_total', $page_total);
	$smarty->assign('page', $page);
	$smarty->assign('prev_page', $page == 1 ? 1 : $page - 1);
	$smarty->assign('next_page', $page == $page_total ? $page_total : $page + 1);
	$smarty->assign('page_url', $page_url);
	$smarty->assign('cou_goods', $cou_goods);
	$smarty->assign('page_title', '领券中心-任务集市');
	$smarty->display('coupons_goods.dwt');
}

if ($_REQUEST['act'] == 'coupons_receive') {
	$cou_id = (!empty($_REQUEST['cou_id']) ? intval($_REQUEST['cou_id']) : 0);
	$sql = 'SELECT c.*,c.cou_total-COUNT(cu.cou_id) cou_surplus FROM ' . $ecs->table('coupons') . ' c LEFT JOIN ' . $ecs->table('coupons_user') . ' cu ON c.cou_id = cu.cou_id GROUP BY c.cou_id  HAVING cou_surplus > 0 AND  c.cou_id = \'' . $cou_id . '\' AND c.cou_end_time > ' . $time . ' LIMIT 1';
	$cou_data = $db->getRow($sql);

	if (!$cou_data) {
		exit(json_encode(array('status' => 'error', 'msg' => $_LANG['lang_coupons_receive_failure'])));
	}

	$sql = 'SELECT COUNT(cou_id) FROM ' . $ecs->table('coupons_user') . ' WHERE user_id = \'' . $user_id . '\' AND cou_id = \'' . $cou_id . '\'';
	$cou_user_num = $db->getOne($sql);

	if ($cou_data['cou_user_num'] <= $cou_user_num) {
		exit(json_encode(array('status' => 'error', 'msg' => sprintf($_LANG['lang_coupons_user_receive'], $cou_data['cou_user_num']))));
	}

	if (strpos(',' . $cou_data['cou_ok_user'] . ',', ',' . $_SESSION['user_rank'] . ',') === false) {
		$rank_name = $db->getOne('SELECT GROUP_CONCAT(rank_name) FROM ' . $ecs->table('user_rank') . ' WHERE rank_id IN(' . $cou_data['cou_ok_user'] . ')');
		exit(json_encode(array('status' => 'error', 'msg' => sprintf($_LANG['lang_coupons_user_rank'], $rank_name))));
	}

	$uc_sn = $time . rand(10, 99);
	$sql = 'INSERT INTO ' . $ecs->table('coupons_user') . ' (`user_id`,`cou_id`,`uc_sn`) VALUES (' . $user_id . ',' . $cou_id . ',\'' . $uc_sn . '\') ';

	if ($db->query($sql)) {
		exit(json_encode(array('status' => 'ok', 'msg' => $_LANG['lang_coupons_receive_succeed'])));
	}
}

?>
