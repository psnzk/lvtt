<?php
//zend by QQ:2172298892
function create_ueditor_editor($input_name, $input_value = '')
{
	global $smarty;
	$input_height = 486;
	$FCKeditor = '<input type="hidden" id="' . $input_name . '" name="' . $input_name . '" value="' . htmlspecialchars($input_value) . '" /><iframe id="' . $input_name . '_frame" src="../plugins/seller_ueditor/ecmobanEditor.php?item=' . $input_name . '" width="100%" height="' . $input_height . '" frameborder="0" scrolling="no"></iframe>';
	return $FCKeditor;
}

function get_sysnav()
{
	$adminru = get_admin_ru_id();
	global $_LANG;
	$catlist = cat_list(0, 0, 0, 'merchants_category', array(), 0, $adminru['ru_id']);

	foreach ($catlist as $key => $val) {
		$val['url'] = build_uri('merchants_store', array('cid' => $val['cat_id'], 'urid' => $adminru['ru_id']), $val['cat_name']);
		$sysmain[] = array('cat_id' => $val['cat_id'], 'cat_name' => $val['cat_name'], 'url' => $val['url']);
	}

	return $sysmain;
}

define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';
require_once ROOT_PATH . '/' . ADMIN_PATH . '/includes/lib_goods.php';
require ROOT_PATH . '/includes/cls_json.php';
require ROOT_PATH . '/includes/lib_visual.php';
$adminru = get_admin_ru_id();

if ($_REQUEST['act'] == 'dialog_content') {
	$json = new JSON();
	$result = array('content' => '', 'sgs' => '');
	$temp = (!empty($_REQUEST['temp']) ? $_REQUEST['temp'] : '');
	$smarty->assign('temp', $temp);
	$result['sgs'] = $temp;
	$result['content'] = $GLOBALS['smarty']->fetch('library/dialog.lbi');
	exit($json->encode($result));
}
else if ($_REQUEST['act'] == 'dialog_warehouse') {
	$json = new JSON();
	$result = array('content' => '', 'sgs' => '');
	$temp = (!empty($_REQUEST['temp']) ? $_REQUEST['temp'] : '');
	$user_id = (!empty($_REQUEST['user_id']) ? intval($_REQUEST['user_id']) : $adminru['ru_id']);
	$goods_id = (!empty($_REQUEST['goods_id']) ? intval($_REQUEST['goods_id']) : 0);
	$smarty->assign('temp', $temp);
	$result['sgs'] = $temp;
	$grade_rank = get_seller_grade_rank($user_id);
	$smarty->assign('grade_rank', $grade_rank);
	$smarty->assign('integral_scale', $_CFG['integral_scale']);
	$warehouse_list = get_warehouse_list();
	$smarty->assign('warehouse_list', $warehouse_list);
	$smarty->assign('user_id', $user_id);
	$smarty->assign('goods_id', $goods_id);
	$result['content'] = $GLOBALS['smarty']->fetch('library/dialog.lbi');
	exit($json->encode($result));
}
else if ($_REQUEST['act'] == 'dialog_img') {
	$json = new JSON();
	$result = array('content' => '', 'sgs' => '');
	$temp = (!empty($_REQUEST['temp']) ? $_REQUEST['temp'] : '');
	$smarty->assign('temp', $temp);
	$goods_id = (!empty($_REQUEST['goods_id']) ? $_REQUEST['goods_id'] : '');
	$smarty->assign('goods_id', $goods_id);
	$result['sgs'] = $temp;
	$result['content'] = $GLOBALS['smarty']->fetch('library/dialog.lbi');
	exit($json->encode($result));
}
else if ($_REQUEST['act'] == 'dialog_add') {
	$json = new JSON();
	$result = array('content' => '', 'sgs' => '');
	$temp = (!empty($_REQUEST['temp']) ? $_REQUEST['temp'] : '');
	$smarty->assign('temp', $temp);
	$result['sgs'] = $temp;
	$country_list = get_regions();
	$smarty->assign('countries', $country_list);
	$result['content'] = $GLOBALS['smarty']->fetch('library/dialog.lbi');
	exit($json->encode($result));
}
else if ($_REQUEST['act'] == 'extension_category') {
	$json = new JSON();
	$result = array('content' => '', 'sgs' => '');
	$temp = (!empty($_REQUEST['temp']) ? $_REQUEST['temp'] : '');
	$smarty->assign('temp', $temp);
	$result['sgs'] = $temp;
	$goods_id = (empty($_REQUEST['goods_id']) ? 0 : intval($_REQUEST['goods_id']));
	$goods = get_admin_goods_info($goods_id, array('user_id'));
	$goods['user_id'] = empty($goods['user_id']) ? $adminru['ru_id'] : $goods['user_id'];

	if ($goods['user_id']) {
		$seller_shop_cat = seller_shop_cat($goods['user_id']);
	}

	$level_limit = 3;
	$category_level = array();

	for ($i = 1; $i <= $level_limit; $i++) {
		$category_list = array();

		if ($i == 1) {
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

	$smarty->assign('category_level', $category_level);

	if (0 < $goods_id) {
		$other_cat_list1 = array();
		$sql = 'SELECT ga.cat_id FROM ' . $ecs->table('goods_cat') . ' as ga ' . ' WHERE ga.goods_id = \'' . $goods_id . '\'';
		$other_cat1 = $db->getCol($sql);
		$other_category = array();

		foreach ($other_cat1 as $key => $val) {
			$other_category[$key]['cat_id'] = $val;
			$other_category[$key]['cat_name'] = get_every_category($val);
		}

		$smarty->assign('other_category', $other_category);
	}

	$smarty->assign('goods_id', $goods_id);
	$result['content'] = $GLOBALS['smarty']->fetch('library/extension_category.lbi');
	exit($json->encode($result));
}
else if ($_REQUEST['act'] == 'shop_banner') {
	$json = new JSON();
	$result = array('content' => '', 'sgs' => '', 'mode' => '');
	$smarty->assign('temp', 'shop_banner');
	$json = new JSON();
	$result = array('content' => '', 'mode' => '');
	$result['mode'] = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
	$_REQUEST['spec_attr'] = strip_tags(urldecode($_REQUEST['spec_attr']));
	$_REQUEST['spec_attr'] = json_str_iconv($_REQUEST['spec_attr']);
	$_REQUEST['spec_attr'] = !empty($_REQUEST['spec_attr']) ? stripslashes($_REQUEST['spec_attr']) : '';

	if (!empty($_REQUEST['spec_attr'])) {
		$spec_attr = $json->decode($_REQUEST['spec_attr']);
		$spec_attr = object_to_array($spec_attr);
	}

	$defualt = '';

	if ($result['mode'] == 'lunbo') {
		$defualt = 'shade';
	}
	else if ($result['mode'] == 'advImg1') {
		$defualt = 'yesSlide';
	}

	$spec_attr['slide_type'] = isset($spec_attr['slide_type']) ? $spec_attr['slide_type'] : $defualt;
	$spec_attr['target'] = isset($spec_attr['target']) ? addslashes($spec_attr['target']) : '_blank';
	$pic_src = (isset($spec_attr['pic_src']) && ($spec_attr['pic_src'] != ',') ? $spec_attr['pic_src'] : array());
	$link = (isset($spec_attr['link']) && ($spec_attr['link'] != ',') ? explode(',', $spec_attr['link']) : array());
	$sort = (isset($spec_attr['sort']) && ($spec_attr['sort'] != ',') ? $spec_attr['sort'] : array());
	$pic_number = (isset($_REQUEST['pic_number']) ? intval($_REQUEST['pic_number']) : 0);
	$result['diff'] = isset($_REQUEST['diff']) ? intval($_REQUEST['diff']) : 0;
	$count = count($pic_src);
	$arr = array();

	for ($i = 0; $i < $count; $i++) {
		if ($pic_src[$i]) {
			$arr[$i + 1]['pic_src'] = $pic_src[$i];
			$arr[$i + 1]['link'] = $link[$i];
			$arr[$i + 1]['sort'] = $sort[$i];
		}
	}

	$smarty->assign('banner_list', $arr);
	$sql = 'SELECT album_id,album_mame FROM' . $ecs->table('gallery_album') . 'WHERE ru_id = \'' . $adminru['ru_id'] . '\'';
	$album_list = $db->getAll($sql);
	$smarty->assign('album_list', $album_list);

	if (!empty($album_list)) {
		$sql = 'SELECT pic_id,ru_id,album_id,pic_name,pic_file,pic_size,pic_spec FROM' . $ecs->table('pic_album') . 'WHERE album_id = \'' . $album_list[0]['album_id'] . '\' ' . $where;
		$pic_list = $db->getAll($sql);
		$smarty->assign('pic_list', $pic_list);
	}

	$smarty->assign('pic_number', $pic_number);
	$smarty->assign('mode', $result['mode']);
	$smarty->assign('spec_attr', $spec_attr);
	$result['content'] = $GLOBALS['smarty']->fetch('library/shop_banner.lbi');
	exit($json->encode($result));
}
else if ($_REQUEST['act'] == 'pic_album') {
	$json = new JSON();
	$result = array('content' => '', 'sgs' => '');
	$album_id = (isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0);
	$smarty->assign('album_id', $album_id);
	$smarty->assign('temp', $_REQUEST['act']);
	$sql = 'SELECT album_id,album_mame FROM' . $ecs->table('gallery_album') . 'WHERE ru_id = \'' . $adminru['ru_id'] . '\'';
	$album_list = $db->getAll($sql);
	$smarty->assign('album_list', $album_list);
	$sql = 'SELECT album_mame FROM' . $ecs->table('gallery_album') . ' WHERE album_id = \'' . $album_id . '\'';
	$album_mame = $db->getOne($sql);
	$smarty->assign('album_mame', $album_mame);
	$result['content'] = $GLOBALS['smarty']->fetch('library/dialog.lbi');
	exit($json->encode($result));
}
else if ($_REQUEST['act'] == 'goods_info') {
	$json = new JSON();
	$result = array('content' => '', 'mode' => '');
	$_REQUEST['spec_attr'] = strip_tags(urldecode($_REQUEST['spec_attr']));
	$_REQUEST['spec_attr'] = json_str_iconv($_REQUEST['spec_attr']);
	$_REQUEST['spec_attr'] = !empty($_REQUEST['spec_attr']) ? stripslashes($_REQUEST['spec_attr']) : '';

	if (!empty($_REQUEST['spec_attr'])) {
		$spec_attr = $json->decode(stripslashes($_REQUEST['spec_attr']));
		$spec_attr = object_to_array($spec_attr);
	}

	$spec_attr['is_title'] = isset($spec_attr['is_title']) ? $spec_attr['is_title'] : 0;
	$spec_attr['itemsLayout'] = isset($spec_attr['itemsLayout']) ? $spec_attr['itemsLayout'] : 'row4';
	$result['mode'] = isset($_REQUEST['mode']) ? addslashes($_REQUEST['mode']) : '';
	$result['diff'] = isset($_REQUEST['diff']) ? intval($_REQUEST['diff']) : 0;

	if ($spec_attr['goods_ids']) {
		$goods_info = explode(',', $spec_attr['goods_ids']);

		foreach ($goods_info as $k => $v) {
			if (!$v) {
				unset($goods_info[$k]);
			}
		}

		if (!empty($goods_info)) {
			$where = ' WHERE g.is_on_sale=1 AND g.is_delete=0 AND g.goods_id' . db_create_in($goods_info) . ' AND g.user_id = \'' . $adminru['ru_id'] . '\'';

			if ($GLOBALS['_CFG']['review_goods'] == 1) {
				$where .= ' AND g.review_status > 2 ';
			}

			$sql = 'SELECT g.goods_name,g.goods_id,g.goods_thumb,g.original_img,g.shop_price FROM ' . $ecs->table('goods') . ' AS g ' . $where;
			$goods_list = $db->getAll($sql);

			foreach ($goods_list as $k => $v) {
				$goods_list[$k]['shop_price'] = price_format($v['shop_price']);
			}

			$smarty->assign('goods_list', $goods_list);
		}
	}

	$select_category_html = '';
	$seller_shop_cat = seller_shop_cat($adminru['ru_id']);
	$select_category_html = insert_select_category(0, 0, 0, 'cat_id', 0, 'category', $seller_shop_cat);
	$smarty->assign('select_category_html', $select_category_html);
	$smarty->assign('brand_list', get_brand_list());
	$smarty->assign('arr', $spec_attr);
	$smarty->assign('temp', 'goods_info');
	$result['content'] = $GLOBALS['smarty']->fetch('library/shop_banner.lbi');
	exit($json->encode($result));
}
else if ($_REQUEST['act'] == 'custom') {
	$json = new JSON();
	$result = array('content' => '', 'mode' => '');
	$custom_content = (isset($_REQUEST['custom_content']) ? unescape($_REQUEST['custom_content']) : '');
	$custom_content = (!empty($custom_content) ? stripslashes($custom_content) : '');
	$result['mode'] = isset($_REQUEST['mode']) ? addslashes($_REQUEST['mode']) : '';
	$result['diff'] = isset($_REQUEST['diff']) ? intval($_REQUEST['diff']) : 0;
	$FCKeditor = create_ueditor_editor('custom_content', $custom_content);
	$smarty->assign('FCKeditor', $FCKeditor);
	$smarty->assign('temp', $_REQUEST['act']);
	$result['content'] = $GLOBALS['smarty']->fetch('library/shop_banner.lbi');
	exit($json->encode($result));
}
else if ($_REQUEST['act'] == 'header') {
	$json = new JSON();
	$result = array('content' => '', 'mode' => '');
	$arr = array();
	$smarty->assign('temp', $_REQUEST['act']);
	$_REQUEST['spec_attr'] = !empty($_REQUEST['spec_attr']) ? stripslashes($_REQUEST['spec_attr']) : '';
	$_REQUEST['spec_attr'] = urldecode($_REQUEST['spec_attr']);
	$_REQUEST['spec_attr'] = json_str_iconv($_REQUEST['spec_attr']);

	if (!empty($_REQUEST['spec_attr'])) {
		$spec_attr = json_decode($_REQUEST['spec_attr'], true);
	}

	$spec_attr['header_type'] = isset($spec_attr['header_type']) ? $spec_attr['header_type'] : 'defalt_type';
	$custom_content = (isset($spec_attr['custom_content']) ? unescape($spec_attr['custom_content']) : '');
	$custom_content = (!empty($custom_content) ? stripslashes($custom_content) : '');
	$result['mode'] = isset($_REQUEST['mode']) ? addslashes($_REQUEST['mode']) : '';
	$FCKeditor = create_ueditor_editor('custom_content', $custom_content);
	$smarty->assign('FCKeditor', $FCKeditor);
	$smarty->assign('content', $spec_attr);
	$result['content'] = $GLOBALS['smarty']->fetch('library/shop_banner.lbi');
	exit($json->encode($result));
}
else if ($_REQUEST['act'] == 'navigator') {
	$json = new JSON();
	$result = array('content' => '', 'mode' => '');
	$_REQUEST['spec_attr'] = strip_tags(urldecode($_REQUEST['spec_attr']));
	$_REQUEST['spec_attr'] = json_str_iconv($_REQUEST['spec_attr']);
	$_REQUEST['spec_attr'] = !empty($_REQUEST['spec_attr']) ? stripslashes($_REQUEST['spec_attr']) : '';

	if (!empty($_REQUEST['spec_attr'])) {
		$spec_attr = $json->decode($_REQUEST['spec_attr']);
		$spec_attr = object_to_array($spec_attr);
	}

	$spec_attr['target'] = isset($spec_attr['target']) ? $spec_attr['target'] : '_blank';
	$result['diff'] = isset($_REQUEST['diff']) ? intval($_REQUEST['diff']) : 0;
	$where = ' where ru_id = ' . $adminru['ru_id'];
	$sql = 'SELECT id, name,ifshow,  vieworder,  url ' . ' FROM ' . $GLOBALS['ecs']->table('merchants_nav') . $where . ' ORDER by vieworder';
	$navigator = $db->getAll($sql);
	$smarty->assign('navigator', $navigator);
	$smarty->assign('temp', $_REQUEST['act']);
	$sysmain = get_sysnav();
	$smarty->assign('sysmain', $sysmain);
	$smarty->assign('attr', $spec_attr);
	$result['mode'] = isset($_REQUEST['mode']) ? addslashes($_REQUEST['mode']) : '';
	$result['content'] = $GLOBALS['smarty']->fetch('library/shop_banner.lbi');
	exit($json->encode($result));
}
else if ($_REQUEST['act'] == 'template_information') {
	$json = new JSON();
	$result = array('content' => '', 'mode' => '');
	$code = (isset($_REQUEST['code']) ? addslashes($_REQUEST['code']) : '');
	$adminru = get_admin_ru_id();

	if ($code) {
		$smarty->assign('template', get_seller_template_info($code, $adminru['ru_id']));
	}

	$smarty->assign('code', $code);
	$smarty->assign('ru_id', $adminru['ru_id']);
	$smarty->assign('temp', $_REQUEST['act']);
	$result['content'] = $GLOBALS['smarty']->fetch('library/shop_banner.lbi');
	exit($json->encode($result));
}
else if ($_REQUEST['act'] == 'album_move') {
	$json = new JSON();
	$result = array('content' => '', 'pic_id' => '', 'old_album_id' => '');
	$pic_id = (isset($_REQUEST['pic_id']) ? intval($_REQUEST['pic_id']) : 0);
	$temp = (!empty($_REQUEST['act']) ? $_REQUEST['act'] : '');
	$smarty->assign('temp', $temp);
	$sql = 'SELECT album_id,album_mame FROM' . $ecs->table('gallery_album') . ' WHERE ru_id = \'' . $adminru['ru_id'] . '\'';
	$album_list = $db->getAll($sql);
	$smarty->assign('album_list', $album_list);
	$sql = 'SELECT album_id FROM' . $ecs->table('pic_album') . 'WHERE pic_id = \'' . $pic_id . '\' AND ru_id = \'' . $adminru['ru_id'] . '\'';
	$album_id = $db->getOne($sql);
	$smarty->assign('album_id', $album_id);
	$result['pic_id'] = $pic_id;
	$result['old_album_id'] = $album_id;
	$result['content'] = $GLOBALS['smarty']->fetch('library/dialog.lbi');
	exit($json->encode($result));
}
else if ($_REQUEST['act'] == 'add_albun_pic') {
	$json = new JSON();
	$result = array('content' => '', 'pic_id' => '', 'old_album_id' => '');
	$temp = (!empty($_REQUEST['act']) ? $_REQUEST['act'] : '');
	$smarty->assign('temp', $temp);
	$result['content'] = $GLOBALS['smarty']->fetch('library/dialog.lbi');
	exit($json->encode($result));
}

?>
