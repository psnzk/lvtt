<?php
//zend  QQ:2172298892
function get_pzd_list($ru_id)
{
	$result = get_filter();

	if ($result === false) {
		$filter['album_mame'] = empty($_REQUEST['album_mame']) ? '' : trim($_REQUEST['album_mame']);
		$where = ' WHERE 1 ';

		if ($filter['album_mame']) {
			$where .= ' AND ga.album_mame LIKE \'%' . mysql_like_quote($filter['album_mame']) . '%\'';
		}

		if (0 < $ru_id) {
			$where .= ' AND ga.ru_id = \'' . $ru_id . '\'';
		}

		$sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('gallery_album') . ' AS ga' . $where;
		$filter['record_count'] = $GLOBALS['db']->getOne($sql);
		$filter = page_and_size($filter);
		$sql = 'SELECT album_id,ru_id,album_mame,album_cover,album_desc,sort_order FROM' . $GLOBALS['ecs']->table('gallery_album') . ' AS ga' . '  ' . $where . ' ORDER BY sort_order ASC LIMIT ' . $filter['start'] . ',' . $filter['page_size'];
		$filter['keywords'] = stripslashes($filter['keywords']);
		set_filter($filter, $sql);
	}
	else {
		$sql = $result['sql'];
		$filter = $result['filter'];
	}

	$row = $GLOBALS['db']->getAll($sql);

	foreach ($row as $k => $v) {
		if (0 < $v['ru_id']) {
			$row[$k]['shop_name'] = get_shop_name($v['ru_id'], 1);
		}
		else {
			$row[$k]['shop_name'] = '商创自营';
		}

		$row[$k]['gallery_count'] = $GLOBALS['db']->getOne('SELECT COUNT(\'pic_id\') FROM' . $GLOBALS['ecs']->table('pic_album') . ' WHERE album_id = \'' . $v['album_id'] . '\'');
	}

	$arr = array('pzd_list' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
	return $arr;
}

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

function get_pic_album($album_id = 0)
{
	$result = get_filter();

	if ($result === false) {
		$filter['album_id'] = $album_id;
		$sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('pic_album') . ' WHERE album_id = \'' . $filter['album_id'] . '\'';
		$filter['record_count'] = $GLOBALS['db']->getOne($sql);
		$filter = page_and_size($filter);
		$sql = 'SELECT pic_id,ru_id,album_id,pic_name,pic_file,pic_size,pic_spec FROM' . $GLOBALS['ecs']->table('pic_album') . ' WHERE album_id = \'' . $filter['album_id'] . '\' ORDER BY pic_id ASC LIMIT ' . $filter['start'] . ',20';
		$filter['keywords'] = stripslashes($filter['keywords']);
		set_filter($filter, $sql);
	}
	else {
		$sql = $result['sql'];
		$filter = $result['filter'];
	}

	$row = $GLOBALS['db']->getAll($sql);

	foreach ($row as $k => $v) {
		if (0 < $v['pic_size']) {
			$row[$k]['pic_size'] = number_format($v['pic_size'] / 1024, 2) . 'k';
		}
	}

	$arr = array('pzd_list' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
	return $arr;
}

define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';
require_once ROOT_PATH . 'includes/cls_image.php';
$exc = new exchange($ecs->table('gallery_album'), $db, 'album_id', 'album_mame');
$adminru = get_admin_ru_id();
$smarty->assign('priv_ru', 1);
$smarty->assign('menu_select', array('action' => '02_cat_and_goods', 'current' => 'gallery_album'));
$allow_file_types = '|GIF|JPG|PNG|';

if ($_REQUEST['act'] == 'list') {
	$smarty->assign('ur_here', $_LANG['gallery_album']);
	$smarty->assign('action_link', array('text' => $_LANG['add_album'], 'href' => 'gallery_album.php?act=add'));
	$store_list = get_common_store_list();
	$smarty->assign('store_list', $store_list);
	$offline_store = get_pzd_list($adminru['ru_id']);
	$smarty->assign('gallery_album', $offline_store['pzd_list']);
	$smarty->assign('filter', $offline_store['filter']);
	$smarty->assign('record_count', $offline_store['record_count']);
	$smarty->assign('page_count', $offline_store['page_count']);
	$smarty->assign('full_page', 1);
	$smarty->display('gallery_album.dwt');
}
else if ($_REQUEST['act'] == 'query') {
	$offline_store = get_pzd_list($adminru['ru_id']);
	$smarty->assign('gallery_album', $offline_store['pzd_list']);
	$smarty->assign('filter', $offline_store['filter']);
	$smarty->assign('record_count', $offline_store['record_count']);
	$smarty->assign('page_count', $offline_store['page_count']);
	make_json_result($smarty->fetch('gallery_album.dwt'), '', array('filter' => $offline_store['filter'], 'page_count' => $offline_store['page_count']));
}
else {
	if (($_REQUEST['act'] == 'add') || ($_REQUEST['act'] == 'edit')) {
		$smarty->assign('ur_here', $_LANG['add_album']);
		$smarty->assign('action_link', array('text' => $_LANG['gallery_album'], 'href' => 'gallery_album.php?act=list'));
		$album_id = (isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0);

		if (0 < $album_id) {
			$sql = 'SELECT album_id,album_mame,album_cover,album_desc,sort_order FROM' . $ecs->table('gallery_album') . ' WHERE album_id = \'' . $album_id . '\'';
			$album_info = $db->getRow($sql);
			$smarty->assign('album_info', $album_info);
		}

		$form_action = ($_REQUEST['act'] == 'add' ? 'insert' : 'update');
		$smarty->assign('form_action', $form_action);
		$smarty->display('gallery_album_info.dwt');
	}
	else {
		if (($_REQUEST['act'] == 'insert') || ($_REQUEST['act'] == 'update')) {
			$album_mame = (isset($_REQUEST['album_mame']) ? addslashes($_REQUEST['album_mame']) : '');
			$album_desc = (isset($_REQUEST['album_desc']) ? addslashes($_REQUEST['album_desc']) : '');
			$sort_order = (isset($_REQUEST['sort_order']) ? intval($_REQUEST['sort_order']) : 50);

			if ($_REQUEST['act'] == 'insert') {
				$is_only = $exc->is_only('album_mame', $album_mame, 0, 'ru_id = ' . $adminru['ru_id']);

				if (!$is_only) {
					sys_msg(sprintf($_LANG['title_exist'], stripslashes($album_mame)), 1);
				}

				$file_url = '';
				if ((isset($_FILES['album_cover']['error']) && ($_FILES['album_cover']['error'] == 0)) || (!isset($_FILES['album_cover']['error']) && isset($_FILES['album_cover']['tmp_name']) && ($_FILES['album_cover']['tmp_name'] != 'none'))) {
					if (!check_file_type($_FILES['album_cover']['tmp_name'], $_FILES['album_cover']['name'], $allow_file_types)) {
						sys_msg($_LANG['invalid_file']);
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

				if ($db->query($sql) == true) {
					$link[0]['text'] = $_LANG['continue_add_album'];
					$link[0]['href'] = 'gallery_album.php?act=add';
					$link[1]['text'] = $_LANG['bank_list'];
					$link[1]['href'] = 'gallery_album.php?act=list';
					sys_msg($_LANG['add_succeed'], 0, $link);
				}
			}
			else {
				$album_id = (isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0);
				$is_only = $exc->is_only('album_mame', $album_mame, 0, 'ru_id = ' . $adminru['ru_id'] . ' AND album_id != \'' . $album_id . '\'');

				if (!$is_only) {
					sys_msg(sprintf($_LANG['title_exist'], stripslashes($album_mame)), 1);
				}

				$file_url = '';
				if ((isset($_FILES['album_cover']['error']) && ($_FILES['album_cover']['error'] == 0)) || (!isset($_FILES['album_cover']['error']) && isset($_FILES['album_cover']['tmp_name']) && ($_FILES['album_cover']['tmp_name'] != 'none'))) {
					if (!check_file_type($_FILES['album_cover']['tmp_name'], $_FILES['album_cover']['name'], $allow_file_types)) {
						sys_msg($_LANG['invalid_file']);
					}

					$res = upload_article_file($_FILES['album_cover']);

					if ($res != false) {
						$file_url = $res;
					}
				}

				if ($file_url == '') {
					$file_url = $_POST['file_url'];
				}

				$sql = 'SELECT album_cover FROM ' . $ecs->table('gallery_album') . ' WHERE album_id = \'' . $album_id . '\'';
				$old_url = $db->getOne($sql);
				if (($old_url != '') && ($old_url != $file_url) && (strpos($old_url, 'http: ') === false) && (strpos($old_url, 'https: ') === false)) {
					@unlink(ROOT_PATH . $old_url);
				}

				$sql = 'UPDATE' . $ecs->table('gallery_album') . ' SET album_mame=\'' . $album_mame . '\',album_cover=\'' . $file_url . '\'' . ',album_desc=\'' . $album_desc . '\',sort_order=\'' . $sort_order . '\' WHERE album_id = \'' . $album_id . '\'';

				if ($db->query($sql) == true) {
					$link[0]['text'] = $_LANG['bank_list'];
					$link[0]['href'] = 'gallery_album.php?act=list';
					sys_msg($_LANG['edit_succeed'], 0, $link);
				}
			}
		}
		else if ($_REQUEST['act'] == 'view') {
			$album_id = (isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0);
			$sql = 'SELECT album_mame FROM ' . $ecs->table('gallery_album') . ' WHERE album_id = \'' . $album_id . '\'';
			$album_mame = $db->getOne($sql);
			$smarty->assign('ur_here', sprintf($_LANG['view_pic'], stripslashes($album_mame)));
			$smarty->assign('action_link', array('text' => '上传图片', 'spec' => 'ectype=\'addpic_album\''));
			$smarty->assign('album_id', $album_id);
			$sql = 'SELECT album_id,album_mame FROM ' . $GLOBALS['ecs']->table('gallery_album') . ' WHERE ru_id = \'' . $adminru['ru_id'] . '\'';
			$album_list = $db->getAll($sql);
			$smarty->assign('album_list', $album_list);
			$offline_store = get_pic_album($album_id);
			$smarty->assign('pic_album', $offline_store['pzd_list']);
			$smarty->assign('filter', $offline_store['filter']);
			$smarty->assign('record_count', $offline_store['record_count']);
			$smarty->assign('page_count', $offline_store['page_count']);
			$smarty->assign('full_page', 1);
			$smarty->display('pic_album.dwt');
		}
		else if ($_REQUEST['act'] == 'pic_query') {
			$album_id = (isset($_REQUEST['album_id']) ? intval($_REQUEST['album_id']) : 0);
			$offline_store = get_pic_album($album_id);
			$smarty->assign('pic_album', $offline_store['pzd_list']);
			$smarty->assign('filter', $offline_store['filter']);
			$smarty->assign('record_count', $offline_store['record_count']);
			$smarty->assign('page_count', $offline_store['page_count']);
			make_json_result($smarty->fetch('pic_album.dwt'), '', array('filter' => $offline_store['filter'], 'page_count' => $offline_store['page_count']));
		}
		else if ($_REQUEST['act'] == 'remove') {
			$id = intval($_GET['id']);
			$sql = 'SELECT album_cover FROM ' . $ecs->table('gallery_album') . ' WHERE album_id = \'' . $id . '\'';
			$old_url = $db->getOne($sql);
			if (($old_url != '') && (@strpos($old_url, 'http://') === false) && (@strpos($old_url, 'https://') === false)) {
				@unlink(ROOT_PATH . $old_url);
			}

			$exc->drop($id);
			$url = 'gallery_album.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
			ecs_header('Location: ' . $url . "\n");
			exit();
		}
		else if ($_REQUEST['act'] == 'pic_remove') {
			require ROOT_PATH . '/includes/cls_json.php';
			$json = new JSON();
			$result = array('error' => '', 'content' => '', 'url' => '');
			$id = (isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0);
			$sql = 'SELECT pic_file,pic_thumb,album_id FROM ' . $ecs->table('pic_album') . ' WHERE pic_id = \'' . $id . '\'';
			$pic_info = $db->getRow($sql);
			if (($pic_info['pic_file'] != '') && (@strpos($pic_info['pic_file'], 'http://') === false) && (@strpos($pic_info['pic_file'], 'https://') === false)) {
				@unlink(ROOT_PATH . $pic_info['pic_file']);
			}

			if (($pic_info['pic_thumb'] != '') && (@strpos($pic_info['pic_thumb'], 'http://') === false) && (@strpos($pic_info['pic_thumb'], 'https://') === false)) {
				@unlink(ROOT_PATH . $pic_info['pic_thumb']);
			}

			$sql = 'DELETE FROM' . $ecs->table('pic_album') . ' WHERE pic_id = \'' . $id . '\' ';

			if ($db->query($sql)) {
				$result['error'] = 0;
				$result['id'] = $id;
			}
			else {
				$result['error'] = 1;
				$result['content'] = '系统出错，请重试！';
			}

			exit(json_encode($result));
		}
		else if ($_REQUEST['act'] == 'remove_batch') {
			$checkboxes = (!empty($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : array());

			if (!empty($checkboxes)) {
				$sql = 'SELECT album_cover FROM ' . $ecs->table('gallery_album') . ' WHERE album_id' . db_create_in($checkboxes);
				$album_cover = $db->getAll($sql);

				if (!empty($album_cover)) {
					foreach ($album_cover as $k => $v) {
						if (($v['album_cover'] != '') && (@strpos($v['album_cover'], 'http://') === false) && (@strpos($v['album_cover'], 'https://') === false)) {
							@unlink(ROOT_PATH . $v['album_cover']);
						}
					}
				}

				$sql = 'DELETE FROM' . $ecs->table('gallery_album') . ' WHERE album_id' . db_create_in($checkboxes);

				if ($db->query($sql) == true) {
					$link[] = array('text' => $_LANG['back_list'], 'href' => 'gallery_album.php?act=list&' . list_link_postfix());
					sys_msg($_LANG['delete_succeed'], 0, $link);
				}
			}
			else {
				$link[] = array('text' => $_LANG['back_list'], 'href' => 'gallery_album.php?act=list&' . list_link_postfix());
				sys_msg($_LANG['delete_fail'], 0, $link);
			}
		}
		else if ($_REQUEST['act'] == 'edit_sort_order') {
			$id = intval($_POST['id']);
			$order = json_str_iconv(trim($_POST['val']));

			if ($exc->edit('sort_order = \'' . $order . '\'', $id)) {
				clear_cache_files();
				make_json_result(stripslashes($order));
			}
			else {
				make_json_error($db->error());
			}
		}
		else if ($_REQUEST['act'] == 'upload_pic') {
			include_once ROOT_PATH . '/includes/cls_image.php';
			$image = new cls_image($_CFG['bgcolor']);
			require_once ROOT_PATH . '/' . ADMIN_PATH . '/includes/lib_goods.php';
			$result = array('error' => 0, 'pic' => '', 'name' => '');
			$album_id = (isset($_REQUEST['album_id']) ? intval($_REQUEST['album_id']) : 0);
			$goods_img = '';
			$goods_thumb = '';
			$original_img = '';
			$old_original_img = '';
			$file_url = '';
			$pic_name = '';
			$pic_size = 0;
			$proc_thumb = (isset($GLOBALS['shop_id']) && (0 < $GLOBALS['shop_id']) ? false : true);
			if ((isset($_FILES['file']['error']) && ($_FILES['file']['error'] == 0)) || (!isset($_FILES['file']['error']) && isset($_FILES['file']['tmp_name']) && ($_FILES['file']['tmp_name'] != 'none'))) {
				if (!check_file_type($_FILES['file']['tmp_name'], $_FILES['file']['name'], $allow_file_types)) {
					sys_msg($_LANG['invalid_file']);
				}

				$image_name = explode('.', $_FILES['file']['name']);
				$pic_name = $image_name['0'];
				$pic_size = intval($_FILES['file']['size']);
				$dir = 'gallery_album/original_img';
				$original_img = $image->upload_image($_FILES['file'], $dir);
				$goods_img = $original_img;
				if ($proc_thumb && !empty($original_img)) {
					if (($_CFG['thumb_width'] != 0) || ($_CFG['thumb_height'] != 0)) {
						$goods_thumb = $image->make_thumb('../' . $original_img, $GLOBALS['_CFG']['thumb_width'], $GLOBALS['_CFG']['thumb_height'], '../data/gallery_album/thumb_img/');

						if ($goods_thumb === false) {
							sys_msg($image->error_msg(), 1, array(), false);
						}
					}
				}

				$original_img = $original_img;
				$goods_thumb = reformat_image_name('goods_thumb', $album_id, $goods_thumb, 'gallery_thumb');
				$result['data'] = array('original_img' => $original_img, 'goods_thumb' => $goods_thumb);
				list($width, $height, $type, $attr) = getimagesize('../' . $original_img);
				$pic_spec = $width . 'x' . $height;
				$add_time = gmtime();
				$sql = 'SELECT ru_id FROM' . $ecs->table('gallery_album') . ' WHERE album_id = \'' . $album_id . '\'';
				$ru_id = $db->getOne($sql);
				$sql = 'INSERT INTO' . $ecs->table('pic_album') . '(`ru_id`,`album_id`,`pic_name`,`pic_file`,`pic_size`,`pic_spec`,`add_time`,`pic_thumb`) VALUES(\'' . $ru_id . '\',\'' . $album_id . '\',\'' . $pic_name . '\',\'' . $original_img . '\',\'' . $pic_size . '\',\'' . $pic_spec . '\',\'' . $add_time . '\',\'' . $goods_thumb . '\')';

				if ($db->query($sql) == true) {
					$result['error'] = '0';
				}
			}
			else {
				$result['error'] = '1';
				$result['massege'] = '上传有误，清检查服务器配置！';
			}

			exit(json_encode($result));
		}
		else if ($_REQUEST['act'] == 'batch') {
			$checkboxes = (!empty($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : array());
			$old_album_id = (isset($_REQUEST['old_album_id']) ? intval($_REQUEST['old_album_id']) : 0);
			$album_id = (isset($_REQUEST['album_id']) ? intval($_REQUEST['album_id']) : 0);
			$type = (isset($_REQUEST['type']) ? addslashes($_REQUEST['type']) : '');

			if (!empty($checkboxes)) {
				if ($type == 'remove') {
					$sql = 'SELECT pic_file,pic_thumb FROM' . $ecs->table('pic_album') . ' WHERE ru_id=\'' . $adminru['ru_id'] . '\' AND pic_id' . db_create_in($checkboxes);
					$pic_info = $db->getAll($sql);

					if (!empty($pic_info)) {
						foreach ($pic_info as $v) {
							if (($v['pic_file'] != '') && (@strpos($v['pic_file'], 'http://') === false) && (@strpos($v['pic_file'], 'https://') === false)) {
								@unlink(ROOT_PATH . $v['pic_file']);
							}

							if (($v['pic_thumb'] != '') && (@strpos($v['pic_thumb'], 'http://') === false) && (@strpos($v['pic_thumb'], 'https://') === false)) {
								@unlink(ROOT_PATH . $v['pic_thumb']);
							}
						}
					}

					$sql = 'DELETE FROM' . $ecs->table('pic_album') . ' WHERE ru_id=\'' . $adminru['ru_id'] . '\' AND  pic_id' . db_create_in($checkboxes);

					if ($db->query($sql) == true) {
						$link[] = array('text' => $_LANG['bank_list'], 'href' => 'gallery_album.php?act=view&id=' . $old_album_id);
						sys_msg($_LANG['delete_succeed'], 0, $link);
					}
				}
				else if (0 < $album_id) {
					$sql = 'UPDATE' . $ecs->table('pic_album') . ' SET album_id = \'' . $album_id . '\' WHERE ru_id=\'' . $adminru['ru_id'] . '\' AND pic_id' . db_create_in($checkboxes);

					if ($db->query($sql) == true) {
						$link[] = array('text' => $_LANG['bank_list'], 'href' => 'gallery_album.php?act=view&id=' . $old_album_id);
						sys_msg($_LANG['remove_succeed'], 0, $link);
					}
				}
				else {
					$link[] = array('text' => $_LANG['bank_list'], 'href' => 'gallery_album.php?act=view&id=' . $old_album_id);
					sys_msg($_LANG['album_fail'], 1, $link);
				}
			}
			else {
				$link[] = array('text' => $_LANG['bank_list'], 'href' => 'gallery_album.php?act=view&id=' . $old_album_id);
				sys_msg($_LANG['handle_fail'], 1, $link);
			}
		}
	}
}

?>
