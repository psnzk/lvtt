<?php
//dezend by  QQ:2172298892
function create_html($out = '', $cache_id = 0, $cachename = '', $suffix = '')
{
	$smarty = new cls_template();
	$smarty->template_dir = ROOT_PATH . SELLER_PATH . '/templates';
	$smarty->cache_lifetime = $_CFG['cache_time'];
	$smarty->cache_dir = ROOT_PATH . 'data/seller_templates';
	$back = '';

	if ($out) {
		if (0 < $cache_id) {
			$seller_tem = 'seller_tem_' . $cache_id;
		}
		else {
			$seller_tem = 'seller_tem';
		}

		$out = str_replace("\r", '', $out);

		while (strpos($out, "\n\n") !== false) {
			$out = str_replace("\n\n", "\n", $out);
		}

		$hash_dir = $smarty->cache_dir . '/' . $seller_tem . '/' . $suffix;

		if (!is_dir($hash_dir)) {
			mkdir($hash_dir, 511, true);
		}

		if (file_put_contents($hash_dir . '/' . $cachename, $out, LOCK_EX) === false) {
			trigger_error('can\'t write:' . $hash_dir . '/' . $cachename);
			$back = '';
		}
		else {
			$back = $cachename;
		}

		$smarty->template = array();
	}
	else {
		$back = '';
	}

	return $back;
}

function unescape($str)
{
	$ret = '';
	$len = strlen($str);

	for ($i = 0; $i < $len; $i++) {
		if (($str[$i] == '%') && ($str[$i + 1] == 'u')) {
			$val = hexdec(substr($str, $i + 2, 4));

			if ($val < 127) {
				$ret .= chr($val);
			}
			else if ($val < 2048) {
				$ret .= chr(192 | ($val >> 6)) . chr(128 | ($val & 63));
			}
			else {
				$ret .= chr(224 | ($val >> 12)) . chr(128 | (($val >> 6) & 63)) . chr(128 | ($val & 63));
			}

			$i += 5;
		}
		else if ($str[$i] == '%') {
			$ret .= urldecode(substr($str, $i, 3));
			$i += 2;
		}
		else {
			$ret .= $str[$i];
		}
	}

	return $ret;
}

function get_html_file($name)
{
	$smarty = new cls_template();

	if (file_exists($name)) {
		$smarty->_current_file = $name;
		$source = $smarty->fetch_str(file_get_contents($name));
	}
	else {
		$source = '';
	}

	return $source;
}

function get_seller_templates($ru_id = 0, $type = 0, $tem = '')
{
	if ($type == 0) {
		$seller_templates = 'pc_page';
	}
	else {
		$seller_templates = 'pc_html';
	}

	$arr = '';

	if ($tem == '') {
		$sql = 'SELECT seller_templates FROM' . $GLOBALS['ecs']->table('seller_shopinfo') . ' WHERE ru_id=' . $ru_id;
		$arr['tem'] = $GLOBALS['db']->getOne($sql);
		$dir = ROOT_PATH . 'data/seller_templates/seller_tem_' . $ru_id . '/store_tpl_1';
		if (($arr['tem'] == '') || !file_exists($dir . '/pc_page.php')) {
			$file_html = ROOT_PATH . 'data/seller_templates/seller_tem/store_tpl_1';

			if (!is_dir($dir)) {
				mkdir($dir, 511, true);
			}

			recurse_copy($file_html, $dir);
			$sql = 'UPDATE ' . $GLOBALS['ecs']->table('seller_shopinfo') . ' SET seller_templates = \'store_tpl_1\' WHERE ru_id = \'' . $ru_id . '\'';
			$GLOBALS['db']->query($sql);
			$arr['tem'] = 'store_tpl_1';
		}
	}
	else {
		$arr['tem'] = $tem;
	}

	$filename = ROOT_PATH . 'data/seller_templates' . '/seller_tem_' . $ru_id . '/' . $arr['tem'] . '/' . $seller_templates . '.php';
	$arr['out'] = get_html_file($filename);
	return $arr;
}

function get_seller_template_info($template_name, $ru_id = 0)
{
	if (empty($template_style) || ($template_style == '')) {
		$template_style = '';
	}

	if (0 < $ru_id) {
		$seller_tem = 'seller_tem_' . $ru_id;
	}
	else {
		$seller_tem = 'seller_tem';
	}

	$info = array();
	$ext = array('png', 'gif', 'jpg', 'jpeg');
	$info['code'] = $template_name;
	$info['screenshot'] = '';

	foreach ($ext as $val) {
		if (file_exists('../data/seller_templates/' . $seller_tem . '/' . $template_name . '/screenshot.' . $val)) {
			$info['screenshot'] = '../data/seller_templates/' . $seller_tem . '/' . $template_name . '/screenshot.' . $val;
			break;
		}
	}

	foreach ($ext as $val) {
		if (file_exists('../data/seller_templates/' . $seller_tem . '/' . $template_name . '/template.' . $val)) {
			$info['template'] = '../data/seller_templates/' . $seller_tem . '/' . $template_name . '/template.' . $val;
			break;
		}
	}

	$info_path = '../data/seller_templates/' . $seller_tem . '/' . $template_name . '/tpl_info.txt';
	if (file_exists($info_path) && !empty($template_name)) {
		$custom_content = addslashes(iconv('GB2312', 'UTF-8', $info_path));
		$arr = @array_slice(file($info_path), 0, 9);
		$arr[1] = addslashes(iconv('GB2312', 'UTF-8', $arr[1]));
		$arr[2] = addslashes(iconv('GB2312', 'UTF-8', $arr[2]));
		$arr[3] = addslashes(iconv('GB2312', 'UTF-8', $arr[3]));
		$arr[4] = addslashes(iconv('GB2312', 'UTF-8', $arr[4]));
		$arr[5] = addslashes(iconv('GB2312', 'UTF-8', $arr[5]));
		$arr[6] = addslashes(iconv('GB2312', 'UTF-8', $arr[6]));
		$arr[7] = addslashes(iconv('GB2312', 'UTF-8', $arr[7]));
		$arr[8] = addslashes(iconv('GB2312', 'UTF-8', $arr[8]));
		$template_name = explode('：', $arr[1]);
		$template_uri = explode('：', $arr[2]);
		$template_desc = explode('：', $arr[3]);
		$template_version = explode('：', $arr[4]);
		$template_author = explode('：', $arr[5]);
		$author_uri = explode('：', $arr[6]);
		$tpl_dwt_code = explode('：', $arr[7]);
		$win_goods_type = explode('：', $arr[8]);
		$info['name'] = isset($template_name[1]) ? trim($template_name[1]) : '';
		$info['uri'] = isset($template_uri[1]) ? trim($template_uri[1]) : '';
		$info['desc'] = isset($template_desc[1]) ? trim($template_desc[1]) : '';
		$info['version'] = isset($template_version[1]) ? trim($template_version[1]) : '';
		$info['author'] = isset($template_author[1]) ? trim($template_author[1]) : '';
		$info['author_uri'] = isset($author_uri[1]) ? trim($author_uri[1]) : '';
		$info['dwt_code'] = isset($tpl_dwt_code[1]) ? trim($tpl_dwt_code[1]) : '';
		$info['win_goods_type'] = isset($win_goods_type[1]) ? trim($win_goods_type[1]) : '';
		$info['sort'] = substr($info['code'], -1, 1);
	}
	else {
		$info['name'] = '';
		$info['uri'] = '';
		$info['desc'] = '';
		$info['version'] = '';
		$info['author'] = '';
		$info['author_uri'] = '';
		$info['dwt_code'] = '';
		$info['sort'] = '';
	}

	return $info;
}

function object_to_array($obj)
{
	$_arr = (is_object($obj) ? get_object_vars($obj) : $obj);

	if ($_arr) {
		foreach ($_arr as $key => $val) {
			$val = (is_array($val) || is_object($val) ? object_to_array($val) : $val);
			$arr[$key] = $val;
		}
	}
	else {
		$arr = array();
	}

	return $arr;
}

function getleft_attr($type = 0, $ru_id = 0, $tem = '')
{
	$sql = 'SELECT bg_color ,img_file ,if_show,bgrepeat,align FROM' . $GLOBALS['ecs']->table('templates_left') . ' WHERE ru_id = \'' . $ru_id . '\' AND type = \'' . $type . '\' AND seller_templates = \'' . $tem . '\'';
	return $GLOBALS['db']->getRow($sql);
}

function del_DirAndFile($dirName)
{
	if (is_dir($dirName)) {
		if ($handle = opendir($dirName)) {
			while (false !== ($item = readdir($handle))) {
				if (($item != '.') && ($item != '..')) {
					if (is_dir($dirName . '/' . $item)) {
						del_dirandfile($dirName . '/' . $item);
					}
					else {
						unlink($dirName . '/' . $item);
					}
				}
			}

			closedir($handle);
			return rmdir($dirName);
		}
	}
	else {
		return true;
	}
}

function recurse_copy($src, $des)
{
	$dir = opendir($src);

	if (!is_dir($des)) {
		mkdir($des, 511, true);
	}

	while (false !== ($file = readdir($dir))) {
		if (($file != '.') && ($file != '..')) {
			if (is_dir($src . '/' . $file)) {
				recurse_copy($src . '/' . $file, $des . '/' . $file);
			}
			else {
				copy($src . '/' . $file, $des . '/' . $file);
			}
		}
	}

	closedir($dir);
}

function get_new_dirName($ru_id = 0)
{
	$des = ROOT_PATH . 'data/seller_templates/seller_tem_' . $ru_id;
	$res = array();
	$dir = opendir($des);

	while (false !== ($file = readdir($dir))) {
		if (($file != '.') && ($file != '..')) {
			if (is_dir($des . '/' . $file)) {
				$arr = explode('_', $file);

				if ($arr[2]) {
					$res[] = $arr[2];
				}
			}
		}
	}

	closedir($dir);

	if ($res) {
		$suffix = max($res) + 1;
		return 'backup_tpl_' . $suffix;
	}
	else {
		return 'backup_tpl_1';
	}
}


?>
