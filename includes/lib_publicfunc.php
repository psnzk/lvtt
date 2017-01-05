<?php
//zend by QQ:2172298892
function get_warehouse_area_attr_price_insert($warehouse_area, $goods_id, $goods_attr_id, $table)
{
	$arr = array();

	if (is_array($warehouse_area)) {
		for ($i = 0; $i < count($warehouse_area); $i++) {
			if (!empty($warehouse_area[$i])) {
				$parent = array('goods_id' => $goods_id, 'goods_attr_id' => $goods_attr_id);

				if ($table == 'warehouse_attr') {
					$where = ' AND warehouse_id = \'' . $warehouse_area[$i] . '\'';
					$parent['warehouse_id'] = $warehouse_area[$i];
					$parent['attr_price'] = $_POST['attr_price_' . $warehouse_area[$i]];
				}
				else if ($table == 'warehouse_area_attr') {
					$where = ' AND area_id = \'' . $warehouse_area[$i] . '\'';
					$parent['area_id'] = $warehouse_area[$i];
					$parent['attr_price'] = $_POST['attrPrice_' . $warehouse_area[$i]];
				}

				$sql = 'SELECT id FROM ' . $GLOBALS['ecs']->table($table) . ' WHERE goods_id = \'' . $goods_id . '\' AND goods_attr_id = \'' . $goods_attr_id . '\' ' . $where;
				$id = $GLOBALS['db']->getOne($sql);

				if (0 < $id) {
					$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table($table), $parent, 'UPDATE', 'goods_id = \'' . $goods_id . '\' and goods_attr_id = \'' . $goods_attr_id . '\' ' . $where);
				}
				else {
					$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table($table), $parent, 'INSERT');
				}
			}
		}
	}
	else if (is_array($goods_attr_id)) {
		for ($i = 0; $i < count($goods_attr_id); $i++) {
			if (!empty($goods_attr_id[$i])) {
				$parent = array('goods_id' => $goods_id, 'goods_attr_id' => $goods_attr_id[$i]);

				if ($table == 'warehouse_attr') {
					$where = ' AND warehouse_id = \'' . $warehouse_area . '\'';
					$parent['warehouse_id'] = $warehouse_area;
					$parent['attr_price'] = $_POST['attr_price_' . $goods_attr_id[$i]];
				}
				else if ($table == 'warehouse_area_attr') {
					$where = ' AND area_id = \'' . $warehouse_area . '\'';
					$parent['area_id'] = $warehouse_area;
					$parent['attr_price'] = $_POST['attrPrice_' . $goods_attr_id[$i]];
				}

				$sql = 'SELECT id FROM ' . $GLOBALS['ecs']->table($table) . ' WHERE goods_id = \'' . $goods_id . '\' AND goods_attr_id = \'' . $goods_attr_id[$i] . '\' ' . $where;
				$id = $GLOBALS['db']->getOne($sql);

				if (0 < $id) {
					$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table($table), $parent, 'UPDATE', 'goods_id = \'' . $goods_id . '\' and goods_attr_id = \'' . $goods_attr_id[$i] . '\' ' . $where);
				}
				else {
					$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table($table), $parent, 'INSERT');
				}
			}
		}
	}
}

function get_seller_grade_rank($ru_id)
{
	$sql = 'SELECT sg.goods_sun, sg.seller_temp, sg.favorable_rate, sg.give_integral, sg.rank_integral, sg.pay_integral FROM ' . $GLOBALS['ecs']->table('merchants_grade') . ' AS mg, ' . $GLOBALS['ecs']->table('seller_grade') . ' AS sg ' . ' WHERE mg.grade_id = sg.id AND ru_id = \'' . $ru_id . '\' LIMIT 1';
	$res = $GLOBALS['db']->getRow($sql);
	$res['favorable_rate'] = !empty($res['favorable_rate']) ? $res['favorable_rate'] / 100 : 1;
	$res['give_integral'] = !empty($res['give_integral']) ? $res['give_integral'] / 100 : 1;
	$res['rank_integral'] = !empty($res['rank_integral']) ? $res['rank_integral'] / 100 : 1;
	$res['pay_integral'] = !empty($res['pay_integral']) ? $res['pay_integral'] / 100 : 1;
	return $res;
}

function get_account_log_list($ru_id, $type = 0)
{
	require_once ROOT_PATH . 'includes/lib_order.php';
	$result = get_filter();

	if ($result === false) {
		$filter['keywords'] = !isset($_REQUEST['keywords']) ? '' : trim($_REQUEST['keywords']);
		if (isset($_REQUEST['is_ajax']) && ($_REQUEST['is_ajax'] == 1)) {
			$filter['keywords'] = json_str_iconv($filter['keywords']);
		}

		$filter['order_sn'] = !isset($_REQUEST['order_sn']) ? '' : trim($_REQUEST['order_sn']);
		$filter['out_up'] = !isset($_REQUEST['out_up']) ? 0 : intval($_REQUEST['out_up']);
		$filter['log_type'] = !isset($_REQUEST['log_type']) ? 0 : intval($_REQUEST['log_type']);
		$filter['handler'] = !isset($_REQUEST['handler']) ? 0 : intval($_REQUEST['handler']);
		$filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'sal.log_id' : trim($_REQUEST['sort_by']);
		$filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
		$filter['act_type'] = !isset($_REQUEST['act_type']) ? 'detail' : $_REQUEST['act_type'];
		$filter['ru_id'] = !isset($_REQUEST['ru_id']) ? $ru_id : intval($_REQUEST['ru_id']);
		$ex_where = ' WHERE 1 ';

		if ($filter['order_sn']) {
			$ex_where .= ' AND (sal.apply_sn = \'' . $filter['order_sn'] . '\'';
			$ex_where .= ' OR ';
			$ex_where .= ' (SELECT order_sn FROM ' . $GLOBALS['ecs']->table('order_info') . ' AS oi WHERE sal.order_id = oi.order_id LIMIT 1) = \'' . $filter['order_sn'] . '\')';
		}

		if ($filter['out_up']) {
			if ($filter['out_up'] != 4) {
				$ex_where .= ' AND (sal.log_type > \'' . $filter['out_up'] . '\' OR sal.log_type =  \'' . $filter['out_up'] . '\')';
			}
			else {
				$ex_where .= ' AND sal.log_type = \'' . $filter['out_up'] . '\'';
			}
		}

		if ($filter['handler']) {
			if ($filter['handler'] == 1) {
				$ex_where .= ' AND sal.is_paid = 1';
			}
			else {
				$ex_where .= ' AND sal.is_paid = 0';
			}
		}

		if ($filter['log_type']) {
			$ex_where .= ' AND sal.log_type = \'' . $filter['log_type'] . '\'';
		}

		$filter['store_search'] = empty($_REQUEST['store_search']) ? 0 : intval($_REQUEST['store_search']);
		$filter['merchant_id'] = isset($_REQUEST['merchant_id']) ? intval($_REQUEST['merchant_id']) : 0;
		$filter['store_keyword'] = isset($_REQUEST['store_keyword']) ? trim($_REQUEST['store_keyword']) : '';
		$store_where = '';
		$store_search_where = '';

		if ($filter['store_search'] != 0) {
			if ($ru_id == 0) {
				if ($_REQUEST['store_type']) {
					$store_search_where = 'AND mis.shopNameSuffix = \'' . $_REQUEST['store_type'] . '\'';
				}

				if ($filter['store_search'] == 1) {
					$ex_where .= ' AND mis.user_id = \'' . $filter['merchant_id'] . '\' ';
				}
				else if ($filter['store_search'] == 2) {
					$store_where .= ' AND mis.rz_shopName LIKE \'%' . mysql_like_quote($filter['store_keyword']) . '%\'';
				}
				else if ($filter['store_search'] == 3) {
					$store_where .= ' AND mis.shoprz_brandName LIKE \'%' . mysql_like_quote($filter['store_keyword']) . '%\' ' . $store_search_where;
				}

				if (1 < $filter['store_search']) {
					$ex_where .= ' AND mis.user_id > 0 ' . $store_where . ' ';
				}
			}
		}

		$type = implode(',', $type);

		if ($filter['ru_id']) {
			$ex_where .= ' AND sal.ru_id = \'' . $filter['ru_id'] . '\'';
		}

		$sql = 'SELECT count(*) FROM ' . $GLOBALS['ecs']->table('seller_account_log') . ' AS sal ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('merchants_shop_information') . ' AS mis ON sal.ru_id = mis.user_id ' . ' ' . $ex_where . ' AND sal.log_type IN(' . $type . ')';
		$filter['record_count'] = $GLOBALS['db']->getOne($sql);
		$filter = page_and_size($filter);
		$sql = 'SELECT sal.* FROM ' . $GLOBALS['ecs']->table('seller_account_log') . ' AS sal ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('merchants_shop_information') . ' AS mis ON sal.ru_id = mis.user_id ' . ' ' . $ex_where . ' AND sal.log_type IN(' . $type . ')' . ' ORDER BY ' . $filter['sort_by'] . ' ' . $filter['sort_order'] . ' LIMIT ' . $filter['start'] . ',' . $filter['page_size'];
		$filter['keywords'] = stripslashes($filter['keywords']);
		set_filter($filter, $sql);
	}
	else {
		$sql = $result['sql'];
		$filter = $result['filter'];
	}

	$res = $GLOBALS['db']->getAll($sql);
	$arr = array();

	for ($i = 0; $i < count($res); $i++) {
		$res[$i]['shop_name'] = get_shop_name($res[$i]['ru_id'], 1);
		$order = order_info($res[$i]['order_id']);
		$res[$i]['order_sn'] = $order['order_sn'];
		$res[$i]['amount'] = price_format($res[$i]['amount'], false);
		$res[$i]['add_time'] = local_date($GLOBALS['_CFG']['time_format'], $res[$i]['add_time']);
		$res[$i]['payment_info'] = payment_info($res[$i]['pay_id']);
	}

	$arr = array('log_list' => $res, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
	return $arr;
}

function get_account_log_info($log_id)
{
	$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('seller_account_log') . ' WHERE log_id = \'' . $log_id . '\'';
	$res = $GLOBALS['db']->getRow($sql);

	if ($res) {
		$res['shop_name'] = get_shop_name($res['ru_id'], 1);
		$res['payment_info'] = payment_info($res['pay_id']);
	}

	return $res;
}

function get_seller_category()
{
	$sql = 'SELECT c.*, (SELECT c2.cat_name FROM ' . $GLOBALS['ecs']->table('category') . ' AS c2 WHERE c2.cat_id = c.parent_id LIMIT 1) AS parent_name ' . ' FROM ' . $GLOBALS['ecs']->table('merchants_category') . ' AS mc,' . $GLOBALS['ecs']->table('category') . ' AS c ' . ' WHERE 1 AND mc.cat_id = c.cat_id';
	$res = $GLOBALS['db']->getAll($sql);
	$chid_level = 0;
	$level = 1;
	$arr = array();

	if ($res) {
		foreach ($res as $key => $row) {
			$arr[$key]['cat_id'] = $row['cat_id'];
			$arr[$key]['cat_name'] = $row['cat_name'];
			$arr[$key]['parent_id'] = $row['parent_id'];
			$arr[$key]['keywords'] = $row['keywords'];
			$arr[$key]['cat_desc'] = $row['cat_desc'];
			$arr[$key]['sort_order'] = $row['sort_order'];
			$arr[$key]['measure_unit'] = $row['measure_unit'];
			$arr[$key]['show_in_nav'] = $row['show_in_nav'];
			$arr[$key]['style'] = $row['style'];
			$arr[$key]['grade'] = $row['grade'];
			$arr[$key]['filter_attr'] = $row['filter_attr'];
			$arr[$key]['is_top_style'] = $row['is_top_style'];
			$arr[$key]['top_style_tpl'] = $row['top_style_tpl'];
			$arr[$key]['cat_icon'] = $row['cat_icon'];
			$arr[$key]['is_top_show'] = $row['is_top_show'];
			$arr[$key]['category_links'] = $row['category_links'];
			$arr[$key]['category_topic'] = $row['category_topic'];
			$arr[$key]['pinyin_keyword'] = $row['pinyin_keyword'];
			$arr[$key]['cat_alias_name'] = $row['cat_alias_name'];
			$arr[$key]['template_file'] = $row['template_file'];
			$arr[$key]['parent_name'] = $row['parent_name'];
			$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('merchants_category') . ' WHERE cat_id = \'' . $row['parent_id'] . '\'';

			if ($GLOBALS['db']->getOne($sql, true)) {
				$cat_level = get_seller_cat_level($row['parent_id']);

				if ($cat_level['parent_id'] != 0) {
					$chid = get_seller_cat_level($cat_level['parent_id']);

					if ($chid) {
						$chid_level += 1;
					}
				}

				$arr[$key]['level'] = $level + $chid_level;
			}
			else {
				$arr[$key]['level'] = 0;
			}

			$cat_level = array('一', '二', '三', '四', '五', '六', '气', '八', '九', '十');
			$arr[$key]['belongs'] = $cat_level[$arr[$key]['level']] . '级';

			if ($arr[$key]['level'] == 0) {
				$row['parent_id'] = 0;
			}
		}
	}

	return $arr;
}

function get_seller_cat_level($parent_id = 0, $level = 1)
{
	$sql = 'SELECT c.cat_id, c.cat_name, c.parent_id FROM ' . $GLOBALS['ecs']->table('merchants_category') . ' AS mc,' . $GLOBALS['ecs']->table('category') . ' AS c' . ' WHERE mc.cat_id = c.cat_id AND c.cat_id = \'' . $parent_id . '\' LIMIT 1';
	$row = $GLOBALS['db']->getRow($sql);
	return $row;
}

function get_seller_select_category($cat_id = 0, $relation = 0, $self = true, $user_id = 0)
{
	static $cat_list = array();
	$cat_list[] = intval($cat_id);

	if ($user_id) {
		$where = ' AND user_id = \'' . $user_id . '\'';
	}

	if ($relation == 0) {
		return $cat_list;
	}
	else if ($relation == 1) {
		$sql = ' select parent_id from ' . $GLOBALS['ecs']->table('merchants_category') . ' where cat_id=\'' . $cat_id . '\' ' . $where;
		$parent_id = $GLOBALS['db']->getOne($sql);

		if (!empty($parent_id)) {
			get_seller_select_category($parent_id, $relation, $self, $user_id);
		}

		if ($self == false) {
			unset($cat_list[0]);
		}

		$cat_list[] = 0;
		return array_reverse(array_unique($cat_list));
	}
	else if ($relation == 2) {
		$sql = ' select cat_id from ' . $GLOBALS['ecs']->table('merchants_category') . ' where parent_id=\'' . $cat_id . '\' ' . $where;
		$child_id = $GLOBALS['db']->getCol($sql);

		if (!empty($child_id)) {
			foreach ($child_id as $key => $val) {
				get_seller_select_category($val, $relation, $self, $user_id);
			}
		}

		if ($self == false) {
			unset($cat_list[0]);
		}

		return $cat_list;
	}
}

function get_seller_category_list($cat_id = 0, $relation = 0, $user_id = 0)
{
	$where = '';

	if ($user_id) {
		$where .= ' AND user_id = \'' . $user_id . '\'';
	}

	if ($relation == 0) {
		$parent_id = $GLOBALS['db']->getOne(' SELECT parent_id FROM ' . $GLOBALS['ecs']->table('merchants_category') . ' WHERE cat_id = \'' . $cat_id . '\' ' . $where);
	}
	else if ($relation == 1) {
		$parent_id = $GLOBALS['db']->getOne(' SELECT parent_id FROM ' . $GLOBALS['ecs']->table('merchants_category') . ' WHERE cat_id = \'' . $cat_id . '\' ' . $where);
	}
	else if ($relation == 2) {
		$parent_id = $cat_id;
	}

	$parent_id = (empty($parent_id) ? 0 : $parent_id);
	$category_list = $GLOBALS['db']->getAll(' SELECT cat_id, cat_name FROM ' . $GLOBALS['ecs']->table('merchants_category') . ' WHERE parent_id = \'' . $parent_id . '\' ' . $where);

	foreach ($category_list as $key => $val) {
		if ($cat_id == $val['cat_id']) {
			$is_selected = 1;
		}
		else {
			$is_selected = 0;
		}

		$category_list[$key]['is_selected'] = $is_selected;
	}

	return $category_list;
}

function set_default_filter($goods_id = 0, $cat_id = 0, $user_id = 0, $cat_type_show = 0, $table = 'category')
{
	if ($cat_id) {
		$parent_cat_list = get_select_category($cat_id, 1, true, $user_id, $table);
		$filter_category_navigation = get_array_category_info($parent_cat_list, $table);
		$GLOBALS['smarty']->assign('filter_category_navigation', $filter_category_navigation);
	}

	if ($user_id) {
		$seller_shop_cat = seller_shop_cat($user_id);
	}
	else {
		$seller_shop_cat = array();
	}

	$GLOBALS['smarty']->assign('filter_category_list', get_category_list($cat_id, 0, $seller_shop_cat, $user_id, 2, $table));
	$GLOBALS['smarty']->assign('filter_brand_list', search_brand_list($goods_id));
	$GLOBALS['smarty']->assign('cat_type_show', $cat_type_show);
	return true;
}

function set_seller_default_filter($goods_id = 0, $cat_id = 0, $user_id = 0)
{
	if (0 < $cat_id) {
		$seller_parent_cat_list = get_seller_select_category($cat_id, 1, true, $user_id);
		$seller_filter_category_navigation = get_seller_array_category_info($seller_parent_cat_list);
		$GLOBALS['smarty']->assign('seller_filter_category_navigation', $seller_filter_category_navigation);
	}

	$GLOBALS['smarty']->assign('seller_filter_category_list', get_seller_category_list($cat_id, 0, $user_id));
	$GLOBALS['smarty']->assign('seller_cat_type_show', 1);
	return true;
}

function get_seller_every_category($cat_id = 0)
{
	$parent_cat_list = get_seller_category_array($cat_id, 1, true);
	$filter_category_navigation = get_seller_array_category_info($parent_cat_list);
	$cat_nav = '';

	if ($filter_category_navigation) {
		foreach ($filter_category_navigation as $key => $val) {
			if ($key == 0) {
				$cat_nav .= $val['cat_name'];
			}
			else if (0 < $key) {
				$cat_nav .= ' > ' . $val['cat_name'];
			}
		}
	}

	return $cat_nav;
}

function get_seller_category_array($cat_id = 0, $relation = 0, $self = true)
{
	$cat_list[] = intval($cat_id);

	if ($relation == 0) {
		return $cat_list;
	}
	else if ($relation == 1) {
		do {
			$sql = ' select parent_id from ' . $GLOBALS['ecs']->table('merchants_category') . ' where cat_id=\'' . $cat_id . '\' ';
			$parent_id = $GLOBALS['db']->getOne($sql);

			if (!empty($parent_id)) {
				$cat_list[] = $parent_id;
				$cat_id = $parent_id;
			}
		} while (!empty($parent_id));

		if ($self == false) {
			unset($cat_list[0]);
		}

		$cat_list[] = 0;
		return array_reverse(array_unique($cat_list));
	}
	else if ($relation == 2) {
	}
}

function get_seller_array_category_info($arr = array())
{
	if ($arr) {
		$sql = ' SELECT cat_id, cat_name FROM ' . $GLOBALS['ecs']->table('merchants_category') . ' WHERE cat_id ' . db_create_in($arr);
		return $GLOBALS['db']->getAll($sql);
	}
	else {
		return false;
	}
}

function seller_shop_cat($user_id = 0)
{
	$seller_shop_cat = '';

	if ($user_id) {
		$sql = 'SELECT user_shopMain_category FROM ' . $GLOBALS['ecs']->table('merchants_shop_information') . ' WHERE user_id = \'' . $user_id . '\'';
		$seller_shop_cat = $GLOBALS['db']->getOne($sql, true);
	}

	$arr = array();
	$arr['parent'] = '';

	if ($seller_shop_cat) {
		$seller_shop_cat = explode('-', $seller_shop_cat);

		foreach ($seller_shop_cat as $key => $row) {
			if ($row) {
				$cat = explode(':', $row);
				$arr[$key]['cat_id'] = $cat[0];
				$arr[$key]['cat_tree'] = $cat[1];
				$arr['parent'] .= $cat[0] . ',';

				if ($cat[1]) {
					$arr['parent'] .= $cat[1] . ',';
				}
			}
		}
	}

	$arr['parent'] = substr($arr['parent'], 0, -1);
	return $arr;
}

function get_seller_cat_info($cat_id)
{
	$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('merchants_category') . ' WHERE cat_id = \'' . $cat_id . '\' LIMIT 1';
	$row = $GLOBALS['db']->getRow($sql);

	if ($row) {
		$row['is_show_merchants'] = $row['is_show'];
	}

	return $row;
}

function get_admin_goods_info($goods_id = 0, $select = array())
{
	if ($select) {
		$select = implode(',', $select);
	}
	else {
		$select = '*';
	}

	$sql = 'SELECT ' . $select . ' FROM ' . $GLOBALS['ecs']->table('goods') . ' WHERE goods_id = \'' . $goods_id . '\' LIMIT 1';
	$row = $GLOBALS['db']->getRow($sql);

	if ($row['user_cat']) {
		$cat_info = get_seller_cat_info($row['user_cat']);
		$row['user_cat_name'] = $cat_info['cat_name'];
	}

	return $row;
}

function get_every_category($cat_id = 0, $table = 'category')
{
	$parent_cat_list = get_category_array($cat_id, 1, true, $table);
	$filter_category_navigation = get_array_category_info($parent_cat_list, $table);
	$cat_nav = '';

	if ($filter_category_navigation) {
		foreach ($filter_category_navigation as $key => $val) {
			if ($key == 0) {
				$cat_nav .= $val['cat_name'];
			}
			else if (0 < $key) {
				$cat_nav .= ' > ' . $val['cat_name'];
			}
		}
	}

	return $cat_nav;
}

function get_category_array($cat_id = 0, $relation = 0, $self = true, $table = 'category')
{
	$cat_list[] = intval($cat_id);

	if ($relation == 0) {
		return $cat_list;
	}
	else if ($relation == 1) {
		do {
			$sql = ' SELECT parent_id FROM ' . $GLOBALS['ecs']->table($table) . ' WHERE cat_id=\'' . $cat_id . '\' ';
			$parent_id = $GLOBALS['db']->getOne($sql);

			if (!empty($parent_id)) {
				$cat_list[] = $parent_id;
				$cat_id = $parent_id;
			}
		} while (!empty($parent_id));

		if ($self == false) {
			unset($cat_list[0]);
		}

		$cat_list[] = 0;
		return array_reverse(array_unique($cat_list));
	}
	else if ($relation == 2) {
	}
}

function get_array_category_info($arr = array(), $table = 'category')
{
	if ($arr) {
		$arr = get_del_str_comma($arr);
		$sql = ' SELECT cat_id, cat_name FROM ' . $GLOBALS['ecs']->table($table) . ' WHERE cat_id ' . db_create_in($arr);
		$category_list = $GLOBALS['db']->getAll($sql);

		foreach ($category_list as $key => $val) {
			$category_list[$key]['url'] = build_uri($table, array('cid' => $val['cat_id']), $val['cat_name']);
		}

		return $category_list;
	}
	else {
		return false;
	}
}

function get_add_edit_goods_cat_list($goods_id = 0, $cat_id = 0, $table = 'category', $sin_prefix = '', $user_id = 0, $seller_shop_cat = array())
{
	if (empty($sin_prefix)) {
		$select_category_rel = '';
		$select_category_rel .= insert_select_category(0, 0, 0, 'cat_id1', 1, $table, $seller_shop_cat);
		$GLOBALS['smarty']->assign($sin_prefix . 'select_category_rel', $select_category_rel);
	}

	if (empty($sin_prefix)) {
		$select_category_pak = '';
		$select_category_pak .= insert_select_category(0, 0, 0, 'cat_id2', 1, $table, $seller_shop_cat);
		$GLOBALS['smarty']->assign($sin_prefix . 'select_category_pak', $select_category_pak);
	}

	if ($_REQUEST['act'] == 'add') {
		$select_category_html = '';

		if ($sin_prefix) {
			$select_category_html .= insert_seller_select_category(0, 0, 0, 'user_cat', 0, $table, array(), $user_id);
		}
		else {
			$select_category_html .= insert_select_category(0, 0, 0, 'cat_id', 0, $table, $seller_shop_cat);
		}

		$GLOBALS['smarty']->assign($sin_prefix . 'select_category_html', $select_category_html);
	}
	else {
		if (($_REQUEST['act'] == 'edit') || ($_REQUEST['act'] == 'copy')) {
			$goods = get_admin_goods_info($goods_id, array('cat_id', 'user_cat'));
			$select_category_html = '';

			if ($sin_prefix) {
				$parent_cat_list = get_seller_select_category($cat_id, 1, true, $user_id);
				$cat_id = $goods['user_cat'];
			}
			else {
				$parent_cat_list = get_select_category($cat_id, 1, true);
				$cat_id = $goods['cat_id'];
			}

			for ($i = 0; $i < count($parent_cat_list); $i++) {
				if ($sin_prefix) {
					$select_category_html .= insert_seller_select_category(pos($parent_cat_list), next($parent_cat_list), $i, 'user_cat', 0, $table, array(), $user_id);
				}
				else {
					$select_category_html .= insert_select_category(pos($parent_cat_list), next($parent_cat_list), $i, 'cat_id', 0, $table, $seller_shop_cat);
				}
			}

			$GLOBALS['smarty']->assign($sin_prefix . 'select_category_html', $select_category_html);
			$parent_and_rank = (empty($cat_id) ? '0_0' : $cat_id . '_' . (count($parent_cat_list) - 2));
			$GLOBALS['smarty']->assign($sin_prefix . 'parent_and_rank', $parent_and_rank);
		}
	}
}

function get_admin_user_info($id = 0)
{
	$sql = 'SELECT u.user_id, u.email, u.user_name, u.user_money, u.mobile_phone, u.pay_points, nick_name' . ' FROM ' . $GLOBALS['ecs']->table('users') . ' AS u ' . ' WHERE u.user_id = \'' . $id . '\'';
	return $GLOBALS['db']->getRow($sql);
}

function get_dialog_goods_attr_type($attr_id = 0, $goods_id = 0)
{
	$sql = 'SELECT goods_attr_id, attr_id, attr_value FROM ' . $GLOBALS['ecs']->table('goods_attr') . ' WHERE attr_id = \'' . $attr_id . '\' AND goods_id = \'' . $goods_id . '\' ORDER BY attr_sort';
	$res = $GLOBALS['db']->getAll($sql);

	if ($res) {
		foreach ($res as $key => $row) {
			if ($goods_id) {
				$res[$key]['is_selected'] = 1;
			}
			else {
				$res[$key]['is_selected'] = 0;
			}
		}
	}

	return $res;
}

function seller_grade_list()
{
	$sql = 'SELECT user_id FROM ' . $GLOBALS['ecs']->table('merchants_shop_information') . ' WHERE merchants_audit = 1 ORDER BY user_id ASC';
	return $GLOBALS['db']->getAll($sql);
}

function get_pin_regions()
{
	$arr = array();
	$letters = range('A', 'Z');
	$pin_regions = read_static_cache('pin_regions', '/data/sc_file/');

	if ($pin_regions !== false) {
		foreach ($letters as $key => $row) {
			foreach ($pin_regions as $pk => $prow) {
				if ($row == $prow['initial']) {
					$arr[$row][$pk] = $prow;
				}
			}

			if ($arr[$row]) {
				$arr[$row] = get_array_sort($arr[$row], 'region_id');
			}
		}
	}

	ksort($arr);
	return $arr;
}

function get_updel_goods_attr($goods_id = 0)
{
	$admin_id = get_admin_id();

	if ($admin_id) {
		if ($goods_id) {
			$sql = 'UPDATE ' . $GLOBALS['ecs']->table('goods_attr') . ' SET goods_id = \'' . $goods_id . '\' WHERE admin_id = \'' . $admin_id . '\' AND goods_id = 0';
		}
		else {
			$sql = 'DELETE FROM ' . $GLOBALS['ecs']->table('goods_attr') . ' WHERE admin_id = \'' . $admin_id . '\' AND goods_id = 0';
		}

		$GLOBALS['db']->query($sql);
	}
}

function get_goods_attr_nameId($goods_id = 0, $attr_id = 0, $attr_value = '', $select = 'goods_attr_id', $type = 0)
{
	if ($type == 1) {
		$sql = 'SELECT ' . $select . ' FROM ' . $GLOBALS['ecs']->table('goods_attr') . ' WHERE goods_id = \'' . $goods_id . '\' AND goods_attr_id = \'' . $attr_id . '\'';
	}
	else {
		$sql = 'SELECT ' . $select . ' FROM ' . $GLOBALS['ecs']->table('goods_attr') . ' WHERE goods_id = \'' . $goods_id . '\' AND attr_id = \'' . $attr_id . '\' ' . ' AND attr_value = \'' . $attr_value . '\'';
	}

	return $GLOBALS['db']->getOne($sql);
}

function get_seller_info($ru_id = 0, $select = array())
{
	if ($select && is_array($select)) {
		$select = implode(',', $select);
	}
	else {
		$select = '*';
	}

	$sql = 'SELECT ' . $select . ' FROM ' . $GLOBALS['ecs']->table('seller_shopinfo') . ' WHERE ru_id = \'' . $ru_id . '\'';
	return $GLOBALS['db']->getRow($sql);
}

function get_seller_region($region = array(), $ru_id = 0)
{
	if ($region) {
		$sql = 'SELECT concat(IFNULL(p.region_name, \'\'), \'\', IFNULL(t.region_name, \'\'), \'\', IFNULL(d.region_name, \'\')) AS region ' . 'FROM ' . $GLOBALS['ecs']->table('region') . ' AS p, ' . $GLOBALS['ecs']->table('region') . ' AS t, ' . $GLOBALS['ecs']->table('region') . ' AS d ' . 'WHERE p.region_id = \'' . $region['province'] . '\' AND t.region_id = \'' . $region['city'] . '\' AND d.region_id = \'' . $region['district'] . '\'';
	}
	else {
		$sql = 'SELECT concat(IFNULL(p.region_name, \'\'), \'\', IFNULL(t.region_name, \'\'), \'\', IFNULL(d.region_name, \'\'), \'\', IFNULL(s.region_name, \'\')) AS region ' . 'FROM ' . $GLOBALS['ecs']->table('seller_shopinfo') . ' AS ss ' . 'LEFT JOIN ' . $GLOBALS['ecs']->table('region') . ' AS p ON ss.province = p.region_id ' . 'LEFT JOIN ' . $GLOBALS['ecs']->table('region') . ' AS t ON ss.city = t.region_id ' . 'LEFT JOIN ' . $GLOBALS['ecs']->table('region') . ' AS d ON ss.district = d.region_id ' . 'WHERE ss.ru_id = \'' . $ru_id . '\'';
	}

	return $GLOBALS['db']->getOne($sql);
}

function get_goods_unset_attr($goods_id = 0, $attr_arr = array())
{
	$arr = array();

	if ($attr_arr) {
		$where_select = array();

		if (empty($goods_id)) {
			$admin_id = get_admin_id();
			$where_select['admin_id'] = $admin_id;
		}

		$where_select['goods_id'] = $goods_id;

		foreach ($attr_arr as $key => $row) {
			if ($row) {
				$where_select['attr_value'] = $row[0];
				$attr_info = get_goods_attr_id($where_select, array('ga.goods_id', 'ga.attr_value', 'a.attr_id', 'a.attr_type'), 2, 1);
				if ($attr_info && ($row[0] == $attr_info['attr_value'])) {
					unset($row);
				}
				else {
					$arr[$key] = $row;
				}
			}
		}
	}

	return $arr;
}

if (!defined('IN_ECS')) {
	exit('Hacking attempt');
}

?>
