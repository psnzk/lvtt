<?php
//dezend by  QQ:2172298892
function get_list_download($goods_sn = '', $warehouse_info = array(), $attr_info, $attr_num)
{
	$goods_date = array('model_attr');
	$where = 'goods_sn = \'' . $goods_sn . '\' and is_delete = 0';
	$model_attr = get_table_date('goods', $where, $goods_date, 2);
	$arr = array();
	if ((0 < count($warehouse_info)) && ($model_attr == 2)) {
		foreach ($attr_info as $k => $v) {
			foreach ($v as $k2 => $v2) {
				if ($k2 == 'attr_values') {
					$attr[] = $v2;
				}
			}
		}

		$comb = combination(array_keys($attr), $attr_num);
		$res = array();

		foreach ($comb as $r) {
			$t = array();

			foreach ($r as $k) {
				$t[] = $attr[$k];
			}

			$res = array_merge($res, attr_group($t));
		}

		foreach ($res as $k => $v) {
			$arr[] = array('goods_sn' => $goods_sn, 'region_name' => $warehouse_info[0], 'attr_value' => $v, 'product_sn' => '', 'product_number' => '');
		}
	}

	return $arr;
}

function get_attribute_list($goods_id = 0)
{
	$sql = 'select a.attr_id, a.attr_name from ' . $GLOBALS['ecs']->table('goods_attr') . ' as ga ' . ' left join ' . $GLOBALS['ecs']->table('attribute') . ' as a on ga.attr_id = a.attr_id' . ' where ga.goods_id = \'' . $goods_id . '\' group by ga.attr_id';
	$res = $GLOBALS['db']->getAll($sql);
	$arr = array();

	foreach ($res as $key => $row) {
		$arr[$key]['attr_name'] = $row['attr_name'];
		$arr[$key]['goods_attr'] = get_goods_attr_list($row['attr_id'], $goods_id);
	}

	return $arr;
}

function get_goods_attr_list($attr_id = 0, $goods_id = 0)
{
	$sql = 'select goods_attr_id, attr_value from ' . $GLOBALS['ecs']->table('goods_attr') . ' where goods_id = \'' . $goods_id . '\' and attr_id = \'' . $attr_id . '\' order by goods_attr_id asc';
	$res = $GLOBALS['db']->getAll($sql);
	$arr = array();

	foreach ($res as $key => $row) {
		$arr[$key]['goods_attr_id'] = $row['goods_attr_id'];
		$arr[$key]['attr_value'] = $row['attr_value'];
	}

	return $arr;
}

define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';
require 'includes/lib_goods.php';

if ($_REQUEST['act'] == 'add') {
	admin_priv('goods_manage');
	$goods_id = (isset($_REQUEST['goods_id']) ? intval($_REQUEST['goods_id']) : 0);
	$area_id = (isset($_REQUEST['area_id']) ? intval($_REQUEST['area_id']) : 0);

	if (0 < $goods_id) {
		$smarty->assign('action_link', array('text' => '返回商品货品详细页', 'href' => 'goods.php?act=product_list&goods_id=' . $goods_id));
	}

	$dir = opendir('../languages');
	$lang_list = array('UTF8' => $_LANG['charset']['utf8'], 'GB2312' => $_LANG['charset']['zh_cn'], 'BIG5' => $_LANG['charset']['zh_tw']);
	$download_list = array();

	while (@$file = readdir($dir)) {
		if (($file != '.') && ($file != '..') && ($file != '.svn') && ($file != '_svn') && (is_dir('../languages/' . $file) == true)) {
			$download_list[$file] = sprintf($_LANG['download_file'], isset($_LANG['charset'][$file]) ? $_LANG['charset'][$file] : $file);
		}
	}

	@closedir($dir);
	$smarty->assign('lang_list', $lang_list);
	$smarty->assign('download_list', $download_list);
	$smarty->assign('goods_id', $goods_id);
	$smarty->assign('warehouse_id', $area_id);
	$attribute_list = get_attribute_list($goods_id);
	$smarty->assign('attribute_list', $attribute_list);
	$goods_date = array('goods_name');
	$where = 'goods_id = \'' . $goods_id . '\'';
	$goods_name = get_table_date('goods', $where, $goods_date, 2);
	$smarty->assign('goods_name', $goods_name);
	$ur_here = $_LANG['13_batch_add'];
	$smarty->assign('ur_here', $ur_here);
	assign_query_info();
	$smarty->assign('menu_select', array('action' => '02_cat_and_goods', 'current' => '01_goods_list'));
	$smarty->display('goods_produts_area_batch.dwt');
}
else if ($_REQUEST['act'] == 'upload') {
	admin_priv('goods_manage');
	$smarty->assign('ur_here', $_LANG['13_batch_add']);

	if ($_FILES['file']['name']) {
		$attr_names = file($_FILES['file']['tmp_name']);
		$attr_names = explode(',', $attr_names[0]);
		$attr_names = array_slice($attr_names, 2, -2);

		foreach ($attr_names as $k => $v) {
			$attr_names[$k] = ecs_iconv('GBK', 'UTF8', $v);
		}

		$attr_num = count($attr_names);
		$line_number = 0;
		$arr = array();
		$goods_list = array();
		$field_list = array_keys($_LANG['upload_product']);

		for ($i = 0; $i < $attr_num; $i++) {
			$field_list[] = 'goods_attr' . $i;
		}

		$field_list[] = 'product_sn';
		$field_list[] = 'product_number';
		$_POST['charset'] = 'GB2312';
		$data = file($_FILES['file']['tmp_name']);

		if (0 < count($data)) {
			foreach ($data as $line) {
				if ($line_number == 0) {
					$line_number++;
					continue;
				}

				if (($_POST['charset'] != 'UTF8') && (strpos(strtolower(EC_CHARSET), 'utf') === 0)) {
					$line = ecs_iconv($_POST['charset'], 'UTF8', $line);
				}

				$arr = array();
				$buff = '';
				$quote = 0;
				$len = strlen($line);

				for ($i = 0; $i < $len; $i++) {
					$char = $line[$i];

					if ('\\' == $char) {
						$i++;
						$char = $line[$i];

						switch ($char) {
						case '"':
							$buff .= '"';
							break;

						case '\'':
							$buff .= '\'';
							break;

						case ',':
							$buff .= ',';
							break;

						default:
							$buff .= '\\' . $char;
							break;
						}
					}
					else if ('"' == $char) {
						if (0 == $quote) {
							$quote++;
						}
						else {
							$quote = 0;
						}
					}
					else if (',' == $char) {
						if (0 == $quote) {
							if (!isset($field_list[count($arr)])) {
								continue;
							}

							$field_name = $field_list[count($arr)];
							$arr[$field_name] = trim($buff);
							$buff = '';
							$quote = 0;
						}
						else {
							$buff .= $char;
						}
					}
					else {
						$buff .= $char;
					}

					if ($i == ($len - 1)) {
						if (!isset($field_list[count($arr)])) {
							continue;
						}

						$field_name = $field_list[count($arr)];
						$arr[$field_name] = trim($buff);
					}
				}

				$goods_list[] = $arr;
			}

			$goods_list = get_produts_area_list2($goods_list, $attr_num);
		}
	}

	$_SESSION['goods_list'] = $goods_list;
	$smarty->assign('full_page', 2);
	$smarty->assign('page', 1);
	$smarty->assign('attr_names', $attr_names);
	assign_query_info();
	$smarty->assign('ur_here', '地区属性批量上传');
	$smarty->assign('menu_select', array('action' => '02_cat_and_goods', 'current' => '01_goods_list'));
	$smarty->display('goods_produts_area_batch_add.dwt');
}
else if ($_REQUEST['act'] == 'ajax_insert') {
	include_once ROOT_PATH . 'includes/cls_json.php';
	$json = new JSON();
	$page = (!empty($_REQUEST['page']) ? intval($_REQUEST['page']) : 1);
	$page_size = (isset($_REQUEST['page_size']) ? intval($_REQUEST['page_size']) : 1);
	@set_time_limit(300);
	if (isset($_SESSION['goods_list']) && $_SESSION['goods_list']) {
		$commission_list = $_SESSION['goods_list'];
		$commission_list = $ecs->page_array($page_size, $page, $commission_list);
		$result['list'] = $commission_list['list'][0];
		$result['page'] = $commission_list['filter']['page'] + 1;
		$result['page_size'] = $commission_list['filter']['page_size'];
		$result['record_count'] = $commission_list['filter']['record_count'];
		$result['page_count'] = $commission_list['filter']['page_count'];
		$result['is_stop'] = 1;

		if ($commission_list['filter']['page_count'] < $page) {
			$result['is_stop'] = 0;
		}

		$sql = 'select product_id from ' . $GLOBALS['ecs']->table('products_area') . ' where goods_id = \'' . $result['list']['goods_id'] . '\'' . ' and goods_attr = \'' . $result['list']['goods_attr'] . '\'' . ' and area_id = \'' . $result['list']['area_id'] . '\'';
		$res = $GLOBALS['db']->getOne($sql);

		if ($GLOBALS['db']->getOne($sql)) {
			$result['status_lang'] = '<span style="color: red;">数据已存在</span>';
		}
		else if ($result['is_stop']) {
			$other = array('goods_id' => $result['list']['goods_id'], 'goods_attr' => $result['list']['goods_attr'], 'product_sn' => $result['list']['product_sn'], 'product_number' => $result['list']['product_number'], 'area_id' => $result['list']['area_id']);
			$db->autoExecute($ecs->table('products_area'), $other, 'INSERT');

			if ($db->insert_id()) {
				$result['status_lang'] = '添加成功';
			}
			else {
				$result['status_lang'] = '添加失败';
			}
		}
	}

	exit($json->encode($result));
}
else if ($_REQUEST['act'] == 'download') {
	admin_priv('goods_manage');
	$goods_id = (isset($_REQUEST['goods_id']) ? intval($_REQUEST['goods_id']) : 0);
	$area_id = (isset($_SESSION['product_area']) ? intval($_SESSION['product_area']) : 0);
	$goods_attr = (isset($_REQUEST['goods_attr']) ? explode(',', $_REQUEST['goods_attr']) : array());
	header('Content-type: application/vnd.ms-excel; charset=utf-8');
	header('Content-Disposition: attachment; filename=attr_info_list.csv');

	if ($_GET['charset'] != $_CFG['lang']) {
		$lang_file = '../languages/' . $_GET['charset'] . '/admin/goods_produts_area_batch.php';

		if (file_exists($lang_file)) {
			unset($_LANG['upload_product']);
			require $lang_file;
		}
	}

	if (isset($_LANG['upload_product'])) {
		if (($_GET['charset'] == 'zh_cn') || ($_GET['charset'] == 'zh_tw')) {
			$to_charset = ($_GET['charset'] == 'zh_cn' ? 'GB2312' : 'BIG5');
			$data = join(',', $_LANG['upload_product']);
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

			foreach ($_attribute as $k => $v) {
				$data .= ',' . $v['attr_name'];
			}

			$data .= ',货号';
			$data .= ",库存\t\n";
			$goods_date = array('goods_sn');
			$where = 'goods_id = \'' . $goods_id . '\'';
			$goods_sn = get_table_date('goods', $where, $goods_date, 2);
			$region_name = get_table_date('region_warehouse', 'region_id = \'' . $area_id . '\'', array('region_name'), 2);
			$attr_info = get_list_download($goods_sn, array($region_name), $_attribute, count($_attribute));

			foreach ($attr_info as $k => $v) {
				$data .= $attr_info[$k]['goods_sn'] . ',';
				$data .= $attr_info[$k]['region_name'] . ',';
				$data .= implode(',', $v['attr_value']) . ',';
				$data .= $attr_info[$k]['product_sn'] . ',';
				$data .= $attr_info[$k]['product_number'] . "\t\n";
			}

			echo ecs_iconv(EC_CHARSET, $to_charset, $data);
		}
		else {
			echo join(',', $_LANG['upload_product']);
		}
	}
	else {
		echo 'error: $_LANG[upload_product] not exists';
	}
}

?>
