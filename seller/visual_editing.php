<?php
//zend by QQ:2172298892
define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';
require ROOT_PATH . '/includes/lib_visual.php';
admin_priv('10_visual_editing');
$adminru = get_admin_ru_id();
$smarty->assign('ru_id', $adminru['ru_id']);

if ($_REQUEST['act'] == 'first') {
	$pc_page = get_seller_templates($adminru['ru_id']);
	$str_len = 0 - (str_len(SELLER_PATH) + 1);
	$domain = substr($GLOBALS['ecs']->url(), 0, $str_len);
	$head = getleft_attr('head', $adminru['ru_id'], $pc_page['tem']);
	$content = getleft_attr('content', $adminru['ru_id'], $pc_page['tem']);
	$smarty->assign('pc_page', $pc_page);
	$smarty->assign('head', $head);
	$smarty->assign('content', $content);
	$smarty->assign('domain', $domain);
	$smarty->display('visual_editing.dwt');
}
else if ($_REQUEST['act'] == 'header_bg') {
	include_once ROOT_PATH . '/includes/cls_image.php';
	$image = new cls_image($_CFG['bgcolor']);
	require_once ROOT_PATH . '/' . ADMIN_PATH . '/includes/lib_goods.php';
	$result = array('error' => 0, 'prompt' => '', 'content' => '');
	$type = (isset($_REQUEST['type']) ? addslashes($_REQUEST['type']) : '');
	$name = (isset($_REQUEST['name']) ? addslashes($_REQUEST['name']) : '');
	$suffix = (isset($_REQUEST['suffix']) ? addslashes($_REQUEST['suffix']) : 'store_tpl_1');
	$allow_file_types = '|GIF|JPG|PNG|';

	if ($_FILES[$name]) {
		$file = $_FILES[$name];
		if ((isset($file['error']) && ($file['error'] == 0)) || (!isset($file['error']) && ($file['tmp_name'] != 'none'))) {
			if (!check_file_type($file['tmp_name'], $file['name'], $allow_file_types)) {
				$result['error'] = 1;
				$result['prompt'] = '请上传正确格式图片（' . $allow_file_types）;
			}
			else {
				$ext = array_pop(explode('.', $file['name']));
				$tem = '';

				if ($type == 'headerbg') {
					$tem = '/head';
				}
				else if ($type == 'contentbg') {
					$tem = '/content';
				}

				$file_dir = '../data/seller_templates/seller_tem_' . $adminru['ru_id'] . '/' . $suffix . '/images' . $tem;

				if (!is_dir($file_dir)) {
					mkdir($file_dir, 511, true);
				}

				$bgtype = '';

				if ($type == 'headerbg') {
					$bgtype = 'head';
					$file_name = $file_dir . '/hdfile_' . gmtime() . '.' . $ext;
					$back_name = '/hdfile_' . gmtime() . '.' . $ext;
				}
				else if ($type == 'contentbg') {
					$bgtype = 'content';
					$file_name = $file_dir . '/confile_' . gmtime() . '.' . $ext;
					$back_name = '/confile_' . gmtime() . '.' . $ext;
				}
				else {
					$file_name = $file_dir . '/slide_' . gmtime() . '.' . $ext;
					$back_name = '/slide_' . gmtime() . '.' . $ext;
				}

				if (move_upload_file($file['tmp_name'], $file_name)) {
					$str_len = 0 - (str_len(SELLER_PATH) + 1);
					$url = substr($GLOBALS['ecs']->url(), 0, $str_len);
					$content_file = $file_name;

					if ($bgtype) {
						$sql = 'SELECT id ,img_file FROM' . $ecs->table('templates_left') . ' WHERE ru_id = \'' . $adminru['ru_id'] . '\' AND seller_templates = \'' . $suffix . '\' AND type = \'' . $bgtype . '\'';
						$templates_left = $db->getRow($sql);

						if (0 < $templates_left['id']) {
							if ($templates_left['img_file'] != '') {
								@unlink($templates_left['img_file']);
							}

							$sql = 'UPDATE' . $ecs->table('templates_left') . ' SET img_file = \'' . $content_file . '\' WHERE ru_id = \'' . $adminru['ru_id'] . '\' AND seller_templates = \'' . $suffix . '\' AND id=\'' . $templates_left['id'] . '\' AND type = \'' . $bgtype . '\'';
							$db->query($sql);
						}
						else {
							$sql = 'INSERT INTO' . $ecs->table('templates_left') . ' (`ru_id`,`seller_templates`,`img_file`,`type`) VALUES (\'' . $adminru['ru_id'] . '\',\'' . $suffix . '\',\'' . $content_file . '\',\'' . $bgtype . '\')';
							$db->query($sql);
						}
					}

					$result['error'] = 2;
					$result['content'] = $content_file;
				}
				else {
					$result['error'] = 1;
					$result['prompt'] = '系统错误，请重新上传';
				}
			}
		}
	}
	else {
		$result['error'] = 1;
		$result['prompt'] = '请选择上传的图片';
	}

	exit(json_encode($result));
}
else if ($_REQUEST['act'] == 'file_put_visual') {
	require ROOT_PATH . '/includes/cls_json.php';
	$json = new JSON();
	$result = array('suffix' => '', 'error' => '');
	$content = (isset($_REQUEST['content']) ? unescape($_REQUEST['content']) : '');
	$content = (!empty($content) ? stripslashes($content) : '');
	$content_html = (isset($_REQUEST['content_html']) ? unescape($_REQUEST['content_html']) : '');
	$content_html = (!empty($content_html) ? stripslashes($content_html) : '');
	$head_html = (isset($_REQUEST['head_html']) ? unescape($_REQUEST['head_html']) : '');
	$head_html = (!empty($head_html) ? stripslashes($head_html) : '');
	$suffix = (isset($_REQUEST['suffix']) ? addslashes($_REQUEST['suffix']) : 'store_tpl_1');
	$dir = ROOT_PATH . 'data/seller_templates/seller_tem_' . $adminru['ru_id'] . '/' . $suffix;
	$pc_page_name = 'pc_page.php';
	$pc_html_name = 'pc_html.php';
	$pc_head_name = 'pc_head.php';
	$create_html = create_html($content_html, $adminru['ru_id'], $pc_html_name, $suffix);
	$create = create_html($content, $adminru['ru_id'], $pc_page_name, $suffix);
	$create = create_html($head_html, $adminru['ru_id'], $pc_head_name, $suffix);
	$result['error'] = 0;
	$result['suffix'] = $suffix;
	exit(json_encode($result));
}
else if ($_REQUEST['act'] == 'release') {
	require ROOT_PATH . '/includes/cls_json.php';
	$json = new JSON();
	$result = array('error' => '', 'content' => '');
	$suffix = (isset($_REQUEST['suffix']) ? addslashes($_REQUEST['suffix']) : 'store_tpl_1');
	$dir = ROOT_PATH . 'data/seller_templates/seller_tem_' . $adminru['ru_id'] . '/' . $suffix;
	$type = (isset($_REQUEST['type']) ? intval($_REQUEST['type']) : 0);
	if (!file_exists($dir . '/pc_page.php') || ($type == 1)) {
		$file_html = ROOT_PATH . 'data/seller_templates/seller_tem/' . $suffix;

		if (!is_dir($dir)) {
			mkdir($dir, 511, true);
		}

		recurse_copy($file_html, $dir);
	}

	if ($suffix) {
		$sql = 'UPDATE' . $ecs->table('seller_shopinfo') . ' SET seller_templates = \'' . $suffix . '\' WHERE ru_id = \'' . $adminru['ru_id'] . '\'';

		if ($db->query($sql) == true) {
			$result['error'] = 1;
		}
		else {
			$result['error'] = 0;
			$result['content'] = '系统出错，刷新后重试！';
		}
	}
	else {
		$result['error'] = 0;
		$result['content'] = '请选择模板';
	}

	$result['tem'] = $suffix;
	exit(json_encode($result));
}
else if ($_REQUEST['act'] == 'templates') {
	$sql = 'SELECT seller_templates FROM' . $GLOBALS['ecs']->table('seller_shopinfo') . ' WHERE ru_id=' . $adminru['ru_id'];
	$tem = $GLOBALS['db']->getOne($sql);
	$available_templates = array();
	$default_templates = array();
	$dir = ROOT_PATH . 'data/seller_templates/seller_tem/';
	$template_dir = @opendir($dir);

	while ($file = readdir($template_dir)) {
		if (($file != '.') && ($file != '..') && ($file != '.svn') && ($file != 'index.htm')) {
			$default_templates[] = get_seller_template_info($file);
		}
	}

	$default_templates = get_array_sort($default_templates, 'sort');
	@closedir($template_dir);
	$seller_dir = ROOT_PATH . 'data/seller_templates/seller_tem_' . $adminru['ru_id'] . '/';
	$template_dir = @opendir($seller_dir);

	while ($file = readdir($template_dir)) {
		if (($file != '.') && ($file != '..') && ($file != '.svn') && ($file != 'index.htm')) {
			$available_templates[] = get_seller_template_info($file, $adminru['ru_id']);
		}
	}

	$available_templates = get_array_sort($available_templates, 'sort');
	@closedir($template_dir);
	$smarty->assign('curr_template', get_seller_template_info($tem, $adminru['ru_id'], 1));
	$smarty->assign('available_templates', $available_templates);
	$smarty->assign('default_templates', $default_templates);
	$smarty->assign('default_tem', $tem);
	$smarty->assign('ru_id', $adminru['ru_id']);
	$smarty->display('templates.dwt');
}
else if ($_REQUEST['act'] == 'generate') {
	require ROOT_PATH . '/includes/cls_json.php';
	$json = new JSON();
	$result = array('error' => '', 'content' => '');
	$suffix = (isset($_REQUEST['suffix']) ? addslashes($_REQUEST['suffix']) : 'store_tpl_1');
	$bg_color = (isset($_REQUEST['bg_color']) ? stripslashes($_REQUEST['bg_color']) : '');
	$is_show = (isset($_REQUEST['is_show']) ? intval($_REQUEST['is_show']) : 0);
	$type = (isset($_REQUEST['type']) ? $_REQUEST['type'] : 'hrad');
	$bgshow = (isset($_REQUEST['bgshow']) ? addslashes($_REQUEST['bgshow']) : '');
	$bgalign = (isset($_REQUEST['bgalign']) ? addslashes($_REQUEST['bgalign']) : '');
	$sql = 'SELECT id  FROM' . $ecs->table('templates_left') . ' WHERE ru_id = \'' . $adminru['ru_id'] . '\' AND seller_templates = \'' . $suffix . '\' AND type=\'' . $type . '\'';
	$id = $db->getOne($sql);

	if (0 < $id) {
		$sql = 'UPDATE ' . $ecs->table('templates_left') . ' SET seller_templates = \'' . $suffix . '\',bg_color = \'' . $bg_color . '\' ,if_show = \'' . $is_show . '\',bgrepeat=\'' . $bgshow . '\',align= \'' . $bgalign . '\',type=\'' . $type . '\' WHERE ru_id = \'' . $adminru['ru_id'] . '\' AND seller_templates = \'' . $suffix . '\' AND id=\'' . $id . '\' AND type=\'' . $type . '\'';
	}
	else {
		$sql = 'INSERT INTO ' . $ecs->table('templates_left') . ' (`ru_id`,`seller_templates`,`bg_color`,`if_show`,`bgrepeat`,`align`,`type`) VALUES (\'' . $adminru['ru_id'] . '\',\'' . $suffix . '\',\'' . $bg_color . '\',\'' . $is_show . '\',\'' . $bgshow . '\',\'' . $bgalign . '\',\'' . $type . '\')';
	}

	if ($db->query($sql) == true) {
		$result['error'] = 1;
	}
	else {
		$result['error'] = 2;
		$result['content'] = '系统出错。请重试！！！';
	}

	exit(json_encode($result));
}
else if ($_REQUEST['act'] == 'remove_img') {
	$fileimg = (isset($_REQUEST['fileimg']) ? addslashes($_REQUEST['fileimg']) : '');
	$suffix = (isset($_REQUEST['suffix']) ? addslashes($_REQUEST['suffix']) : '');
	$type = (isset($_REQUEST['type']) ? addslashes($_REQUEST['type']) : '');

	if ($fileimg != '') {
		@unlink($fileimg);
	}

	$sql = 'UPDATE ' . $ecs->table('templates_left') . ' SET img_file = \'\' WHERE ru_id = \'' . $adminru['ru_id'] . '\' AND type = \'' . $type . '\' AND seller_templates = \'' . $suffix . '\'';
	$db->query($sql);
}
else if ($_REQUEST['act'] == 'edit_information') {
	include_once ROOT_PATH . '/includes/cls_image.php';
	$image = new cls_image($_CFG['bgcolor']);
	$id = $adminru['ru_id'];
	$tem = (isset($_REQUEST['tem']) ? addslashes($_REQUEST['tem']) : '');
	$name = (isset($_REQUEST['name']) ? 'tpl name：' . addslashes($_REQUEST['name']) : 'tpl name：');
	$version = (isset($_REQUEST['version']) ? 'version：' . addslashes($_REQUEST['version']) : 'version：');
	$author = (isset($_REQUEST['author']) ? 'author：' . addslashes($_REQUEST['author']) : 'author：');
	$author_url = (isset($_REQUEST['author_url']) ? 'author url：' . $_REQUEST['author_url'] : 'author url：');
	$description = (isset($_REQUEST['description']) ? 'description：' . addslashes($_REQUEST['description']) : 'description：');
	$file_url = '';
	$format = array('png', 'gif', 'jpg');
	$file_dir = '../data/seller_templates/seller_tem_' . $id . '/' . $tem;

	if (!is_dir($file_dir)) {
		mkdir($file_dir, 511, true);
	}

	if ((isset($_FILES['ten_file']['error']) && ($_FILES['ten_file']['error'] == 0)) || (!isset($_FILES['ten_file']['error']) && isset($_FILES['ten_file']['tmp_name']) && ($_FILES['ten_file']['tmp_name'] != 'none'))) {
		if (!check_file_type($_FILES['ten_file']['tmp_name'], $_FILES['ten_file']['name'], $allow_file_types)) {
			sys_msg('图片格式不正确');
		}

		$ext_cover = array_pop(explode('.', $_FILES['ten_file']['name']));
		$file_name = $file_dir . '/';
		$filename = 'screenshot.' . $ext_cover;
		$goods_thumb = $image->make_thumb($_FILES['ten_file']['tmp_name'], 265, 388, $file_name, '', $filename);

		if ($goods_thumb != false) {
			$file_url = $goods_thumb;
		}
	}

	if ($file_url == '') {
		$file_url = $_POST['textfile'];
	}

	if ((isset($_FILES['big_file']['error']) && ($_FILES['big_file']['error'] == 0)) || (!isset($_FILES['big_file']['error']) && isset($_FILES['big_file']['tmp_name']) && ($_FILES['big_file']['tmp_name'] != 'none'))) {
		if (!check_file_type($_FILES['big_file']['tmp_name'], $_FILES['big_file']['name'], $allow_file_types)) {
			sys_msg('图片格式不正确');
		}

		$ext_big = array_pop(explode('.', $_FILES['big_file']['name']));
		$file_name = $file_dir . '/template' . '.' . $ext_big;

		if (move_upload_file($_FILES['big_file']['tmp_name'], $file_name)) {
			$big_file = $file_name;
		}
	}

	$template_dir_img = @opendir($file_dir);

	while ($file = readdir($template_dir_img)) {
		foreach ($format as $val) {
			if (($val != $ext_cover) && ($ext_cover != '')) {
				if (file_exists($file_dir . '/screenshot.' . $val)) {
					@unlink($file_dir . '/screenshot.' . $val);
				}
			}

			if (($val != $ext_big) && ($ext_bug != '')) {
				if (file_exists($file_dir . '/template.' . $val)) {
					@unlink($file_dir . '/template.' . $val);
				}
			}
		}
	}

	@closedir($template_dir_img);
	$end = '------tpl_info------------';
	$tab = "\n";
	$html = $end . $tab . $name . $tab . 'tpl url：' . $file_url . $tab . $description . $tab . $version . $tab . $author . $tab . $author_url . $tab . $end;

	if (file_put_contents($file_dir . '/tpl_info.txt', iconv('UTF-8', 'GB2312', $html), LOCK_EX) === false) {
		$link[0]['text'] = '返回列表';
		$link[0]['href'] = 'visual_editing.php?act=templates';
		sys_msg('\' . ' . $file_dir . ' . \'/tpl_info.txt没有写入权限，请修改权限', 1, $link);
	}
	else {
		$link[0]['text'] = '返回列表';
		$link[0]['href'] = 'visual_editing.php?act=templates';
		sys_msg('修改成功', 0, $link);
	}
}
else if ($_REQUEST['act'] == 'removeTemplate') {
	require ROOT_PATH . '/includes/cls_json.php';
	$json = new JSON();
	$result = array('error' => '', 'content' => '', 'url' => '');
	$code = (isset($_REQUEST['code']) ? addslashes($_REQUEST['code']) : '');
	$ru_id = $adminru['ru_id'];
	$sql = 'SELECT seller_templates FROM' . $GLOBALS['ecs']->table('seller_shopinfo') . ' WHERE ru_id=' . $adminru['ru_id'];
	$default_tem = $GLOBALS['db']->getOne($sql);

	if ($default_tem == $code) {
		$result['error'] = 1;
		$result['content'] = '该模板正在使用中，不能删除！欲删除请先更改模板！';
	}
	else {
		$dir = ROOT_PATH . 'data/seller_templates/seller_tem_' . $ru_id . '/' . $code;
		$rmdir = del_dirandfile($dir);

		if ($rmdir == true) {
			$result['error'] = 0;
			$seller_dir = ROOT_PATH . 'data/seller_templates/seller_tem_' . $adminru['ru_id'] . '/';
			$template_dir = @opendir($seller_dir);

			while ($file = readdir($template_dir)) {
				if (($file != '.') && ($file != '..') && ($file != '.svn') && ($file != 'index.htm')) {
					$available_templates[] = get_seller_template_info($file, $adminru['ru_id']);
				}
			}

			$available_templates = get_array_sort($available_templates, 'sort');
			@closedir($template_dir);
			$smarty->assign('available_templates', $available_templates);
			$sql = 'SELECT seller_templates FROM' . $GLOBALS['ecs']->table('seller_shopinfo') . ' WHERE ru_id=' . $adminru['ru_id'];
			$tem = $GLOBALS['db']->getOne($sql);
			$smarty->assign('default_tem', $tem);
			$smarty->assign('temp', 'backupTemplates');
			$result['content'] = $GLOBALS['smarty']->fetch('library/dialog.lbi');
		}
		else {
			$result['error'] = 1;
			$result['content'] = '系统出错，请重试！';
		}
	}

	exit(json_encode($result));
}
else if ($_REQUEST['act'] == 'defaultTemplate') {
	$code = (isset($_REQUEST['code']) ? addslashes($_REQUEST['code']) : '');
	$dir = ROOT_PATH . 'data/seller_templates/seller_tem_' . $adminru['ru_id'] . '/' . $code;
	$file_html = ROOT_PATH . 'data/seller_templates/seller_tem/' . $code;

	if (!is_dir($dir)) {
		mkdir($dir, 511, true);
	}

	recurse_copy($file_html, $dir);
	ecs_header("Location:visual_editing.php?act=templates\n");
}
else if ($_REQUEST['act'] == 'backupTemplates') {
	require ROOT_PATH . '/includes/cls_json.php';
	include_once ROOT_PATH . '/includes/cls_image.php';
	$json = new JSON();
	$image = new cls_image($_CFG['bgcolor']);
	$result = array('error' => '', 'content' => '');
	$code = (isset($_REQUEST['tem']) ? addslashes($_REQUEST['tem']) : '');
	$type = (isset($_REQUEST['type']) ? intval($_REQUEST['type']) : 0);
	$id = $adminru['ru_id'];
	$name = (isset($_REQUEST['name']) ? 'tpl name：' . addslashes($_REQUEST['name']) : 'tpl name：');
	$version = (isset($_REQUEST['version']) ? 'version：' . addslashes($_REQUEST['version']) : 'version：');
	$author = (isset($_REQUEST['author']) ? 'author：' . addslashes($_REQUEST['author']) : 'author：');
	$author_url = (isset($_REQUEST['author_url']) ? 'author url：' . $_REQUEST['author_url'] : 'author url：');
	$description = (isset($_REQUEST['description']) ? 'description：' . addslashes($_REQUEST['description']) : 'description：');
	$format = array('png', 'gif', 'jpg');

	if ($code) {
		$file_html = ROOT_PATH . 'data/seller_templates/seller_tem_' . $adminru['ru_id'] . '/' . $code;
		$new_dirName = get_new_dirname($adminru['ru_id']);
		$dir = ROOT_PATH . 'data/seller_templates/seller_tem_' . $adminru['ru_id'] . '/' . $new_dirName;

		if (!is_dir($dir)) {
			mkdir($dir, 511, true);
		}

		recurse_copy($file_html, $dir);
		$file_url = '';
		$file_dir = '../data/seller_templates/seller_tem_' . $id . '/' . $new_dirName;

		if (!is_dir($file_dir)) {
			mkdir($file_dir, 511, true);
		}

		if ((isset($_FILES['ten_file']['error']) && ($_FILES['ten_file']['error'] == 0)) || (!isset($_FILES['ten_file']['error']) && isset($_FILES['ten_file']['tmp_name']) && ($_FILES['ten_file']['tmp_name'] != 'none'))) {
			if (!check_file_type($_FILES['ten_file']['tmp_name'], $_FILES['ten_file']['name'], $allow_file_types)) {
				sys_msg('图片格式不正确');
			}

			$ext_cover = array_pop(explode('.', $_FILES['ten_file']['name']));
			$file_name = $file_dir . '/';
			$filename = 'screenshot.' . $ext_cover;
			$goods_thumb = $image->make_thumb($_FILES['ten_file']['tmp_name'], 265, 388, $file_name, '', $filename);

			if ($goods_thumb != false) {
				$file_url = $goods_thumb;
			}
		}

		if ($file_url == '') {
			$file_url = $_POST['textfile'];
		}

		if ((isset($_FILES['big_file']['error']) && ($_FILES['big_file']['error'] == 0)) || (!isset($_FILES['big_file']['error']) && isset($_FILES['big_file']['tmp_name']) && ($_FILES['big_file']['tmp_name'] != 'none'))) {
			if (!check_file_type($_FILES['big_file']['tmp_name'], $_FILES['big_file']['name'], $allow_file_types)) {
				sys_msg('图片格式不正确');
			}

			$ext_big = array_pop(explode('.', $_FILES['big_file']['name']));
			$file_name = $file_dir . '/template' . '.' . $ext_big;

			if (move_upload_file($_FILES['big_file']['tmp_name'], $file_name)) {
				$big_file = $file_name;
			}
		}

		$template_dir_img = @opendir($file_dir);

		while ($file = readdir($template_dir_img)) {
			foreach ($format as $val) {
				if (($val != $ext_cover) && ($ext_cover != '')) {
					if (file_exists($file_dir . '/screenshot.' . $val)) {
						@unlink($file_dir . '/screenshot.' . $val);
					}
				}

				if (($val != $ext_big) && ($ext_bug != '')) {
					if (file_exists($file_dir . '/template.' . $val)) {
						@unlink($file_dir . '/template.' . $val);
					}
				}
			}
		}

		@closedir($template_dir_img);
		$end = '------tpl_info------------';
		$tab = "\n";
		$html = $end . $tab . $name . $tab . 'tpl url：' . $file_url . $tab . $description . $tab . $version . $tab . $author . $tab . $author_url . $tab . $end;
		file_put_contents($file_dir . '/tpl_info.txt', iconv('UTF-8', 'GB2312', $html), LOCK_EX);
		$seller_dir = ROOT_PATH . 'data/seller_templates/seller_tem_' . $adminru['ru_id'] . '/';
		$template_dir = @opendir($seller_dir);

		while ($file = readdir($template_dir)) {
			if (($file != '.') && ($file != '..') && ($file != '.svn') && ($file != 'index.htm')) {
				$available_templates[] = get_seller_template_info($file, $adminru['ru_id']);
			}
		}

		$available_templates = get_array_sort($available_templates, 'sort');
		@closedir($template_dir);
		$smarty->assign('available_templates', $available_templates);
		$sql = 'SELECT seller_templates FROM' . $GLOBALS['ecs']->table('seller_shopinfo') . ' WHERE ru_id=' . $adminru['ru_id'];
		$tem = $GLOBALS['db']->getOne($sql);
		$smarty->assign('default_tem', $tem);
		$smarty->assign('temp', 'backupTemplates');
		$result['content'] = $GLOBALS['smarty']->fetch('library/dialog.lbi');
	}
	else {
		$result['error'] = 1;
		$result['content'] = '请选择备份模板！';
	}

	exit(json_encode($result));
}
else if ($_REQUEST['act'] == 'export_tem') {
	$checkboxes = (!empty($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : array());

	if (!empty($checkboxes)) {
		include_once 'includes/cls_phpzip.php';
		$zip = new PHPZip();
		$dir = ROOT_PATH . 'data/seller_templates/seller_tem_' . $adminru['ru_id'] . '/';
		$dir_zip = $dir;
		$file_mune = array();

		foreach ($checkboxes as $v) {
			if ($v) {
				$addfiletozip = $zip->get_filelist($dir_zip . $v);

				foreach ($addfiletozip as $k => $val) {
					if ($v) {
						$addfiletozip[$k] = $v . '/' . $val;
					}
				}

				$file_mune = array_merge($file_mune, $addfiletozip);
			}
		}

		foreach ($file_mune as $v) {
			if (file_exists($dir . '/' . $v)) {
				$zip->add_file(file_get_contents($dir . '/' . $v), $v);
			}
		}

		header('Cache-Control: max-age=0');
		header('Content-Description: File Transfer');
		header('Content-Disposition: attachment; filename=templates_list.zip');
		header('Content-Type: application/zip');
		header('Content-Transfer-Encoding: binary');
		header('Content-Type: application/unknown');
		exit($zip->file());
	}
	else {
		$link[0]['text'] = '返回列表';
		$link[0]['href'] = 'visual_editing.php?act=templates';
		sys_msg('请选择导出的模板', 1, $link);
	}
}

?>
