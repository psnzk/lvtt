<?php
//zend  QQ:2172298892
function cat_update($cat_id, $args)
{
	if (empty($args) || empty($cat_id)) {
		return false;
	}

	return $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('merchants_category'), $args, 'update', 'cat_id=\'' . $cat_id . '\'');
}

function get_attr_list()
{
	$sql = 'SELECT a.attr_id, a.cat_id, a.attr_name ' . ' FROM ' . $GLOBALS['ecs']->table('attribute') . ' AS a,  ' . $GLOBALS['ecs']->table('goods_type') . ' AS c ' . ' WHERE  a.cat_id = c.cat_id AND c.enabled = 1 ' . ' ORDER BY a.cat_id , a.sort_order';
	$arr = $GLOBALS['db']->getAll($sql);
	$list = array();

	foreach ($arr as $val) {
		$list[$val['cat_id']][] = array($val['attr_id'] => $val['attr_name']);
	}

	return $list;
}

function insert_cat_recommend($recommend_type, $cat_id)
{
	if (!empty($recommend_type)) {
		$recommend_res = $GLOBALS['db']->getAll('SELECT recommend_type FROM ' . $GLOBALS['ecs']->table('cat_recommend') . ' WHERE cat_id=' . $cat_id);

		if (empty($recommend_res)) {
			foreach ($recommend_type as $data) {
				$data = intval($data);
				$GLOBALS['db']->query('INSERT INTO ' . $GLOBALS['ecs']->table('cat_recommend') . '(cat_id, recommend_type) VALUES (\'' . $cat_id . '\', \'' . $data . '\')');
			}
		}
		else {
			$old_data = array();

			foreach ($recommend_res as $data) {
				$old_data[] = $data['recommend_type'];
			}

			$delete_array = array_diff($old_data, $recommend_type);

			if (!empty($delete_array)) {
				$GLOBALS['db']->query('DELETE FROM ' . $GLOBALS['ecs']->table('cat_recommend') . ' WHERE cat_id=' . $cat_id . ' AND recommend_type ' . db_create_in($delete_array));
			}

			$insert_array = array_diff($recommend_type, $old_data);

			if (!empty($insert_array)) {
				foreach ($insert_array as $data) {
					$data = intval($data);
					$GLOBALS['db']->query('INSERT INTO ' . $GLOBALS['ecs']->table('cat_recommend') . '(cat_id, recommend_type) VALUES (\'' . $cat_id . '\', \'' . $data . '\')');
				}
			}
		}
	}
	else {
		$GLOBALS['db']->query('DELETE FROM ' . $GLOBALS['ecs']->table('cat_recommend') . ' WHERE cat_id=' . $cat_id);
	}
}

function cat_list_one($cat_id = 0, $cat_level = 0)
{
	if ($cat_id == 0) {
		$arr = cat_list($cat_id);
		return $arr;
	}
	else {
		$arr = cat_list($cat_id);

		foreach ($arr as $key => $value) {
			if ($key == $cat_id) {
				unset($arr[$cat_id]);
			}
		}

		$str = '';

		if ($arr) {
			$cat_level++;
			$str .= '<select name=\'catList' . $cat_level . '\' id=\'cat_list' . $cat_level . '\' onchange=\'catList(this.value, ' . $cat_level . ')\' class=\'select\'>';
			$str .= '<option value=\'0\'>全部分类</option>';

			foreach ($arr as $key1 => $value1) {
				$str .= '<option value=\'' . $value1['cat_id'] . '\'>' . $value1['cat_name'] . '</option>';
			}

			$str .= '</select>';
		}

		return $str;
	}
}

define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';
$exc = new exchange($ecs->table('merchants_category'), $db, 'cat_id', 'cat_name');
$smarty->assign('menus', $_SESSION['menus']);
$smarty->assign('action_type', 'goods');

if (empty($_REQUEST['act'])) {
	$_REQUEST['act'] = 'list';
}
else {
	$_REQUEST['act'] = trim($_REQUEST['act']);
}

$smarty->assign('current', 'category_store_list');
$adminru = get_admin_ru_id();
$smarty->assign('ru_id', $adminru['ru_id']);
$smarty->assign('menu_select', array('action' => '02_cat_and_goods', 'current' => '03_store_category_list'));

if ($_REQUEST['act'] == 'list') {
	$level = (isset($_REQUEST['level']) ? $_REQUEST['level'] + 1 : 0);

	if (0 < $adminru['ru_id']) {
		$smarty->assign('action_link', array('href' => 'category_store.php?act=add', 'text' => $_LANG['04_category_add']));
	}

	$cat_list = get_category_store_list($adminru['ru_id'], 0, $level);
	$smarty->assign('ur_here', $_LANG['03_store_category_list']);
	$smarty->assign('full_page', 1);
	$smarty->assign('cat_info', $cat_list);
	assign_query_info();
	$smarty->display('category_store_list.dwt');
}
else if ($_REQUEST['act'] == 'query') {
	$cat_list = get_category_store_list($adminru['ru_id']);
	$smarty->assign('cat_info', $cat_list);
	make_json_result($smarty->fetch('category_store_list.dwt'));
}
else if ($_REQUEST['act'] == 'ajax_cache_list') {
	include_once ROOT_PATH . 'includes/cls_json.php';
	$json = new JSON();
	$cat_id = (isset($_REQUEST['cat_id']) ? intval($_REQUEST['cat_id']) : 0);
	$level = (isset($_REQUEST['level']) ? intval($_REQUEST['level']) : 0);
	$result['cat_id'] = $cat_id;
	$result['parent_level'] = $level;
	$level = $level + 1;
	$cat_list = get_category_store_child_list($cat_id, $level, $adminru['ru_id']);
	$result['cat_list'] = $cat_list;
	$result['cat_html'] = cat_level_html($cat_list, $adminru['ru_id'], 1, 'merchants_category');
	exit($json->encode($result));
}

if ($_REQUEST['act'] == 'add') {
	admin_priv('cat_manage');
	$select_category_html = '';
	$select_category_html .= insert_seller_select_category(0, 0, 0, 'cat_parent_id', 0, 'merchants_category', array(), $adminru['ru_id']);
	$smarty->assign('select_category_html', $select_category_html);
	$smarty->assign('ur_here', $_LANG['04_category_add']);
	$smarty->assign('action_link', array('href' => 'category_store.php?act=list', 'text' => $_LANG['03_category_list']));
	$smarty->assign('goods_type_list', goods_type_list(0));
	$smarty->assign('attr_list', get_attr_list());
	$smarty->assign('form_act', 'insert');
	$smarty->assign('cat_info', array('is_show' => 1));
	assign_query_info();
	$smarty->display('category_store_info.dwt');
}

if ($_REQUEST['act'] == 'insert') {
	admin_priv('cat_manage');
	$cat['cat_id'] = !empty($_POST['cat_id']) ? intval($_POST['cat_id']) : 0;
	$cat['parent_id'] = isset($_POST['parent_id']) ? trim($_POST['parent_id']) : '0_-1';
	$parent_id = explode('_', $cat['parent_id']);
	$cat['parent_id'] = intval($parent_id[0]);
	$cat['sort_order'] = !empty($_POST['sort_order']) ? intval($_POST['sort_order']) : 0;
	$cat['keywords'] = !empty($_POST['keywords']) ? trim($_POST['keywords']) : '';
	$cat['cat_desc'] = !empty($_POST['cat_desc']) ? $_POST['cat_desc'] : '';
	$cat['measure_unit'] = !empty($_POST['measure_unit']) ? trim($_POST['measure_unit']) : '';
	$cat['cat_name'] = !empty($_POST['cat_name']) ? trim($_POST['cat_name']) : '';
	$cat['user_id'] = $adminru['ru_id'];
	$pin = new pin();
	$pinyin = $pin->Pinyin($cat['cat_name'], 'UTF8');
	$cat['pinyin_keyword'] = $pinyin;
	$cat['show_in_nav'] = !empty($_POST['show_in_nav']) ? intval($_POST['show_in_nav']) : 0;
	$cat['style'] = !empty($_POST['style']) ? trim($_POST['style']) : '';
	$cat['is_show'] = !empty($_POST['is_show']) ? intval($_POST['is_show']) : 0;
	$cat['is_top_show'] = !empty($_POST['is_top_show']) ? intval($_POST['is_top_show']) : 0;
	$cat['is_top_style'] = !empty($_POST['is_top_style']) ? intval($_POST['is_top_style']) : 0;
	$cat['grade'] = !empty($_POST['grade']) ? intval($_POST['grade']) : 0;
	$cat['filter_attr'] = !empty($_POST['filter_attr']) ? implode(',', array_unique(array_diff($_POST['filter_attr'], array(0)))) : 0;
	$cat['cat_recommend'] = !empty($_POST['cat_recommend']) ? $_POST['cat_recommend'] : array();

	if (cat_exists($cat['cat_name'], $cat['parent_id'], 0, $adminru['ru_id'])) {
		$link[] = array('text' => $_LANG['go_back'], 'href' => 'javascript:history.back(-1)');
		sys_msg($_LANG['catname_exist'], 0, $link);
	}

	if ((10 < $cat['grade']) || ($cat['grade'] < 0)) {
		$link[] = array('text' => $_LANG['go_back'], 'href' => 'javascript:history.back(-1)');
		sys_msg($_LANG['grade_error'], 0, $link);
	}

	$cat_name = explode(',', $cat['cat_name']);

	if (1 < count($cat_name)) {
		get_bacth_category($cat_name, $cat, $adminru['ru_id']);
		clear_cache_files();
		$link[0]['text'] = $_LANG['continue_add'];
		$link[0]['href'] = 'category_store.php?act=add';
		$link[1]['text'] = $_LANG['back_list'];
		$link[1]['href'] = 'category_store.php?act=list';
		sys_msg($_LANG['catadd_succed'], 0, $link);
	}
	else if ($db->autoExecute($ecs->table('merchants_category'), $cat) !== false) {
		$cat_id = $db->insert_id();

		if ($cat['show_in_nav'] == 1) {
			$vieworder = $db->getOne('SELECT max(vieworder) FROM ' . $ecs->table('merchants_nav') . ' WHERE type = \'middle\'');
			$vieworder += 2;
			$sql = 'INSERT INTO ' . $ecs->table('merchants_nav') . ' (name,ctype,cid,ru_id,ifshow,vieworder,opennew,url,type)' . ' VALUES(\'' . $cat['cat_name'] . '\', \'c\', \'' . $db->insert_id() . '\', \'' . $adminru['ru_id'] . '\',\'1\',\'' . $vieworder . '\',\'0\', \'' . build_uri('category', array('cid' => $cat_id), $cat['cat_name']) . '\',\'middle\')';
			$db->query($sql);
		}

		insert_cat_recommend($cat['cat_recommend'], $cat_id);
		admin_log($_POST['cat_name'], 'add', 'merchants_category');
		clear_cache_files();
		$link[0]['text'] = $_LANG['continue_add'];
		$link[0]['href'] = 'category_store.php?act=add';
		$link[1]['text'] = $_LANG['back_list'];
		$link[1]['href'] = 'category_store.php?act=list';
		sys_msg($_LANG['catadd_succed'], 0, $link);
	}
}

if ($_REQUEST['act'] == 'edit') {
	admin_priv('cat_manage');
	$cat_id = intval($_REQUEST['cat_id']);
	$cat_info = get_cat_info($cat_id, array(), 'merchants_category');
	$attr_list = get_attr_list();
	$filter_attr_list = array();
	$select_category_html = '';
	$parent_cat_list = get_seller_select_category($cat_id, 1, false, $cat_info['user_id']);

	for ($i = 0; $i < count($parent_cat_list); $i++) {
		$select_category_html .= insert_seller_select_category(pos($parent_cat_list), next($parent_cat_list), $i, 'cat_parent_id', 0, 'merchants_category', array(), $cat_info['user_id']);
	}

	$smarty->assign('select_category_html', $select_category_html);
	$parent_and_rank = (empty($cat_info['parent_id']) ? '0_0' : $cat_info['parent_id'] . '_' . (count($parent_cat_list) - 2));
	$smarty->assign('parent_and_rank', $parent_and_rank);
	if (isset($cat_info['filter_attr']) && $cat_info['filter_attr']) {
		$filter_attr = explode(',', $cat_info['filter_attr']);

		foreach ($filter_attr as $k => $v) {
			$attr_cat_id = $db->getOne('SELECT cat_id FROM ' . $ecs->table('attribute') . ' WHERE attr_id = \'' . intval($v) . '\'');
			$filter_attr_list[$k]['goods_type_list'] = goods_type_list($attr_cat_id);
			$filter_attr_list[$k]['filter_attr'] = $v;
			$attr_option = array();
			if (isset($attr_list[$attr_cat_id]) && $attr_list[$attr_cat_id]) {
				foreach ($attr_list[$attr_cat_id] as $val) {
					$attr_option[key($val)] = current($val);
				}
			}

			$filter_attr_list[$k]['option'] = $attr_option;
		}

		$smarty->assign('filter_attr_list', $filter_attr_list);
	}
	else {
		$attr_cat_id = 0;
	}

	if (isset($cat_info['parent_id']) && ($cat_info['parent_id'] == 0)) {
		$cat_name_arr = explode('、', $cat_info['cat_name']);
		$smarty->assign('cat_name_arr', $cat_name_arr);
	}

	$smarty->assign('attr_list', $attr_list);
	$smarty->assign('attr_cat_id', $attr_cat_id);
	$smarty->assign('ur_here', $_LANG['category_edit']);
	$smarty->assign('action_link', array('text' => $_LANG['03_category_list'], 'href' => 'category_store.php?act=list'));
	$res = $db->getAll('SELECT recommend_type FROM ' . $ecs->table('cat_recommend') . ' WHERE cat_id=' . $cat_id);

	if (!empty($res)) {
		$cat_recommend = array();

		foreach ($res as $data) {
			$cat_recommend[$data['recommend_type']] = 1;
		}

		$smarty->assign('cat_recommend', $cat_recommend);
	}

	$sql = 'select dt_id, dt_title from ' . $ecs->table('merchants_documenttitle') . ' where cat_id = \'' . $cat_id . '\'';
	$title_list = $db->getAll($sql);
	$smarty->assign('title_list', $title_list);
	$smarty->assign('cat_id', $cat_id);
	$smarty->assign('cat_info', $cat_info);
	$smarty->assign('form_act', 'update');
	$smarty->assign('goods_type_list', goods_type_list(0));
	assign_query_info();
	$smarty->display('category_store_info.dwt');
}
else if ($_REQUEST['act'] == 'add_category') {
	$parent_id = (empty($_REQUEST['parent_id']) ? 0 : intval($_REQUEST['parent_id']));
	$category = (empty($_REQUEST['cat']) ? '' : json_str_iconv(trim($_REQUEST['cat'])));

	if (cat_exists($category, $parent_id)) {
		make_json_error($_LANG['catname_exist']);
	}
	else {
		$sql = 'INSERT INTO ' . $ecs->table('merchants_category') . '(cat_name, parent_id, is_show)' . 'VALUES ( \'' . $category . '\', \'' . $parent_id . '\', 1)';
		$db->query($sql);
		$category_id = $db->insert_id();
		$arr = array('parent_id' => $parent_id, 'id' => $category_id, 'cat' => $category);
		clear_cache_files();
		make_json_result($arr);
	}
}

if ($_REQUEST['act'] == 'update') {
	admin_priv('cat_manage');
	$cat_id = (!empty($_POST['cat_id']) ? intval($_POST['cat_id']) : 0);
	$old_cat_name = $_POST['old_cat_name'];
	$cat['parent_id'] = isset($_POST['parent_id']) ? trim($_POST['parent_id']) : '0_-1';
	$parent_id = explode('_', $cat['parent_id']);
	$cat['parent_id'] = intval($parent_id[0]);
	$cat['sort_order'] = !empty($_POST['sort_order']) ? intval($_POST['sort_order']) : 0;
	$cat['keywords'] = !empty($_POST['keywords']) ? trim($_POST['keywords']) : '';
	$cat['cat_desc'] = !empty($_POST['cat_desc']) ? $_POST['cat_desc'] : '';
	$cat['measure_unit'] = !empty($_POST['measure_unit']) ? trim($_POST['measure_unit']) : '';
	$cat['cat_name'] = !empty($_POST['cat_name']) ? trim($_POST['cat_name']) : '';
	$pin = new pin();
	$pinyin = $pin->Pinyin($cat['cat_name'], 'UTF8');
	$cat['pinyin_keyword'] = $pinyin;
	$cat['is_show'] = !empty($_POST['is_show']) ? intval($_POST['is_show']) : 0;
	$cat['is_top_show'] = !empty($_POST['is_top_show']) ? intval($_POST['is_top_show']) : 0;
	$cat['is_top_style'] = !empty($_POST['is_top_style']) ? intval($_POST['is_top_style']) : 0;
	$cat['show_in_nav'] = !empty($_POST['show_in_nav']) ? intval($_POST['show_in_nav']) : 0;
	$cat['style'] = !empty($_POST['style']) ? trim($_POST['style']) : '';
	$cat['grade'] = !empty($_POST['grade']) ? intval($_POST['grade']) : 0;
	$cat['filter_attr'] = !empty($_POST['filter_attr']) ? implode(',', array_unique(array_diff($_POST['filter_attr'], array(0)))) : 0;
	$cat['cat_recommend'] = !empty($_POST['cat_recommend']) ? $_POST['cat_recommend'] : array();

	if ($cat['cat_name'] != $old_cat_name) {
		if (cat_exists($cat['cat_name'], $cat['parent_id'], $cat_id, $adminru['ru_id'])) {
			$link[] = array('text' => $_LANG['go_back'], 'href' => 'javascript:history.back(-1)');
			sys_msg($_LANG['catname_exist'], 0, $link);
		}
	}

	$children = get_array_keys_cat($cat_id, 0, 'merchants_category');

	if (in_array($cat['parent_id'], $children)) {
		$link[] = array('text' => $_LANG['go_back'], 'href' => 'javascript:history.back(-1)');
		sys_msg($_LANG['is_leaf_error'], 0, $link);
	}

	if ((10 < $cat['grade']) || ($cat['grade'] < 0)) {
		$link[] = array('text' => $_LANG['go_back'], 'href' => 'javascript:history.back(-1)');
		sys_msg($_LANG['grade_error'], 0, $link);
	}

	if ($db->autoExecute($ecs->table('merchants_category'), $cat, 'UPDATE', 'cat_id=\'' . $cat_id . '\'')) {
		if ($cat['cat_name'] != $dat['cat_name']) {
			$sql = 'UPDATE ' . $ecs->table('merchants_nav') . ' SET name = \'' . $cat['cat_name'] . '\' WHERE ctype = \'c\' AND cid = \'' . $cat_id . '\' AND type = \'middle\'';
			$db->query($sql);
		}

		$nid = $db->getOne('SELECT id FROM ' . $ecs->table('merchants_nav') . ' WHERE cid = \'' . $cat_id . '\'');

		if ($nid) {
			if ($cat['show_in_nav'] == 1) {
				$sql = 'UPDATE ' . $ecs->table('merchants_nav') . ' SET ifshow = 1, cat_id = \'' . $cat_id . '\' WHERE ctype = \'c\' AND cid = \'' . $cat_id . '\' AND type = \'middle\'';
				$db->query($sql);
			}
			else {
				$sql = 'UPDATE ' . $ecs->table('merchants_nav') . ' SET ifshow = 0 WHERE ctype = \'c\' AND cid = \'' . $cat_id . '\' AND type = \'middle\'';
				$db->query($sql);
			}
		}
		else if ($cat['show_in_nav'] == 1) {
			$vieworder = $db->getOne('SELECT max(vieworder) FROM ' . $ecs->table('merchants_nav') . ' WHERE type = \'middle\'');
			$vieworder += 2;
			$uri = build_uri('merchants_store', array('urid' => $user_id, 'cid' => $cat_id), $cat['cat_name']);
			$sql = 'INSERT INTO ' . $ecs->table('merchants_nav') . ' (name,ctype,cid,ru_id,ifshow,vieworder,opennew,url,type) VALUES(\'' . $cat['cat_name'] . '\', \'c\', \'' . $cat_id . '\', \'' . $adminru['ru_id'] . '\',\'1\',\'' . $vieworder . '\',\'0\', \'' . $uri . '\',\'middle\')';
			$db->query($sql);
		}

		clear_cache_files();
		admin_log($_POST['cat_name'], 'edit', 'merchants_category');
		$link[] = array('text' => $_LANG['back_list'], 'href' => 'category_store.php?act=list');
		sys_msg($_LANG['catedit_succed'], 0, $link);
	}
}

if ($_REQUEST['act'] == 'move') {
	admin_priv('cat_drop');
	$cat_id = (!empty($_REQUEST['cat_id']) ? intval($_REQUEST['cat_id']) : 0);
	$smarty->assign('ur_here', $_LANG['move_goods']);
	$smarty->assign('action_link', array('href' => 'category_store.php?act=list', 'text' => $_LANG['03_store_category_list']));
	$cat_list = cat_list($cat_id, 0, 0, 'merchants_category', array(), 0, $user_id);
	$smarty->assign('cat_list', $cat_list);
	$smarty->assign('file_name', 'category_store');
	$smarty->assign('is_table', 1);
	$smarty->assign('form_act', 'move_cat');
	assign_query_info();
	$smarty->display('category_move.dwt');
}

if ($_REQUEST['act'] == 'move_cat') {
	admin_priv('cat_drop');
	$cat_id = (!empty($_POST['cat_id']) ? intval($_POST['cat_id']) : 0);
	$target_cat_id = (!empty($_POST['target_cat_id']) ? intval($_POST['target_cat_id']) : 0);
	if (($cat_id == 0) || ($target_cat_id == 0)) {
		$link[] = array('text' => $_LANG['go_back'], 'href' => 'category_store.php?act=move');
		sys_msg($_LANG['cat_move_empty'], 0, $link);
	}

	$sql = 'UPDATE ' . $ecs->table('goods') . ' SET user_cat = \'' . $target_cat_id . '\' ' . 'WHERE user_cat = \'' . $cat_id . '\' AND user_id = \'' . $adminru['ru_id'] . '\'';

	if ($db->query($sql)) {
		clear_cache_files();
		$link[] = array('text' => $_LANG['go_back'], 'href' => 'category_store.php?act=list');
		sys_msg($_LANG['move_cat_success'], 0, $link);
	}
}

if ($_REQUEST['act'] == 'edit_sort_order') {
	check_authz_json('cat_manage');
	$id = intval($_POST['id']);
	$val = intval($_POST['val']);

	if (cat_update($id, array('sort_order' => $val))) {
		clear_cache_files();
		make_json_result($val);
	}
	else {
		make_json_error($db->error());
	}
}

if ($_REQUEST['act'] == 'edit_measure_unit') {
	check_authz_json('cat_manage');
	$id = intval($_POST['id']);
	$val = json_str_iconv($_POST['val']);

	if (cat_update($id, array('measure_unit' => $val))) {
		clear_cache_files();
		make_json_result($val);
	}
	else {
		make_json_error($db->error());
	}
}

if ($_REQUEST['act'] == 'edit_grade') {
	check_authz_json('cat_manage');
	$id = intval($_POST['id']);
	$val = intval($_POST['val']);
	if ((10 < $val) || ($val < 0)) {
		make_json_error($_LANG['grade_error']);
	}

	if (cat_update($id, array('grade' => $val))) {
		clear_cache_files();
		make_json_result($val);
	}
	else {
		make_json_error($db->error());
	}
}

if ($_REQUEST['act'] == 'toggle_show_in_nav') {
	check_authz_json('cat_manage');
	$id = intval($_POST['id']);
	$val = intval($_POST['val']);

	if (cat_update($id, array('show_in_nav' => $val)) != false) {
		if ($val == 1) {
			$vieworder = $db->getOne('SELECT max(vieworder) FROM ' . $ecs->table('merchants_nav') . ' WHERE type = \'middle\'');
			$vieworder += 2;
			$catname = $db->getOne('SELECT cat_name FROM ' . $ecs->table('merchants_category') . ' WHERE cat_id = \'' . $id . '\'');
			$_CFG['rewrite'] = 0;
			$uri = build_uri('merchants_store', array('cid' => $id, 'urid' => $adminru['ru_id']), $catname);
			$nid = $db->getOne('SELECT id FROM ' . $ecs->table('merchants_nav') . ' WHERE ctype = \'c\' AND cid = \'' . $id . '\' AND type = \'middle\'');

			if (empty($nid)) {
				$sql = 'INSERT INTO ' . $ecs->table('merchants_nav') . ' (name,ctype,cid,ifshow,vieworder,opennew,url,type) VALUES(\'' . $catname . '\', \'c\', \'' . $id . '\',\'1\',\'' . $vieworder . '\',\'0\', \'' . $uri . '\',\'middle\')';
			}
			else {
				$sql = 'UPDATE ' . $ecs->table('merchants_nav') . ' SET ifshow = 1 WHERE ctype = \'c\' AND cid = \'' . $id . '\' AND type = \'middle\'';
			}

			$db->query($sql);
		}
		else {
			$db->query('UPDATE ' . $ecs->table('merchants_nav') . 'SET ifshow = 0 WHERE ctype = \'c\' AND cid = \'' . $id . '\' AND type = \'middle\'');
		}

		clear_cache_files();
		make_json_result($val);
	}
	else {
		make_json_error($db->error());
	}
}

if ($_REQUEST['act'] == 'toggle_is_show') {
	check_authz_json('cat_manage');
	$id = intval($_POST['id']);
	$val = intval($_POST['val']);

	if (cat_update($id, array('is_show' => $val)) != false) {
		clear_cache_files();
		make_json_result($val);
	}
	else {
		make_json_error($db->error());
	}
}

if ($_REQUEST['act'] == 'remove') {
	check_authz_json('cat_manage');
	$cat_id = intval($_GET['id']);
	$cat_name = $db->getOne('SELECT cat_name FROM ' . $ecs->table('merchants_category') . ' WHERE cat_id=\'' . $cat_id . '\'');
	$cat_count = $db->getOne('SELECT COUNT(*) FROM ' . $ecs->table('merchants_category') . ' WHERE parent_id=\'' . $cat_id . '\'');
	$goods_count = $db->getOne('SELECT COUNT(*) FROM ' . $ecs->table('goods') . ' WHERE user_cat = \'' . $cat_id . '\'');
	if (($cat_count == 0) && ($goods_count == 0)) {
		$sql = 'DELETE FROM ' . $ecs->table('merchants_category') . ' WHERE cat_id = \'' . $cat_id . '\'';

		if ($db->query($sql)) {
			$db->query('DELETE FROM ' . $ecs->table('merchants_nav') . 'WHERE ctype = \'c\' AND cid = \'' . $cat_id . '\' AND type = \'middle\'');
			clear_cache_files();
			admin_log($cat_name, 'remove', 'merchants_category');
		}
	}
	else {
		make_json_error($cat_name . ' ' . $_LANG['cat_isleaf']);
	}

	$url = 'category_store.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
	ecs_header('Location: ' . $url . "\n");
	exit();
}

if ($_REQUEST['act'] == 'title_remove') {
	check_authz_json('cat_manage');
	$dt_id = intval($_GET['dt_id']);
	$cat_id = intval($_GET['cat_id']);
	$sql = 'delete from ' . $ecs->table('merchants_documenttitle') . ' where dt_id = \'' . $dt_id . '\'';
	$db->query($sql);
	$url = 'category_store.php?act=titleFileView&cat_id=' . $cat_id;
	ecs_header('Location: ' . $url . "\n");
	exit();
}

?>
