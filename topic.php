<?php
//dezend by  QQ:2172298892
define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';

if ((DEBUG_MODE & 2) != 2) {
	$smarty->caching = true;
}

require ROOT_PATH . '/includes/lib_area.php';
$topic_id = (empty($_REQUEST['topic_id']) ? 0 : intval($_REQUEST['topic_id']));
$sql = 'SELECT template FROM ' . $ecs->table('topic') . 'WHERE topic_id = \'' . $topic_id . '\' and  ' . gmtime() . ' >= start_time and ' . gmtime() . '<= end_time';
$topic = $db->getRow($sql);

if (empty($topic)) {
	ecs_header("Location: ./\n");
	exit();
}

$templates = (empty($topic['template']) ? 'topic.dwt' : $topic['template']);
$cache_id = sprintf('%X', crc32($_SESSION['user_rank'] . '-' . $_CFG['lang'] . '-' . $topic_id));

if (!$smarty->is_cached($templates, $cache_id)) {
	$sql = 'SELECT * FROM ' . $ecs->table('topic') . ' WHERE topic_id = \'' . $topic_id . '\'';
	$topic = $db->getRow($sql);
	$topic['data'] = addcslashes($topic['data'], '\'');
	$tmp = @unserialize($topic['data']);
	$arr = (array) $tmp;
	$goods_id = array();

	foreach ($arr as $key => $value) {
		foreach ($value as $k => $val) {
			$opt = explode('|', $val);
			$arr[$key][$k] = $opt[1];
			$goods_id[] = $opt[1];
		}
	}

	require ROOT_PATH . '/includes/lib_area.php';
	$area_info = get_area_info($province_id);
	$area_id = $area_info['region_id'];
	$where = 'regionId = \'' . $province_id . '\'';
	$date = array('parent_id');
	$region_id = get_table_date('region_warehouse', $where, $date, 2);
	$leftJoin = '';
	$leftJoin .= ' left join ' . $GLOBALS['ecs']->table('warehouse_goods') . ' as wg on g.goods_id = wg.goods_id and wg.region_id = \'' . $region_id . '\' ';
	$leftJoin .= ' left join ' . $GLOBALS['ecs']->table('warehouse_area_goods') . ' as wag on g.goods_id = wag.goods_id and wag.region_id = \'' . $area_id . '\' ';
	$sql = 'SELECT g.goods_id, g.goods_name, g.goods_name_style, g.market_price, g.is_new, g.is_best, g.is_hot, g.shop_price AS org_price, ' . 'IFNULL(mp.user_price, IF(g.model_price < 1, g.shop_price, IF(g.model_price < 2, wg.warehouse_price, wag.region_price)) * \'' . $_SESSION['discount'] . '\') AS shop_price, ' . 'IF(g.model_price < 1, g.promote_price, IF(g.model_price < 2, wg.warehouse_promote_price, wag.region_promote_price)) as promote_price, ' . 'g.promote_start_date, g.promote_end_date, g.goods_brief, g.goods_thumb , g.goods_img ' . 'FROM ' . $GLOBALS['ecs']->table('goods') . ' AS g ' . $leftJoin . 'LEFT JOIN ' . $GLOBALS['ecs']->table('member_price') . ' AS mp ' . 'ON mp.goods_id = g.goods_id AND mp.user_rank = \'' . $_SESSION['user_rank'] . '\' ' . 'WHERE ' . db_create_in($goods_id, 'g.goods_id');
	$res = $GLOBALS['db']->query($sql);
	$sort_goods_arr = array();

	while ($row = $GLOBALS['db']->fetchRow($res)) {
		if (0 < $row['promote_price']) {
			$promote_price = bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
			$row['promote_price'] = 0 < $promote_price ? price_format($promote_price) : '';
		}
		else {
			$row['promote_price'] = '';
		}

		if (0 < $row['shop_price']) {
			$row['shop_price'] = price_format($row['shop_price']);
		}
		else {
			$row['shop_price'] = price_format(0);
		}

		$row['url'] = build_uri('goods', array('gid' => $row['goods_id']), $row['goods_name']);
		$row['goods_style_name'] = add_style($row['goods_name'], $row['goods_name_style']);
		$row['short_name'] = 0 < $GLOBALS['_CFG']['goods_name_length'] ? sub_str($row['goods_name'], $GLOBALS['_CFG']['goods_name_length']) : $row['goods_name'];
		$row['goods_thumb'] = get_image_path($row['goods_id'], $row['goods_thumb'], true);
		$row['short_style_name'] = add_style($row['short_name'], $row['goods_name_style']);

		foreach ($arr as $key => $value) {
			foreach ($value as $val) {
				if ($val == $row['goods_id']) {
					$key = ($key == 'default' ? $_LANG['all_goods'] : $key);
					$sort_goods_arr[$key][] = $row;
				}
			}
		}
	}

	assign_template();
	$position = assign_ur_here(0, $topic['title']);
	$smarty->assign('page_title', $position['title']);
	$smarty->assign('ur_here', $position['ur_here']);
	$smarty->assign('helps', get_shop_help());
	$smarty->assign('show_marketprice', $_CFG['show_marketprice']);
	$smarty->assign('sort_goods_arr', $sort_goods_arr);
	$smarty->assign('topic', $topic);
	$smarty->assign('keywords', $topic['keywords']);
	$smarty->assign('description', $topic['description']);
	$smarty->assign('title_pic', $topic['title_pic']);
	$smarty->assign('base_style', '#' . $topic['base_style']);
	$categories_pro = get_category_tree_leve_one();
	$smarty->assign('categories_pro', $categories_pro);
	$template_file = (empty($topic['template']) ? 'topic.dwt' : $topic['template']);
}

$smarty->display($templates, $cache_id);

?>
