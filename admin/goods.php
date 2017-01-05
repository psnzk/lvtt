<?php
//zend by QQ:2172298892
function list_link($is_add = true, $extension_code = '')
{
	$href = 'goods.php?act=list';

	if (!empty($extension_code)) {
		$href .= '&extension_code=' . $extension_code;
	}

	if (!$is_add) {
		$href .= '&' . list_link_postfix();
	}

	if ($extension_code == 'virtual_card') {
		$text = $GLOBALS['_LANG']['50_virtual_card_list'];
	}
	else {
		$text = $GLOBALS['_LANG']['01_goods_list'];
	}

	return array('href' => $href, 'text' => $text);
}

function add_link($extension_code = '')
{
	$href = 'goods.php?act=add';

	if (!empty($extension_code)) {
		$href .= '&extension_code=' . $extension_code;
	}

	if ($extension_code == 'virtual_card') {
		$text = $GLOBALS['_LANG']['51_virtual_card_add'];
	}
	else {
		$text = $GLOBALS['_LANG']['02_goods_add'];
	}

	return array('href' => $href, 'text' => $text);
}

function goods_parse_url($url)
{
	$parse_url = @parse_url($url);
	return !empty($parse_url['scheme']) && !empty($parse_url['host']);
}

function handle_volume_price($goods_id, $is_volume, $number_list, $price_list, $id_list)
{
	if ($is_volume) {
		foreach ($price_list as $key => $price) {
			$volume_number = $number_list[$key];
			$volume_id = (isset($id_list[$key]) && !empty($id_list[$key]) ? $id_list[$key] : 0);

			if (!empty($price)) {
				if ($volume_id) {
					$sql = 'SELECT id FROM ' . $GLOBALS['ecs']->table('volume_price') . ' WHERE goods_id = \'' . $goods_id . '\' AND (volume_price = \'' . $price . '\' OR volume_number = \'' . $volume_number . '\')';

					if (!$GLOBALS['db']->getOne($sql)) {
						$sql = 'UPDATE ' . $GLOBALS['ecs']->table('volume_price') . ' SET volume_number = \'' . $volume_number . '\', volume_price = \'' . $price . '\' WHERE id = \'' . $volume_id . '\'';
						$GLOBALS['db']->query($sql);
					}
				}
				else {
					$sql = 'SELECT id FROM ' . $GLOBALS['ecs']->table('volume_price') . ' WHERE goods_id = \'' . $goods_id . '\' AND (volume_price = \'' . $price . '\' OR volume_number = \'' . $volume_number . '\')';

					if (!$GLOBALS['db']->getOne($sql)) {
						$sql = 'INSERT INTO ' . $GLOBALS['ecs']->table('volume_price') . ' (price_type, goods_id, volume_number, volume_price) ' . 'VALUES (\'1\', \'' . $goods_id . '\', \'' . $volume_number . '\', \'' . $price . '\')';
						$GLOBALS['db']->query($sql);
					}
				}
			}
		}
	}
	else {
		$sql = 'DELETE FROM ' . $GLOBALS['ecs']->table('volume_price') . ' WHERE price_type = \'1\' AND goods_id = \'' . $goods_id . '\'';
		$GLOBALS['db']->query($sql);
	}
}

function update_goods_stock($goods_id, $value, $warehouse_id = 0)
{
	if ($goods_id) {
		$sql = 'UPDATE ' . $GLOBALS['ecs']->table('warehouse_goods') . "\r\n                SET region_number = region_number + " . $value . ",\r\n                    last_update = '" . gmtime() . "'\r\n                WHERE goods_id = '" . $goods_id . '\' and region_id = \'' . $warehouse_id . '\'';
		$result = $GLOBALS['db']->query($sql);
		clear_cache_files();
		return $result;
	}
	else {
		return false;
	}
}

function get_areaRegion_list()
{
	$sql = 'select ra_id, ra_name from ' . $GLOBALS['ecs']->table('merchants_region_area') . ' where 1 order by ra_sort asc';
	$res = $GLOBALS['db']->getAll($sql);
	$arr = array();

	foreach ($res as $key => $row) {
		$arr[$key]['ra_id'] = $row['ra_id'];
		$arr[$key]['ra_name'] = $row['ra_name'];
		$arr[$key]['area'] = get_arearegion_info_list($row['ra_id']);
	}

	return $arr;
}

function get_areaRegion_info_list($ra_id)
{
	if (0 < $ra_id) {
		$where_raId = ' and mr.ra_id = \'' . $ra_id . '\'';
	}

	$sql = 'select rw.region_id, rw.region_name from ' . $GLOBALS['ecs']->table('merchants_region_info') . ' as mr ' . ' left join ' . $GLOBALS['ecs']->table('region') . ' as r on mr.region_id = r.region_id' . ' left join ' . $GLOBALS['ecs']->table('region_warehouse') . ' as rw on r.region_id = rw.regionId' . ' where 1' . $where_raId;
	return $GLOBALS['db']->getAll($sql);
}

function get_area_goods($goods_id)
{
	$sql = 'select rw.region_id, rw.region_name from ' . $GLOBALS['ecs']->table('link_area_goods') . ' as lag' . ' left join ' . $GLOBALS['ecs']->table('region_warehouse') . ' as rw on lag.region_id = rw.region_id' . ' where lag.goods_id = \'' . $goods_id . '\'';
	return $GLOBALS['db']->getAll($sql);
}

function is_mer($goods_id)
{
	$sql = ' SELECT user_id FROM ' . $GLOBALS['ecs']->table('goods') . ' WHERE goods_id = \'' . $goods_id . '\' ';
	$one = $GLOBALS['db']->getOne($sql);

	if ($one == 0) {
		return false;
	}
	else {
		return $one;
	}
}

function is_distribution($ru_id)
{
	$field = get_table_file_name($GLOBALS['ecs']->table('merchants_steps_fields'), 'is_distribution');

	if ($field['bool']) {
		$sql = ' SELECT is_distribution FROM ' . $GLOBALS['ecs']->table('merchants_steps_fields') . ' WHERE user_id = \'' . $ru_id . '\' ';
		$one = $GLOBALS['db']->getOne($sql);

		if ($one == '是') {
			return true;
		}
		else {
			return false;
		}
	}
	else {
		return false;
	}
}

define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';
require_once ROOT_PATH . '/' . ADMIN_PATH . '/includes/lib_goods.php';
include_once ROOT_PATH . '/includes/cls_image.php';
$image = new cls_image($_CFG['bgcolor']);
$exc = new exchange($ecs->table('goods'), $db, 'goods_id', 'goods_name');
$exc_extend = new exchange($ecs->table('goods_extend'), $db, 'goods_id', 'extend_id');
$exc_gallery = new exchange($ecs->table('goods_gallery'), $db, 'img_id', 'goods_id');
$admin_id = get_admin_id();
$adminru = get_admin_ru_id();

if ($adminru['ru_id'] == 0) {
	$smarty->assign('priv_ru', 1);
}
else {
	$smarty->assign('priv_ru', 0);
}

$smarty->assign('review_goods', $GLOBALS['_CFG']['review_goods']);
if (($_REQUEST['act'] == 'list') || ($_REQUEST['act'] == 'trash')) {
	admin_priv('goods_manage');
	get_updel_goods_attr();
	$cat_id = (empty($_REQUEST['cat_id']) ? 0 : intval($_REQUEST['cat_id']));
	$code = (empty($_REQUEST['extension_code']) ? '' : trim($_REQUEST['extension_code']));
	$suppliers_id = (isset($_REQUEST['suppliers_id']) ? (empty($_REQUEST['suppliers_id']) ? '' : trim($_REQUEST['suppliers_id'])) : '');
	$is_on_sale = (isset($_REQUEST['is_on_sale']) ? (empty($_REQUEST['is_on_sale']) && ($_REQUEST['is_on_sale'] === 0) ? '' : trim($_REQUEST['is_on_sale'])) : '');
	$handler_list = array();
	$handler_list['virtual_card'][] = array('url' => 'virtual_card.php?act=card', 'title' => $_LANG['card'], 'icon' => 'icon-credit-card');
	$handler_list['virtual_card'][] = array('url' => 'virtual_card.php?act=replenish', 'title' => $_LANG['replenish'], 'icon' => 'icon-plus-sign');
	$handler_list['virtual_card'][] = array('url' => 'virtual_card.php?act=batch_card_add', 'title' => $_LANG['batch_card_add'], 'icon' => 'icon-paste');
	if (($_REQUEST['act'] == 'list') && isset($handler_list[$code])) {
		$smarty->assign('add_handler', $handler_list[$code]);
		$smarty->assign('menu_select', array('action' => '02_cat_and_goods', 'current' => '50_virtual_card_list'));
	}
	else if ($_REQUEST['act'] == 'trash') {
		$smarty->assign('menu_select', array('action' => '02_cat_and_goods', 'current' => '11_goods_trash'));
	}
	else if ($_REQUEST['act'] == 'review_status') {
		$smarty->assign('menu_select', array('action' => '02_cat_and_goods', 'current' => '01_review_status'));
	}
	else {
		$smarty->assign('menu_select', array('action' => '02_cat_and_goods', 'current' => '01_goods_list'));
	}

	$suppliers_list_name = suppliers_list_name();
	$suppliers_exists = 1;

	if (empty($suppliers_list_name)) {
		$suppliers_exists = 0;
	}

	$smarty->assign('is_on_sale', $is_on_sale);
	$smarty->assign('suppliers_id', $suppliers_id);
	$smarty->assign('suppliers_exists', $suppliers_exists);
	$smarty->assign('suppliers_list_name', $suppliers_list_name);
	$smarty->assign('file_list', get_contents_section());
	unset($suppliers_list_name);
	unset($suppliers_exists);
	$goods_ur = array('' => $_LANG['01_goods_list'], 'virtual_card' => $_LANG['50_virtual_card_list']);
	$ur_here = ($_REQUEST['act'] == 'list' ? $goods_ur[$code] : $_LANG['11_goods_trash']);
	$smarty->assign('ur_here', $ur_here);
	$action_link = ($_REQUEST['act'] == 'list' ? add_link($code) : array('href' => 'goods.php?act=list', 'text' => $_LANG['01_goods_list']));
	$smarty->assign('action_link', $action_link);
	$action_link2 = ($_REQUEST['act'] == 'list' ? array('href' => 'goods.php?act=add_desc', 'text' => '商品统一详情') : '');
	$smarty->assign('action_link2', $action_link2);
	$smarty->assign('code', $code);
	$smarty->assign('brand_list', get_brand_list());
	$smarty->assign('store_brand', get_store_brand_list());
	$smarty->assign('intro_list', get_intro_list());
	$smarty->assign('lang', $_LANG);
	$smarty->assign('list_type', $_REQUEST['act'] == 'list' ? 'goods' : 'trash');
	$smarty->assign('use_storage', empty($_CFG['use_storage']) ? 0 : 1);
	$suppliers_list = suppliers_list_info(' is_check = 1 ');
	$suppliers_list_count = count($suppliers_list);
	$smarty->assign('suppliers_list', $suppliers_list_count == 0 ? 0 : $suppliers_list);
	$goods_list = goods_list($_REQUEST['act'] == 'list' ? 0 : 1, $_REQUEST['act'] == 'list' ? ($code == '' ? 1 : 0) : -1, '', 3);
	$smarty->assign('goods_list', $goods_list['goods']);
	$smarty->assign('filter', $goods_list['filter']);
	$smarty->assign('record_count', $goods_list['record_count']);
	$smarty->assign('page_count', $goods_list['page_count']);
	$smarty->assign('full_page', 1);
	$sort_flag = sort_flag($goods_list['filter']);
	$smarty->assign($sort_flag['tag'], $sort_flag['img']);
	$specifications = get_goods_type_specifications();
	$smarty->assign('specifications', $specifications);
	$store_list = get_common_store_list();
	$smarty->assign('store_list', $store_list);
	$smarty->assign('nowTime', gmtime());
	set_default_filter();

	if (isset($_GET['self'])) {
		$smarty->assign('self', 1);
	}
	else if (isset($_GET['merchants'])) {
		$smarty->assign('merchants', 1);
	}

	$goods_list_type = get_goods_type_number();
	$smarty->assign('goods_list_type', $goods_list_type);
	$smarty->assign('cfg', $_CFG);
	assign_query_info();
	$htm_file = ($_REQUEST['act'] == 'list' ? 'goods_list.dwt' : ($_REQUEST['act'] == 'trash' ? 'goods_trash.dwt' : 'group_list.dwt'));
	$smarty->display($htm_file);
}
else {
	if (($_REQUEST['act'] == 'add') || ($_REQUEST['act'] == 'edit') || ($_REQUEST['act'] == 'copy')) {
		if (file_exists(MOBILE_DRP)) {
			if (0 < $adminru['ru_id']) {
				$dis = is_distribution($adminru['ru_id']);
				$smarty->assign('is_dis', $dis);
			}

			if ($adminru['ru_id'] == 0) {
				$smarty->assign('is_dis', 1);
			}
		}

		include_once ROOT_PATH . 'includes/fckeditor/fckeditor.php';
		$is_add = $_REQUEST['act'] == 'add';
		$is_copy = $_REQUEST['act'] == 'copy';
		$code = (empty($_REQUEST['extension_code']) ? '' : trim($_REQUEST['extension_code']));
		get_updel_goods_attr();
		$properties = (empty($_REQUEST['properties']) ? 0 : intval($_REQUEST['properties']));
		$smarty->assign('properties', $properties);
		$res = $db->getAll(' SELECT img_url,thumb_url,img_original FROM' . $ecs->table('goods_gallery') . ' WHERE goods_id = 0 AND img_id' . db_create_in($_SESSION['thumb_img_id' . $_SESSION['admin_id']]));

		if (!empty($res)) {
			foreach ($res as $k) {
				if ($k['img_url']) {
					@unlink('../' . $k['img_url']);
				}

				if ($k['thumb_url']) {
					@unlink('../' . $k['thumb_url']);
				}

				if ($k['img_original']) {
					@unlink('../' . $k['img_original']);
				}
			}
		}

		$db->query('DELETE FROM' . $ecs->table('goods_gallery') . ' WHERE goods_id = 0 AND img_id' . db_create_in($_SESSION['thumb_img_id' . $_SESSION['admin_id']]));
		unset($_SESSION['thumb_img_id' . $_SESSION['admin_id']]);
		$db->query('DELETE FROM' . $ecs->table('warehouse_goods') . ' WHERE goods_id = 0');
		$db->query('DELETE FROM' . $ecs->table('warehouse_area_goods') . ' WHERE goods_id = 0');

		if ($code == 'virual_card') {
			admin_priv('virualcard');
		}
		else {
			admin_priv('goods_manage');
		}

		$suppliers_list_name = suppliers_list_name();
		$suppliers_exists = 1;

		if (empty($suppliers_list_name)) {
			$suppliers_exists = 0;
		}

		$smarty->assign('suppliers_exists', $suppliers_exists);
		$smarty->assign('suppliers_list_name', $suppliers_list_name);
		unset($suppliers_list_name);
		unset($suppliers_exists);
		if ((ini_get('safe_mode') == 1) && (!file_exists('../' . IMAGE_DIR . '/' . date('Ym')) || !is_dir('../' . IMAGE_DIR . '/' . date('Ym')))) {
			if (@!mkdir('../' . IMAGE_DIR . '/' . date('Ym'), 511)) {
				$warning = sprintf($_LANG['safe_mode_warning'], '../' . IMAGE_DIR . '/' . date('Ym'));
				$smarty->assign('warning', $warning);
			}
		}
		else {
			if (file_exists('../' . IMAGE_DIR . '/' . date('Ym')) && (file_mode_info('../' . IMAGE_DIR . '/' . date('Ym')) < 2)) {
				$warning = sprintf($_LANG['not_writable_warning'], '../' . IMAGE_DIR . '/' . date('Ym'));
				$smarty->assign('warning', $warning);
			}
		}

		$adminru = get_admin_ru_id();
		$goods_id = (isset($_REQUEST['goods_id']) && !empty($_REQUEST['goods_id']) ? intval($_REQUEST['goods_id']) : 0);

		if ($is_add) {
			$last_choose = array(0, 0);

			if (!empty($_COOKIE['ECSCP']['last_choose'])) {
				$last_choose = explode('|', $_COOKIE['ECSCP']['last_choose']);
			}

			$goods = array(
				'goods_id'           => 0,
				'user_id'            => 0,
				'goods_desc'         => '',
				'goods_shipai'       => '',
				'cat_id'             => '0',
				'brand_id'           => $last_choose[1],
				'is_on_sale'         => '1',
				'is_alone_sale'      => '1',
				'is_shipping'        => '0',
				'other_cat'          => array(),
				'goods_type'         => 0,
				'shop_price'         => 0,
				'promote_price'      => 0,
				'market_price'       => 0,
				'integral'           => 0,
				'goods_number'       => $_CFG['default_storage'],
				'warn_number'        => 1,
				'promote_start_date' => local_date($GLOBALS['_CFG']['time_format']),
				'promote_end_date'   => local_date($GLOBALS['_CFG']['time_format'], local_strtotime('+1 month')),
				'goods_weight'       => 0,
				'give_integral'      => 0,
				'rank_integral'      => 0,
				'user_cat'           => 0,
				'goods_extend'       => array('is_reality' => 0, 'is_return' => 0, 'is_fast' => 0)
				);

			if ($code != '') {
				$goods['goods_number'] = 0;
			}

			$link_goods_list = array();
			$sql = 'DELETE FROM ' . $ecs->table('link_goods') . ' WHERE (goods_id = 0 OR link_goods_id = 0)' . ' AND admin_id = \'' . $_SESSION['admin_id'] . '\'';
			$db->query($sql);
			$group_goods_list = array();
			$sql = 'DELETE FROM ' . $ecs->table('group_goods') . ' WHERE parent_id = 0 AND admin_id = \'' . $_SESSION['admin_id'] . '\'';
			$db->query($sql);
			$goods_article_list = array();
			$sql = 'DELETE FROM ' . $ecs->table('goods_article') . ' WHERE goods_id = 0 AND admin_id = \'' . $_SESSION['admin_id'] . '\'';
			$db->query($sql);
			$sql = 'DELETE FROM ' . $ecs->table('goods_attr') . ' WHERE goods_id = 0';
			$db->query($sql);
			$img_list = array();
		}
		else {
			$goods = get_admin_goods_info($goods_id);
			if ($is_copy && ($code != '')) {
				$goods['goods_number'] = 0;
			}

			if (empty($goods) === true) {
				$goods = array(
					'goods_id'           => 0,
					'user_id'            => 0,
					'goods_desc'         => '',
					'goods_shipai'       => '',
					'cat_id'             => 0,
					'is_on_sale'         => '1',
					'is_alone_sale'      => '1',
					'is_shipping'        => '0',
					'other_cat'          => array(),
					'goods_type'         => 0,
					'shop_price'         => 0,
					'promote_price'      => 0,
					'market_price'       => 0,
					'integral'           => 0,
					'goods_number'       => 1,
					'warn_number'        => 1,
					'promote_start_date' => local_date($GLOBALS['_CFG']['time_format']),
					'promote_end_date'   => local_date($GLOBALS['_CFG']['time_format'], local_strtotime('+1 month')),
					'goods_weight'       => 0,
					'give_integral'      => 0,
					'rank_integral'      => 0,
					'user_cat'           => 0,
					'goods_extend'       => array('is_reality' => 0, 'is_return' => 0, 'is_fast' => 0)
					);
			}

			$goods['goods_extend'] = get_goods_extend($goods['goods_id']);
			$specifications = get_goods_type_specifications();
			$goods['specifications_id'] = $specifications[$goods['goods_type']];
			$_attribute = get_goods_specifications_list($goods['goods_id']);
			$goods['_attribute'] = empty($_attribute) ? '' : 1;

			if (0 < $goods['goods_weight']) {
				$goods['goods_weight_by_unit'] = 1 <= $goods['goods_weight'] ? $goods['goods_weight'] : $goods['goods_weight'] / 0.001;
			}

			if (!empty($goods['goods_brief'])) {
				$goods['goods_brief'] = $goods['goods_brief'];
			}

			if (!empty($goods['keywords'])) {
				$goods['keywords'] = $goods['keywords'];
			}

			if (isset($goods['is_xiangou']) && ($goods['is_xiangou'] == '0')) {
				unset($goods['xiangou_start_date']);
				unset($goods['xiangou_end_date']);
			}
			else {
				$goods['xiangou_start_date'] = local_date('Y-m-d H:i:s', $goods['xiangou_start_date']);
				$goods['xiangou_end_date'] = local_date('Y-m-d H:i:s', $goods['xiangou_end_date']);
			}

			if (!empty($goods['goods_product_tag'])) {
				$goods['goods_product_tag'] = $goods['goods_product_tag'];
			}

			if (isset($goods['is_promote']) && ($goods['is_promote'] == '0')) {
				unset($goods['promote_start_date']);
				unset($goods['promote_end_date']);
			}
			else {
				$goods['promote_start_date'] = local_date($GLOBALS['_CFG']['time_format'], $goods['promote_start_date']);
				$goods['promote_end_date'] = local_date($GLOBALS['_CFG']['time_format'], $goods['promote_end_date']);
			}

			$other_cat_list1 = array();
			$sql = 'SELECT ga.cat_id FROM ' . $ecs->table('goods_cat') . ' as ga ' . ' WHERE ga.goods_id = \'' . $goods_id . '\'';
			$other_cat1 = $db->getCol($sql);
			$other_catids = '';

			foreach ($other_cat1 as $key => $val) {
				$other_catids .= $val . ',';
			}

			$other_catids = substr($other_catids, 0, -1);
			$smarty->assign('other_catids', $other_catids);
			$smarty->assign('file_list', get_contents_section());

			if ($_REQUEST['act'] == 'copy') {
				$goods['goods_id'] = 0;
				$goods['goods_sn'] = '';
				$goods['goods_name'] = '';
				$goods['goods_img'] = '';
				$goods['goods_thumb'] = '';
				$goods['original_img'] = '';
				$sql = 'DELETE FROM ' . $ecs->table('link_goods') . ' WHERE (goods_id = 0 OR link_goods_id = 0)' . ' AND admin_id = \'' . $_SESSION['admin_id'] . '\'';
				$db->query($sql);
				$sql = 'SELECT \'0\' AS goods_id, link_goods_id, is_double, \'' . $_SESSION['admin_id'] . '\' AS admin_id' . ' FROM ' . $ecs->table('link_goods') . ' WHERE goods_id = \'' . $_REQUEST['goods_id'] . '\' ';
				$res = $db->query($sql);

				while ($row = $db->fetchRow($res)) {
					$db->autoExecute($ecs->table('link_goods'), $row, 'INSERT');
				}

				$sql = 'SELECT goods_id, \'0\' AS link_goods_id, is_double, \'' . $_SESSION['admin_id'] . '\' AS admin_id' . ' FROM ' . $ecs->table('link_goods') . ' WHERE link_goods_id = \'' . $_REQUEST['goods_id'] . '\' ';
				$res = $db->query($sql);

				while ($row = $db->fetchRow($res)) {
					$db->autoExecute($ecs->table('link_goods'), $row, 'INSERT');
				}

				$sql = 'DELETE FROM ' . $ecs->table('group_goods') . ' WHERE parent_id = 0 AND admin_id = \'' . $_SESSION['admin_id'] . '\'';
				$db->query($sql);
				$sql = 'SELECT 0 AS parent_id, goods_id, goods_price, \'' . $_SESSION['admin_id'] . '\' AS admin_id ' . 'FROM ' . $ecs->table('group_goods') . ' WHERE parent_id = \'' . $_REQUEST['goods_id'] . '\' ';
				$res = $db->query($sql);

				while ($row = $db->fetchRow($res)) {
					$db->autoExecute($ecs->table('group_goods'), $row, 'INSERT');
				}

				$sql = 'DELETE FROM ' . $ecs->table('goods_article') . ' WHERE goods_id = 0 AND admin_id = \'' . $_SESSION['admin_id'] . '\'';
				$db->query($sql);
				$sql = 'SELECT 0 AS goods_id, article_id, \'' . $_SESSION['admin_id'] . '\' AS admin_id ' . 'FROM ' . $ecs->table('goods_article') . ' WHERE goods_id = \'' . $_REQUEST['goods_id'] . '\' ';
				$res = $db->query($sql);

				while ($row = $db->fetchRow($res)) {
					$db->autoExecute($ecs->table('goods_article'), $row, 'INSERT');
				}

				$sql = 'DELETE FROM ' . $ecs->table('goods_attr') . ' WHERE goods_id = 0';
				$db->query($sql);
				$sql = 'SELECT 0 AS goods_id, attr_id, attr_value, attr_price ' . 'FROM ' . $ecs->table('goods_attr') . ' WHERE goods_id = \'' . $_REQUEST['goods_id'] . '\' ';
				$res = $db->query($sql);

				while ($row = $db->fetchRow($res)) {
					$db->autoExecute($ecs->table('goods_attr'), addslashes_deep($row), 'INSERT');
				}
			}

			$link_goods_list = get_linked_goods($goods['goods_id']);
			$group_goods_list = get_group_goods($goods['goods_id']);
			$goods_article_list = get_goods_articles($goods['goods_id']);

			if (is_array($group_goods_list)) {
				foreach ($group_goods_list as $k => $val) {
					$group_goods_list[$k]['goods_name'] = '[' . $val['group_name'] . ']' . $val['goods_name'];
				}
			}

			if (isset($GLOBALS['shop_id']) && (10 < $GLOBALS['shop_id']) && !empty($goods['original_img'])) {
				$goods['goods_img'] = get_image_path($goods_id, $goods['goods_img']);
				$goods['goods_thumb'] = get_image_path($goods_id, $goods['goods_thumb'], true);
			}

			$sql = 'SELECT * FROM ' . $ecs->table('goods_gallery') . ' WHERE goods_id = \'' . $goods_id . '\' ORDER BY img_desc';
			$img_list = $db->getAll($sql);
			if (isset($GLOBALS['shop_id']) && (0 < $GLOBALS['shop_id'])) {
				foreach ($img_list as $key => $gallery_img) {
					$img_list[$key] = $gallery_img;

					if (!empty($gallery_img['external_url'])) {
						$img_list[$key]['img_url'] = $gallery_img['external_url'];
						$img_list[$key]['thumb_url'] = $gallery_img['external_url'];
					}
					else {
						$img_list[$key]['img_url'] = get_image_path($gallery_img['goods_id'], $gallery_img['img_original'], false, 'gallery');
						$img_list[$key]['thumb_url'] = get_image_path($gallery_img['goods_id'], $gallery_img['img_original'], true, 'gallery');
					}
				}
			}
			else {
				$proc_thumb = (isset($GLOBALS['shop_id']) && (0 < $GLOBALS['shop_id']) ? false : true);

				foreach ($img_list as $key => $gallery_img) {
					$img_list[$key] = $gallery_img;

					if (!empty($gallery_img['external_url'])) {
						$img_list[$key]['img_url'] = $gallery_img['external_url'];
						$img_list[$key]['thumb_url'] = $gallery_img['external_url'];
					}
					else if ($proc_thumb) {
						$img_list[$key]['thumb_url'] = '../' . (empty($gallery_img['thumb_url']) ? $gallery_img['img_url'] : $gallery_img['thumb_url']);
					}
					else {
						$img_list[$key]['thumb_url'] = empty($gallery_img['thumb_url']) ? $gallery_img['img_url'] : $gallery_img['thumb_url'];
					}
				}
			}

			$img_desc = array();

			foreach ($img_list as $k => $v) {
				$img_desc[] = $v['img_desc'];
			}

			@$img_default = min($img_desc);
			$min_img_id = $db->getOne(' SELECT img_id   FROM ' . $ecs->table('goods_gallery') . ' WHERE goods_id = \'' . $goods_id . '\' AND img_desc = \'' . $img_default . '\' ORDER BY img_desc   LIMIT 1');
			$smarty->assign('min_img_id', $min_img_id);
		}

		if (0 < $adminru['ru_id']) {
			$goods['user_id'] = $adminru['ru_id'];
		}

		$warehouse_list = get_warehouse_region();
		$smarty->assign('warehouse_list', $warehouse_list);
		$smarty->assign('count_warehouse', count($warehouse_list));
		$warehouse_goods_list = get_warehouse_goods_list($goods_id);
		$smarty->assign('warehouse_goods_list', $warehouse_goods_list);
		$warehouse_area_goods_list = get_warehouse_area_goods_list($goods_id);
		$smarty->assign('warehouse_area_goods_list', $warehouse_area_goods_list);
		$area_count = get_all_warehouse_area_count();
		$smarty->assign('area_count', $area_count);
		$areaRegion_list = get_arearegion_list();
		$smarty->assign('areaRegion_list', $areaRegion_list);
		$smarty->assign('area_goods_list', get_area_goods($goods_id));
		$consumption_list = get_goods_con_list($goods_id, 'goods_consumption');
		$smarty->assign('consumption_list', $consumption_list);
		$group_goods = get_cfg_group_goods();
		$smarty->assign('group_list', $group_goods);
		$smarty->assign('ru_id', $adminru['ru_id']);
		$goods_name_style = explode('+', empty($goods['goods_name_style']) ? '+' : $goods['goods_name_style']);
		create_html_editor('goods_desc', $goods['goods_desc']);
		create_html_editor2('goods_shipai', 'goods_shipai', $goods['goods_shipai']);

		if (!empty($goods['stages'])) {
			$stages = unserialize($goods['stages']);
		}

		$grade_rank = get_seller_grade_rank($goods['user_id']);
		$smarty->assign('grade_rank', $grade_rank);
		$smarty->assign('integral_scale', $_CFG['integral_scale']);

		if ($goods['user_id']) {
			if ($goods['is_promote']) {
				$goods['use_give_integral'] = $grade_rank['give_integral'] * $goods['promote_price'];
				$goods['use_rank_integral'] = $grade_rank['rank_integral'] * $goods['promote_price'];
				$goods['use_pay_integral'] = ($goods['promote_price'] / 100) * $_CFG['integral_scale'] * $grade_rank['pay_integral'];
			}
			else {
				$goods['use_give_integral'] = $grade_rank['give_integral'] * $goods['shop_price'];
				$goods['use_rank_integral'] = $grade_rank['rank_integral'] * $goods['shop_price'];
				$goods['use_pay_integral'] = ($goods['shop_price'] / 100) * $_CFG['integral_scale'] * $grade_rank['pay_integral'];
			}
		}

		$smarty->assign('code', $code);
		$smarty->assign('ur_here', $is_add ? (empty($code) ? $_LANG['02_goods_add'] : $_LANG['51_virtual_card_add']) : ($_REQUEST['act'] == 'edit' ? $_LANG['edit_goods'] : $_LANG['copy_goods']));
		$smarty->assign('action_link', list_link($is_add, $code));
		$smarty->assign('goods', $goods);
		$smarty->assign('stages', $stages);
		$smarty->assign('goods_name_color', $goods_name_style[0]);
		$smarty->assign('goods_name_style', $goods_name_style[1]);
		$smarty->assign('brand_list', search_brand_list($goods_id));
		$smarty->assign('brand_name', get_brand_name($goods_id));
		$smarty->assign('unit_list', get_unit_list());
		$smarty->assign('user_rank_list', get_user_rank_list());
		$smarty->assign('weight_unit', $is_add ? '1' : (1 <= $goods['goods_weight'] ? '1' : '0.001'));
		$smarty->assign('cfg', $_CFG);
		$smarty->assign('form_act', $is_add ? 'insert' : ($_REQUEST['act'] == 'edit' ? 'update' : 'insert'));
		if (($_REQUEST['act'] == 'add') || ($_REQUEST['act'] == 'edit')) {
			$smarty->assign('is_add', true);
		}

		if (!$is_add) {
			$smarty->assign('member_price_list', get_member_price_list($goods_id));
		}

		$smarty->assign('link_goods_list', $link_goods_list);
		$smarty->assign('group_goods_list', $group_goods_list);
		$smarty->assign('goods_article_list', $goods_article_list);
		$smarty->assign('img_list', $img_list);
		$smarty->assign('goods_type_list', goods_type_list($goods['goods_type'], $goods['goods_id'], 'array'));
		$smarty->assign('goods_type_name', $GLOBALS['db']->getOne(' SELECT cat_name FROM ' . $GLOBALS['ecs']->table('goods_type') . ' WHERE cat_id = \'' . $goods['goods_type'] . '\' '));
		$smarty->assign('gd', gd_version());
		$smarty->assign('thumb_width', $_CFG['thumb_width']);
		$smarty->assign('thumb_height', $_CFG['thumb_height']);
		$volume_price_list = get_volume_price_list($goods_id);
		$smarty->assign('volume_price_list', $volume_price_list);

		if ($goods['user_id']) {
			$seller_shop_cat = seller_shop_cat($goods['user_id']);
		}
		else {
			$seller_shop_cat = array();
		}

		$level_limit = 3;
		$category_level = array();

		if ($_REQUEST['act'] == 'add') {
			for ($i = 1; $i <= $level_limit; $i++) {
				$category_list = array();

				if ($i == 1) {
					$category_list = get_category_list();
				}

				$smarty->assign('cat_level', $i);
				$smarty->assign('category_list', $category_list);
				$category_level[$i] = $smarty->fetch('templates/library/get_select_category.lbi');
			}
		}

		if (($_REQUEST['act'] == 'edit') || ($_REQUEST['act'] == 'copy')) {
			$parent_cat_list = get_select_category($goods['cat_id'], 1, true);

			for ($i = 1; $i <= $level_limit; $i++) {
				$category_list = array();

				if (isset($parent_cat_list[$i])) {
					$category_list = get_category_list($parent_cat_list[$i], 0, $seller_shop_cat, $goods['user_id'], $i);
				}
				else if ($i == 1) {
					if ($goods['user_id']) {
						$category_list = get_category_list(0, 0, $seller_shop_cat, $goods['user_id'], $i);
					}
					else {
						$category_list = get_category_list();
					}
				}

				$smarty->assign('cat_level', $i);
				$smarty->assign('category_list', $category_list);
				$category_level[$i] = $smarty->fetch('templates/library/get_select_category.lbi');
			}
		}

		$smarty->assign('category_level', $category_level);
		set_default_filter($goods_id, 0, $goods['user_id']);
		$cat_info = get_seller_cat_info($goods['user_cat']);
		set_seller_default_filter(0, $goods['user_cat'], $goods['user_id']);
		get_common_category($goods_id);
		$smarty->assign('user_id', $goods['user_id']);

		if (file_exists(MOBILE_DRP)) {
			$smarty->assign('is_dir', 1);
		}
		else {
			$smarty->assign('is_dir', 0);
		}

		assign_query_info();
		$smarty->display('goods_info.dwt');
	}
	else if ($_REQUEST['act'] == 'review_status') {
		$handler_list = array();
		$handler_list['virtual_card'][] = array('url' => 'virtual_card.php?act=card', 'title' => $_LANG['card'], 'icon' => 'icon-credit-card');
		$handler_list['virtual_card'][] = array('url' => 'virtual_card.php?act=replenish', 'title' => $_LANG['replenish'], 'icon' => 'icon-plus-sign');
		$handler_list['virtual_card'][] = array('url' => 'virtual_card.php?act=batch_card_add', 'title' => $_LANG['batch_card_add'], 'icon' => 'icon-paste');
		if (($_REQUEST['act'] == 'list') && isset($handler_list[$code])) {
			$smarty->assign('add_handler', $handler_list[$code]);
			$smarty->assign('menu_select', array('action' => '02_cat_and_goods', 'current' => '50_virtual_card_list'));
		}
		else if ($_REQUEST['act'] == 'trash') {
			$smarty->assign('menu_select', array('action' => '02_cat_and_goods', 'current' => '11_goods_trash'));
		}
		else if ($_REQUEST['act'] == 'review_status') {
			$smarty->assign('menu_select', array('action' => '02_cat_and_goods', 'current' => '01_review_status'));
		}
		else {
			$smarty->assign('menu_select', array('action' => '02_cat_and_goods', 'current' => '01_goods_list'));
		}

		$type = (isset($_REQUEST['type']) && !empty($_REQUEST['type']) ? addslashes($_REQUEST['type']) : 'not_audit');

		if ($type == 'not_pass') {
			$status = 2;
			$smarty->assign('ur_here', $_LANG['lab_review_not_pass']);
		}
		else {
			$status = 1;
			$smarty->assign('ur_here', $_LANG['lab_review_not_audit']);
		}

		$goods_list_type = get_goods_type_number();
		$smarty->assign('goods_list_type', $goods_list_type);
		$goods_list = goods_list(0, 1, '', $status);
		$smarty->assign('goods_list', $goods_list['goods']);
		$smarty->assign('filter', $goods_list['filter']);
		$smarty->assign('record_count', $goods_list['record_count']);
		$smarty->assign('page_count', $goods_list['page_count']);
		$smarty->assign('full_page', 1);
		$suppliers_list_name = suppliers_list_name();
		$suppliers_exists = 1;

		if (empty($suppliers_list_name)) {
			$suppliers_exists = 0;
		}

		$smarty->assign('is_on_sale', $is_on_sale);
		$smarty->assign('suppliers_id', $suppliers_id);
		$smarty->assign('suppliers_exists', $suppliers_exists);
		$smarty->assign('suppliers_list_name', $suppliers_list_name);
		$smarty->assign('file_list', get_contents_section());
		unset($suppliers_list_name);
		unset($suppliers_exists);
		$smarty->assign('brand_list', get_brand_list());
		$smarty->assign('store_brand', get_store_brand_list());
		$smarty->assign('intro_list', get_intro_list());
		$smarty->assign('lang', $_LANG);
		$smarty->assign('type', $type);
		set_default_filter();
		$sort_flag = sort_flag($goods_list['filter']);
		$smarty->assign($sort_flag['tag'], $sort_flag['img']);
		$smarty->assign('cfg', $_CFG);
		assign_query_info();
		$smarty->display('goods_review_list.dwt');
	}
	else if ($_REQUEST['act'] == 'review_query') {
		$is_delete = (empty($_REQUEST['is_delete']) ? 0 : intval($_REQUEST['is_delete']));
		$code = (empty($_REQUEST['extension_code']) ? '' : trim($_REQUEST['extension_code']));
		$goods_list = goods_list($is_delete, $code == '' ? 1 : 0);
		$handler_list = array();
		$handler_list['virtual_card'][] = array('url' => 'virtual_card.php?act=card', 'title' => $_LANG['card'], 'img' => 'icon_send_bonus.gif');
		$handler_list['virtual_card'][] = array('url' => 'virtual_card.php?act=replenish', 'title' => $_LANG['replenish'], 'img' => 'icon_add.gif');
		$handler_list['virtual_card'][] = array('url' => 'virtual_card.php?act=batch_card_add', 'title' => $_LANG['batch_card_add'], 'img' => 'icon_output.gif');

		if (isset($handler_list[$code])) {
			$smarty->assign('add_handler', $handler_list[$code]);
		}

		$smarty->assign('code', $code);
		$smarty->assign('goods_list', $goods_list['goods']);
		$smarty->assign('filter', $goods_list['filter']);
		$smarty->assign('record_count', $goods_list['record_count']);
		$smarty->assign('page_count', $goods_list['page_count']);
		$smarty->assign('list_type', $is_delete ? 'trash' : 'goods');
		$smarty->assign('use_storage', empty($_CFG['use_storage']) ? 0 : 1);
		$sort_flag = sort_flag($goods_list['filter']);
		$smarty->assign($sort_flag['tag'], $sort_flag['img']);
		$specifications = get_goods_type_specifications();
		$smarty->assign('specifications', $specifications);
		$smarty->assign('nowTime', gmtime());
		make_json_result($smarty->fetch('goods_review_list.dwt'), '', array('filter' => $goods_list['filter'], 'page_count' => $goods_list['page_count']));
	}
	else if ($_REQUEST['act'] == 'get_select_category_pro') {
		$goods_id = (empty($_REQUEST['goods_id']) ? 0 : intval($_REQUEST['goods_id']));
		$cat_id = (empty($_REQUEST['cat_id']) ? 0 : intval($_REQUEST['cat_id']));
		$cat_level = (empty($_REQUEST['cat_level']) ? 0 : intval($_REQUEST['cat_level']));
		$result = array('error' => 0, 'message' => '', 'content' => '');
		$goods = get_admin_goods_info($goods_id, array('user_id'));
		$seller_shop_cat = seller_shop_cat($goods['user_id']);
		$smarty->assign('cat_id', $cat_id);
		$smarty->assign('cat_level', $cat_level + 1);
		$smarty->assign('category_list', get_category_list($cat_id, 2, $seller_shop_cat, $goods['user_id'], $cat_level + 1));
		$result['content'] = $smarty->fetch('templates/library/get_select_category.lbi');
		exit(json_encode($result));
	}
	else if ($_REQUEST['act'] == 'set_common_category_pro') {
		$cat_id = (empty($_REQUEST['cat_id']) ? 0 : intval($_REQUEST['cat_id']));
		$result = array('error' => 0, 'message' => '', 'content' => '');
		$level_limit = 3;
		$category_level = array();
		$parent_cat_list = get_select_category($cat_id, 1, true);

		for ($i = 1; $i <= $level_limit; $i++) {
			$category_list = array();

			if (isset($parent_cat_list[$i])) {
				$category_list = get_category_list($parent_cat_list[$i]);
			}
			else if ($i == 1) {
				$category_list = get_category_list();
			}

			$smarty->assign('cat_level', $i);
			$smarty->assign('category_list', $category_list);
			$category_level[$i] = $smarty->fetch('templates/library/get_select_category.lbi');
		}

		$smarty->assign('cat_id', $cat_id);
		$result['content'] = $category_level;
		exit(json_encode($result));
	}
	else if ($_REQUEST['act'] == 'deal_extension_category') {
		$goods_id = (empty($_REQUEST['goods_id']) ? 0 : intval($_REQUEST['goods_id']));
		$cat_id = (empty($_REQUEST['cat_id']) ? 0 : intval($_REQUEST['cat_id']));
		$type = (empty($_REQUEST['type']) ? '' : trim($_REQUEST['type']));
		$other_catids = (empty($_REQUEST['other_catids']) ? '' : trim($_REQUEST['other_catids']));
		$result = array('error' => 0, 'message' => '', 'content' => '');

		if ($type == 'add') {
			$sql = 'INSERT INTO ' . $GLOBALS['ecs']->table('goods_cat') . ' (goods_id, cat_id) ' . 'VALUES (\'' . $goods_id . '\', \'' . $cat_id . '\')';
			$GLOBALS['db']->query($sql);

			if ($other_catids == '') {
				$other_catids = $cat_id;
			}
			else {
				$other_catids = $other_catids . ',' . $cat_id;
			}
		}
		else if ($type == 'delete') {
			$sql = 'DELETE FROM ' . $GLOBALS['ecs']->table('goods_cat') . ' WHERE goods_id = \'' . $goods_id . '\' ' . 'AND cat_id = \'' . $cat_id . '\' ';
			$GLOBALS['db']->query($sql);
			$other_catids = str_replace(',' . $cat_id, '', $other_catids);
		}

		$result['content'] = $other_catids;
		exit(json_encode($result));
	}
	else if ($_REQUEST['act'] == 'goods_model_list') {
		$goods_id = (empty($_REQUEST['goods_id']) ? 0 : intval($_REQUEST['goods_id']));
		$user_id = (empty($_REQUEST['user_id']) ? 0 : intval($_REQUEST['user_id']));
		$model = (empty($_REQUEST['model']) ? 0 : intval($_REQUEST['model']));
		$result = array('error' => 0, 'message' => '', 'content' => '');

		if ($model == 1) {
			$warehouse_goods_list = get_warehouse_goods_list($goods_id);
			$smarty->assign('warehouse_goods_list', $warehouse_goods_list);
		}

		if ($model == 2) {
			$warehouse_area_goods_list = get_warehouse_area_goods_list($goods_id);
			$smarty->assign('warehouse_area_goods_list', $warehouse_area_goods_list);
		}

		$smarty->assign('goods_id', $goods_id);
		$smarty->assign('user_id', $user_id);
		$smarty->assign('model', $model);
		$result['content'] = $smarty->fetch('templates/library/goods_model_list.lbi');
		exit(json_encode($result));
	}
	else if ($_REQUEST['act'] == 'get_attribute') {
		check_authz_json('goods_manage');
		$goods_id = (empty($_REQUEST['goods_id']) ? 0 : intval($_REQUEST['goods_id']));
		$goods_type = (empty($_REQUEST['goods_type']) ? 0 : intval($_REQUEST['goods_type']));
		$model = (!isset($_REQUEST['modelAttr']) ? -1 : intval($_REQUEST['modelAttr']));
		$result = array('error' => 0, 'message' => '', 'content' => '');
		$attribute = set_goods_attribute($goods_type, $goods_id, $model);
		$result['goods_attribute'] = $attribute['goods_attribute'];
		$result['goods_attr_gallery'] = $attribute['goods_attr_gallery'];
		$result['model'] = $model;
		$result['goods_id'] = $goods_id;
		$result['is_spec'] = $attribute['is_spec'];
		exit(json_encode($result));
	}
	else if ($_REQUEST['act'] == 'set_attribute_table') {
		check_authz_json('goods_manage');
		$goods_id = (empty($_REQUEST['goods_id']) ? 0 : intval($_REQUEST['goods_id']));
		$goods_type = (empty($_REQUEST['goods_type']) ? 0 : intval($_REQUEST['goods_type']));
		$attr_id_arr = (empty($_REQUEST['attr_id']) ? array() : explode(',', $_REQUEST['attr_id']));
		$attr_value_arr = (empty($_REQUEST['attr_value']) ? array() : explode(',', $_REQUEST['attr_value']));
		$goods_model = (empty($_REQUEST['goods_model']) ? 0 : intval($_REQUEST['goods_model']));
		$region_id = (empty($_REQUEST['region_id']) ? 0 : intval($_REQUEST['region_id']));
		$result = array('error' => 0, 'message' => '', 'content' => '');
		$group_attr = array('goods_id' => $goods_id, 'goods_type' => $goods_type, 'attr_id' => empty($attr_id_arr) ? '' : implode(',', $attr_id_arr), 'attr_value' => empty($attr_value_arr) ? '' : implode(',', $attr_value_arr), 'goods_model' => $goods_model, 'region_id' => $region_id);
		$result['group_attr'] = json_encode($group_attr);

		if ($goods_model == 0) {
			$model_name = '';
		}
		else if ($goods_model == 1) {
			$model_name = '仓库';
		}
		else if ($goods_model == 2) {
			$model_name = '地区';
		}

		$region_name = $GLOBALS['db']->getOne(' SELECT region_name FROM ' . $GLOBALS['ecs']->table('region_warehouse') . ' WHERE region_id =\'' . $region_id . '\' ');
		$smarty->assign('region_name', $region_name);
		$smarty->assign('goods_model', $goods_model);
		$smarty->assign('model_name', $model_name);
		$goods_info = $GLOBALS['db']->getRow(' SELECT market_price, shop_price, model_attr FROM ' . $GLOBALS['ecs']->table('goods') . ' WHERE goods_id = \'' . $goods_id . '\' ');
		$smarty->assign('goods_info', $goods_info);

		foreach ($attr_id_arr as $key => $val) {
			$attr_arr[$val][] = $attr_value_arr[$key];
		}

		$attr_spec = array();
		$attribute_array = array();

		if (0 < count($attr_arr)) {
			$i = 0;

			foreach ($attr_arr as $key => $val) {
				$sql = 'SELECT attr_name, attr_type FROM ' . $GLOBALS['ecs']->table('attribute') . ' WHERE attr_id =\'' . $key . '\' LIMIT 1';
				$attr_info = $GLOBALS['db']->getRow($sql);
				$attribute_array[$i]['attr_id'] = $key;
				$attribute_array[$i]['attr_name'] = $attr_info['attr_name'];
				$attribute_array[$i]['attr_value'] = $val;
				$attr_values_arr = array();

				foreach ($val as $k => $v) {
					$data = get_goods_attr_id(array('attr_id' => $key, 'attr_value' => $v, 'goods_id' => $goods_id), array('ga.*, a.attr_type'), array(1, 2), 1);

					if (!$data) {
						$sql = ' INSERT INTO ' . $GLOBALS['ecs']->table('goods_attr') . ' (goods_id, attr_id, attr_value, admin_id) ' . ' VALUES ' . ' (\'' . $goods_id . '\', \'' . $key . '\', \'' . $v . '\', \'' . $_SESSION['admin_id'] . '\') ';
						$GLOBALS['db']->query($sql);
						$data['goods_attr_id'] = $GLOBALS['db']->insert_id();
					}

					$data['attr_value'] = $v;
					$data['is_selected'] = 1;
					$attr_values_arr[] = $data;
				}

				$attr_spec[$i] = $attribute_array[$i];
				$attr_spec[$i]['attr_values_arr'] = $attr_values_arr;
				$attribute_array[$i]['attr_values_arr'] = $attr_values_arr;

				if ($attr_info['attr_type'] == 2) {
					unset($attribute_array[$i]);
				}

				$i++;
			}

			$attr_arr = get_goods_unset_attr($goods_id, $attr_arr);

			if (count($attr_arr) == 1) {
				foreach (reset($attr_arr) as $key => $val) {
					$attr_group[][] = $val;
				}
			}
			else {
				$attr_group = attr_group($attr_arr);
			}

			foreach ($attr_group as $key => $val) {
				$group = array();
				$product_info = get_product_info_by_attr($goods_id, $val, $goods_model, $region_id);

				if (!empty($product_info)) {
					$group = $product_info;
				}

				foreach ($val as $k => $v) {
					$group['attr_info'][$k]['attr_id'] = $attribute_array[$k]['attr_id'];
					$group['attr_info'][$k]['attr_value'] = $v;
				}

				$attr_group[$key] = $group;
			}

			$smarty->assign('attr_group', $attr_group);
			$smarty->assign('attribute_array', $attribute_array);
		}

		$smarty->assign('group_attr', $result['group_attr']);
		$smarty->assign('goods_attr_price', $GLOBALS['_CFG']['goods_attr_price']);
		$GLOBALS['smarty']->assign('goods_id', $goods_id);
		$GLOBALS['smarty']->assign('goods_type', $goods_type);
		$result['content'] = $smarty->fetch('library/attribute_table.lbi');
		$smarty->assign('attr_spec', $attr_spec);
		$result['goods_attr_gallery'] = $smarty->fetch('library/goods_attr_gallery.lbi');
		exit(json_encode($result));
	}
	else if ($_REQUEST['act'] == 'search_brand') {
		include_once ROOT_PATH . 'includes/cls_json.php';
		$json = new JSON();
		$search_keyword = trim($_GET['search_keyword']);
		$ru_id = intval($_GET['ru_id']);
		$goods_id = intval($_GET['goods_id']);

		if (0 < $ru_id) {
			$sql = 'SELECT bid, brandName FROM ' . $GLOBALS['ecs']->table('merchants_shop_brand') . ' where brandName LIKE \'%' . $search_keyword . '%\' AND user_id = \'' . $ru_id . '\' AND audit_status = 1 ORDER BY bid ASC';
			$res = $GLOBALS['db']->getAll($sql);
			$brand_list = array();

			foreach ($res as $row) {
				$link_brand = get_link_brand_list($row['bid'], 3);
				$brand_list[$row['bid']] = addslashes($row['brandName']);
			}
		}
		else if (!is_mer($goods_id)) {
			$sql = 'SELECT brand_id, brand_name FROM ' . $GLOBALS['ecs']->table('brand') . ' WHERE brand_name LIKE \'%' . $search_keyword . '%\' ORDER BY sort_order';
			$res = $GLOBALS['db']->getAll($sql);
			$brand_list = array();

			foreach ($res as $row) {
				$brand_list[$row['brand_id']] = addslashes($row['brand_name']);
			}
		}
		else {
			$user_id = is_mer($goods_id);
			$sql = 'SELECT bid, brandName FROM ' . $GLOBALS['ecs']->table('merchants_shop_brand') . ' where brandName LIKE \'%' . $search_keyword . '%\' AND user_id = \'' . $user_id . '\' AND audit_status = 1 ORDER BY bid ASC';
			$res = $GLOBALS['db']->getAll($sql);
			$brand_list = array();

			foreach ($res as $row) {
				$link_brand = get_link_brand_list($row['bid'], 3);
				$brand_list[$row['bid']] = addslashes($row['brandName']);
			}
		}

		$option = '<option value="0">请选择...</option>';

		foreach ($brand_list as $key => $value) {
			$option .= '<option value=\'' . $key . '\'>' . $value . '</option>';
		}

		exit(json_encode($option));
	}
	else if ($_REQUEST['act'] == 'add_desc') {
		admin_priv('goods_manage');
		$action_link = array('href' => 'goods.php?act=list', 'text' => '商品列表');
		$smarty->assign('action_link', $action_link);
		$smarty->assign('ur_here', '商品统一详情');
		$sql = 'delete from ' . $ecs->table('link_desc_temporary') . ' where 1';
		$db->query($sql);
		create_html_editor2('goods_desc', 'goods_desc', '');
		$sql = 'select id, goods_id, desc_name, goods_desc from ' . $GLOBALS['ecs']->table('link_goods_desc') . ' where 1';
		$desc_list = $db->getAll($sql);
		$smarty->assign('form_act', 'insert_link_desc');
		$smarty->assign('desc_list', $desc_list);
		set_default_filter();
		assign_query_info();
		$smarty->display('goods_desc.dwt');
	}
	else if ($_REQUEST['act'] == 'edit_link_desc') {
		admin_priv('goods_manage');
		$id = (!empty($_REQUEST['id']) ? intval($_REQUEST['id']) : '');
		$smarty->assign('ur_here', '描述编辑');
		$sql = 'delete from ' . $ecs->table('link_desc_temporary') . ' where 1';
		$db->query($sql);
		$action_link = array('href' => 'goods.php?act=add_desc', 'text' => $_LANG['go_back']);
		$smarty->assign('action_link', $action_link);
		$action_link2 = array('href' => 'goods.php?act=list', 'text' => '商品列表');
		$smarty->assign('action_link2', $action_link2);
		$other = array('id', 'desc_name', 'goods_desc');
		$goods_desc = get_table_date('link_goods_desc', 'id=\'' . $id . '\'', $other);
		$link_goods_list = get_linked_goods_desc($id);
		create_html_editor2('goods_desc', 'goods_desc', $goods_desc['goods_desc']);
		$smarty->assign('goods', $goods_desc);
		$smarty->assign('link_goods_list', $link_goods_list);
		$smarty->assign('form_act', 'update_link_desc');
		set_default_filter();
		assign_query_info();
		$smarty->display('goods_desc.dwt');
	}
	else if ($_REQUEST['act'] == 'add_link_desc') {
		include_once ROOT_PATH . 'includes/cls_json.php';
		$json = new JSON();
		check_authz_json('goods_manage');
		$linked_array = $json->decode($_GET['add_ids']);
		$linked_goods = $json->decode($_GET['JSON']);
		$id = $linked_goods[0];
		get_add_edit_link_desc($linked_array, 0, $id);
		$linked_goods = get_linked_goods_desc();
		$options = array();

		foreach ($linked_goods as $val) {
			$options[] = array('value' => $val['goods_id'], 'text' => $val['goods_name'], 'data' => '');
		}

		clear_cache_files();
		make_json_result($options);
	}
	else if ($_REQUEST['act'] == 'drop_link_desc') {
		include_once ROOT_PATH . 'includes/cls_json.php';
		$json = new JSON();
		check_authz_json('goods_manage');
		$drop_goods = $json->decode($_GET['drop_ids']);
		$linked_goods = $json->decode($_GET['JSON']);
		$id = $linked_goods[0];
		get_add_edit_link_desc($drop_goods, 1, $id);
		$linked_goods = get_linked_goods_desc();
		$options = array();

		foreach ($linked_goods as $val) {
			$options[] = array('value' => $val['goods_id'], 'text' => $val['goods_name'], 'data' => '');
		}

		if (empty($linked_goods)) {
			$sql = 'delete from ' . $ecs->table('link_desc_temporary') . ' where 1';
			$db->query($sql);
		}

		clear_cache_files();
		make_json_result($options);
	}
	else {
		if (($_REQUEST['act'] == 'insert_link_desc') || ($_REQUEST['act'] == 'update_link_desc')) {
			$desc_name = (!empty($_REQUEST['desc_name']) ? trim($_REQUEST['desc_name']) : '');
			$goods_desc = (!empty($_REQUEST['goods_desc']) ? $_REQUEST['goods_desc'] : '');
			$id = (!empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0);
			$sql = 'select goods_id from ' . $GLOBALS['ecs']->table('link_desc_temporary') . ' where 1';
			$goods_id = $GLOBALS['db']->getOne($sql);
			$other = array('goods_id' => $goods_id, 'desc_name' => $desc_name, 'goods_desc' => $goods_desc);

			if (!empty($desc_name)) {
				$sql = 'delete from ' . $GLOBALS['ecs']->table('link_desc_goodsid') . ' where d_id = \'' . $id . '\'';
				$GLOBALS['db']->query($sql);

				if (0 < $id) {
					$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('link_goods_desc'), $other, 'UPDATE', 'id = \'' . $id . '\'');
					$link_cnt = '编辑成功';
				}
				else {
					$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('link_goods_desc'), $other, 'INSERT');
					$id = $GLOBALS['db']->insert_id();
					$link_cnt = '添加成功';
				}
			}
			else {
				$link_cnt = '描述名称不能为空';
			}

			if (!empty($goods_id)) {
				get_add_desc_goodsid($goods_id, $id);
			}

			if (0 < $id) {
				$link[0] = array('text' => $_LANG['go_back'], 'href' => 'goods.php?act=edit_link_desc&id=' . $id);
			}

			$link[1] = array('text' => '添加关联商品描述', 'href' => 'goods.php?act=add_desc');
			$link[2] = array('text' => $_LANG['01_goods_list'], 'href' => 'goods.php?act=list');
			sys_msg($link_cnt, 0, $link);
		}
		else if ($_REQUEST['act'] == 'del_link_desc') {
			$id = (!empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0);
			$sql = 'delete from ' . $ecs->table('link_goods_desc') . ' where id = \'' . $id . '\'';
			$db->query($sql);
			$link[0] = array('text' => '添加关联商品描述', 'href' => 'goods.php?act=add_desc');
			$link[1] = array('text' => $_LANG['01_goods_list'], 'href' => 'goods.php?act=list');
			sys_msg('删除成功', 0, $link);
		}
		else {
			if (($_REQUEST['act'] == 'insert') || ($_REQUEST['act'] == 'update')) {
				$code = (empty($_REQUEST['extension_code']) ? '' : trim($_REQUEST['extension_code']));
				$proc_thumb = (isset($GLOBALS['shop_id']) && (0 < $GLOBALS['shop_id']) ? false : true);

				if ($code == 'virtual_card') {
					admin_priv('virualcard');
				}
				else {
					admin_priv('goods_manage');
				}

				if ($_POST['goods_sn']) {
					$sql = 'SELECT COUNT(*) FROM ' . $ecs->table('goods') . ' WHERE goods_sn = \'' . $_POST['goods_sn'] . '\' AND is_delete = 0 AND goods_id <> \'' . $_POST['goods_id'] . '\'';

					if (0 < $db->getOne($sql)) {
						sys_msg($_LANG['goods_sn_exists'], 1, array(), false);
					}
				}

				$is_insert = $_REQUEST['act'] == 'insert';
				$original_img = (empty($_REQUEST['original_img']) ? '' : trim($_REQUEST['original_img']));
				$goods_img = (empty($_REQUEST['goods_img']) ? '' : trim($_REQUEST['goods_img']));
				$goods_thumb = (empty($_REQUEST['goods_thumb']) ? '' : trim($_REQUEST['goods_thumb']));
				$is_img_url = (empty($_REQUEST['is_img_url']) ? 0 : intval($_REQUEST['is_img_url']));
				$_POST['goods_img_url'] = isset($_POST['goods_img_url']) && !empty($_POST['goods_img_url']) ? trim($_POST['goods_img_url']) : '';
				if (!empty($_POST['goods_img_url']) && ($_POST['goods_img_url'] != 'http://') && ((strpos($_POST['goods_img_url'], 'http://') !== false) || (strpos($_POST['goods_img_url'], 'https://') !== false)) && ($is_img_url == 1)) {
					$admin_temp_dir = 'seller';
					$admin_temp_dir = ROOT_PATH . 'temp' . '/' . $admin_temp_dir . '/' . 'admin_' . $admin_id;

					if (!file_exists($admin_temp_dir)) {
						make_dir($admin_temp_dir);
					}

					if (get_http_basename($_POST['goods_img_url'], $admin_temp_dir)) {
						$original_img = $admin_temp_dir . '/' . basename($_POST['goods_img_url']);
					}

					if ($original_img === false) {
						sys_msg($image->error_msg(), 1, array(), false);
					}

					$goods_img = $original_img;

					if ($_CFG['auto_generate_gallery']) {
						$img = $original_img;
						$pos = strpos(basename($img), '.');
						$newname = dirname($img) . '/' . $image->random_filename() . substr(basename($img), $pos);

						if (!copy($img, $newname)) {
							sys_msg('fail to copy file: ' . realpath('../' . $img), 1, array(), false);
						}

						$img = $newname;
						$gallery_img = $img;
						$gallery_thumb = $img;
					}

					if (($proc_thumb && (0 < $image->gd_version())) || $is_url_goods_img) {
						if (empty($is_url_goods_img)) {
							if (($_CFG['image_width'] != 0) || ($_CFG['image_height'] != 0)) {
								$goods_img = $image->make_thumb(array('img' => $goods_img, 'type' => 1), $GLOBALS['_CFG']['image_width'], $GLOBALS['_CFG']['image_height']);

								if ($goods_img === false) {
									sys_msg($image->error_msg(), 1, array(), false);
								}
							}

							if ($_CFG['auto_generate_gallery']) {
								$newname = dirname($img) . '/' . $image->random_filename() . substr(basename($img), $pos);

								if (!copy($img, $newname)) {
									sys_msg('fail to copy file: ' . realpath('../' . $img), 1, array(), false);
								}

								$gallery_img = $newname;
							}

							if ((0 < intval($_CFG['watermark_place'])) && !empty($GLOBALS['_CFG']['watermark'])) {
								if ($image->add_watermark($goods_img, '', $GLOBALS['_CFG']['watermark'], $GLOBALS['_CFG']['watermark_place'], $GLOBALS['_CFG']['watermark_alpha']) === false) {
									sys_msg($image->error_msg(), 1, array(), false);
								}

								if ($_CFG['auto_generate_gallery']) {
									if ($image->add_watermark($gallery_img, '', $GLOBALS['_CFG']['watermark'], $GLOBALS['_CFG']['watermark_place'], $GLOBALS['_CFG']['watermark_alpha']) === false) {
										sys_msg($image->error_msg(), 1, array(), false);
									}
								}
							}
						}

						if ($_CFG['auto_generate_gallery']) {
							if (($_CFG['thumb_width'] != 0) || ($_CFG['thumb_height'] != 0)) {
								$gallery_thumb = $image->make_thumb(array('img' => $img, 'type' => 1), $GLOBALS['_CFG']['thumb_width'], $GLOBALS['_CFG']['thumb_height']);

								if ($gallery_thumb === false) {
									sys_msg($image->error_msg(), 1, array(), false);
								}
							}
						}
					}

					if ($proc_thumb && !empty($original_img)) {
						if (($_CFG['thumb_width'] != 0) || ($_CFG['thumb_height'] != 0)) {
							$goods_thumb = $image->make_thumb(array('img' => $original_img, 'type' => 1), $GLOBALS['_CFG']['thumb_width'], $GLOBALS['_CFG']['thumb_height']);

							if ($goods_thumb === false) {
								sys_msg($image->error_msg(), 1, array(), false);
							}
						}
						else {
							$goods_thumb = $original_img;
						}
					}
				}

				if (empty($_POST['goods_sn'])) {
					$max_id = ($is_insert ? $db->getOne('SELECT MAX(goods_id) + 1 FROM ' . $ecs->table('goods')) : $_REQUEST['goods_id']);
					$goods_sn = generate_goods_sn($max_id);
				}
				else {
					$goods_sn = trim($_POST['goods_sn']);
				}

				$goods_img_id = (!empty($_REQUEST['img_id']) ? $_REQUEST['img_id'] : '');
				$shop_price = (!empty($_POST['shop_price']) ? trim($_POST['shop_price']) : 0);
				$shop_price = floatval($shop_price);
				$market_price = (!empty($_POST['market_price']) ? trim($_POST['market_price']) : 0);
				$market_price = floatval($market_price);
				$promote_price = (!empty($_POST['promote_price']) ? trim($_POST['promote_price']) : 0);
				$promote_price = floatval($promote_price);

				if (!isset($_POST['is_promote'])) {
					$is_promote = 0;
				}
				else {
					$is_promote = $_POST['is_promote'];
				}

				$promote_start_date = ($is_promote && !empty($_POST['promote_start_date']) ? local_strtotime($_POST['promote_start_date']) : 0);
				$promote_end_date = ($is_promote && !empty($_POST['promote_end_date']) ? local_strtotime($_POST['promote_end_date']) : 0);
				$goods_weight = (!empty($_POST['goods_weight']) ? $_POST['goods_weight'] * $_POST['weight_unit'] : 0);
				$is_best = (isset($_POST['is_best']) ? 1 : 0);
				$is_new = (isset($_POST['is_new']) ? 1 : 0);
				$is_hot = (isset($_POST['is_hot']) ? 1 : 0);
				$is_on_sale = (!empty($_POST['is_on_sale']) ? 1 : 0);
				$is_alone_sale = (!empty($_POST['is_alone_sale']) ? 1 : 0);
				$is_shipping = (!empty($_POST['is_shipping']) ? 1 : 0);
				$goods_number = (isset($_POST['goods_number']) ? $_POST['goods_number'] : 0);
				$warn_number = (isset($_POST['warn_number']) ? $_POST['warn_number'] : 0);
				$goods_type = (isset($_POST['goods_type']) ? $_POST['goods_type'] : 0);
				$give_integral = (isset($_POST['give_integral']) ? intval($_POST['give_integral']) : '-1');
				$rank_integral = (isset($_POST['rank_integral']) ? intval($_POST['rank_integral']) : '-1');
				$suppliers_id = (isset($_POST['suppliers_id']) ? intval($_POST['suppliers_id']) : '0');
				$is_volume = (isset($_POST['is_volume']) ? intval($_POST['is_volume']) : '0');
				$is_fullcut = (isset($_POST['is_fullcut']) ? intval($_POST['is_fullcut']) : '0');
				$is_distribution = (isset($_POST['is_distribution']) ? intval($_POST['is_distribution']) : 0);

				if ($is_distribution == 1) {
					$dis_commission = ((0 < $_POST['dis_commission']) && ($_POST['dis_commission'] <= 100) ? intval($_POST['dis_commission']) : 0);
				}

				$bar_code = (isset($_POST['bar_code']) && !empty($_POST['bar_code']) ? trim($_POST['bar_code']) : '');
				$goods_name_style = $_POST['goods_name_color'] . '+' . $_POST['goods_name_style'];
				$other_catids = (isset($_POST['other_catids']) ? trim($_POST['other_catids']) : '');
				$catgory_id = (empty($_POST['cat_id']) ? '' : intval($_POST['cat_id']));
				if (empty($catgory_id) && !empty($_POST['common_category'])) {
					$catgory_id = intval($_POST['common_category']);
				}

				$brand_id = (empty($_POST['brand_id']) ? '' : intval($_POST['brand_id']));
				$store_category = (!empty($_POST['store_category']) ? intval($_POST['store_category']) : 0);

				if (0 < $store_category) {
					$catgory_id = $store_category;
				}

				if ($_POST['is_stages']) {
					$stages = serialize($_POST['stages_num']);
				}
				else {
					$stages = '';
				}

				$stages_rate = (isset($_POST['stages_rate']) && !empty($_POST['stages_rate']) ? floatval($_POST['stages_rate']) : 0);
				$adminru = get_admin_ru_id();
				$model_price = (isset($_POST['model_price']) ? intval($_POST['model_price']) : 0);
				$model_inventory = (isset($_POST['model_inventory']) ? intval($_POST['model_inventory']) : 0);
				$model_attr = (isset($_POST['model_attr']) ? intval($_POST['model_attr']) : 0);
				$review_status = 1;

				if ($GLOBALS['_CFG']['review_goods'] == 0) {
					$review_status = 5;
				}
				else if (0 < $adminru['ru_id']) {
					$sql = 'select review_goods from ' . $ecs->table('merchants_shop_information') . ' where user_id = \'' . $adminru['ru_id'] . '\'';
					$review_goods = $db->getOne($sql);

					if ($review_goods == 0) {
						$review_status = 5;
					}
				}
				else {
					$review_status = 5;
				}

				$xiangou_num = (!empty($_POST['xiangou_num']) ? intval($_POST['xiangou_num']) : 0);
				$is_xiangou = (empty($xiangou_num) ? 0 : 1);
				$xiangou_start_date = ($is_xiangou && !empty($_POST['xiangou_start_date']) ? local_strtotime($_POST['xiangou_start_date']) : 0);
				$xiangou_end_date = ($is_xiangou && !empty($_POST['xiangou_end_date']) ? local_strtotime($_POST['xiangou_end_date']) : 0);
				$cfull = (isset($_POST['cfull']) ? $_POST['cfull'] : array());
				$creduce = (isset($_POST['creduce']) ? $_POST['creduce'] : array());
				$c_id = (isset($_POST['c_id']) ? $_POST['c_id'] : array());
				$sfull = (isset($_POST['sfull']) ? $_POST['sfull'] : array());
				$sreduce = (isset($_POST['sreduce']) ? $_POST['sreduce'] : array());
				$s_id = (isset($_POST['s_id']) ? $_POST['s_id'] : array());
				$largest_amount = (!empty($_POST['largest_amount']) ? trim($_POST['largest_amount']) : 0);
				$largest_amount = floatval($largest_amount);
				$group_number = (!empty($_POST['group_number']) ? intval($_POST['group_number']) : 0);
				$store_new = (isset($_POST['store_new']) ? 1 : 0);
				$store_hot = (isset($_POST['store_hot']) ? 1 : 0);
				$store_best = (isset($_POST['store_best']) ? 1 : 0);
				$goods_name = trim($_POST['goods_name']);
				$pin = new pin();
				$pinyin = $pin->Pinyin($goods_name, 'UTF8');
				$user_cat = (!empty($_POST['user_cat']) ? intval($_POST['user_cat']) : 0);
				$where_drp_sql = '';
				$where_drp_val = '';

				if (file_exists(MOBILE_DRP)) {
					$where_drp_sql = ', is_distribution, dis_commission';
					$where_drp_val = ', \'' . $is_distribution . '\', \'' . $dis_commission . '\'';
				}

				if ($is_insert) {
					if ($code == '') {
						$sql = 'INSERT INTO ' . $ecs->table('goods') . ' (goods_name, goods_name_style, goods_sn, bar_code, ' . 'cat_id, user_cat, brand_id, shop_price, market_price, is_promote, promote_price, ' . 'promote_start_date, promote_end_date, goods_img, goods_thumb, original_img, keywords, goods_brief, ' . 'seller_note, goods_weight, goods_number, warn_number, integral, give_integral, is_best, is_new, is_hot, ' . 'is_on_sale, is_alone_sale, is_shipping, goods_desc, add_time, last_update, goods_type, rank_integral, suppliers_id , goods_shipai' . ', user_id, model_price, model_inventory, model_attr, review_status' . ', group_number, store_new, store_hot, store_best' . ', goods_product_tag, is_volume, is_fullcut' . $where_drp_sql . ', is_xiangou, xiangou_num, xiangou_start_date, xiangou_end_date, largest_amount, pinyin_keyword,stages,stages_rate' . ')' . 'VALUES (\'' . $goods_name . '\', \'' . $goods_name_style . '\', \'' . $goods_sn . '\', \'' . $bar_code . '\', \'' . $catgory_id . '\', ' . '\'' . $user_cat . '\', \'' . $brand_id . '\', \'' . $shop_price . '\', \'' . $market_price . '\', \'' . $is_promote . '\',\'' . $promote_price . '\', ' . '\'' . $promote_start_date . '\', \'' . $promote_end_date . '\', \'' . $goods_img . '\', \'' . $goods_thumb . '\', \'' . $original_img . '\', ' . '\'' . $_POST['keywords'] . '\', \'' . $_POST['goods_brief'] . '\', \'' . $_POST['seller_note'] . '\', \'' . $goods_weight . '\', \'' . $goods_number . '\',' . ' \'' . $warn_number . '\', \'' . $_POST['integral'] . '\', \'' . $give_integral . '\', \'' . $is_best . '\', \'' . $is_new . '\', \'' . $is_hot . '\', \'' . $is_on_sale . '\', \'' . $is_alone_sale . '\', ' . $is_shipping . ', ' . ' \'' . $_POST['goods_desc'] . '\', \'' . gmtime() . '\', \'' . gmtime() . '\', \'' . $goods_type . '\', \'' . $rank_integral . '\', \'' . $suppliers_id . '\' , \'' . $_POST['goods_shipai'] . '\'' . ', \'' . $adminru['ru_id'] . '\', \'' . $model_price . '\', \'' . $model_inventory . '\', \'' . $model_attr . '\', \'' . $review_status . '\'' . ', \'' . $group_number . '\', \'' . $store_new . '\', \'' . $store_hot . '\', \'' . $store_best . '\'' . ', \'' . $_POST['goods_product_tag'] . '\', \'' . $is_volume . '\', \'' . $is_fullcut . '\'' . $where_drp_val . ', \'' . $is_xiangou . '\', \'' . $xiangou_num . '\', \'' . $xiangou_start_date . '\', \'' . $xiangou_end_date . '\', \'' . $largest_amount . '\', \'' . $pinyin . '\',\'' . $stages . '\',\'' . $stages_rate . '\'' . ')';
					}
					else {
						$sql = 'INSERT INTO ' . $ecs->table('goods') . ' (goods_name, goods_name_style, goods_sn, bar_code, ' . 'cat_id, user_cat, brand_id, shop_price, market_price, is_promote, promote_price, ' . 'promote_start_date, promote_end_date, goods_img, goods_thumb, original_img, keywords, goods_brief, ' . 'seller_note, goods_weight, goods_number, warn_number, integral, give_integral, is_best, is_new, is_hot, is_real, ' . 'is_on_sale, is_alone_sale, is_shipping, goods_desc, add_time, last_update, goods_type, extension_code, rank_integral ,  goods_shipai' . ', user_id, model_price, model_inventory, model_attr, review_status' . ', group_number, store_new, store_hot, store_best' . ', goods_product_tag, is_volume, is_fullcut' . $where_drp_sql . ', is_xiangou, xiangou_num, xiangou_start_date, xiangou_end_date, largest_amount, pinyin_keyword,stages,stages_rate' . ')' . 'VALUES (\'' . $goods_name . '\', \'' . $goods_name_style . '\', \'' . $goods_sn . '\', \'' . $bar_code . '\', \'' . $catgory_id . '\', ' . '\'' . $user_cat . '\', \'' . $brand_id . '\', \'' . $shop_price . '\', \'' . $market_price . '\', \'' . $is_promote . '\',\'' . $promote_price . '\', ' . '\'' . $promote_start_date . '\', \'' . $promote_end_date . '\', \'' . $goods_img . '\', \'' . $goods_thumb . '\', \'' . $original_img . '\', ' . '\'' . $_POST['keywords'] . '\', \'' . $_POST['goods_brief'] . '\', \'' . $_POST['seller_note'] . '\', \'' . $goods_weight . '\', \'' . $goods_number . '\',' . ' \'' . $warn_number . '\', \'' . $_POST['integral'] . '\', \'' . $give_integral . '\', \'' . $is_best . '\', \'' . $is_new . '\', \'' . $is_hot . '\', 0, \'' . $is_on_sale . '\', \'' . $is_alone_sale . '\', ' . $is_shipping . ', ' . ' \'' . $_POST['goods_desc'] . '\', \'' . gmtime() . '\', \'' . gmtime() . '\', \'' . $goods_type . '\', \'' . $code . '\', \'' . $rank_integral . '\' , \'' . $_POST['goods_shipai'] . '\'' . ', \'' . $adminru['ru_id'] . '\', \'' . $model_price . '\', \'' . $model_inventory . '\', \'' . $model_attr . '\', \'' . $review_status . '\'' . ', \'' . $group_number . '\', \'' . $store_new . '\', \'' . $store_hot . '\', \'' . $store_best . '\'' . ', \'' . $_POST['goods_product_tag'] . '\', \'' . $is_volume . '\', \'' . $is_fullcut . '\'' . $where_drp_val . ', \'' . $is_xiangou . '\', \'' . $xiangou_num . '\', \'' . $xiangou_start_date . '\', \'' . $xiangou_end_date . '\', \'' . $largest_amount . '\', \'' . $pinyin . '\',\'' . $stages . '\',\'' . $stages_rate . '\'' . ')';
					}

					$not_number = (!empty($goods_number) ? 1 : 0);
					$number = '+ ' . $goods_number;
					$use_storage = 7;
				}
				else {
					$sql = 'SELECT goods_thumb, goods_img, original_img ' . ' FROM ' . $ecs->table('goods') . ' WHERE goods_id = \'' . $_REQUEST['goods_id'] . '\' LIMIT 1';
					$row = $db->getRow($sql);

					if ($goods_img) {
						if ($goods_img != $row['goods_img']) {
							@unlink(ROOT_PATH . $row['goods_img']);
							@unlink(ROOT_PATH . $row['original_img']);
						}
					}

					if ($goods_thumb) {
						if ($goods_thumb != $row['goods_thumb']) {
							@unlink(ROOT_PATH . $row['goods_thumb']);
						}
					}

					get_goods_file_content($_REQUEST['goods_id'], $GLOBALS['_CFG']['goods_file'], $adminru['ru_id'], $review_goods, $model_attr);
					$where_drp_up = '';

					if (file_exists(MOBILE_DRP)) {
						$where_drp_up = 'dis_commission = \'' . $dis_commission . '\', ' . 'is_distribution = \'' . $is_distribution . '\', ';
					}

					$sql = 'UPDATE ' . $ecs->table('goods') . ' SET ' . 'goods_name = \'' . $goods_name . '\', ' . 'goods_name_style = \'' . $goods_name_style . '\', ' . 'goods_sn = \'' . $goods_sn . '\', ' . 'bar_code = \'' . $bar_code . '\', ' . 'cat_id = \'' . $catgory_id . '\', ' . 'brand_id = \'' . $brand_id . '\', ' . 'shop_price = \'' . $shop_price . '\', ' . 'market_price = \'' . $market_price . '\', ' . 'is_promote = \'' . $is_promote . '\', ' . 'is_volume = \'' . $is_volume . '\', ' . 'is_fullcut = \'' . $is_fullcut . '\', ' . 'model_price = \'' . $model_price . '\', ' . 'model_inventory = \'' . $model_inventory . '\', ' . 'model_attr = \'' . $model_attr . '\', ' . 'largest_amount = \'' . $largest_amount . '\', ' . 'group_number = \'' . $group_number . '\',' . 'store_new = \'' . $store_new . '\',' . 'store_hot = \'' . $store_hot . '\',' . 'store_best = \'' . $store_best . '\',' . 'is_xiangou=\'' . $is_xiangou . '\',' . 'xiangou_num = \'' . $xiangou_num . '\',' . 'xiangou_start_date = \'' . $xiangou_start_date . '\',' . 'xiangou_end_date = \'' . $xiangou_end_date . '\',' . 'goods_product_tag = \'' . $_POST['goods_product_tag'] . '\', ' . 'pinyin_keyword = \'' . $pinyin . '\', ' . 'stages = \'' . $stages . '\', ' . 'stages_rate = \'' . $stages_rate . '\', ' . 'user_cat = \'' . $user_cat . '\', ' . $where_drp_up . 'promote_price = \'' . $promote_price . '\', ' . 'promote_start_date = \'' . $promote_start_date . '\', ' . 'suppliers_id = \'' . $suppliers_id . '\', ' . 'promote_end_date = \'' . $promote_end_date . '\', ';

					if ($goods_img) {
						$sql .= 'goods_img = \'' . $goods_img . '\', original_img = \'' . $original_img . '\', ';
					}

					if ($goods_thumb) {
						$sql .= 'goods_thumb = \'' . $goods_thumb . '\', ';
					}

					if ($code != '') {
						$sql .= 'is_real=0, extension_code=\'' . $code . '\', ';
					}

					$sql .= 'keywords = \'' . $_POST['keywords'] . '\', ' . 'goods_brief = \'' . $_POST['goods_brief'] . '\', ' . 'seller_note = \'' . $_POST['seller_note'] . '\', ' . 'goods_weight = \'' . $goods_weight . '\',' . 'goods_number = \'' . $goods_number . '\', ' . 'warn_number = \'' . $warn_number . '\', ' . 'integral = \'' . $_POST['integral'] . '\', ' . 'give_integral = \'' . $give_integral . '\', ' . 'rank_integral = \'' . $rank_integral . '\', ' . 'is_best = \'' . $is_best . '\', ' . 'is_new = \'' . $is_new . '\', ' . 'is_hot = \'' . $is_hot . '\', ' . 'is_on_sale = \'' . $is_on_sale . '\', ' . 'is_alone_sale = \'' . $is_alone_sale . '\', ' . 'is_shipping = \'' . $is_shipping . '\', ' . 'goods_desc = \'' . $_POST['goods_desc'] . '\', ' . 'goods_shipai = \'' . $_POST['goods_shipai'] . '\', ' . 'last_update = \'' . gmtime() . '\', ' . 'goods_type = \'' . $goods_type . '\' ' . 'WHERE goods_id = \'' . $_REQUEST['goods_id'] . '\' LIMIT 1';
					$goodsInfo = get_admin_goods_info($_REQUEST['goods_id'], array('goods_number'));

					if ($goodsInfo['goods_number'] < $goods_number) {
						$not_number = $goods_number - $goodsInfo['goods_number'];
						$not_number = (!empty($not_number) ? 1 : 0);
						$number = $goods_number - $goodsInfo['goods_number'];
						$number = '+ ' . $number;
						$use_storage = 13;
					}
					else {
						$not_number = $goodsInfo['goods_number'] - $goods_number;
						$not_number = (!empty($not_number) ? 1 : 0);
						$number = $goodsInfo['goods_number'] - $goods_number;
						$number = '- ' . $number;
						$use_storage = 8;
					}
				}

				$db->query($sql);
				$goods_id = ($is_insert ? $db->insert_id() : $_REQUEST['goods_id']);

				if ($is_insert) {
					if ($other_catids) {
						$other_catids = get_del_str_comma($other_catids);
						$sql = 'UPDATE' . $ecs->table('goods_cat') . ' SET goods_id=\'' . $goods_id . '\' WHERE goods_id = 0 AND cat_id in (' . $other_catids . ')';
						$db->query($sql);
					}
				}

				if ($goods_id) {
					$is_reality = (!empty($_POST['is_reality']) ? intval($_POST['is_reality']) : 0);
					$is_return = (!empty($_POST['is_return']) ? intval($_POST['is_return']) : 0);
					$is_fast = (!empty($_POST['is_fast']) ? intval($_POST['is_fast']) : 0);
					$extend = $db->getOne('select count(goods_id) from ' . $ecs->table('goods_extend') . ' where goods_id=\'' . $goods_id . '\'');

					if (0 < $extend) {
						$extend_sql = 'update ' . $ecs->table('goods_extend') . ' SET `is_reality`=\'' . $is_reality . '\',`is_return`=\'' . $is_return . '\',`is_fast`=\'' . $is_fast . '\' WHERE goods_id=\'' . $goods_id . '\'';
					}
					else {
						$extend_sql = 'INSERT INTO ' . $ecs->table('goods_extend') . '(`goods_id`, `is_reality`, `is_return`, `is_fast`) VALUES (\'' . $goods_id . '\',\'' . $is_reality . '\',\'' . $is_return . '\',\'' . $is_fast . '\')';
					}

					$db->query($extend_sql);
					get_updel_goods_attr($goods_id);
				}

				if ($not_number) {
					$logs_other = array('goods_id' => $goods_id, 'order_id' => 0, 'use_storage' => $use_storage, 'admin_id' => $_SESSION['admin_id'], 'number' => $number, 'model_inventory' => $model_inventory, 'model_attr' => $model_attr, 'product_id' => 0, 'warehouse_id' => 0, 'area_id' => 0, 'add_time' => gmtime());
					$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('goods_inventory_logs'), $logs_other, 'INSERT');
				}

				get_goods_payfull($is_fullcut, $cfull, $creduce, $c_id, $goods_id, 'goods_consumption');

				if ($is_insert) {
					if ($model_price == 1) {
						$warehouse_id = (isset($_POST['warehouse_id']) ? $_POST['warehouse_id'] : array());

						if ($warehouse_id) {
							$warehouse_id = implode(',', $warehouse_id);
							$db->query(' UPDATE ' . $ecs->table('warehouse_goods') . ' SET goods_id = \'' . $goods_id . '\' WHERE w_id ' . db_create_in($warehouse_id));
						}
					}
					else if ($model_price == 2) {
						$warehouse_area_id = (isset($_POST['warehouse_area_id']) ? $_POST['warehouse_area_id'] : array());

						if ($warehouse_id) {
							$warehouse_area_id = implode(',', $warehouse_area_id);
							$db->query(' UPDATE ' . $ecs->table('warehouse_area_goods') . ' SET goods_id = \'' . $goods_id . '\' WHERE a_id ' . db_create_in($warehouse_area_id));
						}
					}

					admin_log($_POST['goods_name'], 'add', 'goods');
				}
				else {
					admin_log($_POST['goods_name'], 'edit', 'goods');
					$shop_price_format = price_format($shop_price);
					$sql = 'SELECT * FROM ' . $ecs->table('sale_notice') . ' WHERE goods_id=\'' . $_REQUEST['goods_id'] . '\' AND STATUS!=1';
					$notice_list = $db->getAll($sql);

					foreach ($notice_list as $key => $val) {
						$sql = ' select user_name from ' . $GLOBALS['ecs']->table('users') . ' where user_id=\'' . $val['user_id'] . '\' ';
						$user_info = $GLOBALS['db']->getRow($sql);
						$user_name = $user_info['user_name'];
						$send_ok = 0;
						if (($shop_price <= $val['hopeDiscount']) && $val['cellphone'] && ($_CFG['sms_price_notice'] == '1')) {
							if ($goods['user_id']) {
								$shop_name = get_shop_name($goods['user_id'], 1);
							}
							else {
								$shop_name = $GLOBALS['_CFG']['shop_name'];
							}

							if ($GLOBALS['_CFG']['sms_type'] == 0) {
								$goods_name = sub_str($_POST['goods_name'], 15);
								$msg = sprintf($GLOBALS['_LANG']['sale_notice_sms'], $goods_name, $shop_price_format);
								include_once '../includes/cls_sms.php';
								$sms = new sms();
								$res = $sms->send($val['cellphone'], $msg, '', '60', 1);
							}
							else if ($GLOBALS['_CFG']['sms_type'] == 1) {
								$user_info = get_admin_user_info($val['user_id']);
								$smsParams = array('user_name' => $user_info['user_name'], 'goods_sn' => $goods_sn, 'mobile_phone' => $val['cellphone']);
								$result = sms_ali($smsParams, 'sms_price_notic');

								if ($result) {
									$resp = $GLOBALS['ecs']->ali_yu($result);
								}
								else {
									sys_msg('阿里大鱼短信配置异常', 1);
								}
							}

							$send_type = 2;

							if ($res) {
								$sql = 'UPDATE ' . $ecs->table('sale_notice') . ' SET status = 1, send_type=2 WHERE goods_id = \'' . $_REQUEST['goods_id'] . '\' AND user_id=\'' . $val['user_id'] . '\'';
								$db->query($sql);
								$send_ok = 1;
								notice_log($goods_id, $val['cellphone'], $send_ok, $send_type);
							}
							else {
								$sql = 'UPDATE ' . $ecs->table('sale_notice') . ' SET status = 3, send_type=2 WHERE goods_id = \'' . $_REQUEST['goods_id'] . '\' AND user_id=\'' . $val['user_id'] . '\'';
								$db->query($sql);
								$send_ok = 0;
								notice_log($goods_id, $val['cellphone'], $send_ok, $send_type);
							}
						}

						if (($send_ok == 0) && ($shop_price <= $val['hopeDiscount']) && $val['email']) {
							$template = get_mail_template('sale_notice');
							$smarty->assign('user_name', $user_name);
							$smarty->assign('goods_name', $_POST['goods_name']);
							$smarty->assign('goods_link', $ecs->url() . 'goods.php?id=' . $_REQUEST['goods_id']);
							$smarty->assign('send_date', local_date($GLOBALS['_CFG']['time_format'], gmtime()));
							$content = $smarty->fetch('str:' . $template['template_content']);
							$send_type = 1;

							if (send_mail($user_name, $val['email'], $template['template_subject'], $content, $template['is_html'])) {
								$sql = 'UPDATE ' . $ecs->table('sale_notice') . ' SET status = 1, send_type=1 WHERE goods_id = \'' . $_REQUEST['goods_id'] . '\' AND user_id=\'' . $val['user_id'] . '\'';
								$db->query($sql);
								$send_ok = 1;
								notice_log($goods_id, $val['email'], $send_ok, $send_type);
							}
							else {
								$sql = 'UPDATE ' . $ecs->table('sale_notice') . ' SET status = 3, send_type=1 WHERE goods_id = \'' . $_REQUEST['goods_id'] . '\' AND user_id=\'' . $val['user_id'] . '\'';
								$db->query($sql);
								$send_ok = 0;
								notice_log($goods_id, $val['email'], $send_ok, $send_type);
							}
						}
					}
				}

				if ((isset($_POST['attr_id_list']) && isset($_POST['attr_value_list'])) || (empty($_POST['attr_id_list']) && empty($_POST['attr_value_list']))) {
					$goods_attr_list = array();
					$keywords_arr = explode(' ', $_POST['keywords']);
					$keywords_arr = array_flip($keywords_arr);

					if (isset($keywords_arr[''])) {
						unset($keywords_arr['']);
					}

					$sql = 'SELECT attr_id, attr_index FROM ' . $ecs->table('attribute') . ' WHERE cat_id = \'' . $goods_type . '\'';
					$attr_res = $db->query($sql);
					$attr_list = array();

					while ($row = $db->fetchRow($attr_res)) {
						$attr_list[$row['attr_id']] = $row['attr_index'];
					}

					$sql = "SELECT g.*, a.attr_type\r\n                FROM " . $ecs->table('goods_attr') . " AS g\r\n                    LEFT JOIN " . $ecs->table('attribute') . " AS a\r\n                        ON a.attr_id = g.attr_id\r\n                WHERE g.goods_id = '" . $goods_id . '\'';
					$res = $db->query($sql);

					while ($row = $db->fetchRow($res)) {
						$goods_attr_list[$row['attr_id']][$row['attr_value']] = array('sign' => 'delete', 'goods_attr_id' => $row['goods_attr_id']);
					}

					if (isset($_POST['attr_id_list'])) {
						foreach ($_POST['attr_id_list'] as $key => $attr_id) {
							$attr_value = $_POST['attr_value_list'][$key];
							$attr_price = $_POST['attr_price_list'][$key];
							$attr_sort = $_POST['attr_sort_list'][$key];

							if (!empty($attr_value)) {
								if (isset($goods_attr_list[$attr_id][$attr_value])) {
									$goods_attr_list[$attr_id][$attr_value]['sign'] = 'update';
									$goods_attr_list[$attr_id][$attr_value]['attr_price'] = $attr_price;
									$goods_attr_list[$attr_id][$attr_value]['attr_sort'] = $attr_sort;
								}
								else {
									$goods_attr_list[$attr_id][$attr_value]['sign'] = 'insert';
									$goods_attr_list[$attr_id][$attr_value]['attr_price'] = $attr_price;
									$goods_attr_list[$attr_id][$attr_value]['attr_sort'] = $attr_sort;
								}

								$val_arr = explode(' ', $attr_value);

								foreach ($val_arr as $k => $v) {
									if (!isset($keywords_arr[$v]) && ($attr_list[$attr_id] == '1')) {
										$keywords_arr[$v] = $v;
									}
								}
							}
						}
					}

					$keywords_arr2 = array();

					if (isset($_POST['gallery_attr_id'])) {
						foreach ($_POST['gallery_attr_id'] as $key => $attr_id) {
							$gallery_attr_value = $_POST['gallery_attr_value'][$key];
							$gallery_attr_price = $_POST['gallery_attr_price'][$key];
							$gallery_attr_sort = $_POST['gallery_attr_sort'][$key];

							if (!empty($gallery_attr_value)) {
								if (isset($goods_attr_list[$attr_id][$gallery_attr_value])) {
									$goods_attr_list[$attr_id][$gallery_attr_value]['sign'] = 'update';
									$goods_attr_list[$attr_id][$gallery_attr_value]['attr_price'] = $gallery_attr_price;
									$goods_attr_list[$attr_id][$gallery_attr_value]['attr_sort'] = $gallery_attr_sort;
								}
								else {
									$goods_attr_list[$attr_id][$gallery_attr_value]['sign'] = 'insert';
									$goods_attr_list[$attr_id][$gallery_attr_value]['attr_price'] = $gallery_attr_price;
									$goods_attr_list[$attr_id][$gallery_attr_value]['attr_sort'] = $gallery_attr_sort;
								}

								$val_arr2 = explode(' ', $gallery_attr_value);

								foreach ($val_arr2 as $k => $v) {
									if (!isset($keywords_arr[$v]) && ($attr_list[$attr_id] == '1')) {
										$keywords_arr2[$v] = $v;
									}
								}
							}
						}
					}

					if ($keywords_arr && $keywords_arr2) {
						$keywords_arr = array_merge($keywords_arr, $keywords_arr2);
					}
					else if (empty($keywords_arr)) {
						$keywords_arr = $keywords_arr2;
					}

					$keywords = join(' ', array_flip($keywords_arr));
					$sql = 'UPDATE ' . $ecs->table('goods') . ' SET keywords = \'' . $keywords . '\' WHERE goods_id = \'' . $goods_id . '\' LIMIT 1';
					$db->query($sql);

					foreach ($goods_attr_list as $attr_id => $attr_value_list) {
						foreach ($attr_value_list as $attr_value => $info) {
							if ($info['sign'] == 'insert') {
								$sql = 'INSERT INTO ' . $ecs->table('goods_attr') . ' (attr_id, goods_id, attr_value, attr_price, attr_sort)' . 'VALUES (\'' . $attr_id . '\', \'' . $goods_id . '\', \'' . $attr_value . '\', \'' . $info['attr_price'] . '\', \'' . $info['attr_sort'] . '\')';
							}
							else if ($info['sign'] == 'update') {
								$sql = 'UPDATE ' . $ecs->table('goods_attr') . ' SET attr_price = \'' . $info['attr_price'] . '\', attr_sort = \'' . $info['attr_sort'] . '\' WHERE goods_attr_id = \'' . $info['goods_attr_id'] . '\' LIMIT 1';
							}
							else {
								if ($model_attr == 1) {
									$table = 'products_warehouse';
								}
								else if ($model_attr == 2) {
									$table = 'products_area';
								}
								else {
									$table = 'products';
								}

								$where = ' AND goods_id = \'' . $goods_id . '\'';
								$ecs->get_del_find_in_set($info['goods_attr_id'], $where, $table, 'goods_attr', '|');
								$sql = 'DELETE FROM ' . $ecs->table('goods_attr') . ' WHERE goods_attr_id = \'' . $info['goods_attr_id'] . '\' LIMIT 1';
							}

							$db->query($sql);
						}
					}
				}

				if (isset($_POST['user_rank']) && isset($_POST['user_price'])) {
					if (empty($_POST['goods_user_price'])) {
						foreach ($_POST['user_price'] as $k => $v) {
							$_POST['user_price'][$k] = -1;
						}

						handle_member_price($goods_id, $_POST['user_rank'], $_POST['user_price']);
					}
					else {
						handle_member_price($goods_id, $_POST['user_rank'], $_POST['user_price']);
					}
				}

				if (isset($_POST['volume_number']) && isset($_POST['volume_price'])) {
					handle_volume_price($goods_id, $is_volume, $_POST['volume_number'], $_POST['volume_price'], $_POST['id']);
				}

				if (isset($_POST['other_cat'])) {
					handle_other_cat($goods_id, array_unique($_POST['other_cat']));
				}

				if ($is_insert) {
					handle_link_goods($goods_id);
					handle_group_goods($goods_id);
					handle_goods_article($goods_id);
					handle_goods_area($goods_id);
					$thumb_img_id = $_SESSION['thumb_img_id' . $_SESSION['admin_id']];

					if ($thumb_img_id) {
						$sql = ' UPDATE ' . $ecs->table('goods_gallery') . ' SET goods_id = \'' . $goods_id . '\' WHERE goods_id = 0 AND img_id ' . db_create_in($thumb_img_id);
						$db->query($sql);
					}

					unset($_SESSION['thumb_img_id' . $_SESSION['admin_id']]);
				}

				if (!empty($_POST['goods_img_url']) && ($is_img_url == 1)) {
					$original_img = reformat_image_name('goods', $goods_id, $original_img, 'source');
					$goods_img = reformat_image_name('goods', $goods_id, $goods_img, 'goods');
					$goods_thumb = reformat_image_name('goods_thumb', $goods_id, $goods_thumb, 'thumb');
					$sql = ' UPDATE ' . $ecs->table('goods') . ' SET goods_thumb = \'' . $goods_thumb . '\', goods_img = \'' . $goods_img . '\', original_img = \'' . $original_img . '\' WHERE goods_id = \'' . $goods_id . '\' ';
					$db->query($sql);

					if (isset($img)) {
						if (empty($is_url_goods_img)) {
							$img = reformat_image_name('gallery', $goods_id, $img, 'source');
							$gallery_img = reformat_image_name('gallery', $goods_id, $gallery_img, 'goods');
						}
						else {
							$img = $url_goods_img;
							$gallery_img = $url_goods_img;
						}

						$gallery_thumb = reformat_image_name('gallery_thumb', $goods_id, $gallery_thumb, 'thumb');
						$sql = 'INSERT INTO ' . $ecs->table('goods_gallery') . ' (goods_id, img_url, thumb_url, img_original) ' . 'VALUES (\'' . $goods_id . '\', \'' . $gallery_img . '\', \'' . $gallery_thumb . '\', \'' . $img . '\')';
						$db->query($sql);
					}

					get_oss_add_file(array($goods_img, $goods_thumb, $original_img, $gallery_img, $gallery_thumb, $img));
				}
				else {
					get_oss_add_file(array($goods_img, $goods_thumb, $original_img));
				}

				setcookie('ECSCP[last_choose]', $catgory_id . '|' . $brand_id, gmtime() + 86400);
				$where_products = '';
				$goods_model = (isset($_POST['goods_model']) && !empty($_POST['goods_model']) ? intval($_POST['goods_model']) : 0);
				$warehouse = (isset($_POST['warehouse']) && !empty($_POST['warehouse']) ? intval($_POST['warehouse']) : 0);
				$region = (isset($_POST['region']) && !empty($_POST['region']) ? intval($_POST['region']) : 0);

				if ($goods_model == 1) {
					$table = 'products_warehouse';
					$region_id = $warehouse;
					$products_extension_insert_name = ' , warehouse_id ';
					$products_extension_insert_value = ' , \'' . $warehouse . '\' ';
					$where_products .= ' AND warehouse_id = \'' . $warehouse . '\' ';
				}
				else if ($goods_model == 2) {
					$table = 'products_area';
					$region_id = $region;
					$products_extension_insert_name = ' , area_id ';
					$products_extension_insert_value = ' , \'' . $region . '\' ';
					$where_products .= ' AND area_id = \'' . $region . '\' ';
				}
				else {
					$table = 'products';
					$products_extension_insert_name = '';
					$products_extension_insert_value = '';
				}

				$product['goods_id'] = $goods_id;
				$product['attr'] = $_POST['attr'];
				$product['product_id'] = $_POST['product_id'];
				$product['product_sn'] = $_POST['product_sn'];
				$product['product_number'] = $_POST['product_number'];
				$product['product_price'] = $_POST['product_price'];
				$product['product_market_price'] = $_POST['product_market_price'];
				$product['product_warn_number'] = $_POST['product_warn_number'];
				$product['bar_code'] = $_POST['product_bar_code'];

				if (empty($product['goods_id'])) {
					sys_msg($_LANG['sys']['wrong'] . $_LANG['cannot_found_goods'], 1, array(), false);
				}

				$sql = 'SELECT goods_sn, goods_name, goods_type, shop_price, model_inventory, model_attr FROM ' . $ecs->table('goods') . ' WHERE goods_id = \'' . $goods_id . '\' LIMIT 1';
				$goods = $db->getRow($sql);

				if (empty($product['product_sn'])) {
					$product['product_sn'] = array();
				}

				foreach ($product['product_sn'] as $key => $value) {
					$product['product_number'][$key] = trim($product['product_number'][$key]);
					$product['product_id'][$key] = isset($product['product_id'][$key]) && !empty($product['product_id'][$key]) ? intval($product['product_id'][$key]) : 0;
					$logs_other = array('goods_id' => $goods_id, 'order_id' => 0, 'admin_id' => $_SESSION['admin_id'], 'model_inventory' => $goods['model_inventory'], 'model_attr' => $goods['model_attr'], 'add_time' => gmtime());

					if ($goods_model == 1) {
						$logs_other['warehouse_id'] = $warehouse;
						$logs_other['area_id'] = 0;
					}
					else if ($goods_model == 2) {
						$logs_other['warehouse_id'] = 0;
						$logs_other['area_id'] = $region;
					}
					else {
						$logs_other['warehouse_id'] = 0;
						$logs_other['area_id'] = 0;
					}

					if ($product['product_id'][$key]) {
						$goods_product = get_product_info($product['product_id'][$key], 'product_number', $goods_model);

						if ($goods_product['product_number'] != $product['product_number'][$key]) {
							if ($product['product_number'][$key] < $goods_product['product_number']) {
								$number = $goods_product['product_number'] - $product['product_number'][$key];
								$number = '- ' . $number;
								$logs_other['use_storage'] = 10;
							}
							else {
								$number = $product['product_number'][$key] - $goods_product['product_number'];
								$number = '+ ' . $number;
								$logs_other['use_storage'] = 11;
							}

							$logs_other['number'] = $number;
							$logs_other['product_id'] = $product['product_id'][$key];
							$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('goods_inventory_logs'), $logs_other, 'INSERT');
						}

						$sql = 'UPDATE ' . $GLOBALS['ecs']->table($table) . ' SET product_number = \'' . $product['product_number'][$key] . '\', ' . ' product_price = \'' . $product['product_price'][$key] . '\', ' . ' product_warn_number = \'' . $product['product_warn_number'][$key] . '\'' . ' WHERE product_id = \'' . $product['product_id'][$key] . '\'';
						$GLOBALS['db']->query($sql);
					}
					else {
						$number = 0;

						foreach ($product['attr'] as $attr_key => $attr_value) {
							if (empty($attr_value[$key])) {
								continue 2;
							}

							$is_spec_list[$attr_key] = 'true';
							$value_price_list[$attr_key] = $attr_value[$key] . chr(9) . '';
							$id_list[$attr_key] = $attr_key;
						}

						$goods_attr_id = handle_goods_attr($product['goods_id'], $id_list, $is_spec_list, $value_price_list);
						$goods_attr = sort_goods_attr_id_array($goods_attr_id);

						if (!empty($goods_attr['sort'])) {
							$goods_attr = implode('|', $goods_attr['sort']);
						}
						else {
							$goods_attr = '';
						}

						if (check_goods_attr_exist($goods_attr, $product['goods_id'], 0, $region_id)) {
							continue;
						}

						$sql = 'INSERT INTO ' . $GLOBALS['ecs']->table($table) . ' (goods_id, goods_attr, product_sn, product_number, product_price, product_market_price, product_warn_number, bar_code ' . $products_extension_insert_name . ') VALUES ' . ' (\'' . $product['goods_id'] . '\', \'' . $goods_attr . '\', \'' . $value . '\', \'' . $product['product_number'][$key] . '\', \'' . $product['product_price'][$key] . '\', \'' . $product['product_market_price'][$key] . '\', \'' . $product['product_warn_number'][$key] . '\', \'' . $product['bar_code'][$key] . '\' ' . $products_extension_insert_value . ')';

						if (!$GLOBALS['db']->query($sql)) {
							continue;
						}
						else {
							$product_id = $GLOBALS['db']->insert_id();

							if (empty($value)) {
								$sql = 'UPDATE ' . $GLOBALS['ecs']->table($table) . "\r\n                            SET product_sn = '" . $goods['goods_sn'] . 'g_p' . $GLOBALS['db']->insert_id() . "'\r\n                            WHERE product_id = '" . $product_id . '\'';
								$GLOBALS['db']->query($sql);
							}

							$number = '+ ' . $product['product_number'][$key];
							$logs_other['use_storage'] = 9;
							$logs_other['product_id'] = $product_id;
							$logs_other['number'] = $number;
							$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('goods_inventory_logs'), $logs_other, 'INSERT');
						}
					}
				}

				clear_cache_files();
				$link = array();

				if ($code == 'virtual_card') {
					$link[1] = array('href' => 'virtual_card.php?act=replenish&goods_id=' . $goods_id, 'text' => $_LANG['add_replenish']);
				}

				if ($is_insert) {
					$link[2] = add_link($code);
				}

				$link[3] = list_link($is_insert, $code);

				for ($i = 0; $i < count($link); $i++) {
					$key_array[] = $i;
				}

				krsort($link);
				$link = array_combine($key_array, $link);
				sys_msg($is_insert ? $_LANG['add_goods_ok'] : $_LANG['edit_goods_ok'], 0, $link);
			}
			else if ($_REQUEST['act'] == 'batch') {
				$code = (empty($_REQUEST['extension_code']) ? '' : trim($_REQUEST['extension_code']));
				$goods_id = (!empty($_POST['checkboxes']) ? join(',', $_POST['checkboxes']) : 0);

				if (isset($_POST['type'])) {
					if ($_POST['type'] == 'trash') {
						admin_priv('remove_back');
						update_goods($goods_id, 'is_delete', '1');
						admin_log('', 'batch_trash', 'goods');
					}
					else if ($_POST['type'] == 'on_sale') {
						admin_priv('goods_manage');
						update_goods($goods_id, 'is_on_sale', '1');
					}
					else if ($_POST['type'] == 'not_on_sale') {
						admin_priv('goods_manage');
						update_goods($goods_id, 'is_on_sale', '0');
					}
					else if ($_POST['type'] == 'best') {
						admin_priv('goods_manage');
						update_goods($goods_id, 'is_best', '1');
					}
					else if ($_POST['type'] == 'not_best') {
						admin_priv('goods_manage');
						update_goods($goods_id, 'is_best', '0');
					}
					else if ($_POST['type'] == 'new') {
						admin_priv('goods_manage');
						update_goods($goods_id, 'is_new', '1');
					}
					else if ($_POST['type'] == 'not_new') {
						admin_priv('goods_manage');
						update_goods($goods_id, 'is_new', '0');
					}
					else if ($_POST['type'] == 'hot') {
						admin_priv('goods_manage');
						update_goods($goods_id, 'is_hot', '1');
					}
					else if ($_POST['type'] == 'not_hot') {
						admin_priv('goods_manage');
						update_goods($goods_id, 'is_hot', '0');
					}
					else if ($_POST['type'] == 'move_to') {
						admin_priv('goods_manage');
						update_goods($goods_id, 'cat_id', $_POST['target_cat']);
					}
					else if ($_POST['type'] == 'suppliers_move_to') {
						admin_priv('goods_manage');
						update_goods($goods_id, 'suppliers_id', $_POST['suppliers_id']);
					}
					else if ($_POST['type'] == 'restore') {
						admin_priv('remove_back');
						update_goods($goods_id, 'is_delete', '0');
						admin_log('', 'batch_restore', 'goods');
					}
					else if ($_POST['type'] == 'drop') {
						admin_priv('remove_back');
						delete_goods($goods_id);
						admin_log('', 'batch_remove', 'goods');
					}
					else if ($_POST['type'] == 'review_to') {
						admin_priv('remove_back');
						update_goods($goods_id, 'review_status', $_POST['review_status'], $_POST['review_content']);
						admin_log('', 'review_to', 'goods');
					}
				}

				clear_cache_files();
				if (($_POST['type'] == 'drop') || ($_POST['type'] == 'restore')) {
					$link[] = array('href' => 'goods.php?act=trash', 'text' => $_LANG['11_goods_trash']);
				}
				else {
					$link[] = list_link(true, $code);
				}

				sys_msg($_LANG['batch_handle_ok'], 0, $link);
			}
			else if ($_REQUEST['act'] == 'show_image') {
				if (isset($GLOBALS['shop_id']) && (0 < $GLOBALS['shop_id'])) {
					$img_url = $_GET['img_url'];
				}
				else if (strpos($_GET['img_url'], 'http://') === 0) {
					$img_url = $_GET['img_url'];
				}
				else {
					$img_url = '../' . $_GET['img_url'];
				}

				$smarty->assign('img_url', $img_url);
				$smarty->display('goods_show_image.dwt');
			}
			else if ($_REQUEST['act'] == 'edit_goods_name') {
				check_authz_json('goods_manage');
				$goods_id = intval($_POST['id']);
				$goods_name = json_str_iconv(trim($_POST['val']));

				if ($exc->edit('goods_name = \'' . $goods_name . '\', last_update=' . gmtime(), $goods_id)) {
					clear_cache_files();
					make_json_result(stripslashes($goods_name));
				}
			}
			else if ($_REQUEST['act'] == 'edit_goods_sn') {
				check_authz_json('goods_manage');
				$goods_id = intval($_POST['id']);
				$goods_sn = json_str_iconv(trim($_POST['val']));

				if (!$exc->is_only('goods_sn', $goods_sn, $goods_id, 'user_id = \'' . $adminru['ru_id'] . '\'')) {
					make_json_error($_LANG['goods_sn_exists']);
				}

				$where = ' AND (SELECT g.user_id FROM ' . $GLOBALS['ecs']->table('goods') . ' AS g WHERE g.goods_id = p.goods_id LIMIT 1) = \'' . $adminru['ru_id'] . '\'';
				$sql = 'SELECT p.goods_id FROM ' . $ecs->table('products') . ' AS p WHERE p.product_sn=\'' . $goods_sn . '\'' . $where;

				if ($db->getOne($sql)) {
					make_json_error($_LANG['goods_sn_exists']);
				}

				if ($exc->edit('goods_sn = \'' . $goods_sn . '\', last_update=' . gmtime(), $goods_id)) {
					clear_cache_files();
					make_json_result(stripslashes($goods_sn));
				}
			}
			else if ($_REQUEST['act'] == 'edit_goods_bar_code') {
				check_authz_json('goods_manage');
				$goods_id = intval($_POST['id']);
				$bar_code = json_str_iconv(trim($_POST['val']));

				if (!$exc->is_only('bar_code', $bar_code, $goods_id, 'user_id = \'' . $adminru['ru_id'] . '\'')) {
					make_json_error($_LANG['goods_bar_code_exists']);
				}

				$where = ' AND (SELECT g.user_id FROM ' . $GLOBALS['ecs']->table('goods') . ' AS g WHERE g.goods_id = p.goods_id LIMIT 1) = \'' . $adminru['ru_id'] . '\'';
				$sql = 'SELECT p.goods_id FROM ' . $ecs->table('products') . ' AS p WHERE p.bar_code = \'' . $bar_code . '\'' . $where;

				if ($db->getOne($sql)) {
					make_json_error($_LANG['goods_bar_code_exists']);
				}

				if ($exc->edit('bar_code = \'' . $bar_code . '\'', $goods_id)) {
					clear_cache_files();
					make_json_result(stripslashes($bar_code));
				}
			}
			else if ($_REQUEST['act'] == 'check_goods_sn') {
				check_authz_json('goods_manage');
				$goods_id = intval($_REQUEST['goods_id']);
				$goods_sn = htmlspecialchars(json_str_iconv(trim($_REQUEST['goods_sn'])));

				if (!$exc->is_only('goods_sn', $goods_sn, $goods_id)) {
					make_json_error($_LANG['goods_sn_exists']);
				}

				if (!empty($goods_sn)) {
					$sql = 'SELECT goods_id FROM ' . $ecs->table('products') . 'WHERE product_sn=\'' . $goods_sn . '\'';

					if ($db->getOne($sql)) {
						make_json_error($_LANG['goods_sn_exists']);
					}
				}

				make_json_result('');
			}
			else if ($_REQUEST['act'] == 'check_products_goods_sn') {
				check_authz_json('goods_manage');
				$goods_id = intval($_REQUEST['goods_id']);
				$goods_sn = json_str_iconv(trim($_REQUEST['goods_sn']));
				$products_sn = explode('||', $goods_sn);

				if (!is_array($products_sn)) {
					make_json_result('');
				}
				else {
					foreach ($products_sn as $val) {
						if (empty($val)) {
							continue;
						}

						if (is_array($int_arry)) {
							if (in_array($val, $int_arry)) {
								make_json_error($val . $_LANG['goods_sn_exists']);
							}
						}

						$int_arry[] = $val;

						if (!$exc->is_only('goods_sn', $val, '0')) {
							make_json_error($val . $_LANG['goods_sn_exists']);
						}

						$sql = 'SELECT goods_id FROM ' . $ecs->table('products') . 'WHERE product_sn=\'' . $val . '\'';

						if ($db->getOne($sql)) {
							make_json_error($val . $_LANG['goods_sn_exists']);
						}
					}
				}

				make_json_result('');
			}
			else if ($_REQUEST['act'] == 'edit_goods_price') {
				check_authz_json('goods_manage');
				$goods_id = intval($_POST['id']);
				$goods_price = floatval($_POST['val']);
				$price_rate = floatval($_CFG['market_price_rate'] * $goods_price);
				if (($goods_price < 0) || (($goods_price == 0) && ($_POST['val'] != $goods_price))) {
					make_json_error($_LANG['shop_price_invalid']);
				}
				else if ($exc->edit('shop_price = \'' . $goods_price . '\', market_price = \'' . $price_rate . '\', last_update=' . gmtime(), $goods_id)) {
					clear_cache_files();
					make_json_result(number_format($goods_price, 2, '.', ''));
				}
			}
			else if ($_REQUEST['act'] == 'edit_goods_number') {
				check_authz_json('goods_manage');
				$goods_id = intval($_POST['id']);
				$goods_num = intval($_POST['val']);
				if (($goods_num < 0) || (($goods_num == 0) && ($_POST['val'] != $goods_num))) {
					make_json_error($_LANG['goods_number_error']);
				}

				if (check_goods_product_exist($goods_id) == 1) {
					make_json_error($_LANG['sys']['wrong'] . $_LANG['cannot_goods_number']);
				}

				$goodsInfo = get_admin_goods_info($goods_id, array('goods_number', 'model_inventory', 'model_attr'));

				if ($goods_num != $goodsInfo['goods_number']) {
					if ($goodsInfo['goods_number'] < $goods_num) {
						$number = $goods_num - $goodsInfo['goods_number'];
						$number = '+ ' . $number;
						$use_storage = 13;
					}
					else {
						$number = $goodsInfo['goods_number'] - $goods_num;
						$number = '- ' . $number;
						$use_storage = 8;
					}

					$logs_other = array('goods_id' => $goods_id, 'order_id' => 0, 'use_storage' => $use_storage, 'admin_id' => $_SESSION['admin_id'], 'number' => $number, 'model_inventory' => $goodsInfo['model_inventory'], 'model_attr' => $goodsInfo['model_attr'], 'product_id' => 0, 'warehouse_id' => 0, 'area_id' => 0, 'add_time' => gmtime());
					$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('goods_inventory_logs'), $logs_other, 'INSERT');
				}

				if ($exc->edit('goods_number = \'' . $goods_num . '\', last_update=' . gmtime(), $goods_id)) {
					clear_cache_files();
					make_json_result($goods_num);
				}
			}
			else if ($_REQUEST['act'] == 'toggle_on_sale') {
				check_authz_json('goods_manage');
				$goods_id = intval($_POST['id']);
				$on_sale = intval($_POST['val']);

				if ($exc->edit('is_on_sale = \'' . $on_sale . '\', last_update=' . gmtime(), $goods_id)) {
					if ($on_sale == 0) {
						$db->query('DELETE FROM ' . $ecs->table('cart') . ' WHERE goods_id = \'' . $goods_id . '\' ');
					}
					else {
						$sql = 'SELECT act_id FROM ' . $ecs->table('presale_activity') . ' WHERE goods_id = \'' . $goods_id . '\'';

						if ($db->getOne($sql, true)) {
							$db->query('DELETE FROM ' . $GLOBALS['ecs']->table('presale_activity') . ' WHERE goods_id = \'' . $goods_id . '\' ');
							$db->query('DELETE FROM ' . $GLOBALS['ecs']->table('cart') . ' WHERE goods_id = \'' . $goods_id . '\' ');
						}
					}

					clear_cache_files();
					make_json_result($on_sale);
				}
			}
			else if ($_REQUEST['act'] == 'edit_img_desc') {
				check_authz_json('goods_manage');
				$img_id = intval($_POST['id']);
				$img_desc = intval($_POST['val']);

				if ($exc_gallery->edit('img_desc = \'' . $img_desc . '\'', $img_id)) {
					clear_cache_files();
					make_json_result($img_desc);
				}
			}
			else if ($_REQUEST['act'] == 'toggle_best') {
				check_authz_json('goods_manage');
				$goods_id = intval($_POST['id']);
				$is_best = intval($_POST['val']);

				if ($exc->edit('is_best = \'' . $is_best . '\', last_update=' . gmtime(), $goods_id)) {
					clear_cache_files();
					make_json_result($is_best);
				}
			}
			else if ($_REQUEST['act'] == 'main_dsc') {
				$data = read_static_cache('seller_goods_str');

				if ($data === false) {
					$shop_url = urlencode($ecs->url());
					$shop_info = get_shop_info_content(0);

					if ($shop_info) {
						$shop_country = $shop_info['country'];
						$shop_province = $shop_info['province'];
						$shop_city = $shop_info['city'];
						$shop_address = $shop_info['shop_address'];
					}
					else {
						$shop_country = $_CFG['shop_country'];
						$shop_province = $_CFG['shop_province'];
						$shop_city = $_CFG['shop_city'];
						$shop_address = $_CFG['shop_address'];
					}

					$qq = (!empty($_CFG['qq']) ? $_CFG['qq'] : $shop_info['kf_qq']);
					$ww = (!empty($_CFG['ww']) ? $_CFG['ww'] : $shop_info['kf_ww']);
					$service_email = (!empty($_CFG['service_email']) ? $_CFG['service_email'] : $shop_info['seller_email']);
					$service_phone = (!empty($_CFG['service_phone']) ? $_CFG['service_phone'] : $shop_info['kf_tel']);
					$shop_country = $db->getOne('SELECT region_name FROM ' . $ecs->table('region') . ' WHERE region_id=\'' . $shop_country . '\'');
					$shop_province = $db->getOne('SELECT region_name FROM ' . $ecs->table('region') . ' WHERE region_id=\'' . $shop_province . '\'');
					$shop_city = $db->getOne('SELECT region_name FROM ' . $ecs->table('region') . ' WHERE region_id=\'' . $shop_city . '\'');
					$httpData = array('domain' => $ecs->get_domain(), 'url' => urldecode($shop_url), 'shop_name' => $_CFG['shop_name'], 'shop_title' => $_CFG['shop_title'], 'shop_desc' => $_CFG['shop_desc'], 'shop_keywords' => $_CFG['shop_keywords'], 'country' => $shop_country, 'province' => $shop_province, 'city' => $shop_city, 'address' => $shop_address, 'qq' => $qq, 'ww' => $ww, 'ym' => $service_phone, 'msn' => $_CFG['msn'], 'email' => $service_email, 'phone' => $_CFG['sms_shop_mobile'], 'icp' => $_CFG['icp_number'], 'version' => VERSION, 'release' => RELEASE, 'language' => $_CFG['lang'], 'php_ver' => PHP_VERSION, 'mysql_ver' => $db->version(), 'charset' => EC_CHARSET);
					$Http = new Http();
					$Http->doPost($_CFG['certi'], $httpData);
					write_static_cache('seller_goods_str', $httpData);
				}
			}
			else if ($_REQUEST['act'] == 'toggle_new') {
				check_authz_json('goods_manage');
				$goods_id = intval($_POST['id']);
				$is_new = intval($_POST['val']);

				if ($exc->edit('is_new = \'' . $is_new . '\', last_update=' . gmtime(), $goods_id)) {
					clear_cache_files();
					make_json_result($is_new);
				}
			}
			else if ($_REQUEST['act'] == 'toggle_hot') {
				check_authz_json('goods_manage');
				$goods_id = intval($_POST['id']);
				$is_hot = intval($_POST['val']);

				if ($exc->edit('is_hot = \'' . $is_hot . '\', last_update=' . gmtime(), $goods_id)) {
					clear_cache_files();
					make_json_result($is_hot);
				}
			}
			else if ($_REQUEST['act'] == 'toggle_store_best') {
				check_authz_json('goods_manage');
				$goods_id = intval($_POST['id']);
				$store_best = intval($_POST['val']);

				if ($exc->edit('store_best = \'' . $store_best . '\', last_update=' . gmtime(), $goods_id)) {
					clear_cache_files();
					make_json_result($store_best);
				}
			}
			else if ($_REQUEST['act'] == 'toggle_store_new') {
				check_authz_json('goods_manage');
				$goods_id = intval($_POST['id']);
				$store_new = intval($_POST['val']);

				if ($exc->edit('store_new = \'' . $store_new . '\', last_update=' . gmtime(), $goods_id)) {
					clear_cache_files();
					make_json_result($store_new);
				}
			}
			else if ($_REQUEST['act'] == 'toggle_store_hot') {
				check_authz_json('goods_manage');
				$goods_id = intval($_POST['id']);
				$store_hot = intval($_POST['val']);

				if ($exc->edit('store_hot = \'' . $store_hot . '\', last_update=' . gmtime(), $goods_id)) {
					clear_cache_files();
					make_json_result($store_hot);
				}
			}
			else if ($_REQUEST['act'] == 'toggle_is_reality') {
				check_authz_json('goods_manage');
				$id = intval($_POST['id']);
				$val = intval($_POST['val']);

				if ($exc_extend->edit('is_reality = \'' . $val . '\'', $id)) {
					clear_cache_files();
					make_json_result($val);
				}
			}
			else if ($_REQUEST['act'] == 'toggle_is_return') {
				check_authz_json('goods_manage');
				$id = intval($_POST['id']);
				$val = intval($_POST['val']);

				if ($exc_extend->edit('is_return = \'' . $val . '\'', $id)) {
					clear_cache_files();
					make_json_result($val);
				}
			}
			else if ($_REQUEST['act'] == 'toggle_is_fast') {
				check_authz_json('goods_manage');
				$id = intval($_POST['id']);
				$val = intval($_POST['val']);

				if ($exc_extend->edit('is_fast = \'' . $val . '\'', $id)) {
					clear_cache_files();
					make_json_result($val);
				}
			}
			else if ($_REQUEST['act'] == 'toggle_is_shipping') {
				check_authz_json('goods_manage');
				$goods_id = intval($_POST['id']);
				$is_shipping = intval($_POST['val']);

				if ($exc->edit('is_shipping = \'' . $is_shipping . '\', last_update=' . gmtime(), $goods_id)) {
					clear_cache_files();
					make_json_result($is_shipping);
				}
			}
			else if ($_REQUEST['act'] == 'edit_sort_order') {
				check_authz_json('goods_manage');
				$goods_id = intval($_POST['id']);
				$sort_order = intval($_POST['val']);

				if ($exc->edit('sort_order = \'' . $sort_order . '\', last_update=' . gmtime(), $goods_id)) {
					clear_cache_files();
					make_json_result($sort_order);
				}
			}
			else if ($_REQUEST['act'] == 'query') {
				$is_delete = (empty($_REQUEST['is_delete']) ? 0 : intval($_REQUEST['is_delete']));
				$code = (empty($_REQUEST['extension_code']) ? '' : trim($_REQUEST['extension_code']));
				$goods_list = goods_list($is_delete, $code == '' ? 1 : 0);
				$handler_list = array();
				$handler_list['virtual_card'][] = array('url' => 'virtual_card.php?act=card', 'title' => $_LANG['card'], 'img' => 'icon_send_bonus.gif');
				$handler_list['virtual_card'][] = array('url' => 'virtual_card.php?act=replenish', 'title' => $_LANG['replenish'], 'img' => 'icon_add.gif');
				$handler_list['virtual_card'][] = array('url' => 'virtual_card.php?act=batch_card_add', 'title' => $_LANG['batch_card_add'], 'img' => 'icon_output.gif');

				if (isset($handler_list[$code])) {
					$smarty->assign('add_handler', $handler_list[$code]);
				}

				$smarty->assign('code', $code);
				$smarty->assign('goods_list', $goods_list['goods']);
				$smarty->assign('filter', $goods_list['filter']);
				$smarty->assign('record_count', $goods_list['record_count']);
				$smarty->assign('page_count', $goods_list['page_count']);
				$smarty->assign('list_type', $is_delete ? 'trash' : 'goods');
				$smarty->assign('use_storage', empty($_CFG['use_storage']) ? 0 : 1);
				$sort_flag = sort_flag($goods_list['filter']);
				$smarty->assign($sort_flag['tag'], $sort_flag['img']);
				$specifications = get_goods_type_specifications();
				$smarty->assign('specifications', $specifications);
				$tpl = ($is_delete ? 'goods_trash.dwt' : 'goods_list.dwt');
				$store_list = get_common_store_list();
				$smarty->assign('store_list', $store_list);
				$smarty->assign('nowTime', gmtime());
				set_default_filter();
				make_json_result($smarty->fetch($tpl), '', array('filter' => $goods_list['filter'], 'page_count' => $goods_list['page_count']));
			}
			else if ($_REQUEST['act'] == 'remove') {
				$goods_id = intval($_REQUEST['id']);
				check_authz_json('remove_back');
				$sql = 'SELECT goods_id, user_id ' . 'FROM ' . $ecs->table('goods') . ' WHERE goods_id = \'' . $goods_id . '\'';
				$goods = $db->getRow($sql);
				$adminru = get_admin_ru_id();
				if ((0 < $adminru['ru_id']) && ($adminru['ru_id'] != $goods['user_id'])) {
					make_json_error('非法操作,信息已被记录');
				}

				if ($exc->edit('is_delete = 1', $goods_id)) {
					clear_cache_files();
					$goods_name = $exc->get_name($goods_id);
					admin_log(addslashes($goods_name), 'trash', 'goods');
					$url = 'goods.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
					ecs_header('Location: ' . $url . "\n");
					exit();
				}
			}
			else if ($_REQUEST['act'] == 'restore_goods') {
				$goods_id = intval($_REQUEST['id']);
				check_authz_json('remove_back');
				$exc->edit('is_delete = 0, add_time = \'' . gmtime() . '\'', $goods_id);
				clear_cache_files();
				$goods_name = $exc->get_name($goods_id);
				admin_log(addslashes($goods_name), 'restore', 'goods');
				$url = 'goods.php?act=query&' . str_replace('act=restore_goods', '', $_SERVER['QUERY_STRING']);
				ecs_header('Location: ' . $url . "\n");
				exit();
			}
			else if ($_REQUEST['act'] == 'drop_goods') {
				check_authz_json('remove_back');
				$goods_id = intval($_REQUEST['id']);

				if ($goods_id <= 0) {
					make_json_error('invalid params');
				}

				$sql = 'SELECT goods_id, goods_name, is_delete, is_real, goods_thumb, user_id, ' . 'goods_img, original_img ' . 'FROM ' . $ecs->table('goods') . ' WHERE goods_id = \'' . $goods_id . '\'';
				$goods = $db->getRow($sql);

				if (empty($goods)) {
					make_json_error($_LANG['goods_not_exist']);
				}

				$adminru = get_admin_ru_id();
				if ((0 < $adminru['ru_id']) && ($adminru['ru_id'] != $goods['user_id'])) {
					make_json_error('非法操作，信息已被记录');
				}

				if ($goods['is_delete'] != 1) {
					make_json_error($_LANG['goods_not_in_recycle_bin']);
				}

				if ($goods['goods_desc']) {
					$desc_preg = get_goods_desc_images_preg('', $goods['goods_desc']);
					get_desc_images_del($desc_preg['images_list']);
				}

				if (!empty($goods['goods_thumb'])) {
					@unlink('../' . $goods['goods_thumb']);
				}

				if (!empty($goods['goods_img'])) {
					@unlink('../' . $goods['goods_img']);
				}

				if (!empty($goods['original_img'])) {
					@unlink('../' . $goods['original_img']);
				}

				get_oss_del_file(array($goods['goods_thumb'], $goods['goods_img'], $goods['original_img']));
				$exc->drop($goods_id);
				$sql = 'delete from ' . $ecs->table('goods_extend') . ' where goods_id=\'' . $goods_id . '\'';
				$db->query($sql);
				$sql = 'DELETE FROM ' . $ecs->table('products') . ' WHERE goods_id = \'' . $goods_id . '\'';
				$db->query($sql);
				admin_log(addslashes($goods['goods_name']), 'remove', 'goods');
				$sql = 'SELECT img_url, thumb_url, img_original ' . 'FROM ' . $ecs->table('goods_gallery') . ' WHERE goods_id = \'' . $goods_id . '\'';
				$res = $db->query($sql);

				while ($row = $db->fetchRow($res)) {
					if (!empty($row['img_url'])) {
						@unlink('../' . $row['img_url']);
					}

					if (!empty($row['thumb_url'])) {
						@unlink('../' . $row['thumb_url']);
					}

					if (!empty($row['img_original'])) {
						@unlink('../' . $row['img_original']);
					}

					get_oss_del_file(array($row['img_url'], $row['thumb_url'], $row['img_original']));
				}

				$sql = 'DELETE FROM ' . $ecs->table('goods_gallery') . ' WHERE goods_id = \'' . $goods_id . '\'';
				$db->query($sql);
				$sql = 'DELETE FROM ' . $ecs->table('collect_goods') . ' WHERE goods_id = \'' . $goods_id . '\'';
				$db->query($sql);
				$sql = 'DELETE FROM ' . $ecs->table('goods_article') . ' WHERE goods_id = \'' . $goods_id . '\'';
				$db->query($sql);
				$sql = 'DELETE FROM ' . $ecs->table('goods_attr') . ' WHERE goods_id = \'' . $goods_id . '\'';
				$db->query($sql);
				$sql = 'DELETE FROM ' . $ecs->table('goods_cat') . ' WHERE goods_id = \'' . $goods_id . '\'';
				$db->query($sql);
				$sql = 'DELETE FROM ' . $ecs->table('member_price') . ' WHERE goods_id = \'' . $goods_id . '\'';
				$db->query($sql);
				$sql = 'DELETE FROM ' . $ecs->table('group_goods') . ' WHERE parent_id = \'' . $goods_id . '\'';
				$db->query($sql);
				$sql = 'DELETE FROM ' . $ecs->table('group_goods') . ' WHERE goods_id = \'' . $goods_id . '\'';
				$db->query($sql);
				$sql = 'DELETE FROM ' . $ecs->table('link_goods') . ' WHERE goods_id = \'' . $goods_id . '\'';
				$db->query($sql);
				$sql = 'DELETE FROM ' . $ecs->table('link_goods') . ' WHERE link_goods_id = \'' . $goods_id . '\'';
				$db->query($sql);
				$sql = 'DELETE FROM ' . $ecs->table('tag') . ' WHERE goods_id = \'' . $goods_id . '\'';
				$db->query($sql);
				$sql = 'DELETE FROM ' . $ecs->table('comment') . ' WHERE comment_type = 0 AND id_value = \'' . $goods_id . '\'';
				$db->query($sql);
				$sql = 'DELETE FROM ' . $ecs->table('collect_goods') . ' WHERE goods_id = \'' . $goods_id . '\'';
				$db->query($sql);
				$sql = 'DELETE FROM ' . $ecs->table('booking_goods') . ' WHERE goods_id = \'' . $goods_id . '\'';
				$db->query($sql);
				$sql = 'DELETE FROM ' . $ecs->table('goods_activity') . ' WHERE goods_id = \'' . $goods_id . '\'';
				$db->query($sql);
				$sql = 'DELETE FROM ' . $ecs->table('cart') . ' WHERE goods_id = \'' . $goods_id . '\'';
				$db->query($sql);
				$sql = 'DELETE FROM ' . $ecs->table('warehouse_goods') . ' WHERE goods_id = \'' . $goods_id . '\'';
				$db->query($sql);
				$sql = 'DELETE FROM ' . $ecs->table('warehouse_attr') . ' WHERE goods_id = \'' . $goods_id . '\'';
				$db->query($sql);
				$sql = 'DELETE FROM ' . $ecs->table('warehouse_area_goods') . ' WHERE goods_id = \'' . $goods_id . '\'';
				$db->query($sql);
				$sql = 'DELETE FROM ' . $ecs->table('warehouse_area_attr') . ' WHERE goods_id = \'' . $goods_id . '\'';
				$db->query($sql);

				if ($goods['is_real'] != 1) {
					$sql = 'DELETE FROM ' . $ecs->table('virtual_card') . ' WHERE goods_id = \'' . $goods_id . '\'';
					if (!$db->query($sql, 'SILENT') && ($db->errno() != 1146)) {
						exit($db->error());
					}
				}

				clear_cache_files();
				$url = 'goods.php?act=query&' . str_replace('act=drop_goods', '', $_SERVER['QUERY_STRING']);
				ecs_header('Location: ' . $url . "\n");
				exit();
			}
			else if ($_REQUEST['act'] == 'drop_image') {
				check_authz_json('goods_manage');
				$img_id = (empty($_REQUEST['img_id']) ? 0 : intval($_REQUEST['img_id']));
				$sql = 'SELECT img_url, thumb_url, img_original ' . ' FROM ' . $GLOBALS['ecs']->table('goods_gallery') . ' WHERE img_id = \'' . $img_id . '\'';
				$row = $GLOBALS['db']->getRow($sql);
				$img_url = ROOT_PATH . $row['img_url'];
				$thumb_url = ROOT_PATH . $row['thumb_url'];
				$img_original = ROOT_PATH . $row['img_original'];
				if (($row['img_url'] != '') && is_file($img_url)) {
					@unlink($img_url);
				}

				if (($row['thumb_url'] != '') && is_file($thumb_url)) {
					@unlink($thumb_url);
				}

				if (($row['img_original'] != '') && is_file($img_original)) {
					@unlink($img_original);
				}

				get_oss_del_file(array($row['img_url'], $row['thumb_url'], $row['img_original']));
				$sql = 'DELETE FROM ' . $GLOBALS['ecs']->table('goods_gallery') . ' WHERE img_id = \'' . $img_id . '\' LIMIT 1';
				$GLOBALS['db']->query($sql);
				clear_cache_files();
				make_json_result($img_id);
			}
			else if ($_REQUEST['act'] == 'drop_product') {
				include_once ROOT_PATH . 'includes/cls_json.php';
				$json = new JSON();
				check_authz_json('goods_manage');
				$product_id = (empty($_REQUEST['product_id']) ? 0 : intval($_REQUEST['product_id']));
				$group_attr = (empty($_REQUEST['group_attr']) ? '' : $_REQUEST['group_attr']);
				$group_attr = $json->decode($group_attr, true);

				if ($group_attr['goods_model'] == 1) {
					$table = 'products_warehouse';
				}
				else if ($group_attr['goods_model'] == 2) {
					$table = 'products_area';
				}
				else {
					$table = 'products';
				}

				$sql = 'DELETE FROM ' . $GLOBALS['ecs']->table($table) . ' WHERE product_id = \'' . $product_id . '\' LIMIT 1';
				$GLOBALS['db']->query($sql);
				clear_cache_files();
				make_json_result_too($product_id, 0, '', $group_attr);
			}
			else if ($_REQUEST['act'] == 'drop_warehouse') {
				check_authz_json('goods_manage');
				$w_id = (empty($_REQUEST['w_id']) ? 0 : intval($_REQUEST['w_id']));
				$sql = 'DELETE FROM ' . $GLOBALS['ecs']->table('warehouse_goods') . ' WHERE w_id = \'' . $w_id . '\' LIMIT 1';
				$GLOBALS['db']->query($sql);
				clear_cache_files();
				make_json_result($w_id);
			}
			else if ($_REQUEST['act'] == 'edit_warehouse_number') {
				check_authz_json('goods_manage');
				$w_id = intval($_POST['id']);
				$region_number = intval($_POST['val']);
				$sql = 'SELECT goods_id, region_number, region_id FROM ' . $ecs->table('warehouse_goods') . ' WHERE w_id = \'' . $w_id . '\' LIMIT 1';
				$warehouse_goods = $db->getRow($sql);
				$goodsInfo = get_admin_goods_info($warehouse_goods['goods_id'], array('model_inventory', 'model_attr'));

				if ($region_number != $warehouse_goods['region_number']) {
					if ($warehouse_goods['region_number'] < $region_number) {
						$number = $region_number - $warehouse_goods['region_number'];
						$number = '+ ' . $number;
						$use_storage = 13;
					}
					else {
						$number = $warehouse_goods['region_number'] - $region_number;
						$number = '- ' . $number;
						$use_storage = 8;
					}

					$logs_other = array('goods_id' => $warehouse_goods['goods_id'], 'order_id' => 0, 'use_storage' => $use_storage, 'admin_id' => $_SESSION['admin_id'], 'number' => $number, 'model_inventory' => $goodsInfo['model_inventory'], 'model_attr' => $goodsInfo['model_attr'], 'product_id' => 0, 'warehouse_id' => $warehouse_goods['region_id'], 'area_id' => 0, 'add_time' => gmtime());
					$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('goods_inventory_logs'), $logs_other, 'INSERT');
				}

				$sql = 'UPDATE ' . $ecs->table('warehouse_goods') . ' SET region_number = \'' . $region_number . '\' WHERE w_id = \'' . $w_id . '\' ';
				$res = $db->query($sql);

				if ($res) {
					clear_cache_files();
					make_json_result($region_number);
				}
			}
			else if ($_REQUEST['act'] == 'edit_warehouse_sn') {
				check_authz_json('goods_manage');
				$w_id = intval($_POST['id']);
				$region_sn = addslashes(trim($_POST['val']));
				$sql = 'update ' . $ecs->table('warehouse_goods') . ' set region_sn = \'' . $region_sn . '\' where w_id = \'' . $w_id . '\' ';
				$res = $db->query($sql);

				if ($res) {
					clear_cache_files();
					make_json_result($region_sn);
				}
			}
			else if ($_REQUEST['act'] == 'edit_warehouse_price') {
				check_authz_json('goods_manage');
				$w_id = intval($_POST['id']);
				$warehouse_price = floatval($_POST['val']);
				$sql = 'update ' . $ecs->table('warehouse_goods') . ' set warehouse_price = \'' . $warehouse_price . '\' where w_id = \'' . $w_id . '\' ';
				$res = $db->query($sql);

				if ($res) {
					clear_cache_files();
					make_json_result($warehouse_price);
				}
			}
			else if ($_REQUEST['act'] == 'edit_warehouse_promote_price') {
				check_authz_json('goods_manage');
				$w_id = intval($_POST['id']);
				$warehouse_promote_price = floatval($_POST['val']);
				$sql = 'update ' . $ecs->table('warehouse_goods') . ' set warehouse_promote_price = \'' . $warehouse_promote_price . '\' where w_id = \'' . $w_id . '\' ';
				$res = $db->query($sql);

				if ($res) {
					clear_cache_files();
					make_json_result($warehouse_promote_price);
				}
			}
			else if ($_REQUEST['act'] == 'edit_warehouse_give_integral') {
				check_authz_json('goods_manage');
				$w_id = intval($_POST['id']);
				$give_integral = floatval($_POST['val']);
				$sql = 'update ' . $ecs->table('warehouse_goods') . ' set give_integral = \'' . $give_integral . '\' where w_id = \'' . $w_id . '\' ';
				$res = $db->query($sql);
				$other = array('w_id', 'user_id', 'warehouse_price', 'warehouse_promote_price');
				$goods = get_table_date('warehouse_goods', 'w_id=\'' . $w_id . '\'', $other);

				if ($goods['user_id']) {
					if ($goods['warehouse_promote_price']) {
						if ($goods['warehouse_promote_price'] < $goods['warehouse_price']) {
							$shop_price = $goods['warehouse_promote_price'];
						}
						else {
							$shop_price = $goods['warehouse_price'];
						}
					}
					else {
						$shop_price = $goods['warehouse_price'];
					}

					$grade_rank = get_seller_grade_rank($goods['user_id']);
					$give = floor($shop_price * $grade_rank['give_integral']);

					if ($give < $give_integral) {
						make_json_error(sprintf($_LANG['goods_give_integral'], $give));
					}
				}

				if ($res) {
					clear_cache_files();
					make_json_result($give_integral);
				}
			}
			else if ($_REQUEST['act'] == 'edit_warehouse_rank_integral') {
				check_authz_json('goods_manage');
				$w_id = intval($_POST['id']);
				$rank_integral = floatval($_POST['val']);
				$sql = 'update ' . $ecs->table('warehouse_goods') . ' set rank_integral = \'' . $rank_integral . '\' where w_id = \'' . $w_id . '\' ';
				$res = $db->query($sql);
				$other = array('w_id', 'user_id', 'warehouse_price', 'warehouse_promote_price');
				$goods = get_table_date('warehouse_goods', 'w_id=\'' . $w_id . '\'', $other);

				if ($goods['user_id']) {
					if ($goods['warehouse_promote_price']) {
						if ($goods['warehouse_promote_price'] < $goods['warehouse_price']) {
							$shop_price = $goods['warehouse_promote_price'];
						}
						else {
							$shop_price = $goods['warehouse_price'];
						}
					}
					else {
						$shop_price = $goods['warehouse_price'];
					}

					$grade_rank = get_seller_grade_rank($goods['user_id']);
					$rank = floor($shop_price * $grade_rank['rank_integral']);

					if ($rank < $rank_integral) {
						make_json_error(sprintf($_LANG['goods_rank_integral'], $rank));
					}
				}

				if ($res) {
					clear_cache_files();
					make_json_result($rank_integral);
				}
			}
			else if ($_REQUEST['act'] == 'edit_warehouse_pay_integral') {
				check_authz_json('goods_manage');
				$w_id = intval($_POST['id']);
				$pay_integral = floatval($_POST['val']);
				$sql = 'update ' . $ecs->table('warehouse_goods') . ' set pay_integral = \'' . $pay_integral . '\' where w_id = \'' . $w_id . '\' ';
				$res = $db->query($sql);
				$other = array('w_id', 'user_id', 'warehouse_price', 'warehouse_promote_price');
				$goods = get_table_date('warehouse_goods', 'w_id=\'' . $w_id . '\'', $other);

				if ($goods['user_id']) {
					if ($goods['warehouse_promote_price']) {
						if ($goods['warehouse_promote_price'] < $goods['warehouse_price']) {
							$shop_price = $goods['warehouse_promote_price'];
						}
						else {
							$shop_price = $goods['warehouse_price'];
						}
					}
					else {
						$shop_price = $goods['warehouse_price'];
					}

					$grade_rank = get_seller_grade_rank($goods['user_id']);
					$pay = floor(($shop_price / 100) * $_CFG['integral_scale'] * $grade_rank['pay_integral']);

					if ($rank < $pay_integral) {
						make_json_error(sprintf($_LANG['goods_pay_integral'], $pay));
					}
				}

				if ($res) {
					clear_cache_files();
					make_json_result($pay_integral);
				}
			}
			else if ($_REQUEST['act'] == 'edit_region_sn') {
				check_authz_json('goods_manage');
				$a_id = intval($_POST['id']);
				$region_sn = addslashes(trim($_POST['val']));
				$sql = 'update ' . $ecs->table('warehouse_area_goods') . ' set region_sn = \'' . $region_sn . '\' where a_id = \'' . $a_id . '\' ';
				$res = $db->query($sql);

				if ($res) {
					clear_cache_files();
					make_json_result($region_sn);
				}
			}
			else if ($_REQUEST['act'] == 'drop_warehouse_area') {
				check_authz_json('goods_manage');
				$a_id = (empty($_REQUEST['a_id']) ? 0 : intval($_REQUEST['a_id']));
				$sql = 'DELETE FROM ' . $GLOBALS['ecs']->table('warehouse_area_goods') . ' WHERE a_id = \'' . $a_id . '\' LIMIT 1';
				$GLOBALS['db']->query($sql);
				clear_cache_files();
				make_json_result($a_id);
			}
			else if ($_REQUEST['act'] == 'edit_region_price') {
				check_authz_json('goods_manage');
				$a_id = intval($_POST['id']);
				$region_price = floatval($_POST['val']);
				$sql = 'update ' . $ecs->table('warehouse_area_goods') . ' set region_price = \'' . $region_price . '\' where a_id = \'' . $a_id . '\' ';
				$res = $db->query($sql);

				if ($res) {
					clear_cache_files();
					make_json_result($region_price);
				}
			}
			else if ($_REQUEST['act'] == 'edit_region_number') {
				check_authz_json('goods_manage');
				$a_id = intval($_POST['id']);
				$region_number = floatval($_POST['val']);
				$sql = 'SELECT goods_id, region_number, region_id FROM ' . $ecs->table('warehouse_area_goods') . ' WHERE a_id = \'' . $a_id . '\' LIMIT 1';
				$area_goods = $db->getRow($sql);
				$goodsInfo = get_admin_goods_info($area_goods['goods_id'], array('model_inventory', 'model_attr'));

				if ($region_number != $area_goods['region_number']) {
					if ($area_goods['region_number'] < $region_number) {
						$number = $region_number - $area_goods['region_number'];
						$number = '+ ' . $number;
						$use_storage = 13;
					}
					else {
						$number = $area_goods['region_number'] - $region_number;
						$number = '- ' . $number;
						$use_storage = 8;
					}

					$logs_other = array('goods_id' => $area_goods['goods_id'], 'order_id' => 0, 'use_storage' => $use_storage, 'admin_id' => $_SESSION['admin_id'], 'number' => $number, 'model_inventory' => $goodsInfo['model_inventory'], 'model_attr' => $goodsInfo['model_attr'], 'product_id' => 0, 'warehouse_id' => 0, 'area_id' => $area_goods['region_id'], 'add_time' => gmtime());
					$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('goods_inventory_logs'), $logs_other, 'INSERT');
				}

				$sql = 'UPDATE ' . $ecs->table('warehouse_area_goods') . ' SET region_number = \'' . $region_number . '\' WHERE a_id = \'' . $a_id . '\' ';
				$res = $db->query($sql);

				if ($res) {
					clear_cache_files();
					make_json_result($region_number);
				}
			}
			else if ($_REQUEST['act'] == 'edit_region_promote_price') {
				check_authz_json('goods_manage');
				$a_id = intval($_POST['id']);
				$region_promote_price = floatval($_POST['val']);
				$sql = 'update ' . $ecs->table('warehouse_area_goods') . ' set region_promote_price = \'' . $region_promote_price . '\' where a_id = \'' . $a_id . '\' ';
				$res = $db->query($sql);

				if ($res) {
					clear_cache_files();
					make_json_result($region_promote_price);
				}
			}
			else if ($_REQUEST['act'] == 'edit_warehouse_area_list') {
				check_authz_json('goods_manage');
				$id = (isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0);
				$key = (isset($_REQUEST['key']) ? intval($_REQUEST['key']) : 0);
				$goods_id = (isset($_REQUEST['goods_id']) ? intval($_REQUEST['goods_id']) : 0);
				$ru_id = (isset($_REQUEST['ru_id']) ? intval($_REQUEST['ru_id']) : 0);
				$type = (isset($_REQUEST['type']) ? intval($_REQUEST['type']) : 1);

				if (0 < $id) {
					$area_list = get_warehouse_area_list($id, $type, $goods_id, $ru_id);
					$smarty->assign('area_list', $area_list);
					$smarty->assign('warehouse_id', $id);
					$smarty->assign('type', $type);
					$result['error'] = 0;
					$result['key'] = $key;
					$result['html'] = $smarty->fetch('library/warehouse_area_list.lbi');
				}
				else {
					$result['key'] = $key;
					$result['error'] = 1;
				}

				make_json_result($result);
			}
			else if ($_REQUEST['act'] == 'edit_region_give_integral') {
				check_authz_json('goods_manage');
				$a_id = intval($_POST['id']);
				$give_integral = floatval($_POST['val']);
				$sql = 'update ' . $ecs->table('warehouse_area_goods') . ' set give_integral = \'' . $give_integral . '\' where a_id = \'' . $a_id . '\' ';
				$res = $db->query($sql);
				$other = array('a_id', 'user_id', 'region_price', 'region_promote_price');
				$goods = get_table_date('warehouse_area_goods', 'a_id=\'' . $a_id . '\'', $other);

				if ($goods['user_id']) {
					if ($goods['region_promote_price']) {
						if ($goods['region_promote_price'] < $goods['region_price']) {
							$shop_price = $goods['region_promote_price'];
						}
						else {
							$shop_price = $goods['region_price'];
						}
					}
					else {
						$shop_price = $goods['region_price'];
					}

					$grade_rank = get_seller_grade_rank($goods['user_id']);
					$give = floor($shop_price * $grade_rank['give_integral']);

					if ($give < $give_integral) {
						make_json_error(sprintf($_LANG['goods_give_integral'], $give));
					}
				}

				if ($res) {
					clear_cache_files();
					make_json_result($give_integral);
				}
			}
			else if ($_REQUEST['act'] == 'edit_region_rank_integral') {
				check_authz_json('goods_manage');
				$a_id = intval($_POST['id']);
				$rank_integral = floatval($_POST['val']);
				$sql = 'update ' . $ecs->table('warehouse_area_goods') . ' set rank_integral = \'' . $rank_integral . '\' where a_id = \'' . $a_id . '\' ';
				$res = $db->query($sql);
				$other = array('a_id', 'user_id', 'region_price', 'region_promote_price');
				$goods = get_table_date('warehouse_area_goods', 'a_id=\'' . $a_id . '\'', $other);

				if ($goods['user_id']) {
					if ($goods['region_promote_price']) {
						if ($goods['region_promote_price'] < $goods['region_price']) {
							$shop_price = $goods['region_promote_price'];
						}
						else {
							$shop_price = $goods['region_price'];
						}
					}
					else {
						$shop_price = $goods['region_price'];
					}

					$grade_rank = get_seller_grade_rank($goods['user_id']);
					$rank = floor($shop_price * $grade_rank['rank_integral']);

					if ($rank < $rank_integral) {
						make_json_error(sprintf($_LANG['goods_rank_integral'], $rank));
					}
				}

				if ($res) {
					clear_cache_files();
					make_json_result($rank_integral);
				}
			}
			else if ($_REQUEST['act'] == 'edit_region_pay_integral') {
				check_authz_json('goods_manage');
				$a_id = intval($_POST['id']);
				$pay_integral = floatval($_POST['val']);
				$sql = 'update ' . $ecs->table('warehouse_area_goods') . ' set pay_integral = \'' . $pay_integral . '\' where a_id = \'' . $a_id . '\' ';
				$res = $db->query($sql);
				$other = array('a_id', 'user_id', 'region_price', 'region_promote_price');
				$goods = get_table_date('warehouse_area_goods', 'a_id=\'' . $a_id . '\'', $other);

				if ($goods['user_id']) {
					if ($goods['region_promote_price']) {
						if ($goods['region_promote_price'] < $goods['region_price']) {
							$shop_price = $goods['region_promote_price'];
						}
						else {
							$shop_price = $goods['region_price'];
						}
					}
					else {
						$shop_price = $goods['region_price'];
					}

					$grade_rank = get_seller_grade_rank($goods['user_id']);
					$pay = floor(($shop_price / 100) * $_CFG['integral_scale'] * $grade_rank['pay_integral']);

					if ($pay < $pay_integral) {
						make_json_error(sprintf($_LANG['goods_pay_integral'], $pay));
					}
				}

				if ($res) {
					clear_cache_files();
					make_json_result($pay_integral);
				}
			}
			else if ($_REQUEST['act'] == 'edit_region_sort') {
				check_authz_json('goods_manage');
				$a_id = intval($_POST['id']);
				$region_sort = floatval($_POST['val']);
				$sql = 'update ' . $ecs->table('warehouse_area_goods') . ' set region_sort = \'' . $region_sort . '\' where a_id = \'' . $a_id . '\' ';
				$res = $db->query($sql);

				if ($res) {
					clear_cache_files();
					make_json_result($region_sort);
				}
			}
			else if ($_REQUEST['act'] == 'get_goods_list') {
				include_once ROOT_PATH . 'includes/cls_json.php';
				$json = new JSON();
				$filters = $json->decode($_GET['JSON']);
				$arr = get_goods_list($filters);
				$opt = array();

				foreach ($arr as $key => $val) {
					$opt[] = array('value' => $val['goods_id'], 'text' => $val['goods_name'], 'data' => $val['shop_price']);
				}

				make_json_result($opt);
			}
			else if ($_REQUEST['act'] == 'product_list') {
				admin_priv('goods_manage');

				if (empty($_GET['goods_id'])) {
					$link[] = array('href' => 'goods.php?act=list', 'text' => $_LANG['cannot_found_goods']);
					sys_msg($_LANG['cannot_found_goods'], 1, $link);
				}
				else {
					$goods_id = intval($_GET['goods_id']);
				}

				$sql = 'SELECT goods_sn, goods_name, goods_type, shop_price, model_attr FROM ' . $ecs->table('goods') . ' WHERE goods_id = \'' . $goods_id . '\'';
				$goods = $db->getRow($sql);

				if (empty($goods)) {
					$link[] = array('href' => 'goods.php?act=list', 'text' => $_LANG['01_goods_list']);
					sys_msg($_LANG['cannot_found_goods'], 1, $link);
				}

				$smarty->assign('sn', sprintf($_LANG['good_goods_sn'], $goods['goods_sn']));
				$smarty->assign('price', sprintf($_LANG['good_shop_price'], $goods['shop_price']));
				$smarty->assign('goods_name', sprintf($_LANG['products_title'], $goods['goods_name']));
				$smarty->assign('goods_sn', sprintf($_LANG['products_title_2'], $goods['goods_sn']));
				$smarty->assign('model_attr', $goods['model_attr']);
				$attribute = get_goods_specifications_list($goods_id);

				if (empty($attribute)) {
					$link[] = array('href' => 'goods.php?act=edit&goods_id=' . $goods_id, 'text' => $_LANG['edit_goods']);
					sys_msg($_LANG['not_exist_goods_attr'], 1, $link);
				}

				foreach ($attribute as $attribute_value) {
					$_attribute[$attribute_value['attr_id']]['attr_values'][] = $attribute_value['attr_value'];
					$_attribute[$attribute_value['attr_id']]['attr_id'] = $attribute_value['attr_id'];
					$_attribute[$attribute_value['attr_id']]['attr_name'] = $attribute_value['attr_name'];
				}

				$attribute_count = count($_attribute);
				$smarty->assign('attribute_count', $attribute_count);
				$smarty->assign('attribute_count_3', $attribute_count + 3);
				$smarty->assign('attribute', $_attribute);
				$smarty->assign('product_sn', $goods['goods_sn'] . '_');
				$smarty->assign('product_number', $_CFG['default_storage']);
				$product = product_list($goods_id, '');
				$smarty->assign('ur_here', $_LANG['18_product_list']);
				$smarty->assign('action_link', array('href' => 'goods.php?act=list', 'text' => $_LANG['01_goods_list']));
				$smarty->assign('product_list', $product['product']);
				$smarty->assign('product_null', empty($product['product']) ? 0 : 1);
				$smarty->assign('use_storage', empty($_CFG['use_storage']) ? 0 : 1);
				$smarty->assign('goods_id', $goods_id);
				$smarty->assign('filter', $product['filter']);
				$smarty->assign('full_page', 1);
				$smarty->assign('product_php', 'goods.php');
				$smarty->assign('batch_php', 'goods_produts_batch.php');
				assign_query_info();
				$smarty->display('product_info.dwt');
			}
			else if ($_REQUEST['act'] == 'product_query') {
				if (empty($_REQUEST['goods_id'])) {
					make_json_error($_LANG['sys']['wrong'] . $_LANG['cannot_found_goods']);
				}
				else {
					$goods_id = intval($_REQUEST['goods_id']);
				}

				$sql = 'SELECT goods_sn, goods_name, goods_type, shop_price FROM ' . $ecs->table('goods') . ' WHERE goods_id = \'' . $goods_id . '\'';
				$goods = $db->getRow($sql);

				if (empty($goods)) {
					make_json_error($_LANG['sys']['wrong'] . $_LANG['cannot_found_goods']);
				}

				$smarty->assign('sn', sprintf($_LANG['good_goods_sn'], $goods['goods_sn']));
				$smarty->assign('price', sprintf($_LANG['good_shop_price'], $goods['shop_price']));
				$smarty->assign('goods_name', sprintf($_LANG['products_title'], $goods['goods_name']));
				$smarty->assign('goods_sn', sprintf($_LANG['products_title_2'], $goods['goods_sn']));
				$attribute = get_goods_specifications_list($goods_id);

				if (empty($attribute)) {
					make_json_error($_LANG['sys']['wrong'] . $_LANG['cannot_found_goods']);
				}

				foreach ($attribute as $attribute_value) {
					$_attribute[$attribute_value['attr_id']]['attr_values'][] = $attribute_value['attr_value'];
					$_attribute[$attribute_value['attr_id']]['attr_id'] = $attribute_value['attr_id'];
					$_attribute[$attribute_value['attr_id']]['attr_name'] = $attribute_value['attr_name'];
				}

				$attribute_count = count($_attribute);
				$smarty->assign('attribute_count', $attribute_count);
				$smarty->assign('attribute', $_attribute);
				$smarty->assign('attribute_count_3', $attribute_count + 10);
				$smarty->assign('product_sn', $goods['goods_sn'] . '_');
				$smarty->assign('product_number', $_CFG['default_storage']);
				$product = product_list($goods_id, '');
				$smarty->assign('ur_here', $_LANG['18_product_list']);
				$smarty->assign('action_link', array('href' => 'goods.php?act=list', 'text' => $_LANG['01_goods_list']));
				$smarty->assign('product_list', $product['product']);
				$smarty->assign('use_storage', empty($_CFG['use_storage']) ? 0 : 1);
				$smarty->assign('goods_id', $goods_id);
				$smarty->assign('filter', $product['filter']);
				$smarty->assign('product_php', 'goods.php');
				$sort_flag = sort_flag($product['filter']);
				$smarty->assign($sort_flag['tag'], $sort_flag['img']);
				make_json_result($smarty->fetch('product_info.dwt'), '', array('filter' => $product['filter'], 'page_count' => $product['page_count']));
			}
			else if ($_REQUEST['act'] == 'product_remove') {
				check_authz_json('remove_back');
				$id_val = $_REQUEST['id'];
				$id_val = explode(',', $id_val);
				$product_id = intval($id_val[0]);
				$warehouse_id = intval($id_val[1]);

				if (empty($product_id)) {
					make_json_error($_LANG['product_id_null']);
				}
				else {
					$product_id = intval($product_id);
				}

				$product = get_product_info($product_id, 'product_number, goods_id');
				$sql = 'DELETE FROM ' . $ecs->table('products') . ' WHERE product_id = \'' . $product_id . '\'';
				$result = $db->query($sql);

				if ($result) {
					$url = 'goods.php?act=product_query&' . str_replace('act=product_remove', '', $_SERVER['QUERY_STRING']);
					ecs_header('Location: ' . $url . "\n");
					exit();
				}
			}
			else if ($_REQUEST['act'] == 'edit_product_price') {
				check_authz_json('goods_manage');
				$product_id = intval($_REQUEST['id']);
				$product_price = floatval($_POST['val']);
				$goods_model = (isset($_REQUEST['goods_model']) ? intval($_REQUEST['goods_model']) : 0);

				if ($goods_model == 1) {
					$table = 'products_warehouse';
				}
				else if ($goods_model == 2) {
					$table = 'products_area';
				}
				else {
					$table = 'products';
				}

				$sql = 'UPDATE ' . $ecs->table($table) . ' SET product_price = \'' . $product_price . '\' WHERE product_id = \'' . $product_id . '\'';
				$result = $db->query($sql);

				if ($result) {
					clear_cache_files();
					make_json_result($product_price);
				}
			}
			else if ($_REQUEST['act'] == 'edit_product_number') {
				check_authz_json('goods_manage');
				$product_id = (!empty($_POST['id']) ? intval($_POST['id']) : 0);
				$product_number = intval($_POST['val']);
				$goods_model = (isset($_REQUEST['goods_model']) ? intval($_REQUEST['goods_model']) : 0);

				if ($product_id) {
					if ($goods_model == 1) {
						$filed = ', warehouse_id';
					}
					else if ($goods_model == 2) {
						$filed = ', area_id';
					}
					else {
						$filed = '';
					}

					$product = get_product_info($product_id, 'product_number, goods_id' . $filed, $goods_model);

					if ($product['product_number'] != $product_number) {
						if ($product_number < $product['product_number']) {
							$number = $product['product_number'] - $product_number;
							$number = '- ' . $number;
							$log_use_storage = 10;
						}
						else {
							$number = $product_number - $product['product_number'];
							$number = '+ ' . $number;
							$log_use_storage = 11;
						}

						$logs_other = array('goods_id' => $product['goods_id'], 'order_id' => 0, 'use_storage' => $log_use_storage, 'admin_id' => $_SESSION['admin_id'], 'number' => $number, 'model_inventory' => $goods_model, 'model_attr' => $goods_model, 'product_id' => $product_id, 'warehouse_id' => isset($product['warehouse_id']) ? $product['warehouse_id'] : 0, 'area_id' => isset($product['area_id']) ? $product['area_id'] : 0, 'add_time' => gmtime());
						$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('goods_inventory_logs'), $logs_other, 'INSERT');
					}
				}

				if ($goods_model == 1) {
					$table = 'products_warehouse';
				}
				else if ($goods_model == 2) {
					$table = 'products_area';
				}
				else {
					$table = 'products';
				}

				$sql = 'UPDATE ' . $ecs->table($table) . ' SET product_number = \'' . $product_number . '\' WHERE product_id = \'' . $product_id . '\'';
				$result = $db->query($sql);

				if ($result) {
					clear_cache_files();
					make_json_result($product_number);
				}
			}
			else if ($_REQUEST['act'] == 'edit_product_warn_number') {
				check_authz_json('goods_manage');
				$product_id = intval($_POST['id']);
				$product_warn_number = intval($_POST['val']);
				$goods_model = (isset($_REQUEST['goods_model']) ? intval($_REQUEST['goods_model']) : 0);

				if ($goods_model == 1) {
					$table = 'products_warehouse';
				}
				else if ($goods_model == 2) {
					$table = 'products_area';
				}
				else {
					$table = 'products';
				}

				$sql = 'UPDATE ' . $ecs->table($table) . ' SET product_warn_number = \'' . $product_warn_number . '\' WHERE product_id = \'' . $product_id . '\'';
				$result = $db->query($sql);

				if ($result) {
					clear_cache_files();
					make_json_result($product_warn_number);
				}
			}
			else if ($_REQUEST['act'] == 'edit_product_sn') {
				check_authz_json('goods_manage');
				$product_id = intval($_REQUEST['id']);
				$product_sn = json_str_iconv(trim($_POST['val']));
				$product_sn = ($_LANG['n_a'] == $product_sn ? '' : $product_sn);
				$goods_model = (isset($_REQUEST['goods_model']) ? intval($_REQUEST['goods_model']) : 0);

				if (check_product_sn_exist($product_sn, $product_id, $adminru['ru_id'], $goods_model)) {
					make_json_error($_LANG['sys']['wrong'] . $_LANG['exist_same_product_sn']);
				}

				if ($goods_model == 1) {
					$table = 'products_warehouse';
				}
				else if ($goods_model == 2) {
					$table = 'products_area';
				}
				else {
					$table = 'products';
				}

				$sql = 'UPDATE ' . $ecs->table($table) . ' SET product_sn = \'' . $product_sn . '\' WHERE product_id = \'' . $product_id . '\'';
				$result = $db->query($sql);

				if ($result) {
					clear_cache_files();
					make_json_result($product_sn);
				}
			}
			else if ($_REQUEST['act'] == 'edit_product_bar_code') {
				check_authz_json('goods_manage');
				$product_id = intval($_REQUEST['id']);
				$bar_code = json_str_iconv(trim($_POST['val']));
				$bar_code = ($_LANG['n_a'] == $bar_code ? '' : $bar_code);
				$goods_model = (isset($_REQUEST['goods_model']) ? intval($_REQUEST['goods_model']) : 0);

				if (check_product_bar_code_exist($bar_code, $product_id, $adminru['ru_id'], $goods_model)) {
					make_json_error($_LANG['sys']['wrong'] . $_LANG['exist_same_bar_code']);
				}

				if ($goods_model == 1) {
					$table = 'products_warehouse';
				}
				else if ($goods_model == 2) {
					$table = 'products_area';
				}
				else {
					$table = 'products';
				}

				$sql = 'UPDATE ' . $ecs->table($table) . ' SET bar_code = \'' . $bar_code . '\' WHERE product_id = \'' . $product_id . '\'';
				$result = $db->query($sql);

				if ($result) {
					clear_cache_files();
					make_json_result($bar_code);
				}
			}
			else if ($_REQUEST['act'] == 'edit_attr_sort') {
				check_authz_json('goods_manage');
				$goods_attr_id = intval($_REQUEST['id']);
				$attr_sort = intval($_POST['val']);
				$sql = 'UPDATE ' . $ecs->table('goods_attr') . ' SET attr_sort = \'' . $attr_sort . '\' WHERE goods_attr_id = \'' . $goods_attr_id . '\'';
				$result = $db->query($sql);

				if ($result) {
					clear_cache_files();
					make_json_result($attr_sort);
				}
			}
			else if ($_REQUEST['act'] == 'edit_attr_price') {
				check_authz_json('goods_manage');
				$goods_attr_id = intval($_REQUEST['id']);
				$attr_price = floatval($_POST['val']);
				$sql = 'UPDATE ' . $ecs->table('goods_attr') . ' SET attr_price = \'' . $attr_price . '\' WHERE goods_attr_id = \'' . $goods_attr_id . '\'';
				$result = $db->query($sql);

				if ($result) {
					clear_cache_files();
					make_json_result($attr_price);
				}
			}
			else if ($_REQUEST['act'] == 'addWarehouse') {
				require ROOT_PATH . '/includes/cls_json.php';
				$json = new JSON();
				$result = array('content' => '', 'error' => 0, 'massege' => '');
				$ware_name = (!empty($_POST['ware_name']) ? $_POST['ware_name'] : '');
				$ware_number = (!empty($_POST['ware_number']) ? intval($_POST['ware_number']) : 0);
				$ware_price = (!empty($_POST['ware_price']) ? $_POST['ware_price'] : 0);
				$ware_price = floatval($ware_price);
				$ware_promote_price = (!empty($_POST['ware_promote_price']) ? $_POST['ware_promote_price'] : 0);
				$ware_promote_price = floatval($ware_promote_price);
				$give_integral = (!empty($_POST['give_integral']) ? intval($_POST['give_integral']) : 0);
				$rank_integral = (!empty($_POST['rank_integral']) ? intval($_POST['rank_integral']) : 0);
				$pay_integral = (!empty($_POST['pay_integral']) ? intval($_POST['pay_integral']) : 0);
				$goods_id = (!empty($_POST['goods_id']) ? intval($_POST['goods_id']) : 0);

				if (empty($ware_name)) {
					$result['error'] = '1';
					$result['massege'] = '请选择仓库';
				}
				else {
					$sql = 'select w_id from ' . $GLOBALS['ecs']->table('warehouse_goods') . ' where goods_id = \'' . $goods_id . '\' and region_id = \'' . $ware_name . '\' AND user_id = \'' . $user_id . '\'';
					$w_id = $GLOBALS['db']->getOne($sql);
					$add_time = gmtime();

					if (0 < $w_id) {
						$result['error'] = '1';
						$result['massege'] = '该商品的仓库库存已存在';
					}
					else if ($ware_number == 0) {
						$result['error'] = '1';
						$result['massege'] = '仓库库存不能为0';
					}
					else if ($ware_price == 0) {
						$result['error'] = '1';
						$result['massege'] = '仓库价格不能为0';
					}
					else {
						$goodsInfo = get_admin_goods_info($goods_id, array('user_id', 'model_inventory', 'model_attr'));
						$goodsInfo['user_id'] = !empty($goodsInfo['user_id']) ? $goodsInfo['user_id'] : $adminru['ru_id'];
						$number = '+ ' . $ware_number;
						$use_storage = 13;
						$logs_other = array('goods_id' => $goods_id, 'order_id' => 0, 'use_storage' => $use_storage, 'admin_id' => $_SESSION['admin_id'], 'number' => $number, 'model_inventory' => $goodsInfo['model_inventory'], 'model_attr' => $goodsInfo['model_attr'], 'product_id' => 0, 'warehouse_id' => $ware_name, 'area_id' => 0, 'add_time' => $add_time);
						$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('goods_inventory_logs'), $logs_other, 'INSERT');
						$sql = 'insert into ' . $GLOBALS['ecs']->table('warehouse_goods') . '(goods_id, region_id, region_number, warehouse_price, warehouse_promote_price, give_integral, rank_integral, pay_integral, user_id, add_time)VALUES(\'' . $goods_id . '\',\'' . $ware_name . '\',\'' . $ware_number . '\',\'' . $ware_price . '\',\'' . $ware_promote_price . '\',\'' . $give_integral . '\',\'' . $rank_integral . '\',\'' . $pay_integral . '\',\'' . $goodsInfo['user_id'] . '\',\'' . $add_time . '\')';

						if ($GLOBALS['db']->query($sql) == true) {
							$result['error'] = '2';
							$get_warehouse_goods_list = get_warehouse_goods_list($goods_id);
							$warehouse_id = '';

							if (!empty($get_warehouse_goods_list)) {
								foreach ($get_warehouse_goods_list as $k => $v) {
									$warehouse_id .= $v['w_id'] . ',';
								}
							}

							$warehouse_id = substr($warehouse_id, 0, strlen($warehouse_id) - 1);
							$smarty->assign('warehouse_id', $warehouse_id);
							$smarty->assign('warehouse_goods_list', $get_warehouse_goods_list);
							$result['content'] = $GLOBALS['smarty']->fetch('library/goods_warehouse.dwt');
						}
					}
				}

				exit($json->encode($result));
			}
			else if ($_REQUEST['act'] == 'addBatchWarehouse') {
				require ROOT_PATH . '/includes/cls_json.php';
				$json = new JSON();
				$result = array('content' => '', 'error' => 0, 'massege' => '');
				$ware_name = (!empty($_POST['ware_name']) ? explode(',', $_POST['ware_name']) : array());
				$ware_number = (!empty($_POST['ware_number']) ? explode(',', $_POST['ware_number']) : array());
				$ware_price = (!empty($_POST['ware_price']) ? explode(',', $_POST['ware_price']) : array());
				$ware_promote_price = (!empty($_POST['ware_promote_price']) ? explode(',', $_POST['ware_promote_price']) : array());
				$goods_id = (!empty($_POST['goods_id']) ? intval($_POST['goods_id']) : 0);

				if (empty($ware_name)) {
					$result['error'] = '1';
					$result['massege'] = '请选择仓库';
				}
				else {
					$add_time = gmtime();
					$goodsInfo = get_admin_goods_info($goods_id, array('user_id', 'model_inventory', 'model_attr'));
					$goodsInfo['user_id'] = !empty($goodsInfo['user_id']) ? $goodsInfo['user_id'] : $adminru['ru_id'];

					for ($i = 0; $i < count($ware_name); $i++) {
						if (!empty($ware_name[$i])) {
							if ($ware_number[$i] == 0) {
								$ware_number[$i] = 1;
							}

							$sql = 'SELECT w_id FROM ' . $GLOBALS['ecs']->table('warehouse_goods') . ' WHERE goods_id = \'' . $goods_id . '\' AND region_id = \'' . $ware_name[$i] . '\'';
							$w_id = $GLOBALS['db']->getOne($sql, true);

							if (0 < $w_id) {
								$result['error'] = '1';
								$result['massege'] = '该商品的仓库库存已存在';
								break;
							}
							else {
								$ware_number[$i] = intval($ware_number[$i]);
								$ware_price[$i] = floatval($ware_price[$i]);
								$number = '+ ' . $ware_number[$i];
								$use_storage = 13;
								$logs_other = array('goods_id' => $goods_id, 'order_id' => 0, 'use_storage' => $use_storage, 'admin_id' => $_SESSION['admin_id'], 'number' => $number, 'model_inventory' => $goodsInfo['model_inventory'], 'model_attr' => $goodsInfo['model_attr'], 'product_id' => 0, 'warehouse_id' => $ware_name[$i], 'area_id' => 0, 'add_time' => $add_time);
								$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('goods_inventory_logs'), $logs_other, 'INSERT');
								$sql = 'insert into ' . $GLOBALS['ecs']->table('warehouse_goods') . '(goods_id, region_id, region_number, warehouse_price, warehouse_promote_price, user_id, add_time)VALUES(\'' . $goods_id . '\',\'' . $ware_name[$i] . '\',\'' . $ware_number[$i] . '\',\'' . $ware_price[$i] . '\',\'' . floatval($ware_promote_price[$i]) . '\',\'' . $goodsInfo['user_id'] . '\',\'' . $add_time . '\')';
								$GLOBALS['db']->query($sql);
								$get_warehouse_goods_list = get_warehouse_goods_list($goods_id);
								$warehouse_id = '';

								if (!empty($get_warehouse_goods_list)) {
									foreach ($get_warehouse_goods_list as $k => $v) {
										$warehouse_id .= $v['w_id'] . ',';
									}
								}

								$warehouse_id = substr($warehouse_id, 0, strlen($warehouse_id) - 1);
								$smarty->assign('warehouse_id', $warehouse_id);
								$smarty->assign('warehouse_goods_list', $get_warehouse_goods_list);
							}
						}
						else {
							$result['error'] = '1';
							$result['massege'] = '请选择仓库';
						}
					}
				}

				exit($json->encode($result));
			}
			else if ($_REQUEST['act'] == 'goods_warehouse') {
				require ROOT_PATH . '/includes/cls_json.php';
				$json = new JSON();
				$result = array('content' => '', 'error' => 0, 'massege' => '');
				$goods_id = (!empty($_REQUEST['goods_id']) ? intval($_REQUEST['goods_id']) : 0);
				$warehouse_goods_list = get_warehouse_goods_list($goods_id);
				$GLOBALS['smarty']->assign('warehouse_goods_list', $warehouse_goods_list);
				$GLOBALS['smarty']->assign('is_list', 1);
				$result['content'] = $GLOBALS['smarty']->fetch('goods_warehouse.dwt');
				exit($json->encode($result));
			}
			else if ($_REQUEST['act'] == 'goods_region') {
				require ROOT_PATH . '/includes/cls_json.php';
				$json = new JSON();
				$result = array('content' => '', 'error' => 0, 'massege' => '');
				$goods_id = (!empty($_REQUEST['goods_id']) ? intval($_REQUEST['goods_id']) : 0);
				$warehouse_area_goods_list = get_warehouse_area_goods_list($goods_id);
				$GLOBALS['smarty']->assign('warehouse_area_goods_list', $warehouse_area_goods_list);
				$GLOBALS['smarty']->assign('is_list', 1);
				$result['content'] = $GLOBALS['smarty']->fetch('goods_region.dwt');
				exit($json->encode($result));
			}
			else if ($_REQUEST['act'] == 'addRegion') {
				require ROOT_PATH . '/includes/cls_json.php';
				$json = new JSON();
				$result = array('content' => '', 'error' => 0, 'massege' => '');
				$warehouse_area_name = (!empty($_POST['warehouse_area_name']) ? $_POST['warehouse_area_name'] : '');
				$area_name = (!empty($_POST['warehouse_area_list']) ? $_POST['warehouse_area_list'] : '');
				$region_number = (!empty($_POST['region_number']) ? intval($_POST['region_number']) : 0);
				$region_price = (!empty($_POST['region_price']) ? floatval($_POST['region_price']) : 0);
				$region_promote_price = (!empty($_POST['region_promote_price']) ? floatval($_POST['region_promote_price']) : 0);
				$give_integral = (!empty($_POST['give_integral']) ? intval($_POST['give_integral']) : 0);
				$rank_integral = (!empty($_POST['rank_integral']) ? intval($_POST['rank_integral']) : 0);
				$pay_integral = (!empty($_POST['pay_integral']) ? intval($_POST['pay_integral']) : 0);
				$goods_id = (!empty($_POST['goods_id']) ? intval($_POST['goods_id']) : 0);

				if (empty($area_name)) {
					$result['error'] = '1';
					$result['massege'] = '请选择地区';
				}
				else if ($region_number == 0) {
					$result['error'] = '1';
					$result['massege'] = '地区库存不能为0';
				}
				else if ($region_price == 0) {
					$result['error'] = '1';
					$result['massege'] = '地区价格不能为0';
				}
				else {
					$add_time = gmtime();
					$sql = 'select a_id from ' . $GLOBALS['ecs']->table('warehouse_area_goods') . ' where goods_id = \'' . $goods_id . '\' and region_id = \'' . $area_name . '\'';
					$a_id = $GLOBALS['db']->getOne($sql);

					if (0 < $a_id) {
						$result['error'] = '1';
						$result['massege'] = '该商品的地区价格已存在';
					}
					else {
						$goodsInfo = get_admin_goods_info($goods_id, array('goods_id', 'user_id', 'model_inventory', 'model_attr'));
						$goodsInfo['user_id'] = !empty($goodsInfo['user_id']) ? $goodsInfo['user_id'] : $adminru['ru_id'];
						$number = '+ ' . $region_number;
						$use_storage = 13;
						$logs_other = array('goods_id' => $goods_id, 'order_id' => 0, 'use_storage' => $use_storage, 'admin_id' => $_SESSION['admin_id'], 'number' => $number, 'model_inventory' => $goodsInfo['model_inventory'], 'model_attr' => $goodsInfo['model_attr'], 'product_id' => 0, 'warehouse_id' => 0, 'area_id' => $area_name, 'add_time' => $add_time);
						$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('goods_inventory_logs'), $logs_other, 'INSERT');
						$sql = 'insert into ' . $GLOBALS['ecs']->table('warehouse_area_goods') . '(goods_id, region_id, region_number, region_price, region_promote_price, give_integral, rank_integral, pay_integral, user_id, add_time)VALUES(\'' . $goods_id . '\',\'' . $area_name . '\',\'' . $region_number . '\',\'' . floatval($region_price) . '\',\'' . floatval($region_promote_price) . '\',\'' . floatval($give_integral) . '\',\'' . floatval($rank_integral) . '\',\'' . floatval($pay_integral) . '\',\'' . $goodsInfo['user_id'] . '\',\'' . $add_time . '\')';

						if ($GLOBALS['db']->query($sql) == true) {
							$result['error'] = '2';
							$warehouse_area_goods_list = get_warehouse_area_goods_list($goods_id);
							$warehouse_id = '';

							if (!empty($warehouse_area_goods_list)) {
								foreach ($warehouse_area_goods_list as $k => $v) {
									$warehouse_id .= $v['a_id'] . ',';
								}
							}

							$warehouse_area_id = substr($warehouse_id, 0, strlen($warehouse_id) - 1);
							$smarty->assign('warehouse_area_id', $warehouse_area_id);
							$smarty->assign('warehouse_area_goods_list', $warehouse_area_goods_list);
							$smarty->assign('goods', $goodsInfo);
							$result['content'] = $GLOBALS['smarty']->fetch('library/goods_region.dwt');
						}
					}
				}

				exit($json->encode($result));
			}
			else if ($_REQUEST['act'] == 'addBatchRegion') {
				require ROOT_PATH . '/includes/cls_json.php';
				$json = new JSON();
				$result = array('content' => '', 'error' => 0, 'massege' => '');
				$warehouse_area_name = (!empty($_POST['warehouse_area_name']) ? explode(',', $_POST['warehouse_area_name']) : array());
				$area_name = (!empty($_POST['warehouse_area_list']) ? explode(',', $_POST['warehouse_area_list']) : array());
				$region_number = (!empty($_POST['region_number']) ? explode(',', $_POST['region_number']) : array());
				$region_price = (!empty($_POST['region_price']) ? explode(',', $_POST['region_price']) : array());
				$region_promote_price = (!empty($_POST['region_promote_price']) ? explode(',', $_POST['region_promote_price']) : array());
				$goods_id = (!empty($_POST['goods_id']) ? intval($_POST['goods_id']) : 0);

				if (empty($area_name)) {
					$result['error'] = '1';
					$result['massege'] = '请选择地区';
				}
				else if (empty($region_number)) {
					$result['error'] = '1';
					$result['massege'] = '地区库存不能为0';
				}
				else if (empty($region_price)) {
					$result['error'] = '1';
					$result['massege'] = '地区价格不能为0';
				}
				else {
					$add_time = gmtime();
					$goodsInfo = get_admin_goods_info($goods_id, array('goods_id', 'user_id', 'model_inventory', 'model_attr'));
					$goodsInfo['user_id'] = !empty($goodsInfo['user_id']) ? $goodsInfo['user_id'] : $adminru['ru_id'];

					for ($i = 0; $i < count($area_name); $i++) {
						if (!empty($area_name[$i])) {
							$sql = 'select a_id from ' . $GLOBALS['ecs']->table('warehouse_area_goods') . ' where goods_id = \'' . $goods_id . '\' and region_id = \'' . $area_name[$i] . '\'';
							$a_id = $GLOBALS['db']->getOne($sql, true);

							if (0 < $a_id) {
								$result['error'] = '1';
								$result['massege'] = '该商品的地区价格已存在';
								break;
							}
							else {
								$ware_number[$i] = intval($ware_number[$i]);
								$ware_price[$i] = floatval($ware_price[$i]);
								$region_promote_price[$i] = floatval($region_promote_price[$i]);
								$number = '+ ' . $ware_number[$i];
								$use_storage = 13;
								$logs_other = array('goods_id' => $goods_id, 'order_id' => 0, 'use_storage' => $use_storage, 'admin_id' => $_SESSION['admin_id'], 'number' => $number, 'model_inventory' => $goodsInfo['model_inventory'], 'model_attr' => $goodsInfo['model_attr'], 'product_id' => 0, 'warehouse_id' => 0, 'area_id' => $area_name[$i], 'add_time' => $add_time);
								$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('goods_inventory_logs'), $logs_other, 'INSERT');
								$sql = 'insert into ' . $GLOBALS['ecs']->table('warehouse_area_goods') . '(goods_id, region_id, region_number, region_price, region_promote_price, user_id, add_time)VALUES(\'' . $goods_id . '\',\'' . $area_name[$i] . '\',\'' . $region_number[$i] . '\',\'' . $region_price[$i] . '\',\'' . $region_promote_price[$i] . '\',\'' . $goodsInfo['user_id'] . '\',\'' . $add_time . '\')';
								$GLOBALS['db']->query($sql);
								$get_warehouse_area_goods_list = get_warehouse_area_goods_list($goods_id);
								$warehouse_id = '';

								if (!empty($get_warehouse_area_goods_list)) {
									foreach ($get_warehouse_area_goods_list as $k => $v) {
										$warehouse_id .= $v['a_id'] . ',';
									}
								}

								$warehouse_area_id = substr($warehouse_id, 0, strlen($warehouse_id) - 1);
								$smarty->assign('warehouse_area_id', $warehouse_area_id);
								$smarty->assign('warehouse_area_goods_list', $get_warehouse_area_goods_list);
								$smarty->assign('goods', $goodsInfo);
							}
						}
						else {
							$result['error'] = '1';
							$result['massege'] = '请选择地区';
							break;
						}
					}
				}

				exit($json->encode($result));
			}
			else if ($_REQUEST['act'] == 'addImg') {
				require ROOT_PATH . '/includes/cls_json.php';
				$json = new JSON();
				$result = array('content' => '', 'error' => 0, 'massege' => '');
				$goods_id = (!empty($_REQUEST['goods_id_img']) ? $_REQUEST['goods_id_img'] : '');
				$img_desc = (!empty($_REQUEST['img_desc']) ? $_REQUEST['img_desc'] : '');
				$img_file = (!empty($_REQUEST['img_file']) ? $_REQUEST['img_file'] : '');
				$php_maxsize = ini_get('upload_max_filesize');
				$htm_maxsize = '2M';

				if ($_FILES['img_url']) {
					foreach ($_FILES['img_url']['error'] as $key => $value) {
						if ($value == 0) {
							if (!$image->check_img_type($_FILES['img_url']['type'][$key])) {
								$result['error'] = '1';
								$result['massege'] = sprintf($_LANG['invalid_img_url'], $key + 1);
							}
							else {
								$goods_pre = 1;
							}
						}
						else if ($value == 1) {
							$result['error'] = '1';
							$result['massege'] = sprintf($_LANG['img_url_too_big'], $key + 1, $php_maxsize);
						}
						else if ($_FILES['img_url']['error'] == 2) {
							$result['error'] = '1';
							$result['massege'] = sprintf($_LANG['img_url_too_big'], $key + 1, $htm_maxsize);
						}
					}
				}

				handle_gallery_image_add($goods_id, $_FILES['img_url'], $img_desc, $img_file, '', '', 'ajax');
				clear_cache_files();

				if (0 < $goods_id) {
					$sql = 'SELECT * FROM ' . $ecs->table('goods_gallery') . ' WHERE goods_id = \'' . $goods_id . '\' ORDER BY img_desc ASC';
				}
				else {
					$img_id = $_SESSION['thumb_img_id' . $_SESSION['admin_id']];
					$where = '';

					if ($img_id) {
						$where = 'AND img_id ' . db_create_in($img_id) . '';
					}

					$sql = 'SELECT * FROM ' . $ecs->table('goods_gallery') . ' WHERE goods_id=\'\' ' . $where . ' ORDER BY img_desc ASC';
				}

				$img_list = $db->getAll($sql);
				if (isset($GLOBALS['shop_id']) && (0 < $GLOBALS['shop_id'])) {
					foreach ($img_list as $key => $gallery_img) {
						$gallery_img[$key]['img_url'] = get_image_path($gallery_img['goods_id'], $gallery_img['img_original'], false, 'gallery');
						$gallery_img[$key]['thumb_url'] = get_image_path($gallery_img['goods_id'], $gallery_img['img_original'], true, 'gallery');
					}
				}
				else {
					foreach ($img_list as $key => $gallery_img) {
						$gallery_img[$key]['thumb_url'] = '../' . (empty($gallery_img['thumb_url']) ? $gallery_img['img_url'] : $gallery_img['thumb_url']);
					}
				}

				$goods['goods_id'] = $goods_id;
				$smarty->assign('img_list', $img_list);
				$img_desc = array();

				foreach ($img_list as $k => $v) {
					$img_desc[] = $v['img_desc'];
				}

				$img_default = min($img_desc);
				$min_img_id = $db->getOne(' SELECT img_id   FROM ' . $ecs->table('goods_gallery') . ' WHERE goods_id = \'' . $goods_id . '\' AND img_desc = \'' . $img_default . '\' ORDER BY img_desc   LIMIT 1');
				$smarty->assign('min_img_id', $min_img_id);
				$smarty->assign('goods', $goods);
				$result['error'] = '2';
				$result['content'] = $GLOBALS['smarty']->fetch('gallery_img.lbi');
				exit($json->encode($result));
			}
			else if ($_REQUEST['act'] == 'img_default') {
				require ROOT_PATH . '/includes/cls_json.php';
				$json = new JSON();
				$result = array('content' => '', 'error' => 0, 'massege' => '', 'img_id' => '');
				$img_id = (!empty($_REQUEST['img_id']) ? intval($_REQUEST['img_id']) : '0');
				$proc_thumb = (isset($GLOBALS['shop_id']) && (0 < $GLOBALS['shop_id']) ? false : true);

				if (0 < $img_id) {
					$goods_gallery = $db->getRow(' SELECT goods_id,img_desc FROM' . $ecs->table('goods_gallery') . ' WHERE img_id= \'' . $img_id . '\'');
					$goods_id = $goods_gallery['goods_id'];
					$sql = 'SELECT MIN(img_desc) FROM' . $ecs->table('goods_gallery') . ' WHERE  goods_id = \'' . $goods_id . '\'';
					$least_img_desc = $db->getOne($sql);
					$db->query('UPDATE' . $ecs->table('goods_gallery') . ' SET img_desc = \'' . $goods_gallery['img_desc'] . '\' WHERE img_desc = \'' . $least_img_desc . '\' AND goods_id = \'' . $goods_id . '\' ');
					$sql = $db->query('UPDATE' . $ecs->table('goods_gallery') . ' SET img_desc = \'' . $least_img_desc . '\' WHERE img_id = \'' . $img_id . '\'');

					if ($sql = true) {
						if (0 < $goods_id) {
							$where = ' goods_id = \'' . $goods_id . '\' ';
						}
						else {
							$where = ' img_id ' . db_create_in($_SESSION['thumb_img_id' . $_SESSION['admin_id']]) . ' and goods_id = 0 ';
						}

						$sql = 'SELECT * FROM ' . $ecs->table('goods_gallery') . ' WHERE ' . $where . '  ORDER BY img_desc ASC ';
						$img_list = $db->getAll($sql);
						if (isset($GLOBALS['shop_id']) && (0 < $GLOBALS['shop_id'])) {
							foreach ($img_list as $key => $gallery_img) {
								$img_list[$key] = $gallery_img;

								if (!empty($gallery_img['external_url'])) {
									$img_list[$key]['img_url'] = $gallery_img['external_url'];
									$img_list[$key]['thumb_url'] = $gallery_img['external_url'];
								}
								else {
									$img_list[$key]['img_url'] = get_image_path($gallery_img['goods_id'], $gallery_img['img_original'], false, 'gallery');
									$img_list[$key]['thumb_url'] = get_image_path($gallery_img['goods_id'], $gallery_img['img_original'], true, 'gallery');
								}
							}
						}
						else {
							foreach ($img_list as $key => $gallery_img) {
								$img_list[$key] = $gallery_img;

								if (!empty($gallery_img['external_url'])) {
									$img_list[$key]['img_url'] = $gallery_img['external_url'];
									$img_list[$key]['thumb_url'] = $gallery_img['external_url'];
								}
								else if ($proc_thumb) {
									$img_list[$key]['thumb_url'] = '../' . (empty($gallery_img['thumb_url']) ? $gallery_img['img_url'] : $gallery_img['thumb_url']);
								}
								else {
									$img_list[$key]['thumb_url'] = empty($gallery_img['thumb_url']) ? $gallery_img['img_url'] : $gallery_img['thumb_url'];
								}
							}
						}

						$img_desc = array();

						if (!empty($img_list)) {
							foreach ($img_list as $k => $v) {
								$img_desc[] = $v['img_desc'];
							}
						}

						if (!empty($img_desc)) {
							$img_default = min($img_desc);
						}

						$min_img_id = $db->getOne(' SELECT img_id   FROM ' . $ecs->table('goods_gallery') . ' WHERE goods_id = \'' . $goods_id . '\' AND img_desc = \'' . $img_default . '\' ORDER BY img_desc LIMIT 1');
						$smarty->assign('min_img_id', $min_img_id);
						$smarty->assign('img_list', $img_list);
						$result['error'] = 1;
						$result['content'] = $GLOBALS['smarty']->fetch('gallery_img.lbi');
					}
					else {
						$result['error'] = 2;
						$result['massege'] = '修改失败';
					}
				}

				exit($json->encode($result));
			}
			else if ($_REQUEST['act'] == 'remove_consumption') {
				require ROOT_PATH . '/includes/cls_json.php';
				$json = new JSON();
				$result = array('error' => 0, 'massege' => '', 'con_id' => '');
				$con_id = (!empty($_REQUEST['con_id']) ? intval($_REQUEST['con_id']) : '0');
				$goods_id = (!empty($_REQUEST['goods_id']) ? intval($_REQUEST['goods_id']) : '0');

				if (0 < $con_id) {
					$sql = 'DELETE FROM' . $ecs->table('goods_consumption') . ' WHERE id = \'' . $con_id . '\' AND goods_id = \'' . $goods_id . '\'';

					if ($db->query($sql)) {
						$result['error'] = 2;
						$result['con_id'] = $con_id;
					}
				}
				else {
					$result['error'] = 1;
					$result['massege'] = '请选择删除目标';
				}

				exit($json->encode($result));
			}
		}
	}
}

?>
