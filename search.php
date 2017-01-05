<?php
//zend by QQ:2172298892
function is_not_null($value)
{
	if (is_array($value)) {
		return !empty($value['from']) || !empty($value['to']);
	}
	else {
		return !empty($value);
	}
}

function get_seachable_attributes($cat_id = 0)
{
	$attributes = array(
		'cate' => array(),
		'attr' => array()
		);
	$sql = 'SELECT t.cat_id, cat_name FROM ' . $GLOBALS['ecs']->table('goods_type') . ' AS t, ' . $GLOBALS['ecs']->table('attribute') . ' AS a' . ' WHERE t.cat_id = a.cat_id AND t.enabled = 1 AND a.attr_index > 0 ';
	$cat = $GLOBALS['db']->getAll($sql);

	if (!empty($cat)) {
		foreach ($cat as $val) {
			$attributes['cate'][$val['cat_id']] = $val['cat_name'];
		}

		$where = (0 < $cat_id ? ' AND a.cat_id = ' . $cat_id : ' AND a.cat_id = ' . $cat[0]['cat_id']);
		$sql = 'SELECT attr_id, attr_name, attr_input_type, attr_type, attr_values, attr_index, sort_order ' . ' FROM ' . $GLOBALS['ecs']->table('attribute') . ' AS a ' . ' WHERE a.attr_index > 0 ' . $where . ' ORDER BY cat_id, sort_order ASC';
		$res = $GLOBALS['db']->query($sql);

		while ($row = $GLOBALS['db']->FetchRow($res)) {
			if (($row['attr_index'] == 1) && ($row['attr_input_type'] == 1)) {
				$row['attr_values'] = str_replace("\r", '', $row['attr_values']);
				$options = explode("\n", $row['attr_values']);
				$attr_value = array();

				foreach ($options as $opt) {
					$attr_value[$opt] = $opt;
				}

				$attributes['attr'][] = array('id' => $row['attr_id'], 'attr' => $row['attr_name'], 'options' => $attr_value, 'type' => 3);
			}
			else {
				$attributes['attr'][] = array('id' => $row['attr_id'], 'attr' => $row['attr_name'], 'type' => $row['attr_index']);
			}
		}
	}

	return $attributes;
}

define('IN_ECS', true);

if (!function_exists('htmlspecialchars_decode')) {
	function htmlspecialchars_decode($string, $quote_style = ENT_COMPAT)
	{
		return strtr($string, array_flip(get_html_translation_table(HTML_SPECIALCHARS, $quote_style)));
	}
}

if (empty($_GET['encode'])) {
	$string = array_merge($_GET, $_POST);

	if (get_magic_quotes_gpc()) {
		require dirname(__FILE__) . '/includes/lib_base.php';
		$string = stripslashes_deep($string);
	}

	$string['search_encode_time'] = time();
	$string = str_replace('+', '%2b', base64_encode(serialize($string)));
	header('Location:search.php?encode=' . $string . "\n");
	exit();
}
else {
	$string = base64_decode(trim($_GET['encode']));

	if ($string !== false) {
		$string = unserialize($string);

		if ($string !== false) {
			if (!empty($string['search_encode_time'])) {
				if (($string['search_encode_time'] + 2) < time()) {
					define('INGORE_VISIT_STATS', true);
				}
			}
			else {
				define('INGORE_VISIT_STATS', true);
			}

			if (@$string['keywords'] == '优惠券') {
				header('location:http://' . str_replace(strrchr($_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'], '/'), '', $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '/coupons.php?act=coupons_index'));
			}
		}
		else {
			$string = array();
		}
	}
	else {
		$string = array();
	}
}

require dirname(__FILE__) . '/includes/init.php';
require ROOT_PATH . '/includes/lib_area.php';
$area_info = get_area_info($province_id);
$area_id = $area_info['region_id'];
$where = 'regionId = \'' . $province_id . '\'';
$date = array('parent_id');
$region_id = get_table_date('region_warehouse', $where, $date, 2);
$_REQUEST = array_merge($_REQUEST, addslashes_deep($string));
$_REQUEST['act'] = !empty($_REQUEST['act']) ? trim($_REQUEST['act']) : '';
$search_type = (!empty($_REQUEST['store_search_cmt']) ? intval($_REQUEST['store_search_cmt']) : 0);
get_request_filter();
$_REQUEST['keywords'] = strip_tags($_REQUEST['keywords']);
$_REQUEST['keywords'] = !empty($_REQUEST['keywords']) ? addslashes_deep(trim($_REQUEST['keywords'])) : '';
$_REQUEST['category'] = !empty($_REQUEST['category']) ? intval($_REQUEST['category']) : 0;
$_REQUEST['goods_type'] = !empty($_REQUEST['goods_type']) ? intval($_REQUEST['goods_type']) : 0;
$_REQUEST['sc_ds'] = !empty($_REQUEST['sc_ds']) ? intval($_REQUEST['sc_ds']) : 0;
$_REQUEST['outstock'] = !empty($_REQUEST['outstock']) ? 1 : 0;
$get_price_max = $_REQUEST['price_max'];
$get_price_min = $_REQUEST['price_min'];
$_REQUEST['price_max'] = isset($_REQUEST['price_max']) && (0 < intval($_REQUEST['price_max'])) ? intval($_REQUEST['price_max']) : 0;
$_REQUEST['price_min'] = isset($_REQUEST['price_min']) && (0 < intval($_REQUEST['price_min'])) ? intval($_REQUEST['price_min']) : 0;
$price_min = $_REQUEST['price_min'];
$price_max = $_REQUEST['price_max'];
$brand = $ecs->get_explode_filter($_REQUEST['brand']);
$smarty->assign('search_type', $search_type);
$smarty->assign('search_keywords', stripslashes(htmlspecialchars_decode($_REQUEST['keywords'])));
$default_sort_order_method = ($_CFG['sort_order_method'] == '0' ? 'DESC' : 'ASC');
$order = (isset($_REQUEST['order']) && in_array(trim(strtoupper($_REQUEST['order'])), array('ASC', 'DESC')) ? trim($_REQUEST['order']) : $default_sort_order_method);
$display = (isset($_REQUEST['display']) && in_array(trim(strtolower($_REQUEST['display'])), array('list', 'grid', 'text')) ? trim($_REQUEST['display']) : (isset($_SESSION['display_search']) ? $_SESSION['display_search'] : 'list'));
$_SESSION['display_search'] = $display;

if ($search_type == 1) {
	if ($display == 'list') {
		$default_sort_order_type = 'shop_id';
		$sort = (isset($_REQUEST['sort']) && in_array(trim(strtolower($_REQUEST['sort'])), array('shop_id', 'goods_number', 'sales_volume')) ? trim($_REQUEST['sort']) : $default_sort_order_type);
	}
	else {
		if (($display == 'grid') || ($display == 'text')) {
			$default_sort_order_type = ($_CFG['sort_order_type'] == '0' ? 'goods_id' : ($_CFG['sort_order_type'] == '1' ? 'shop_price' : 'last_update'));
			$sort = (isset($_REQUEST['sort']) && in_array(trim(strtolower($_REQUEST['sort'])), array('goods_id', 'shop_price', 'last_update', 'sales_volume')) ? trim($_REQUEST['sort']) : $default_sort_order_type);
		}
	}
}
else {
	$smarty->assign('province_row', get_region_info($province_id));
	$smarty->assign('city_row', get_region_info($city_id));
	$smarty->assign('district_row', get_region_info($district_id));
	$province_list = get_warehouse_province();
	$smarty->assign('province_list', $province_list);
	$city_list = get_region_city_county($province_id);
	$smarty->assign('city_list', $city_list);
	$district_list = get_region_city_county($city_id);
	$smarty->assign('district_list', $district_list);
	$smarty->assign('open_area_goods', $GLOBALS['_CFG']['open_area_goods']);
	$default_sort_order_type = ($_CFG['sort_order_type'] == '0' ? 'goods_id' : ($_CFG['sort_order_type'] == '1' ? 'shop_price' : 'last_update'));
	$sort = (isset($_REQUEST['sort']) && in_array(trim(strtolower($_REQUEST['sort'])), array('goods_id', 'shop_price', 'last_update', 'sales_volume', 'comments_number')) ? trim($_REQUEST['sort']) : $default_sort_order_type);
	$is_ship = (isset($_REQUEST['is_ship']) && !empty($_REQUEST['is_ship']) ? addslashes_deep(trim($_REQUEST['is_ship'])) : '');
	$is_self = (isset($_REQUEST['is_self']) && !empty($_REQUEST['is_self']) ? intval($_REQUEST['is_self']) : '');
}

$page = (!empty($_REQUEST['page']) && (0 < intval($_REQUEST['page'])) ? intval($_REQUEST['page']) : 1);
$size = (!empty($_CFG['page_size']) && (0 < intval($_CFG['page_size'])) ? intval($_CFG['page_size']) : 10);
$smarty->assign('category_load_type', $_CFG['category_load_type']);
$smarty->assign('query_string', $_SERVER['QUERY_STRING']);
if (isset($_REQUEST['act']) && ($_REQUEST['act'] == 'load_more_goods')) {
	$goods_num = (empty($_REQUEST['goods_num']) ? 0 : intval($_REQUEST['goods_num']));
	$best_num = (empty($_REQUEST['best_num']) ? 0 : intval($_REQUEST['best_num']));
	$goods_floor = floor(((($goods_num / 4) * 5) / 4) - $best_num);

	if ($goods_floor < 0) {
		$best_size = $_REQUEST['best_num'];
	}
	else {
		$best_size = $goods_floor + 2;
	}
}
else {
	$best_num = 0;
	$best_size = 6;
}

if ($_REQUEST['act'] == 'advanced_search') {
	$goods_type = (!empty($_REQUEST['goods_type']) ? intval($_REQUEST['goods_type']) : 0);
	$attributes = get_seachable_attributes($goods_type);
	$smarty->assign('goods_type_selected', $goods_type);
	$smarty->assign('goods_type_list', $attributes['cate']);
	$smarty->assign('goods_attributes', $attributes['attr']);
	assign_template();
	assign_dynamic('search');
	$position = assign_ur_here(0, $_LANG['advanced_search']);
	$smarty->assign('page_title', $position['title']);
	$smarty->assign('ur_here', $position['ur_here']);
	$categories_pro = get_category_tree_leve_one();
	$smarty->assign('categories_pro', $categories_pro);
	$smarty->assign('helps', get_shop_help());
	$smarty->assign('promotion_info', get_promotion_info());
	$smarty->assign('action', 'form');
	$smarty->assign('use_storage', $_CFG['use_storage']);

	if ($search_type == 0) {
		$smarty->assign('best_goods', get_recommend_goods('best', '', $region_id, $area_info['region_id'], $goods['user_id'], 1));
		$smarty->display('search.dwt');
	}
	else if ($search_type == 1) {
		$smarty->display('merchants_shop_list.dwt');
	}

	exit();
}
else {
	if ($search_type == 0) {
		$ur_here = '搜索商品';
	}
	else if ($search_type == 1) {
		$ur_here = '搜索店铺';
	}

	assign_template();
	assign_dynamic('search');
	$position = assign_ur_here(0, $ur_here . ($_REQUEST['keywords'] ? '_' . $_REQUEST['keywords'] : ''));
	$smarty->assign('page_title', $position['title']);
	$smarty->assign('ur_here', $position['ur_here']);
	$categories_pro = get_category_tree_leve_one();
	$smarty->assign('categories_pro', $categories_pro);
	$smarty->assign('intromode', $intromode);
	$smarty->assign('helps', get_shop_help());
	$smarty->assign('promotion_info', get_promotion_info());
	$smarty->assign('region_id', $region_id);
	$smarty->assign('area_id', $area_id);

	if ($search_type == 0) {
		$keywords = '';
		$tag_where = '';

		if (!empty($_REQUEST['keywords'])) {
			$arr = array();
			$insert_keyword = trim($_REQUEST['keywords']);
			$pin = new pin();
			$pinyin = $pin->Pinyin($insert_keyword, 'UTF8');
			$addtime = local_date('Y-m-d', gmtime());
			$sql = 'INSERT INTO ' . $ecs->table('search_keyword') . '(keyword, pinyin, is_on, count, addtime, pinyin_keyword)VALUES(\'' . $insert_keyword . '\', \'\', \'0\', \'1\', \'' . $addtime . '\', \'' . $pinyin . '\')';
			$db->query($sql);
			$scws_res = scws($_REQUEST['keywords']);
			$arr = explode(',', $scws_res);
			$arr_keyword = $arr;
			$operator = ' AND ';

			if (empty($arr[0])) {
				$arr[0] = $insert_keyword;
			}

			$keywords = 'AND (';
			$goods_ids = array();

			foreach ($arr as $key => $val) {
				if ((0 < $key) && ($key < count($arr)) && (1 < count($arr))) {
					$keywords .= $operator;
				}

				$val = mysql_like_quote(trim($val));
				$sc_dsad = ($_REQUEST['sc_ds'] ? ' OR goods_desc LIKE \'%' . $val . '%\'' : '');
				$keywords .= '(goods_name LIKE \'%' . $val . '%\' OR goods_sn LIKE \'%' . $val . '%\' OR keywords LIKE \'%' . $val . '%\' ' . $sc_dsad . ')';
				$sql = 'SELECT DISTINCT goods_id FROM ' . $ecs->table('tag') . ' WHERE tag_words LIKE \'%' . $val . '%\' ';
				$res = $db->query($sql);

				while ($row = $db->FetchRow($res)) {
					$goods_ids[] = $row['goods_id'];
				}

				$db->autoReplace($ecs->table('keywords'), array('date' => local_date('Y-m-d'), 'searchengine' => 'DSC_B2B2C', 'keyword' => addslashes(str_replace('%', '', $val)), 'count' => 1), array('count' => 1));
			}

			$keywords .= ')';
			$goods_ids = array_unique($goods_ids);
			$tag_where = implode(',', $goods_ids);

			if (!empty($tag_where)) {
				$tag_where = 'OR g.goods_id ' . db_create_in($tag_where);
			}
		}

		$filter_attr_str = (isset($_REQUEST['filter_attr']) ? addslashes(trim($_REQUEST['filter_attr'])) : '0');
		$filter_attr_str = trim(urldecode($filter_attr_str));
		$filter_attr_str = (preg_match('/^[\\d,\\.]+$/', $filter_attr_str) ? $filter_attr_str : '');
		$filter_attr = (empty($filter_attr_str) ? '' : explode('.', $filter_attr_str));

		if (!empty($brand)) {
			$sql = 'SELECT brand_name FROM ' . $GLOBALS['ecs']->table('brand') . ' WHERE brand_id = \'' . $brand . '\' LIMIT 1';
			$brand_name = $db->getOne($sql);
		}
		else {
			$brand_name = '';
		}

		$cat_id = 0;
		$children = get_children($cat_id);
		$leftJoin = '';
		$tag_where = '';

		if ($GLOBALS['_CFG']['open_area_goods'] == 1) {
			$leftJoin .= ' left join ' . $GLOBALS['ecs']->table('link_area_goods') . ' as lag on g.goods_id = lag.goods_id ';
			$tag_where = ' and lag.region_id = \'' . $area_id . '\' ';
		}

		$cat['grade'] = 6;

		if (1 < $cat['grade']) {
			$mm_shop_price = 'wg.warehouse_price, wg.warehouse_promote_price, wag.region_price, wag.region_promote_price, g.model_price, g.model_attr ';
			$leftJoin .= ' left join ' . $GLOBALS['ecs']->table('warehouse_goods') . ' as wg on g.goods_id = wg.goods_id and wg.region_id = \'' . $region_id . '\' ';
			$leftJoin .= ' left join ' . $GLOBALS['ecs']->table('warehouse_area_goods') . ' as wag on g.goods_id = wag.goods_id and wag.region_id = \'' . $area_id . '\' ';
			$price_sel_msb = '(g.brand_id IN(SELECT msb.bid FROM ' . $GLOBALS['ecs']->table('brand') . ' AS b, ' . $GLOBALS['ecs']->table('link_brand') . ' AS lb, ' . $GLOBALS['ecs']->table('merchants_shop_brand') . ' AS msb' . ' WHERE b.is_show = 1 AND b.brand_id = lb.brand_id AND lb.bid = msb.bid AND msb.is_show = 1 AND msb.audit_status = 1) AND g.user_id > 0)';
			$price_sel_brand = '(g.brand_id IN(SELECT b.brand_id FROM ' . $GLOBALS['ecs']->table('brand') . ' AS b' . ' WHERE b.is_show = 1) AND g.user_id = 0)';
			$price_grade_where .= 'AND ( ' . $price_sel_brand . ' OR ' . $price_sel_msb . ')';
			$sql = 'SELECT min(IF(g.model_price < 1, g.shop_price, IF(g.model_price < 2, wg.warehouse_price, wag.region_price))) AS min, ' . ' max(IF(g.model_price < 1, g.shop_price, IF(g.model_price < 2, wg.warehouse_price, wag.region_price))) as max ' . ' FROM ' . $ecs->table('goods') . ' AS g ' . $leftJoin . ' WHERE (' . $children . ' OR ' . get_extension_goods($children) . ') AND g.is_delete = 0 AND g.is_on_sale = 1 AND g.is_alone_sale = 1 ' . $keywords . $tag_where . $price_grade_where;
			$row = $db->getRow($sql);
			$price_grade = 0.0001;

			for ($i = -2; $i <= log10($row['max']); $i++) {
				$price_grade *= 10;
			}

			$dx = ceil(($row['max'] - $row['min']) / $cat['grade'] / $price_grade) * $price_grade;

			if ($dx == 0) {
				$dx = $price_grade;
			}

			for ($i = 1; ($dx * $i) < $row['min']; $i++) {
			}

			for ($j = 1; (($dx * ($i - 1)) + ($price_grade * $j)) < $row['min']; $j++) {
			}

			for ($row['min'] = ($dx * ($i - 1)) + ($price_grade * ($j - 1)); ($dx * $i) <= $row['max']; $i++) {
			}

			$row['max'] = ($dx * $i) + ($price_grade * ($j - 1));
			$sql = 'SELECT (FLOOR((IF(g.model_price < 1, g.shop_price, IF(g.model_price < 2, wg.warehouse_price, wag.region_price)) - ' . $row['min'] . ') / ' . $dx . ')) AS sn, COUNT(*) AS goods_num  ' . ' FROM ' . $ecs->table('goods') . ' AS g ' . $leftJoin . ' WHERE (' . $children . ' OR ' . get_extension_goods($children) . ') AND g.is_delete = 0 AND g.is_on_sale = 1 AND g.is_alone_sale = 1 ' . $keywords . $price_grade_where . ' GROUP BY sn ';
			$price_grade = $db->getAll($sql);

			foreach ($price_grade as $key => $val) {
				if ($val['sn'] != '') {
					$temp_key = $key;
					$price_grade[$temp_key]['goods_num'] = $val['goods_num'];
					$price_grade[$temp_key]['start'] = $row['min'] + round($dx * $val['sn']);
					$price_grade[$temp_key]['end'] = $row['min'] + round($dx * ($val['sn'] + 1));
					$price_grade[$temp_key]['price_range'] = $price_grade[$temp_key]['start'] . '&nbsp;-&nbsp;' . $price_grade[$temp_key]['end'];
					$price_grade[$temp_key]['formated_start'] = price_format($price_grade[$temp_key]['start']);
					$price_grade[$temp_key]['formated_end'] = price_format($price_grade[$temp_key]['end']);
					$price_grade[$temp_key]['url'] = $brands[$temp_key]['url'] = build_uri('search', array('bid' => $brand, 'price_min' => $price_grade[$temp_key]['start'], 'price_max' => $price_grade[$temp_key]['end'], 'filter_attr' => $filter_attr_str), $ur_here);
					if (isset($_REQUEST['price_min']) && ($price_grade[$temp_key]['start'] == $price_min) && ($price_grade[$temp_key]['end'] == $price_max)) {
						$price_grade[$temp_key]['selected'] = 1;
					}
					else {
						$price_grade[$temp_key]['selected'] = 0;
					}
				}
			}

			if (($price_min == 0) && ($price_max == 0)) {
				$smarty->assign('price_grade', $price_grade);
			}
		}

		$where_having = '';
		$brand_select = '';
		$brand_tag_where = '';

		if ($GLOBALS['_CFG']['open_area_goods'] == 1) {
			$brand_select = ' , ( SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('link_area_goods') . ' as lag WHERE lag.goods_id = g.goods_id AND lag.region_id = \'' . $area_id . '\' LIMIT 1) AS area_goods_num ';
			$where_having = ' AND area_goods_num > 0 ';
		}

		if ($GLOBALS['_CFG']['review_goods'] == 1) {
			$brand_tag_where .= ' AND g.review_status > 2 ';
		}

		$sql = 'SELECT b.brand_id, b.brand_name, b.brand_logo, COUNT(*) AS goods_num ' . $brand_select . 'FROM ' . $GLOBALS['ecs']->table('brand') . 'AS b ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' AS g ON g.brand_id = b.brand_id AND g.user_id = 0 AND g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 ' . $brand_tag_where . ' ' . $keywords . ' ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('goods_cat') . ' AS gc ON g.goods_id = gc.goods_id ' . ' WHERE ' . $children . ' OR ' . 'gc.cat_id ' . db_create_in(array_unique(array_merge(array($cat_id), $cat_keys))) . ' AND b.is_show = 1 ' . 'GROUP BY b.brand_id HAVING goods_num > 0 ' . $where_having . ' ORDER BY b.sort_order, b.brand_id ASC';
		$brands_list = $GLOBALS['db']->getAll($sql);
		$sql = 'SELECT b.brand_id, b.brand_name, b.brand_logo, COUNT(*) AS goods_num, g.user_id ' . $brand_select . 'FROM ' . $GLOBALS['ecs']->table('brand') . 'AS b ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('link_brand') . 'AS lb ON lb.brand_id = b.brand_id ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('merchants_shop_brand') . 'AS msb ON msb.bid = lb.bid AND msb.audit_status = 1 AND msb.is_show = 1 ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' AS g ON g.brand_id = msb.bid AND g.user_id > 0 AND g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 ' . $brand_tag_where . ' ' . $keywords . ' ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('goods_cat') . ' AS gc ON g.goods_id = gc.goods_id ' . ' WHERE ' . $children . ' OR ' . 'gc.cat_id ' . db_create_in(array_unique(array_merge(array($cat_id), $cat_keys))) . ' AND b.is_show = 1 ' . 'GROUP BY b.brand_id HAVING goods_num > 0 ' . $where_having . ' ORDER BY b.sort_order, b.brand_id ASC';
		$msb_brands_list = $GLOBALS['db']->getAll($sql);

		if ($msb_brands_list) {
			$new_array = array_merge($brands_list, $msb_brands_list);
			$new = unique_arr($new_array);
			$brands_list = $new;
		}

		$pin = new pin();
		$brands = array();

		foreach ($brands_list as $key => $val) {
			$temp_key = $key;
			$brands[$temp_key]['brand_id'] = $val['brand_id'];
			$brands[$temp_key]['brand_name'] = $val['brand_name'];
			$bdimg_path = 'data/brandlogo/';
			$bd_logo = ($val['brand_logo'] ? $val['brand_logo'] : '');

			if (empty($bd_logo)) {
				$brands[$temp_key]['brand_logo'] = '';
			}
			else {
				$brands[$temp_key]['brand_logo'] = $bdimg_path . $bd_logo;
			}

			$brands[$temp_key]['brand_letters'] = strtoupper(substr($pin->Pinyin($val['brand_name'], 'UTF8'), 0, 1));
			if (($GLOBALS['_CFG']['open_oss'] == 1) && $brands[$temp_key]['brand_logo']) {
				$bucket_info = get_bucket_info();
				$brands[$temp_key]['brand_logo'] = $bucket_info['endpoint'] . $brands[$temp_key]['brand_logo'];
			}

			$brands[$temp_key]['url'] = build_uri('search', array('bid' => $val['brand_id'], 'price_min' => $price_min, 'price_max' => $price_max, 'filter_attr' => $filter_attr_str), $val['brand_name']);
			if (!strpos($brand, ',') && ($brand == $brands_list[$key]['brand_id'])) {
				$brands[$temp_key]['selected'] = 1;
			}

			if (stripos($brand, ',')) {
				$brand2 = explode(',', $brand);

				for ($i = 0; $i < $brand2[$i]; $i++) {
					if ($brand2[$i] == $brands_list[$key]['brand_id']) {
						$brands[$temp_key]['selected'] = 1;
					}
				}
			}
		}

		$ubrand = (isset($_REQUEST['ubrand']) ? intval($_REQUEST['ubrand']) : 0);
		$smarty->assign('ubrand', $ubrand);
		$letter = range('A', 'Z');
		$smarty->assign('letter', $letter);

		if ($brands) {
			$smarty->assign('brands', $brands);
		}

		$get_bd = array();
		$bd = '';

		foreach ($brands as $key => $value) {
			if ($value['selected'] == 1) {
				$bd .= $value['brand_name'] . ',';
				$get_bd[$key]['brand_id'] = $value['brand_id'];
				$brand_id = 'brand=' . $get_bd[$key]['brand_id'];

				if (stripos($value['url'], $brand_id)) {
					$get_bd[$key]['url'] = str_replace($brand_id, 'brand=0', $value['url']);
				}

				$br_url = $get_bd[$key]['url'];
			}
		}

		$get_brand['br_url'] = $br_url;
		$get_brand['bd'] = substr($bd, 0, -1);
		$smarty->assign('get_bd', $get_brand);
		$g_price = array();

		for ($i = 0; $i < count($price_grade); $i++) {
			if ($price_grade[$i]['selected'] == 1) {
				$g_price[$i]['price_range'] = $price_grade[$i]['price_range'];
				$g_price[$i]['url'] = $price_grade[$i]['url'];
				$p_url = $g_price[$i]['url'];
				$p_a = $get_price_min;
				$p_b = $get_price_max;
				if (stripos($p_url, $p_a) && stripos($p_url, $p_b)) {
					if ($p_b < $p_a) {
						$price = array($p_a, $p_b);
						$p_a = $price[1];
						$p_b = $price[0];
					}

					if ((0 < $p_a) && (0 < $p_b)) {
						$g_price[$i]['url'] = str_replace($p_b, 0, str_replace($p_a, 0, $p_url));
					}
					else {
						if (($p_a == 0) && (0 < $p_b)) {
							$g_price[$i]['url'] = str_replace($p_b, 0, $p_url);
						}
					}
				}

				break;
			}
		}

		if (empty($g_price) && ((0 < $price_min) || (0 < $price_max))) {
			if ($price_max < $price_min) {
				$price = array($price_min, $price_max);
				$price_min = $price[1];
				$price_max = $price[0];
			}

			$parray = array();
			$parray['purl'] = build_uri('search', array('bid' => $brand, 'price_min' => 0, 'price_max' => 0, 'filter_attr' => $filter_attr_str), $ur_here);
			$parray['min_max'] = $price_min . ' - ' . $price_max;
			$smarty->assign('parray', $parray);
		}

		$smarty->assign('g_price', $g_price);
		$ext = '';

		if ($self == 1) {
			$ext .= ' AND g.user_id = 0 ';
		}

		if ($ship == 1) {
			$ext .= ' AND g.is_shipping = 1 ';
		}

		$search_filter_attr = '';

		if (0 < $search_filter_attr) {
			$cat_filter_attr = explode(',', $search_filter_attr);
			$all_attr_list = array();
			$attributeInfo = array();

			foreach ($cat_filter_attr as $key => $value) {
				$sql = 'SELECT a.attr_name, attr_cat_type FROM ' . $ecs->table('attribute') . ' AS a, ' . $ecs->table('goods_attr') . ' AS ga left join  ' . $ecs->table('goods') . ' AS g on g.goods_id = ga.goods_id ' . $leftJoin . ' WHERE (' . $children . ' OR ' . get_extension_goods($children) . ') AND a.attr_id = ga.attr_id AND g.is_delete = 0 AND g.is_on_sale = 1 AND g.is_alone_sale = 1 AND a.attr_id=\'' . $value . '\'' . $tag_where;
				$attributeInfo = $db->getRow($sql);

				if ($attributeInfo) {
					$all_attr_list[$key]['filter_attr_name'] = $attributeInfo['attr_name'];
					$all_attr_list[$key]['attr_cat_type'] = $attributeInfo['attr_cat_type'];
					$all_attr_list[$key]['filter_attr_id'] = $value;
					$sql = 'SELECT a.attr_id, MIN(a.goods_attr_id ) AS goods_id, a.attr_value AS attr_value, a.color_value FROM ' . $ecs->table('goods_attr') . ' AS a, ' . $ecs->table('goods') . ' AS g' . ' WHERE (' . $children . ' OR ' . get_extension_goods($children) . ') AND g.goods_id = a.goods_id AND g.is_delete = 0 AND g.is_on_sale = 1 AND g.is_alone_sale = 1 ' . ' AND a.attr_id=\'' . $value . '\' ' . ' GROUP BY a.attr_value';
					$attr_list = $db->getAll($sql);
					$temp_arrt_url_arr = array();

					for ($i = 0; $i < count($cat_filter_attr); $i++) {
						$temp_arrt_url_arr[$i] = !empty($filter_attr[$i]) ? $filter_attr[$i] : 0;
					}

					foreach ($attr_list as $k => $v) {
						$temp_key = $k;
						$temp_arrt_url_arr[$key] = $v['goods_id'];
						$temp_arrt_url = implode('.', $temp_arrt_url_arr);

						if (!empty($v['color_value'])) {
							$arr_color2['c_value'] = $v['attr_value'];
							$arr_color2['c_url'] = '#' . $v['color_value'];
							$v['attr_value'] = $arr_color2;
						}

						$all_attr_list[$key]['attr_list'][$temp_key]['attr_value'] = $v['attr_value'];
						$all_attr_list[$key]['attr_list'][$temp_key]['goods_id'] = $v['goods_id'];
						$all_attr_list[$key]['attr_list'][$temp_key]['url'] = build_uri('category', array('cid' => $cat_id, 'bid' => $brand, 'price_min' => $price_min, 'price_max' => $price_max, 'filter_attr' => $temp_arrt_url), $cat['cat_name']);

						if (!empty($filter_attr[$key])) {
							if (!stripos($filter_attr[$key], ',') && ($filter_attr[$key] == $v['goods_id'])) {
								$all_attr_list[$key]['attr_list'][$temp_key]['selected'] = 1;
							}

							if (stripos($filter_attr[$key], ',')) {
								$color_arr = explode(',', $filter_attr[$key]);

								for ($i = 0; $i < count($color_arr); $i++) {
									if ($color_arr[$i] == $v['goods_id']) {
										$all_attr_list[$key]['attr_list'][$temp_key]['selected'] = 1;
									}
								}
							}
						}
					}
				}
			}

			$color_list = array();

			for ($i = 0; $i < (count($all_attr_list) + 1); $i++) {
				if ($all_attr_list[$i]['attr_cat_type'] == 1) {
					for ($k = 0; $k < count($all_attr_list[$i]['attr_list']); $k++) {
						$array_color = $all_attr_list[$i]['attr_list'];

						if (count($array_color[$k]['attr_value']) == 1) {
							$array['c_value'] = $array_color[$k]['attr_value'];
							$array['c_url'] = '#FFFFFF';
							$all_attr_list[$i]['attr_list'][$k]['attr_value'] = $array;
						}
					}

					$color_list = $all_attr_list[$i];
					unset($all_attr_list[$i]);
				}
			}

			$c_array = array();
			$k = '';

			for ($i = 0; $i < count($color_list['attr_list']); $i++) {
				if ($color_list['attr_list'][$i]['selected'] == 1) {
					$c_array[$i]['filter_attr_name'] = $color_list['filter_attr_name'];
					$c_array[$i]['attr_list']['attr_value'] = $color_list['attr_list'][$i]['attr_value']['c_value'];
					$c_array[$i]['attr_list']['goods_id'] = $color_list['attr_list'][$i]['goods_id'];
					$color_id = $c_array[$i]['attr_list']['goods_id'];
					$k .= $c_array[$i]['attr_list']['attr_value'] . ',';
					$color_url = $color_list['attr_list'][$i]['url'];

					if (strpos($color_url, $color_id)) {
						$c_array[$i]['attr_list']['url'] = str_replace($color_id, 0, $color_url);
					}

					$c_url = $c_array[$i]['attr_list']['url'];
				}
			}

			$c_array = array();
			$c_array['filter_attr_name'] = $color_list['filter_attr_name'];
			$c_array['attr_value'] = substr($k, 0, -1);
			$c_array['url'] = $c_url;
			$g_array = array();

			for ($i = 0; $i < (count($all_attr_list) + 3); $i++) {
				$k = '';

				for ($j = 0; $j < count($all_attr_list[$i]['attr_list']); $j++) {
					if ($all_attr_list[$i]['attr_list'][$j]['selected'] == 1) {
						$g_array[$i]['filter_attr_name'] = $all_attr_list[$i]['filter_attr_name'];
						$g_array[$i]['attr_list']['value'] = $all_attr_list[$i]['attr_list'][$j]['attr_value'];
						$g_array[$i]['attr_list']['goods_id'] = $all_attr_list[$i]['attr_list'][$j]['goods_id'];
						$g_url = $all_attr_list[$i]['attr_list'][$j]['url'];
						$sid = $g_array[$i]['attr_list']['goods_id'];

						if (strpos($g_url, $sid)) {
							$g_array[$i]['attr_list']['url'] = str_replace($sid, 0, $g_url);
						}

						$k .= $all_attr_list[$i]['attr_list'][$j]['attr_value'] . ',';
						$g_array[$i]['g_name'] = substr($k, 0, -1);
						$g_array[$i]['g_url'] = $g_array[$i]['attr_list']['url'];
					}
				}
			}

			$smarty->assign('c_array', $c_array);
			$smarty->assign('g_array', $g_array);
			$smarty->assign('color_search', $color_list);
			$smarty->assign('filter_attr_list', $all_attr_list);

			if (!empty($filter_attr)) {
				$ext_sql = 'SELECT DISTINCT(b.goods_id) FROM ' . $ecs->table('goods_attr') . ' AS a, ' . $ecs->table('goods_attr') . ' AS b ' . 'WHERE ';
				$ext_group_goods = array();

				foreach ($filter_attr as $k => $v) {
					if (is_numeric($v) && ($v != 0) && isset($cat_filter_attr[$k])) {
						$sql = $ext_sql . 'b.attr_value = a.attr_value AND b.attr_id = ' . $cat_filter_attr[$k] . ' AND a.goods_attr_id  in (' . $v . ')';
						$ext_group_goods = $db->getColCached($sql);
						$ext .= ' AND ' . db_create_in($ext_group_goods, 'g.goods_id');
					}
				}
			}
		}

		$action = '';
		if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'form')) {
			$adv_value['keywords'] = htmlspecialchars(stripcslashes($_REQUEST['keywords']));
			$adv_value['brand'] = $_REQUEST['brand'];
			$adv_value['price_min'] = $_REQUEST['price_min'];
			$adv_value['price_max'] = $_REQUEST['price_max'];
			$adv_value['category'] = $_REQUEST['category'];
			$attributes = get_seachable_attributes($_REQUEST['goods_type']);

			foreach ($attributes['attr'] as $key => $val) {
				if (!empty($_REQUEST['attr'][$val['id']])) {
					if ($val['type'] == 2) {
						$attributes['attr'][$key]['value']['from'] = !empty($_REQUEST['attr'][$val['id']]['from']) ? htmlspecialchars(stripcslashes(trim($_REQUEST['attr'][$val['id']]['from']))) : '';
						$attributes['attr'][$key]['value']['to'] = !empty($_REQUEST['attr'][$val['id']]['to']) ? htmlspecialchars(stripcslashes(trim($_REQUEST['attr'][$val['id']]['to']))) : '';
					}
					else {
						$attributes['attr'][$key]['value'] = !empty($_REQUEST['attr'][$val['id']]) ? htmlspecialchars(stripcslashes(trim($_REQUEST['attr'][$val['id']]))) : '';
					}
				}
			}

			if ($_REQUEST['sc_ds']) {
				$smarty->assign('scck', 'checked');
			}

			$smarty->assign('adv_val', $adv_value);
			$smarty->assign('goods_type_list', $attributes['cate']);
			$smarty->assign('goods_attributes', $attributes['attr']);
			$smarty->assign('goods_type_selected', $_REQUEST['goods_type']);
			$smarty->assign('action', 'form');
			$smarty->assign('use_storage', $_CFG['use_storage']);
			$action = 'form';
		}

		$category = (!empty($_REQUEST['category']) ? intval($_REQUEST['category']) : 0);
		$categories = '';

		if (!empty($category)) {
			$children = get_children($category);
			$categories = ' AND ' . $children;
		}

		if (0 < $brand) {
			$sel_where_brand = 'AND b.brand_id IN(' . $brand . ')';
		}
		else {
			$sel_where_brand = '';
		}

		$sel_msb = '(g.brand_id IN(SELECT msb.bid FROM ' . $GLOBALS['ecs']->table('brand') . ' AS b, ' . $GLOBALS['ecs']->table('link_brand') . ' AS lb, ' . $GLOBALS['ecs']->table('merchants_shop_brand') . ' AS msb' . ' WHERE b.is_show = 1 AND b.brand_id = lb.brand_id AND lb.bid = msb.bid AND msb.is_show = 1 AND msb.audit_status = 1 ' . $sel_where_brand . ') AND g.user_id > 0)';
		$sel_brand = '(g.brand_id IN(SELECT b.brand_id FROM ' . $GLOBALS['ecs']->table('brand') . ' AS b' . ' WHERE b.is_show = 1 ' . $sel_where_brand . ') AND g.user_id = 0)';
		$where_brand = 'AND ( ' . $sel_brand . ' OR ' . $sel_msb . ')';
		$shop_price = 'wg.warehouse_price, wg.warehouse_promote_price, wag.region_price, wag.region_promote_price, g.model_price, g.model_attr, ';
		$leftJoin .= ' left join ' . $GLOBALS['ecs']->table('warehouse_goods') . ' as wg on g.goods_id = wg.goods_id and wg.region_id = \'' . $warehouse_id . '\' ';
		$leftJoin .= ' left join ' . $GLOBALS['ecs']->table('warehouse_area_goods') . ' as wag on g.goods_id = wag.goods_id and wag.region_id = \'' . $area_id . '\' ';

		if ($GLOBALS['_CFG']['open_area_goods'] == 1) {
			$leftJoin .= ' left join ' . $GLOBALS['ecs']->table('link_area_goods') . ' as lag on g.goods_id = lag.goods_id ';
			$where_brand .= ' and lag.region_id = \'' . $area_id . '\' ';
		}

		$outstock = (!empty($_REQUEST['outstock']) ? ' AND g.goods_number > 0 ' : '');
		$where_price_min = ($_REQUEST['price_min'] != 0 ? ' AND g.shop_price >= \'' . $_REQUEST['price_min'] . '\'' : '');
		$where_price_max = (($_REQUEST['price_max'] != 0) || ($_REQUEST['price_min'] < 0) ? ' AND g.shop_price <= \'' . $_REQUEST['price_max'] . '\'' : '');
		$intromode = '';

		if (!empty($_REQUEST['intro'])) {
			switch ($_REQUEST['intro']) {
			case 'best':
				$intro = ' AND g.is_best = 1';
				$intromode = 'best';
				$ur_here = $_LANG['best_goods'];
				break;

			case 'new':
				$intro = ' AND g.is_new = 1';
				$intromode = 'new';
				$ur_here = $_LANG['new_goods'];
				break;

			case 'hot':
				$intro = ' AND g.is_hot = 1';
				$intromode = 'hot';
				$ur_here = $_LANG['hot_goods'];
				break;

			case 'promotion':
				$time = gmtime();
				$intro = ' AND g.promote_price > 0 AND g.promote_start_date <= \'' . $time . '\' AND g.promote_end_date >= \'' . $time . '\'';
				$intromode = 'promotion';
				$ur_here = $_LANG['promotion_goods'];
				break;

			default:
				$intro = '';
			}
		}
		else {
			$intro = '';
		}

		if (empty($ur_here)) {
			$ur_here = $_LANG['search_goods'];
		}

		$attr_in = '';
		$attr_num = 0;
		$attr_url = '';
		$attr_arg = array();

		if (!empty($_REQUEST['attr'])) {
			$sql = 'SELECT goods_id, COUNT(*) AS num FROM ' . $ecs->table('goods_attr') . ' WHERE 0 ';

			foreach ($_REQUEST['attr'] as $key => $val) {
				if (is_not_null($val) && is_numeric($key)) {
					$attr_num++;
					$sql .= ' OR (1 ';

					if (is_array($val)) {
						$sql .= ' AND attr_id = \'' . $key . '\'';

						if (!empty($val['from'])) {
							$sql .= (is_numeric($val['from']) ? ' AND attr_value >= ' . floatval($val['from']) : ' AND attr_value >= \'' . $val['from'] . '\'');
							$attr_arg['attr[' . $key . '][from]'] = $val['from'];
							$attr_url .= '&amp;attr[' . $key . '][from]=' . $val['from'];
						}

						if (!empty($val['to'])) {
							$sql .= (is_numeric($val['to']) ? ' AND attr_value <= ' . floatval($val['to']) : ' AND attr_value <= \'' . $val['to'] . '\'');
							$attr_arg['attr[' . $key . '][to]'] = $val['to'];
							$attr_url .= '&amp;attr[' . $key . '][to]=' . $val['to'];
						}
					}
					else {
						$sql .= (isset($_REQUEST['pickout']) ? ' AND attr_id = \'' . $key . '\' AND attr_value = \'' . $val . '\' ' : ' AND attr_id = \'' . $key . '\' AND attr_value LIKE \'%' . mysql_like_quote($val) . '%\' ');
						$attr_url .= '&amp;attr[' . $key . ']=' . $val;
						$attr_arg['attr[' . $key . ']'] = $val;
					}

					$sql .= ')';
				}
			}

			if (0 < $attr_num) {
				$sql .= ' GROUP BY goods_id HAVING num = \'' . $attr_num . '\'';
				$row = $db->getCol($sql);

				if (count($row)) {
					$attr_in = ' AND ' . db_create_in($row, 'g.goods_id');
				}
				else {
					$attr_in = ' AND 0 ';
				}
			}
		}
		else if (isset($_REQUEST['pickout'])) {
			$sql = 'SELECT DISTINCT(goods_id) FROM ' . $ecs->table('goods_attr');
			$col = $db->getCol($sql);

			if (!empty($col)) {
				$attr_in = ' AND ' . db_create_in($col, 'g.goods_id');
			}
		}

		$leftJoin = '';
		$leftJoin .= 'LEFT JOIN ' . $GLOBALS['ecs']->table('brand') . ' AS b ' . 'ON b.brand_id = g.brand_id ';
		$leftJoin .= 'LEFT JOIN ' . $GLOBALS['ecs']->table('link_brand') . ' AS lb ' . 'ON lb.bid = g.brand_id ';
		$leftJoin .= 'LEFT JOIN ' . $GLOBALS['ecs']->table('merchants_shop_brand') . ' AS msb ' . 'ON msb.bid = lb.bid ';
		$tag_where .= 'AND (b.audit_status = 1 OR msb.audit_status = 1) ';
		$shop_price = 'wg.warehouse_price, wg.warehouse_promote_price, wag.region_price, wag.region_promote_price, g.model_price, g.model_attr, ';
		$leftJoin .= ' left join ' . $GLOBALS['ecs']->table('warehouse_goods') . ' as wg on g.goods_id = wg.goods_id and wg.region_id = \'' . $region_id . '\' ';
		$leftJoin .= ' left join ' . $GLOBALS['ecs']->table('warehouse_area_goods') . ' as wag on g.goods_id = wag.goods_id and wag.region_id = \'' . $area_id . '\' ';
		$area_where = '';

		if ($GLOBALS['_CFG']['open_area_goods'] == 1) {
			$leftJoin .= ' left join ' . $GLOBALS['ecs']->table('link_area_goods') . ' as lag on g.goods_id = lag.goods_id ';
			$area_where = ' and lag.region_id = \'' . $area_id . '\' ';
		}

		if ($GLOBALS['_CFG']['review_goods'] == 1) {
			$tag_where .= ' AND g.review_status > 2 ';
		}

		if ($is_ship == 'is_shipping') {
			$tag_where .= ' AND g.is_shipping = 1 ';
		}

		if ($is_self == 1) {
			$tag_where .= ' AND g.user_id = 0 ';
		}

		if ($cou_id = $_REQUEST['cou_id']) {
			$cou_data = $db->getRow('SELECT * FROM ' . $ecs->table('coupons') . ' WHERE cou_id = \'' . $cou_id . '\'');

			if ($cou_data['cou_type'] == 2) {
				if ($cou_data['cou_ok_goods'] != 0) {
					$cou_goods_where = ' AND g.goods_id IN (' . $cou_data['cou_ok_goods'] . ') AND g.user_id =\'' . $cou_data['ru_id'] . '\' ';
				}
				else {
					$cou_goods_where = ' AND g.user_id =\'' . $cou_data['ru_id'] . '\' ';
				}
			}
			else if ($cou_data['cou_goods'] != 0) {
				$cou_goods_where = ' AND g.goods_id IN (' . $cou_data['cou_goods'] . ') AND g.user_id =\'' . $cou_data['ru_id'] . '\' ';
			}
			else {
				$cou_goods_where = ' AND g.user_id =\'' . $cou_data['ru_id'] . '\' ';
			}

			$smarty->assign('cou_id', $cou_id);
			$cou_page_data = '&cou_id=' . $cou_id;
		}

		$sel_msb = '(g.brand_id IN(SELECT msb.bid FROM ' . $GLOBALS['ecs']->table('brand') . ' AS b, ' . $GLOBALS['ecs']->table('link_brand') . ' AS lb, ' . $GLOBALS['ecs']->table('merchants_shop_brand') . ' AS msb' . ' WHERE b.is_show = 1 AND b.brand_id = lb.brand_id AND lb.bid = msb.bid AND msb.is_show = 1 AND msb.audit_status = 1) AND g.user_id > 0)';
		$sel_brand = '(g.brand_id IN(SELECT b.brand_id FROM ' . $GLOBALS['ecs']->table('brand') . ' AS b' . ' WHERE b.is_show = 1) AND g.user_id = 0)';
		$tag_where .= 'AND ( ' . $sel_brand . ' OR ' . $sel_msb . ')';
		$sql = 'SELECT COUNT(*) FROM ' . $ecs->table('goods') . ' AS g ' . $leftJoin . 'WHERE g.is_delete = 0 AND g.is_on_sale = 1 ' . $area_where . ' AND g.is_alone_sale = 1 ' . $attr_in . ' ' . 'AND (( 1 ' . $categories . $keywords . $where_brand . $where_price_min . $where_price_max . $intro . $outstock . ' ) ' . $tag_where . ' ) ' . $cou_goods_where . ' ';
		$count = $db->getOne($sql);
		$max_page = (0 < $count ? ceil($count / $size) : 1);

		if ($max_page < $page) {
			$page = $max_page;
		}

		$sql = 'SELECT g.goods_id, g.user_id, g.goods_name, g.market_price, g.is_new, g.comments_number, g.sales_volume, g.is_best, g.is_hot,g.store_new, g.store_best, g.store_hot, ' . $shop_price . 'IF(g.model_price < 1, g.shop_price, IF(g.model_price < 2, wg.warehouse_price, wag.region_price)) AS org_price, ' . 'IFNULL(mp.user_price, IF(g.model_price < 1, g.shop_price, IF(g.model_price < 2, wg.warehouse_price, wag.region_price)) * \'' . $_SESSION['discount'] . '\') AS shop_price, ' . 'IF(g.model_price < 1, g.promote_price, IF(g.model_price < 2, wg.warehouse_promote_price, wag.region_promote_price)) as promote_price, ' . ' IF(g.model_price < 1, g.goods_number, IF(g.model_price < 2, wg.region_number, wag.region_number)) AS goods_number, ' . 'g.promote_start_date, g.promote_end_date, g.is_promote, g.goods_thumb, g.goods_img, g.goods_brief, g.goods_type ' . 'FROM ' . $ecs->table('goods') . ' AS g ' . $leftJoin . 'LEFT JOIN ' . $GLOBALS['ecs']->table('member_price') . ' AS mp ' . 'ON mp.goods_id = g.goods_id AND mp.user_rank = \'' . $_SESSION['user_rank'] . '\' ' . 'WHERE g.is_delete = 0 AND g.is_on_sale = 1 AND g.is_alone_sale = 1 ' . $attr_in . ' ' . $cou_goods_where . ' ' . 'AND (( 1 ' . $categories . $keywords . $where_brand . $where_price_min . $where_price_max . $intro . $outstock . ' ) ' . $tag_where . ' ) ' . 'ORDER BY g.' . $sort . ' ' . $order;
		if (isset($_REQUEST['act']) && ($_REQUEST['act'] == 'load_more_goods')) {
			$start = intval($_REQUEST['goods_num']);
		}
		else {
			$start = ($page - 1) * $size;
		}

		$res = $db->SelectLimit($sql, $size, $start);
		$region = array(1, $province_id, $city_id, $district_id);
		$arr = array();

		while ($row = $db->FetchRow($res)) {
			if (0 < $row['promote_price']) {
				$promote_price = bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
			}
			else {
				$promote_price = 0;
			}

			$price_other = array('market_price' => $row['market_price'], 'org_price' => $row['org_price'], 'shop_price' => $row['shop_price'], 'promote_price' => $promote_price);
			$price_info = get_goods_one_attr_price($row['goods_id'], $warehouse_id, $area_id, $price_other);
			$row = (!empty($row) ? array_merge($row, $price_info) : $row);
			$promote_price = $row['promote_price'];
			$watermark_img = '';

			if ($promote_price != 0) {
				$watermark_img = 'watermark_promote_small';
			}
			else if ($row['is_new'] != 0) {
				$watermark_img = 'watermark_new_small';
			}
			else if ($row['is_best'] != 0) {
				$watermark_img = 'watermark_best_small';
			}
			else if ($row['is_hot'] != 0) {
				$watermark_img = 'watermark_hot_small';
			}

			if ($watermark_img != '') {
				$arr[$row['goods_id']]['watermark_img'] = $watermark_img;
			}

			$arr[$row['goods_id']]['goods_id'] = $row['goods_id'];

			if ($row['model_attr'] == 1) {
				$table_products = 'products_warehouse';
				$type_files = ' and warehouse_id = \'' . $warehouse_id . '\'';
			}
			else if ($row['model_attr'] == 2) {
				$table_products = 'products_area';
				$type_files = ' and area_id = \'' . $area_id . '\'';
			}
			else {
				$table_products = 'products';
				$type_files = '';
			}

			$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table($table_products) . ' WHERE goods_id = \'' . $row['goods_id'] . '\'' . $type_files . ' LIMIT 0, 1';
			$arr[$row['goods_id']]['prod'] = $GLOBALS['db']->getRow($sql);

			if (empty($prod)) {
				$arr[$row['goods_id']]['prod'] = 1;
			}
			else {
				$arr[$row['goods_id']]['prod'] = 0;
			}

			if ($display == 'grid') {
				$goods_name_keyword = sub_str($row['goods_name'], $GLOBALS['_CFG']['goods_name_length']);
				$goods_name_keyword = '<text>' . $goods_name . '</text>';

				foreach ($arr_keyword as $key => $val_keyword) {
					$goods_name_keyword = preg_replace('/(>.*)(' . $val_keyword . ')(.*<)/Ui', '$1<font style=\'color:#ec5151;\'>' . $val_keyword . '</font>$3', $goods_name);
				}

				$arr[$row['goods_id']]['goods_name_keyword'] = 0 < $GLOBALS['_CFG']['goods_name_length'] ? $goods_name_keyword : $goods_name_keyword;
				$arr[$row['goods_id']]['goods_name'] = 0 < $GLOBALS['_CFG']['goods_name_length'] ? $row['goods_name'] : $row['goods_name'];
			}
			else {
				$goods_name_keyword = '<text>' . $row['goods_name'] . '</text>';

				foreach ($arr_keyword as $key => $val_keyword) {
					$goods_name_keyword = preg_replace('/(>.*)(' . $val_keyword . ')(.*<)/Ui', '$1<font style=\'color:#ec5151;\'>' . $val_keyword . '</font>$3', $goods_name_keyword);
				}

				$arr[$row['goods_id']]['goods_name_keyword'] = $goods_name_keyword;
				$arr[$row['goods_id']]['goods_name'] = $row['goods_name'];
			}

			$arr[$row['goods_id']]['goods_number'] = $row['goods_number'];

			if (0 < $row['market_price']) {
				$discount_arr = get_discount($row['goods_id']);
			}

			$arr[$row['goods_id']]['zhekou'] = $discount_arr['discount'];
			$arr[$row['goods_id']]['jiesheng'] = $discount_arr['jiesheng'];
			$arr[$row['goods_id']]['type'] = $row['goods_type'];
			$arr[$row['goods_id']]['is_promote'] = $row['is_promote'];
			$arr[$row['goods_id']]['sales_volume'] = $row['sales_volume'];
			$arr[$row['goods_id']]['market_price'] = price_format($row['market_price']);
			$arr[$row['goods_id']]['shop_price'] = price_format($row['shop_price']);
			$arr[$row['goods_id']]['promote_price'] = 0 < $promote_price ? price_format($promote_price) : '';
			$arr[$row['goods_id']]['goods_brief'] = $row['goods_brief'];
			$arr[$row['goods_id']]['goods_thumb'] = get_image_path($row['goods_id'], $row['goods_thumb'], true);
			$arr[$row['goods_id']]['goods_img'] = get_image_path($row['goods_id'], $row['goods_img']);
			$arr[$row['goods_id']]['url'] = build_uri('goods', array('gid' => $row['goods_id']), $row['goods_name']);
			$arr[$row['goods_id']]['count'] = selled_count($row['goods_id']);
			$mc_all = ments_count_all($row['goods_id']);
			$mc_one = ments_count_rank_num($row['goods_id'], 1);
			$mc_two = ments_count_rank_num($row['goods_id'], 2);
			$mc_three = ments_count_rank_num($row['goods_id'], 3);
			$mc_four = ments_count_rank_num($row['goods_id'], 4);
			$mc_five = ments_count_rank_num($row['goods_id'], 5);
			$arr[$row['goods_id']]['zconments'] = get_conments_stars($mc_all, $mc_one, $mc_two, $mc_three, $mc_four, $mc_five);
			$arr[$row['goods_id']]['review_count'] = $arr[$row['goods_id']]['zconments']['allmen'];
			$arr[$row['goods_id']]['rz_shopName'] = get_shop_name($row['user_id'], 1);
			$arr[$row['goods_id']]['pictures'] = get_goods_gallery($row['goods_id'], 6);
			$shop_information = get_shop_name($row['user_id']);
			$arr[$row['goods_id']]['is_IM'] = $shop_information['is_IM'];

			if ($row['user_id'] == 0) {
				if ($GLOBALS['db']->getOne('SELECT kf_im_switch FROM ' . $GLOBALS['ecs']->table('seller_shopinfo') . 'WHERE ru_id = 0', true)) {
					$arr[$row['goods_id']]['is_dsc'] = true;
				}
				else {
					$arr[$row['goods_id']]['is_dsc'] = false;
				}
			}
			else {
				$arr[$row['goods_id']]['is_dsc'] = false;
			}

			$build_uri = array('urid' => $row['user_id'], 'append' => $arr[$row['goods_id']]['rz_shopName']);
			$domain_url = get_seller_domain_url($row['user_id'], $build_uri);
			$arr[$row['goods_id']]['store_url'] = $domain_url['domain_name'];
			$arr[$row['goods_id']]['is_new'] = $row['is_new'];
			$arr[$row['goods_id']]['is_best'] = $row['is_best'];
			$arr[$row['goods_id']]['is_hot'] = $row['is_hot'];
			$sql = 'select * from ' . $GLOBALS['ecs']->table('seller_shopinfo') . ' where ru_id=\'' . $row['user_id'] . '\'';
			$basic_info = $GLOBALS['db']->getRow($sql);
			$arr[$row['goods_id']]['kf_type'] = $basic_info['kf_type'];

			if ($basic_info['kf_qq']) {
				$kf_qq = array_filter(preg_split('/\\s+/', $basic_info['kf_qq']));
				$kf_qq = explode('|', $kf_qq[0]);

				if (!empty($kf_qq[1])) {
					$arr[$row['goods_id']]['kf_qq'] = $kf_qq[1];
				}
				else {
					$arr[$row['goods_id']]['kf_qq'] = '';
				}
			}
			else {
				$arr[$row['goods_id']]['kf_qq'] = '';
			}

			if ($basic_info['kf_ww']) {
				$kf_ww = array_filter(preg_split('/\\s+/', $basic_info['kf_ww']));
				$kf_ww = explode('|', $kf_ww[0]);

				if (!empty($kf_ww[1])) {
					$arr[$row['goods_id']]['kf_ww'] = $kf_ww[1];
				}
				else {
					$arr[$row['goods_id']]['kf_ww'] = '';
				}
			}
			else {
				$arr[$row['goods_id']]['kf_ww'] = '';
			}

			$shippingFee = goodsshippingfee($row['goods_id'], $region_id, $region);
			$arr[$row['goods_id']]['shipping_fee_formated'] = $shippingFee['shipping_fee_formated'];
			$arr[$row['goods_id']]['is_collect'] = get_collect_user_goods($row['goods_id']);
		}

		if ($display == 'grid') {
			if ((count($arr) % 2) != 0) {
				$arr[] = array();
			}
		}

		$smarty->assign('goods_list', $arr);
		$smarty->assign('category', $category);
		$smarty->assign('keywords', htmlspecialchars(stripslashes($_REQUEST['keywords'])));
		$smarty->assign('brand', $_REQUEST['brand']);
		$smarty->assign('price_min', $price_min);
		$smarty->assign('price_max', $price_max);
		$smarty->assign('outstock', $_REQUEST['outstock']);
		if (isset($_REQUEST['act']) && ($_REQUEST['act'] == 'load_more_goods')) {
			$smarty->assign('model', intval($_REQUEST['model']));
			$result = array('error' => 0, 'message' => '', 'cat_goods' => '', 'best_goods' => '');
			$result['cat_goods'] = html_entity_decode($smarty->fetch('library/more_goods.lbi'));
			$result['best_goods'] = html_entity_decode($smarty->fetch('library/more_goods_best.lbi'));
			exit(json_encode($result));
		}

		$url_format = 'search.php?category=' . $category . '&amp;keywords=' . urlencode(stripslashes($_REQUEST['keywords'])) . '&amp;brand=' . $_REQUEST['brand'] . '&amp;action=' . $action . '&amp;goods_type=' . $_REQUEST['goods_type'] . '&amp;sc_ds=' . $_REQUEST['sc_ds'] . $cou_page_data;

		if (!empty($intromode)) {
			$url_format .= '&amp;intro=' . $intromode;
		}

		if (isset($_REQUEST['pickout'])) {
			$url_format .= '&amp;pickout=1';
		}

		$url_format .= '&amp;price_min=' . $_REQUEST['price_min'] . '&amp;price_max=' . $_REQUEST['price_max'] . '&amp;sort=' . $sort;
		$url_format .= $attr_url . '&amp;order=' . $order . '&amp;page=';
		$pager['search'] = array('keywords' => stripslashes(trim($_REQUEST['keywords'])), 'category' => $category, 'store_search_cmt' => intval($_REQUEST['store_search_cmt']), 'brand' => $_REQUEST['brand'], 'sort' => $sort, 'order' => $order, 'price_min' => $_REQUEST['price_min'], 'price_max' => $_REQUEST['price_max'], 'action' => $action, 'intro' => empty($intromode) ? '' : trim($intromode), 'goods_type' => $_REQUEST['goods_type'], 'sc_ds' => $_REQUEST['sc_ds'], 'outstock' => $_REQUEST['outstock'], 'is_ship' => $is_ship, 'self_support' => $is_self, 'is_in_stock' => $is_in_stock, 'cou_id' => $cou_id);
		$pager['search'] = array_merge($pager['search'], $attr_arg);
		$pager = get_pager('search.php', $pager['search'], $count, $page, $size);
		$pager['display'] = $display;
		$smarty->assign('url_format', $url_format);
		$smarty->assign('pager', $pager);

		for ($i = 1; $i <= $_CFG['auction_ad']; $i++) {
			$search_left_ad .= '\'search_left_ad' . $i . ',';
			$search_right_ad .= '\'search_right_ad' . $i . ',';
		}

		$smarty->assign('search_left_ad', $search_left_ad);
		$smarty->assign('search_right_ad', $search_right_ad);
		$smarty->assign('best_goods', get_recommend_goods('best', '', $region_id, $area_info['region_id'], $goods['user_id'], 1));
		$cur_url = get_return_self_url();
		$smarty->assign('cur_url', $cur_url);
		$smarty->assign('script_name', 'search');
		$smarty->display('search.dwt');
	}
	else if ($search_type == 1) {
		$keywords = htmlspecialchars(stripcslashes($_REQUEST['keywords']));

		if ($display == 'list') {
			$size = 10;
			$count = get_store_shop_count($keywords, $sort);
			$store_shop_list = get_store_shop_list(1, $keywords, $count, $size, $page, $sort, $order, $region_id, $area_id);
			$smarty->assign('store_shop_list', $store_shop_list['shop_list']);
			$smarty->assign('pager', $store_shop_list['pager']);
		}
		else {
			if (($display == 'grid') || ($display == 'text')) {
				if ($display == 'text') {
					$size = 21;
				}
				else {
					$size = 20;
				}

				$shop_goods_list = get_store_shop_goods_list($keywords, $size, $page, $sort, $order, $region_id, $area_id);
				$smarty->assign('shop_goods_list', $shop_goods_list);
				$count = get_store_shop_goods_count($keywords, $sort);
			}
		}

		if (($display == 'grid') || ($display == 'text')) {
			$url_format = 'search.php?category=0&amp;keywords=' . urlencode(stripslashes($_REQUEST['keywords']));
			$url_format .= '&amp;sort=' . $sort;
			$url_format .= '&amp;order=' . $order . '&amp;page=';
			$pager['search'] = array('keywords' => stripslashes(trim($_REQUEST['keywords'])), 'category' => 0, 'store_search_cmt' => intval($_REQUEST['store_search_cmt']), 'sort' => $sort, 'order' => $order);
			$pager = get_pager('search.php', $pager['search'], $count, $page, $size);
			$pager['display'] = $display;
			$smarty->assign('url_format', $url_format);
			$smarty->assign('count', $count);
			$smarty->assign('page', $page);
			$smarty->assign('pager', $pager);
		}

		$smarty->assign('size', $size);
		$smarty->assign('count', $count);
		$smarty->assign('display', $display);
		$smarty->assign('sort', $sort);
		$store_best_list = get_shop_goods_count_list(0, $region_id, $area_id, 1, 'store_best');
		$smarty->assign('store_best_list', $store_best_list);
		$cur_url = get_return_self_url();
		$smarty->assign('cur_url', $cur_url);
		$smarty->assign('script_name', 'merchants_shop');
		$smarty->display('merchants_shop_list.dwt');
	}
}

?>
