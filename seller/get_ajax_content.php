<?php
//zend by QQ:2172298892
function upload_article_file($upload, $file = '')
{
	if (!make_dir('../' . DATA_DIR . '/gallery_album')) {
		return false;
	}

	$filename = cls_image::random_filename() . substr($upload['name'], strpos($upload['name'], '.'));
	$path = ROOT_PATH . DATA_DIR . '/gallery_album/' . $filename;

	if (move_upload_file($upload['tmp_name'], $path)) {
		return DATA_DIR . '/gallery_album/' . $filename;
	}
	else {
		return false;
	}
}

function set_show_in_nav($type, $id, $val)
{
	if ($type == 'c') {
		$tablename = $GLOBALS['ecs']->table('category');
	}
	else {
		$tablename = $GLOBALS['ecs']->table('article_cat');
	}

	$GLOBALS['db']->query('UPDATE ' . $tablename . ' SET show_in_nav = \'' . $val . '\' WHERE cat_id = \'' . $id . '\'');
	clear_cache_files();
}

define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';
require ROOT_PATH . '/includes/lib_visual.php';
require ROOT_PATH . '/includes/cls_json.php';
$_REQUEST['act'] = trim($_REQUEST['act']);
$data = array('error' => 0, 'message' => '', 'content' => '');
$smarty->assign('menus', $_SESSION['menus']);
$adminru = get_admin_ru_id();

if ($_REQUEST['act'] == 'get_select_category') {
	$cat_id = (empty($_REQUEST['cat_id']) ? 0 : intval($_REQUEST['cat_id']));
	$child_cat_id = (empty($_REQUEST['child_cat_id']) ? 0 : intval($_REQUEST['child_cat_id']));
	$cat_level = (empty($_REQUEST['cat_level']) ? 0 : intval($_REQUEST['cat_level']));
	$select_jsId = (empty($_REQUEST['select_jsId']) ? 'cat_parent_id' : trim($_REQUEST['select_jsId']));
	$type = (empty($_REQUEST['type']) ? 0 : intval($_REQUEST['type']));
	$table = (isset($_REQUEST['table']) && !empty($_REQUEST['table']) ? intval($_REQUEST['table']) : 0);

	if ($table == 1) {
		$adminru = get_admin_ru_id();
		$content = insert_seller_select_category($cat_id, $child_cat_id, $cat_level, $select_jsId, $type, 'merchants_category', array(), $adminru['ru_id']);
	}
	else {
		$seller_shop_cat = seller_shop_cat($adminru['ru_id']);
		$content = insert_select_category($cat_id, $child_cat_id, $cat_level, $select_jsId, $type, 'category', $seller_shop_cat);
	}

	if (!empty($content)) {
		$data['error'] = 1;
		$data['content'] = $content;
	}

	exit(json_encode($data));
}
else if ($_REQUEST['act'] == 'filter_category') {
	$cat_id = (empty($_REQUEST['cat_id']) ? 0 : intval($_REQUEST['cat_id']));
	$cat_type_show = (empty($_REQUEST['cat_type_show']) ? 0 : intval($_REQUEST['cat_type_show']));
	$user_id = (empty($_REQUEST['user_id']) ? 0 : intval($_REQUEST['user_id']));
	$result = array('error' => 0, 'message' => '', 'content' => '');
	$cat_nav = '';

	if ($cat_type_show == 1) {
		if ($cat_id) {
			$parent_cat_list = get_seller_select_category($cat_id, 1, true, $user_id);
			$filter_category_navigation = get_seller_array_category_info($parent_cat_list);
		}
	}
	else if ($cat_id) {
		$parent_cat_list = get_select_category($cat_id, 1, true, $user_id);
		$filter_category_navigation = get_array_category_info($parent_cat_list);

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
		else {
			$cat_nav = '请选择分类';
		}
	}

	if ($cat_id) {
		$cat_level = count($parent_cat_list);
		$result['cat_nav'] = $cat_nav;
	}
	else {
		$cat_level = 0;
		$result['cat_nav'] = '请选择分类';
	}

	if ($cat_type_show == 1) {
		if ($cat_level <= 3) {
			$filter_category_list = get_seller_category_list($cat_id, 2, $user_id);
		}
		else {
			$filter_category_list = get_seller_category_list($cat_id, 0, $user_id);
			$cat_level -= 1;
		}
	}
	else {
		if ($user_id) {
			$seller_shop_cat = seller_shop_cat($user_id);
		}
		else {
			$seller_shop_cat = array();
		}

		if ($cat_level <= 3) {
			$filter_category_list = get_category_list($cat_id, 2, $seller_shop_cat, $user_id, $cat_level);
		}
		else {
			$filter_category_list = get_category_list($cat_id, 0, $seller_shop_cat, $user_id, $cat_level);
			$cat_level -= 1;
		}
	}

	$smarty->assign('user_id', $user_id);
	$smarty->assign('cat_type_show', $cat_type_show);
	$smarty->assign('filter_category_level', $cat_level);

	if ($cat_type_show) {
		if ($cat_id) {
			$smarty->assign('seller_filter_category_navigation', $filter_category_navigation);
		}

		$smarty->assign('seller_filter_category_list', $filter_category_list);
		$result['content'] = $smarty->fetch('templates/library/filter_category_seller.lbi');
	}
	else {
		if ($cat_id) {
			$smarty->assign('filter_category_navigation', $filter_category_navigation);
		}

		$smarty->assign('filter_category_list', $filter_category_list);
		$result['content'] = $smarty->fetch('templates/library/filter_category.lbi');
	}

	exit(json_encode($result));
}
else if ($_REQUEST['act'] == 'get_albun_pic') {
	$result = array('error' => 0, 'message' => '', 'content' => '');
	$sort_name = (isset($_REQUEST['sort_name']) ? intval($_REQUEST['sort_name']) : 0);
	$album_id = (isset($_REQUEST['album_id']) ? intval($_REQUEST['album_id']) : 0);

	if (0 < $sort_name) {
		$where = '';

		switch ($sort_name) {
		case '1':
			$where .= ' ORDER BY add_time ASC';
			break;

		case '2':
			$where .= ' ORDER BY add_time DESC';
			break;

		case '3':
			$where .= ' ORDER BY pic_size ASC';
			break;

		case '4':
			$where .= ' ORDER BY pic_size DESC';
			break;

		case '5':
			$where .= ' ORDER BY pic_name ASC';
			break;

		case '6':
			$where .= ' ORDER BY pic_name DESC';
			break;
		}
	}
	else {
		$where .= ' ORDER BY album_id DESC';
	}

	$sql = 'SELECT pic_id,ru_id,album_id,pic_name,pic_file,pic_size,pic_spec FROM' . $ecs->table('pic_album') . 'WHERE album_id = \'' . $album_id . '\' ' . $where;
	$pic_list = $db->getAll($sql);
	$html = '';

	if (!empty($pic_list)) {
		foreach ($pic_list as $v) {
			$onclick = 'addpic(\'' . $v['pic_file'] . '\')';
			$html .= '<li><a href="javascript:;"><img src="../' . $v['pic_file'] . '"  onclick="' . $onclick . '"><span class="pixel">' . $v['pic_spec'] . '</span></a></li>';
		}
	}

	exit(json_encode($html));
}
else if ($_REQUEST['act'] == 'addmodule') {
	$json = new JSON();
	$result = array('error' => 0, 'message' => '', 'content' => '', 'mode' => '');
	$result['spec_attr'] = !empty($_REQUEST['spec_attr']) ? stripslashes($_REQUEST['spec_attr']) : '';

	if ($_REQUEST['spec_attr']) {
		$_REQUEST['spec_attr'] = strip_tags(urldecode($_REQUEST['spec_attr']));
		$_REQUEST['spec_attr'] = json_str_iconv($_REQUEST['spec_attr']);

		if (!empty($_REQUEST['spec_attr'])) {
			$spec_attr = $json->decode($_REQUEST['spec_attr']);
			$spec_attr = object_to_array($spec_attr);
		}
	}

	$pic_src = (isset($spec_attr['pic_src']) ? $spec_attr['pic_src'] : array());
	$bg_color = (isset($spec_attr['bg_color']) ? $spec_attr['bg_color'] : array());
	$link = (isset($spec_attr['link']) && ($spec_attr['link'] != ',') ? explode(',', $spec_attr['link']) : array());
	$sort = (isset($spec_attr['sort']) ? $spec_attr['sort'] : array());
	$result['mode'] = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
	$is_li = (isset($spec_attr['is_li']) ? intval($spec_attr['is_li']) : 0);
	$result['slide_type'] = isset($spec_attr['slide_type']) ? addslashes($spec_attr['slide_type']) : '';
	$result['itemsLayout'] = isset($spec_attr['itemsLayout']) ? addslashes($spec_attr['itemsLayout']) : '';
	$result['diff'] = isset($_REQUEST['diff']) ? intval($_REQUEST['diff']) : 0;
	$count = count($pic_src);
	$arr = array();
	$sort_vals = array();

	for ($i = 0; $i < $count; $i++) {
		$arr[$i]['pic_src'] = $pic_src[$i];
		$arr[$i]['link'] = $link[$i];
		$arr[$i]['bg_color'] = $bg_color[$i];
		$arr[$i]['sort'] = isset($sort[$i]) ? $sort[$i] : 0;
		$sort_vals[$i] = isset($sort[$i]) ? $sort[$i] : 0;
	}

	if (!empty($arr)) {
		array_multisort($sort_vals, SORT_ASC, $arr);
		$smarty->assign('img_list', $arr);
	}

	$smarty->assign('is_li', $is_li);
	$smarty->assign('temp', 'img_list');
	$smarty->assign('attr', $spec_attr);
	$smarty->assign('mode', $result['mode']);
	$result['content'] = $GLOBALS['smarty']->fetch('library/dialog.lbi');
	exit(json_encode($result));
}
else if ($_REQUEST['act'] == 'changedgoods') {
	$json = new JSON();
	$result = array('error' => 0, 'message' => '', 'content' => '');
	$spec_attr = array();
	$result['spec_attr'] = !empty($_REQUEST['spec_attr']) ? stripslashes($_REQUEST['spec_attr']) : '';

	if ($_REQUEST['spec_attr']) {
		$_REQUEST['spec_attr'] = strip_tags(urldecode($_REQUEST['spec_attr']));
		$_REQUEST['spec_attr'] = json_str_iconv($_REQUEST['spec_attr']);

		if (!empty($_REQUEST['spec_attr'])) {
			$spec_attr = $json->decode($_REQUEST['spec_attr']);
			$spec_attr = object_to_array($spec_attr);
		}
	}

	$sort_order = (isset($_REQUEST['sort_order']) ? $_REQUEST['sort_order'] : 1);
	$cat_id = (isset($_REQUEST['cat_id']) ? explode('_', $_REQUEST['cat_id']) : array());
	$brand_id = (isset($_REQUEST['brand_id']) ? intval($_REQUEST['brand_id']) : 0);
	$keyword = (isset($_REQUEST['keyword']) ? addslashes($_REQUEST['keyword']) : '');
	$goodsAttr = (isset($spec_attr['goods_ids']) ? explode(',', $spec_attr['goods_ids']) : '');
	$goods_ids = (isset($_REQUEST['goods_ids']) ? explode(',', $_REQUEST['goods_ids']) : '');
	$result['goods_ids'] = !empty($goodsAttr) ? $goodsAttr : $goods_ids;
	$result['cat_desc'] = isset($spec_attr['cat_desc']) ? addslashes($spec_attr['cat_desc']) : '';
	$result['cat_name'] = isset($spec_attr['cat_name']) ? addslashes($spec_attr['cat_name']) : '';
	$result['align'] = isset($spec_attr['align']) ? addslashes($spec_attr['align']) : '';
	$result['is_title'] = isset($spec_attr['is_title']) ? intval($spec_attr['is_title']) : 0;
	$result['itemsLayout'] = isset($spec_attr['itemsLayout']) ? addslashes($spec_attr['itemsLayout']) : '';
	$result['diff'] = isset($_REQUEST['diff']) ? intval($_REQUEST['diff']) : 0;
	$type = (isset($_REQUEST['type']) ? intval($_REQUEST['type']) : 0);
	$temp = (isset($_REQUEST['temp']) ? $_REQUEST['temp'] : 'goods_list');
	$result['mode'] = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
	$smarty->assign('temp', $temp);
	$where = 'WHERE g.is_on_sale=1 AND g.is_delete=0 AND g.user_id = \'' . $adminru['ru_id'] . '\'';

	if ($GLOBALS['_CFG']['review_goods'] == 1) {
		$where .= ' AND g.review_status > 2 ';
	}

	if ($cat_id) {
		$where .= ' AND ' . get_children($cat_id[0]);
	}

	if (0 < $brand_id) {
		$where .= ' AND g.brand_id = \'' . $brand_id . '\'';
	}

	if ($keyword) {
		$where .= ' AND g.goods_name  LIKE \'%' . $keyword . '%\'';
	}

	if ($result['goods_ids'] && ($type == '0')) {
		$where .= ' AND g.goods_id' . db_create_in($result['goods_ids']);
	}

	$sort = '';

	switch ($sort_order) {
	case '1':
		$sort .= ' ORDER BY g.add_time ASC';
		break;

	case '2':
		$sort .= ' ORDER BY g.add_time DESC';
		break;

	case '3':
		$sort .= ' ORDER BY g.sort_order ASC';
		break;

	case '4':
		$sort .= ' ORDER BY g.sort_order DESC';
		break;

	case '5':
		$sort .= ' ORDER BY g.goods_name ASC';
		break;

	case '6':
		$sort .= ' ORDER BY g.goods_name DESC';
		break;
	}

	$str_len = 0 - (str_len(SELLER_PATH) + 1);
	$url = substr($GLOBALS['ecs']->url(), 0, $str_len);
	$sql = 'SELECT g.goods_name,g.goods_id,g.goods_thumb,g.shop_price,g.market_price,g.original_img FROM ' . $ecs->table('goods') . ' AS g ' . $where . $sort;
	$goods_list = $db->getAll($sql);

	if (!empty($result['goods_ids'])) {
		foreach ($goods_list as $k => $v) {
			$goods_list[$k]['goods_thumb'] = $url . $v['goods_thumb'];
			$goods_list[$k]['original_img'] = $url . $v['original_img'];
			$goods_list[$k]['url'] = build_uri('goods', array('gid' => $v['goods_id']), $v['goods_name']);
			$goods_list[$k]['shop_price'] = price_format($v['shop_price']);
			if ((0 < $v['goods_id']) && in_array($v['goods_id'], $result['goods_ids']) && (0 < $type)) {
				$goods_list[$k]['is_selected'] = 1;
			}
		}
	}

	$smarty->assign('is_title', $result['is_title']);
	$smarty->assign('goods_list', $goods_list);
	$smarty->assign('attr', $spec_attr);
	$result['content'] = $GLOBALS['smarty']->fetch('library/dialog.lbi');
	exit(json_encode($result));
}
else if ($_REQUEST['act'] == 'navigator') {
	$json = new JSON();
	$attr = array();
	$result = array('error' => 0, 'message' => '', 'content' => '');
	$result['mode'] = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
	$result['diff'] = isset($_REQUEST['diff']) ? intval($_REQUEST['diff']) : 0;
	$result['spec_attr'] = !empty($_REQUEST['spec_attr']) ? stripslashes($_REQUEST['spec_attr']) : '';
	$_REQUEST['spec_attr'] = strip_tags(urldecode($_REQUEST['spec_attr']));
	$_REQUEST['spec_attr'] = json_str_iconv($_REQUEST['spec_attr']);

	if (!empty($_REQUEST['spec_attr'])) {
		$spec_attr = $json->decode($_REQUEST['spec_attr']);
		$spec_attr = object_to_array($spec_attr);
	}

	$result['navColor'] = $spec_attr['navColor'];
	$where = ' where ru_id = ' . $adminru['ru_id'] . ' AND ifshow = 1 ';
	$sql = 'SELECT name, url ' . ' FROM ' . $GLOBALS['ecs']->table('merchants_nav') . $where . ' ORDER by vieworder';
	$navigator = $db->getAll($sql);
	$smarty->assign('navigator', $navigator);
	$smarty->assign('temp', 'navigator');
	$smarty->assign('attr', $spec_attr);
	$result['content'] = $GLOBALS['smarty']->fetch('library/dialog.lbi');
	exit(json_encode($result));
}
else if ($_REQUEST['act'] == 'album_move_back') {
	include_once ROOT_PATH . 'includes/cls_json.php';
	$json = new JSON();
	$result = array('content' => '', 'pic_id' => '');
	$pic_id = (isset($_REQUEST['pic_id']) ? intval($_REQUEST['pic_id']) : 0);
	$album_id = (isset($_REQUEST['album_id']) ? intval($_REQUEST['album_id']) : 0);
	$sql = 'UPDATE' . $ecs->table('pic_album') . ' SET album_id = \'' . $album_id . '\' WHERE pic_id = \'' . $pic_id . '\' AND ru_id = \'' . $adminru['ru_id'] . '\'';
	$db->query($sql);
	$result['pic_id'] = $pic_id;
	exit(json_encode($result));
}
else if ($_REQUEST['act'] == 'add_albun_pic') {
	include_once ROOT_PATH . 'includes/cls_json.php';
	$json = new JSON();
	$result = array('error' => '', 'pic_id' => '', 'content' => '');
	require_once ROOT_PATH . 'includes/cls_image.php';
	$exc = new exchange($ecs->table('gallery_album'), $db, 'album_id', 'album_mame');
	$allow_file_types = '|GIF|JPG|PNG|';
	$album_mame = (isset($_REQUEST['album_mame']) ? addslashes($_REQUEST['album_mame']) : '');
	$album_desc = (isset($_REQUEST['album_desc']) ? addslashes($_REQUEST['album_desc']) : '');
	$sort_order = (isset($_REQUEST['sort_order']) ? intval($_REQUEST['sort_order']) : 50);
	$is_only = $exc->is_only('album_mame', $album_mame, 0, 'ru_id = ' . $adminru['ru_id']);

	if (!$is_only) {
		$result['error'] = 0;
		$result['content'] = '相册’' . $album_mame . '‘存在';
		exit(json_encode($result));
	}

	$file_url = '';
	if ((isset($_FILES['album_cover']['error']) && ($_FILES['album_cover']['error'] == 0)) || (!isset($_FILES['album_cover']['error']) && isset($_FILES['album_cover']['tmp_name']) && ($_FILES['album_cover']['tmp_name'] != 'none'))) {
		if (!check_file_type($_FILES['album_cover']['tmp_name'], $_FILES['album_cover']['name'], $allow_file_types)) {
			$result['error'] = 0;
			$result['content'] = '相册封面格式必须为|GIF|JPG|PNG|格式。请重新上传';
			exit(json_encode($result));
		}

		$res = upload_article_file($_FILES['album_cover']);

		if ($res != false) {
			$file_url = $res;
		}
	}

	if ($file_url == '') {
		$file_url = $_POST['file_url'];
	}

	$time = gmtime();
	$sql = 'INSERT INTO' . $ecs->table('gallery_album') . '(`album_mame`,`album_cover`,`album_desc`,`sort_order`,`add_time`,`ru_id`)' . ' VALUES (\'' . $album_mame . '\',\'' . $file_url . '\',\'' . $album_desc . '\',\'' . $sort_order . '\',\'' . $time . '\',\'' . $adminru['ru_id'] . '\')';
	$db->query($sql);
	$result['error'] = 1;
	$result['pic_id'] = $db->insert_id();
	$sql = 'SELECT album_id,album_mame FROM' . $ecs->table('gallery_album') . 'WHERE ru_id = \'' . $adminru['ru_id'] . '\'';
	$album_list = $db->getAll($sql);
	$html = '<option value="0" >请选择相册</option>';

	if (!empty($album_list)) {
		foreach ($album_list as $v) {
			$selected = '';

			if ($result['pic_id'] = $v['album_id']) {
				$selected = 'selected';
			}

			$html .= '<option value="' . $v['album_id'] . '" ' . $selected . '>' . $v['album_mame'] . '</option>';
		}
	}

	$result['content'] = $html;
	exit(json_encode($result));
}
else if ($_REQUEST['act'] == 'edit_navname') {
	include_once ROOT_PATH . 'includes/cls_json.php';
	$json = new JSON();
	$result = array('error' => '', 'pic_id' => '', 'content' => '');
	$exc = new exchange($ecs->table('merchants_nav'), $db, 'id', 'name');
	$nav_name = (isset($_REQUEST['val']) ? addslashes($_REQUEST['val']) : '');
	$id = (isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0);
	if ((0 < $id) && !empty($nav_name)) {
		$is_only = $exc->is_only('name', $nav_name, 0, ' ru_id = ' . $adminru['ru_id']);

		if (!$is_only) {
			$result['error'] = 0;
			$result['content'] = '导航’' . $nav_name . '‘已存在';
		}
		else {
			$sql = 'UPDATE' . $ecs->table('merchants_nav') . ' SET name = \'' . $nav_name . '\' WHERE id = \'' . $id . '\' AND ru_id = ' . $adminru['ru_id'];
			$db->query($sql);
			$result['error'] = 1;
			$result['content'] = '编辑成功';
		}
	}
	else {
		$result['error'] = 0;
		$result['content'] = '导航不存在或者导航名称不能为空';
	}

	exit(json_encode($result));
}
else if ($_REQUEST['act'] == 'edit_navurl') {
	include_once ROOT_PATH . 'includes/cls_json.php';
	$json = new JSON();
	$result = array('error' => '', 'pic_id' => '', 'content' => '');
	$url = (isset($_REQUEST['val']) ? addslashes($_REQUEST['val']) : '');
	$id = (isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0);

	if (0 < $id) {
		$sql = 'UPDATE' . $ecs->table('merchants_nav') . ' SET url = \'' . $url . '\' WHERE id = \'' . $id . '\' AND ru_id = ' . $adminru['ru_id'];
		$db->query($sql);
		$result['error'] = 1;
		$result['content'] = '编辑成功';
	}
	else {
		$result['error'] = 0;
		$result['content'] = '导航不存在';
	}

	exit(json_encode($result));
}
else if ($_REQUEST['act'] == 'edit_navvieworder') {
	include_once ROOT_PATH . 'includes/cls_json.php';
	$json = new JSON();
	$result = array('error' => '', 'pic_id' => '', 'content' => '');
	$order = (isset($_REQUEST['val']) ? intval($_REQUEST['val']) : '');
	$id = (isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0);

	if (0 < $id) {
		if (preg_match('/^\\d+$/i', $order)) {
			$sql = 'UPDATE' . $ecs->table('merchants_nav') . ' SET vieworder = \'' . $order . '\' WHERE id = \'' . $id . '\' AND ru_id = ' . $adminru['ru_id'];
			$db->query($sql);
			$result['error'] = 1;
			$result['content'] = '编辑成功';
		}
		else {
			$result['error'] = 0;
			$result['content'] = '排序必须为数字';
		}
	}
	else {
		$result['error'] = 0;
		$result['content'] = '导航不存在';
	}

	exit(json_encode($result));
}
else if ($_REQUEST['act'] == 'remove_nav') {
	include_once ROOT_PATH . 'includes/cls_json.php';
	$json = new JSON();
	$result = array('error' => '', 'pic_id' => '', 'content' => '');
	$id = (isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0);

	if (0 < $id) {
		$row = $db->getRow('SELECT ctype,cid,type FROM ' . $GLOBALS['ecs']->table('merchants_nav') . ' WHERE id = \'' . $id . '\' LIMIT 1');
		if (($row['type'] == 'middle') && $row['ctype'] && $row['cid']) {
			set_show_in_nav($row['ctype'], $row['cid'], 0);
		}

		$sql = ' DELETE FROM ' . $GLOBALS['ecs']->table('merchants_nav') . ' WHERE id=\'' . $id . '\' LIMIT 1';
		$db->query($sql);
		$result['error'] = 1;
		$result['content'] = '删除成功';
	}
	else {
		$result['error'] = 0;
		$result['content'] = '导航不存在';
	}

	exit(json_encode($result));
}
else if ($_REQUEST['act'] == 'add_nav') {
	include_once ROOT_PATH . 'includes/cls_json.php';
	$json = new JSON();
	$result = array('error' => '', 'pic_id' => '', 'content' => '');
	$exc = new exchange($ecs->table('merchants_nav'), $db, 'id', 'name');
	$link = (isset($_REQUEST['link']) ? addslashes($_REQUEST['link']) : '');
	$name = (isset($_REQUEST['nav_name']) ? addslashes($_REQUEST['nav_name']) : '');

	if (!empty($name)) {
		$is_only = $exc->is_only('name', $name, 0, ' ru_id = ' . $adminru['ru_id']);

		if (!$is_only) {
			$result['error'] = 0;
			$result['content'] = '导航’' . $name . '‘已存在';
		}
		else {
			$sql = 'INSERT INTO' . $ecs->table('merchants_nav') . '(`name`,`url`,`ifshow`,`type`,`ru_id`,`vieworder`) VALUES(\'' . $name . '\',\'' . $link . '\',1,\'middle\',\'' . $adminru['ru_id'] . '\',50)';
			$db->query($sql);
			$id = $db->insert_id();
			$result['error'] = 1;
			$html_id = '\'' . $id . '\'';
			$html_act_name = '\'edit_navname\'';
			$html_act_url = '\'edit_navurl\'';
			$html_act_order = '\'edit_navvieworder\'';
			$html_act_if_show = '\'edit_ifshow\'';
			$html_act_type = '\'1\'';
			$html = '<tr><td><input type="text" onchange = "edit_nav(this.value ,' . $html_id . ',' . $html_act_name . ')" value="' . $name . '"></td>';
			$html .= '<td><input type="text" onchange = "edit_nav(this.value ,' . $html_id . ',' . $html_act_url . ')" value="' . $link . '"></td>';
			$html .= '<td class="center"><input type="text" onchange = "edit_nav(this.value ,' . $html_id . ',' . $html_act_order . ')" class="small" value="50"></td>';
			$html .= '<td class="center" id="nav_' . $id . '"><img onclick = "edit_nav(' . $html_act_type . ' ,' . $html_id . ',' . $html_act_if_show . ',' . $html_act_type . ')" src="images/yes.gif"/></td>';
			$html .= '<td class="center"><a href="javascript:void(0);" onclick="remove_nav(' . $html_id . ')" class="pic_del del">删除</a></td></tr>';
			$result['content'] = $html;
		}
	}
	else {
		$result['error'] = 0;
		$result['content'] = '导航名称不能为空';
	}

	exit(json_encode($result));
}
else if ($_REQUEST['act'] == 'edit_ifshow') {
	include_once ROOT_PATH . 'includes/cls_json.php';
	$json = new JSON();
	$result = array('error' => '', 'pic_id' => '', 'content' => '');
	$id = (isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0);
	$ifshow = (isset($_REQUEST['val']) ? intval($_REQUEST['val']) : 0);

	if (0 < $id) {
		if ($ifshow == 0) {
			$val = 1;
		}
		else {
			$val = 0;
		}

		$sql = 'UPDATE' . $ecs->table('merchants_nav') . ' SET ifshow = \'' . $val . '\' WHERE id = \'' . $id . '\' AND ru_id = ' . $adminru['ru_id'];
		$db->query($sql);
		$result['error'] = 1;
		$result['id'] = $id;
		$html_ifshow = '\'' . $val . '\'';
		$html_id = '\'' . $id . '\'';
		$html_act_if_show = '\'edit_ifshow\'';
		$html_act_type = '\'1\'';

		if ($val == 1) {
			$src = 'images/yes.gif';
		}
		else {
			$src = 'images/no.gif';
		}

		$html = '<img onclick = "edit_nav(' . $html_ifshow . ' ,' . $html_id . ',' . $html_act_if_show . ',' . $html_act_type . ')" src="' . $src . '"/>';
		$result['content'] = $html;
	}
	else {
		$result['error'] = 0;
		$result['content'] = '导航不存在';
	}

	exit(json_encode($result));
}

?>
