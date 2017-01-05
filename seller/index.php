<?php
//zend  QQ:2172298892
function create_ueditor_editor($input_name, $input_value = '')
{
	global $smarty;
	$input_height = 586;
	$FCKeditor = '<input type="hidden" id="' . $input_name . '" name="' . $input_name . '" value="' . htmlspecialchars($input_value) . '" /><iframe id="' . $input_name . '_frame" src="../plugins/seller_ueditor/ecmobanEditor.php?item=' . $input_name . '" width="100%" height="' . $input_height . '" frameborder="0" scrolling="no"></iframe>';
	$smarty->assign('FCKeditor', $FCKeditor);
}

define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';
require_once ROOT_PATH . '/includes/lib_order.php';
include_once ROOT_PATH . '/includes/cls_image.php';
$image = new cls_image($_CFG['bgcolor']);
$adminru = get_admin_ru_id();
$ru_id = $adminru['ru_id'];
$smarty->assign('ru_id', $ru_id);
$smarty->assign('menus', $_SESSION['menus']);
if (($_REQUEST['act'] == 'merchants_first') || ($_REQUEST['act'] == 'shop_top') || ($_REQUEST['act'] == 'merchants_second')) {
	$smarty->assign('action_type', 'index');
}
else {
	$smarty->assign('action_type', '');
}

if ($ru_id == 0) {
	$smarty->assign('priv_ru', 1);
}
else {
	$smarty->assign('priv_ru', 0);
}

$data = read_static_cache('main_user_str');

if ($data === false) {
	$smarty->assign('is_false', '1');
}
else {
	$smarty->assign('is_false', '0');
}

$data = read_static_cache('seller_goods_str');

if ($data === false) {
	$smarty->assign('goods_false', '1');
}
else {
	$smarty->assign('goods_false', '0');
}

if ($_REQUEST['act'] == '') {
	$user_id = intval($_SESSION['seller_id']);
	$ru_id = $db->getOne('SELECT ru_id FROM ' . $ecs->table('admin_user') . ' WHERE user_id=\'' . $user_id . '\'');
	$sql = 'SELECT u.*,s.* FROM ' . $ecs->table('admin_user') . ' AS u LEFT JOIN ' . $ecs->table('seller_shopinfo') . ' AS s ON u.ru_id = s.ru_id WHERE u.user_id = \'' . $user_id . '\'';
	$seller_info = $db->getRow($sql);
	$seller_info['last_login'] = local_date('Y-m-d H:i:s', $seller_info['last_login']);
	$seller_info['shopName'] = get_shop_name($seller_info['ru_id'], 1);
	$sql = 'SELECT * FROM ' . $ecs->table('goods') . ' WHERE user_id =\'' . $ru_id . '\'';
	$seller_goods = $db->getAll($sql);
	$seller_goods_info['is_sell'] = 0;
	$seller_goods_info['is_delete'] = 0;
	$seller_goods_info['is_on_sale'] = 0;

	foreach ($seller_goods as $K => $v) {
		if ($v['is_delete'] == 1) {
			++$seller_goods_info['is_delete'];
		}
		else if ($v['is_on_sale'] == 1) {
			++$seller_goods_info['is_sell'];
		}
		else if ($v['is_on_sale'] == 0) {
			++$seller_goods_info['is_on_sale'];
		}
	}

	$seller_goods_info['total'] = count($seller_goods);
	$ids = get_pay_ids();
	$today_start = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
	$today_end = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1;
	$month_start = mktime(0, 0, 0, date('m'), 1, date('Y'));
	$month_end = mktime(23, 59, 59, date('m'), date('t'), date('Y'));
	$today = array();
	$where_date = '';
	$where_og = '';
	$where_og .= ' AND (SELECT count(*) FROM ' . $GLOBALS['ecs']->table('order_info') . ' AS oi2 WHERE oi2.main_order_id = oi.order_id) = 0 ';

	if (0 < $ru_id) {
		$where_date .= ' AND (SELECT ru_id FROM ' . $GLOBALS['ecs']->table('order_goods') . ' AS og WHERE oi.order_id = og.order_id LIMIT 1) = \'' . $ru_id . '\'';
	}

	$leftJoin_og = '';
	$where_goods = '';
	$where_cmt = '';

	if (0 < $ru_id) {
		$leftJoin_og = ' left join ' . $GLOBALS['ecs']->table('order_goods') . ' as og on oi.order_id = og.order_id ';
		$where_og .= ' and og.ru_id = ' . $ru_id . ' group by oi.order_id';
		$where_goods = ' and user_id = ' . $ru_id;
		$where_cmt = ' and ru_id = ' . $ru_id;
	}

	$order['finished'] = count($db->getAll('SELECT oi.order_id FROM ' . $ecs->table('order_info') . ' as oi ' . $leftJoin_og . ' WHERE 1 ' . order_query_sql('finished') . $where_og));
	$status['finished'] = CS_FINISHED;
	$order['await_ship'] = count($db->getAll('SELECT oi.order_id' . ' FROM ' . $ecs->table('order_info') . ' as oi ' . $leftJoin_og . ' WHERE 1 ' . order_query_sql('await_ship') . $where_og));
	$status['await_ship'] = CS_AWAIT_SHIP;
	$order['await_pay'] = count($db->getAll('SELECT oi.order_id' . ' FROM ' . $ecs->table('order_info') . ' as oi ' . $leftJoin_og . ' WHERE 1 ' . order_query_sql('await_pay') . $where_og));
	$status['await_pay'] = CS_AWAIT_PAY;
	$order['unconfirmed'] = count($db->getAll('SELECT oi.order_id FROM ' . $ecs->table('order_info') . ' as oi ' . $leftJoin_og . ' WHERE 1 ' . order_query_sql('unconfirmed') . $where_og));
	$status['unconfirmed'] = OS_UNCONFIRMED;
	$order['shipped_deal'] = count($db->getAll('SELECT oi.order_id FROM ' . $ecs->table('order_info') . ' as oi ' . $leftJoin_og . ' WHERE  shipping_status<>' . SS_RECEIVED . $where_og));
	$status['shipped_deal'] = SS_RECEIVED;
	$order['shipped_part'] = count($db->getAll('SELECT oi.order_id FROM ' . $ecs->table('order_info') . ' as oi ' . $leftJoin_og . ' WHERE  shipping_status=' . SS_SHIPPED_PART . $where_og));
	$status['shipped_part'] = OS_SHIPPED_PART;
	$order['stats'] = $db->getRow('SELECT COUNT(*) AS oCount, IFNULL(SUM(oi.order_amount), 0) AS oAmount' . ' FROM ' . $ecs->table('order_info') . ' as oi' . $leftJoin_og . ' where 1 ' . $where_og);
	$where_return = '';

	if (0 < $ru_id) {
		$where_return = ' and og.ru_id = \'' . $ru_id . '\'';
	}

	$sql = 'SELECT o.order_id, o.order_sn FROM ' . $ecs->table('order_info') . ' AS o LEFT JOIN ' . $ecs->table('order_goods') . ' AS og ON og.order_id=o.order_id LEFT JOIN ' . $ecs->table('users') . ' AS u ON u.user_id=o.user_id RIGHT JOIN ' . $ecs->table('order_return') . ' AS r ON r.order_id = o.order_id WHERE 1' . $where_return;
	$order['return_number'] = count($db->getAll($sql));
	$smarty->assign('order', $order);
	$smarty->assign('status', $status);
	$leftJoin_bg = '';
	$where_bg = '';

	if (0 < $ru_id) {
		$leftJoin_bg = ' left join ' . $ecs->table('goods') . ' as g on bg.goods_id = g.goods_id ';
		$where_bg = ' and g.user_id = ' . $ru_id;
	}

	$sql = 'SELECT COUNT(*) FROM ' . $ecs->table('booking_goods') . 'as bg ' . $leftJoin_bg . ' WHERE is_dispose = 0' . $where_bg;
	$booking_goods = $db->getOne($sql);
	$smarty->assign('booking_goods', $booking_goods);
	$smarty->assign('new_repay', $db->getOne('SELECT COUNT(*) FROM ' . $ecs->table('user_account') . ' WHERE process_type = ' . SURPLUS_RETURN . ' AND is_paid = 0 '));
	$sql = 'SELECT COUNT(oi.order_id) order_total,IFNULL(SUM(oi.money_paid+oi.surplus+oi.integral+oi.bonus),0) money_total FROM ' . $ecs->table('order_info') . 'oi LEFT JOIN ' . $ecs->table('order_goods') . ' og ON oi.order_id=og.order_id WHERE oi.pay_status=2 AND og.ru_id=\'' . $ru_id . '\' ';
	$total_shipping_info = $db->getRow($sql);
	$beginYesterday = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
	$endYesterday = mktime(0, 0, 0, date('m'), date('d'), date('Y')) - 1;
	$sql = 'SELECT COUNT(oi.order_id) order_total,IFNULL(SUM(oi.money_paid+oi.surplus+oi.integral+oi.bonus),0) money_total FROM ' . $ecs->table('order_info') . 'oi LEFT JOIN ' . $ecs->table('order_goods') . ' og ON oi.order_id=og.order_id WHERE oi.pay_time BETWEEN ' . $beginYesterday . ' AND ' . $endYesterday . '  AND  oi.pay_status=2  AND og.ru_id=\'' . $ru_id . '\' ';
	$yseterday_shipping_info = $db->getRow($sql);
	$beginThismonth = mktime(0, 0, 0, date('m'), 1, date('Y'));
	$endThismonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));
	$sql = 'SELECT COUNT(oi.order_id) order_total,IFNULL(SUM(oi.money_paid+oi.surplus+oi.integral+oi.bonus),0) money_total FROM ' . $ecs->table('order_info') . 'oi LEFT JOIN ' . $ecs->table('order_goods') . ' og ON oi.order_id=og.order_id WHERE oi.pay_time BETWEEN ' . $beginThismonth . ' AND ' . $endThismonth . '  AND  oi.pay_status=2  AND og.ru_id=\'' . $ru_id . '\' ';
	$month_shipping_info = $db->getRow($sql);
	$favourable_count = get_favourable_count($ru_id);
	$smarty->assign('favourable_count', $favourable_count);
	$smarty->assign('file_list', get_dir_file_list());
	$favourable_dateout_count = get_favourable_dateout_count($ru_id);
	$smarty->assign('favourable_dateout_count', $favourable_dateout_count);
	$reply_count = get_comment_reply_count($ru_id);
	$smarty->assign('reply_count', $reply_count);
	$hot_count = get_goods_special_count($ru_id, 'is_hot');
	$new_count = get_goods_special_count($ru_id, 'is_new');
	$best_count = get_goods_special_count($ru_id, 'is_best');
	$promotion_count = get_goods_special_count($ru_id, 'promotion');
	$smarty->assign('hot_count', $hot_count);
	$smarty->assign('new_count', $new_count);
	$smarty->assign('best_count', $best_count);
	$smarty->assign('promotion_count', $promotion_count);
	$sql = 'SELECT * FROM ' . $ecs->table('article') . 'WHERE cat_id =14 ';
	$articles = $db->getAll($sql);
	$sql = 'SELECT SUM(og.goods_number) goods_shipping_total,og.goods_name,og.goods_id FROM ' . $ecs->table('order_goods') . ' og LEFT JOIN ' . $ecs->table('order_info') . ' oi ON og.order_id = oi.order_id  WHERE og.ru_id=\'' . $ru_id . '\'  AND  oi.pay_status=2 GROUP BY og.goods_id ORDER BY goods_shipping_total DESC LIMIT 10';
	$goods_info = $db->getAll($sql);
	$smarty->assign('total_shipping_info', $total_shipping_info);
	$smarty->assign('month_shipping_info', $month_shipping_info);
	$smarty->assign('yseterday_shipping_info', $yseterday_shipping_info);
	$smarty->assign('goods_info', $goods_info);
	$smarty->assign('articles', $articles);
	$smarty->assign('seller_goods_info', $seller_goods_info);
	$smarty->assign('seller_info', $seller_info);
	$smarty->assign('shop_url', urlencode($ecs->url()));
	$merchants_goods_comment = get_merchants_goods_comment($seller_info['ru_id']);
	$smarty->assign('merch_cmt', $merchants_goods_comment);
	$smarty->display('index.dwt');
}
else if ($_REQUEST['act'] == 'merchants_first') {
	admin_priv('seller_store_informa');
	$smarty->assign('countries', get_regions());
	$smarty->assign('provinces', get_regions(1, 1));
	$sql = 'select notice from ' . $ecs->table('seller_shopinfo') . ' where ru_id = 0 LIMIT 1';
	$seller_notice = $db->getOne($sql);
	$smarty->assign('seller_notice', $seller_notice);
	$sql = 'select ss.*,sq.* from ' . $ecs->table('seller_shopinfo') . ' as ss ' . ' left join ' . $ecs->table('seller_qrcode') . ' as sq on sq.ru_id = ss.ru_id ' . ' where ss.ru_id=\'' . $adminru['ru_id'] . '\' LIMIT 1';
	$seller_shop_info = $db->getRow($sql);
	$action = 'add';

	if ($seller_shop_info) {
		$action = 'update';
	}

	$shipping_list = warehouse_shipping_list();
	$smarty->assign('shipping_list', $shipping_list);
	$domain_name = $db->getOne(' SELECT domain_name FROM' . $ecs->table('seller_domain') . ' WHERE ru_id=\'' . $adminru['ru_id'] . '\'');
	$seller_shop_info['domain_name'] = $domain_name;
	$smarty->assign('shop_info', $seller_shop_info);
	$shop_information = get_shop_name($adminru['ru_id']);
	$adminru['ru_id'] == 0 ? $shop_information['is_dsc'] = true : $shop_information['is_dsc'] = false;
	$smarty->assign('shop_information', $shop_information);
	$shop_information = get_shop_name($adminru['ru_id']);
	$smarty->assign('shop_information', $shop_information);
	$smarty->assign('cities', get_regions(2, $seller_shop_info['province']));
	$smarty->assign('districts', get_regions(3, $seller_shop_info['city']));
	$smarty->assign('data_op', $action);
	assign_query_info();
	$smarty->assign('current', 'index_first');
	$smarty->assign('ur_here', $_LANG['04_merchants_basic_info']);
	$smarty->display('store_setting.dwt');
}
else if ($_REQUEST['act'] == 'merchants_second') {
	$shop_name = (empty($_POST['shop_name']) ? '' : addslashes(trim($_POST['shop_name'])));
	$shop_title = (empty($_POST['shop_title']) ? '' : addslashes(trim($_POST['shop_title'])));
	$shop_keyword = (empty($_POST['shop_keyword']) ? '' : addslashes(trim($_POST['shop_keyword'])));
	$shop_country = (empty($_POST['shop_country']) ? '' : intval($_POST['shop_country']));
	$shop_province = (empty($_POST['shop_province']) ? '' : intval($_POST['shop_province']));
	$shop_city = (empty($_POST['shop_city']) ? '' : intval($_POST['shop_city']));
	$shop_district = (empty($_POST['shop_district']) ? '' : intval($_POST['shop_district']));
	$shipping_id = (empty($_POST['shipping_id']) ? '' : intval($_POST['shipping_id']));
	$shop_address = (empty($_POST['shop_address']) ? '' : addslashes(trim($_POST['shop_address'])));
	$mobile = (empty($_POST['mobile']) ? '' : trim($_POST['mobile']));
	$seller_email = (empty($_POST['seller_email']) ? '' : addslashes(trim($_POST['seller_email'])));
	$street_desc = (empty($_POST['street_desc']) ? '' : addslashes(trim($_POST['street_desc'])));
	$kf_qq = (empty($_POST['kf_qq']) ? '' : $_POST['kf_qq']);
	$kf_ww = (empty($_POST['kf_ww']) ? '' : $_POST['kf_ww']);
	$kf_touid = (empty($_POST['kf_touid']) ? '' : addslashes(trim($_POST['kf_touid'])));
	$kf_appkey = (empty($_POST['kf_appkey']) ? 0 : addslashes(trim($_POST['kf_appkey'])));
	$kf_secretkey = (empty($_POST['kf_secretkey']) ? 0 : addslashes(trim($_POST['kf_secretkey'])));
	$kf_logo = (empty($_POST['kf_logo']) ? 'http://' : addslashes(trim($_POST['kf_logo'])));
	$kf_welcomeMsg = (empty($_POST['kf_welcomeMsg']) ? '' : addslashes(trim($_POST['kf_welcomeMsg'])));
	$meiqia = (empty($_POST['meiqia']) ? '' : addslashes(trim($_POST['meiqia'])));
	$kf_type = (empty($_POST['kf_type']) ? '' : intval($_POST['kf_type']));
	$kf_tel = (empty($_POST['kf_tel']) ? '' : addslashes(trim($_POST['kf_tel'])));
	$notice = (empty($_POST['notice']) ? '' : addslashes(trim($_POST['notice'])));
	$data_op = (empty($_POST['data_op']) ? '' : $_POST['data_op']);
	$check_sellername = (empty($_POST['check_sellername']) ? 0 : intval($_POST['check_sellername']));
	$shop_style = intval($_POST['shop_style']);
	$domain_name = (empty($_POST['domain_name']) ? '' : trim($_POST['domain_name']));
	$templates_mode = (empty($_REQUEST['templates_mode']) ? 0 : intval($_REQUEST['templates_mode']));
	$tengxun_key = (empty($_POST['tengxun_key']) ? '' : addslashes(trim($_POST['tengxun_key'])));
	$longitude = (empty($_POST['longitude']) ? '' : addslashes(trim($_POST['longitude'])));
	$latitude = (empty($_POST['latitude']) ? '' : addslashes(trim($_POST['latitude'])));

	if (!empty($domain_name)) {
		$sql = ' SELECT count(id) FROM ' . $ecs->table('seller_domain') . ' WHERE domain_name = \'' . $domain_name . '\' AND ru_id !=\'' . $adminru['ru_id'] . '\'';

		if (0 < $db->getOne($sql)) {
			$lnk[] = array('text' => '返回首页', 'href' => 'index.php?act=main');
			sys_msg('域名已存在', 0, $lnk);
		}
	}

	$seller_domain = array('ru_id' => $adminru['ru_id'], 'domain_name' => $domain_name);
	$shop_info = array('ru_id' => $adminru['ru_id'], 'shop_name' => $shop_name, 'shop_title' => $shop_title, 'shop_keyword' => $shop_keyword, 'country' => $shop_country, 'province' => $shop_province, 'city' => $shop_city, 'district' => $shop_district, 'shipping_id' => $shipping_id, 'shop_address' => $shop_address, 'mobile' => $mobile, 'seller_email' => $seller_email, 'kf_qq' => $kf_qq, 'kf_ww' => $kf_ww, 'kf_appkey' => $kf_appkey, 'kf_secretkey' => $kf_secretkey, 'kf_touid' => $kf_touid, 'kf_logo' => $kf_logo, 'kf_welcomeMsg' => $kf_welcomeMsg, 'meiqia' => $meiqia, 'kf_type' => $kf_type, 'kf_tel' => $kf_tel, 'notice' => $notice, 'street_desc' => $street_desc, 'shop_style' => $shop_style, 'check_sellername' => $check_sellername, 'templates_mode' => $templates_mode, 'tengxun_key' => $tengxun_key, 'longitude' => $longitude, 'latitude' => $latitude);
	$sql = 'SELECT ss.shop_logo, ss.logo_thumb, ss.street_thumb, ss.brand_thumb, sq.qrcode_thumb FROM ' . $ecs->table('seller_shopinfo') . ' as ss ' . ' left join ' . $ecs->table('seller_qrcode') . ' as sq on sq.ru_id=ss.ru_id ' . ' WHERE ss.ru_id=\'' . $adminru['ru_id'] . '\'';
	$store = $db->getRow($sql);
	$allow_file_types = '|GIF|JPG|PNG|BMP|';

	if ($_FILES['shop_logo']) {
		$file = $_FILES['shop_logo'];
		if ((isset($file['error']) && ($file['error'] == 0)) || (!isset($file['error']) && ($file['tmp_name'] != 'none'))) {
			if (!check_file_type($file['tmp_name'], $file['name'], $allow_file_types)) {
				sys_msg(sprintf($_LANG['msg_invalid_file'], $file['name']));
			}
			else {
				$ext = array_pop(explode('.', $file['name']));
				$file_name = '../seller_imgs/seller_logo/seller_logo' . $adminru['ru_id'] . '.' . $ext;

				if (move_upload_file($file['tmp_name'], $file_name)) {
					$shop_info['shop_logo'] = $file_name;
				}
				else {
					sys_msg(sprintf($_LANG['msg_upload_failed'], $file['name'], '../seller_imgs/seller_' . $adminru['ru_id']));
				}
			}
		}
	}

	$del_logo_thumb = '';

	if ($_FILES['logo_thumb']) {
		$file = $_FILES['logo_thumb'];
		if ((isset($file['error']) && ($file['error'] == 0)) || (!isset($file['error']) && ($file['tmp_name'] != 'none'))) {
			if (!check_file_type($file['tmp_name'], $file['name'], $allow_file_types)) {
				sys_msg(sprintf($_LANG['msg_invalid_file'], $file['name']));
			}
			else {
				$ext = array_pop(explode('.', $file['name']));
				$file_name = '../seller_imgs/seller_logo/logo_thumb/logo_thumb' . $adminru['ru_id'] . '.' . $ext;

				if (move_upload_file($file['tmp_name'], $file_name)) {
					include_once ROOT_PATH . '/includes/cls_image.php';
					$image = new cls_image($_CFG['bgcolor']);
					$goods_thumb = $image->make_thumb($file_name, 120, 120, '../seller_imgs/seller_logo/logo_thumb/');
					$shop_info['logo_thumb'] = $goods_thumb;

					if (!empty($goods_thumb)) {
						if ($store['logo_thumb']) {
							$store['logo_thumb'] = str_replace('../', '', $store['logo_thumb']);
							$del_logo_thumb = $store['logo_thumb'];
						}

						@unlink(ROOT_PATH . $del_logo_thumb);
					}
				}
				else {
					sys_msg(sprintf($_LANG['msg_upload_failed'], $file['name'], 'seller_imgs/logo_thumb_' . $adminru['ru_id']));
				}
			}
		}
	}

	$street_thumb = $image->upload_image($_FILES['street_thumb'], 'store_street/street_thumb');
	$brand_thumb = $image->upload_image($_FILES['brand_thumb'], 'store_street/brand_thumb');
	$domain_id = $db->getOne('SELECT id FROM ' . $ecs->table('seller_domain') . ' WHERE ru_id =\'' . $adminru['ru_id'] . '\'');

	if (0 < $domain_id) {
		$db->autoExecute($ecs->table('seller_domain'), $seller_domain, 'UPDATE', 'ru_id=\'' . $adminru['ru_id'] . '\'');
	}
	else {
		$db->autoExecute($ecs->table('seller_domain'), $seller_domain, 'INSERT');
	}

	if ($_FILES['qrcode_thumb']) {
		$file = $_FILES['qrcode_thumb'];
		if ((isset($file['error']) && ($file['error'] == 0)) || (!isset($file['error']) && ($file['tmp_name'] != 'none'))) {
			if (!check_file_type($file['tmp_name'], $file['name'], $allow_file_types)) {
				sys_msg(sprintf($_LANG['msg_invalid_file'], $file['name']));
			}
			else {
				$ext = array_pop(explode('.', $file['name']));
				$file_name = '../seller_imgs/seller_qrcode/qrcode_thumb/qrcode_thumb' . $adminru['ru_id'] . '.' . $ext;

				if (move_upload_file($file['tmp_name'], $file_name)) {
					include_once ROOT_PATH . '/includes/cls_image.php';
					$image = new cls_image($_CFG['bgcolor']);
					$qrcode_thumb = $image->make_thumb($file_name, 120, 120, '../seller_imgs/seller_qrcode/qrcode_thumb/');

					if (!empty($qrcode_thumb)) {
						if ($store['qrcode_thumb']) {
							$store['qrcode_thumb'] = str_replace('../', '', $store['qrcode_thumb']);
							$del_logo_thumb = $store['qrcode_thumb'];
						}

						@unlink(ROOT_PATH . $del_logo_thumb);
					}

					$sql = ' select * from ' . $GLOBALS['ecs']->table('seller_qrcode') . ' where ru_id=\'' . $adminru['ru_id'] . '\' limit 1';
					$qrinfo = $GLOBALS['db']->getRow($sql);

					if (empty($qrinfo)) {
						$sql = ' insert into ' . $GLOBALS['ecs']->table('seller_qrcode') . ' (ru_id,qrcode_thumb) ' . ' values ' . '(\'' . $adminru['ru_id'] . '\',\'' . $qrcode_thumb . '\')';
						$GLOBALS['db']->query($sql);
					}
					else {
						$sql = ' update ' . $GLOBALS['ecs']->table('seller_qrcode') . ' set ru_id=\'' . $adminru['ru_id'] . '\', ' . ' qrcode_thumb=\'' . $qrcode_thumb . '\' ';
						$GLOBALS['db']->query($sql);
					}
				}
				else {
					sys_msg(sprintf($_LANG['msg_upload_failed'], $file['name'], 'seller_imgs/qrcode_thumb_' . $adminru['ru_id']));
				}
			}
		}
	}

	$shop_logo = '';

	if ($shop_info['shop_logo']) {
		$shop_logo = str_replace('../', '', $shop_info['shop_logo']);
	}

	$add_logo_thumb = '';

	if ($shop_info['logo_thumb']) {
		$add_logo_thumb = str_replace('../', '', $shop_info['logo_thumb']);
	}

	get_oss_add_file(array($street_thumb, $brand_thumb, $shop_logo, $add_logo_thumb));

	if ($data_op == 'add') {
		$shop_info['street_thumb'] = $street_thumb;
		$shop_info['brand_thumb'] = $brand_thumb;

		if (!$store) {
			$db->autoExecute($ecs->table('seller_shopinfo'), $shop_info, 'INSERT');
		}

		$lnk[] = array('text' => '返回上一步', 'href' => 'index.php?act=merchants_first');
		sys_msg('添加店铺信息成功', 0, $lnk);
	}
	else {
		$sql = 'select check_sellername from ' . $ecs->table('seller_shopinfo') . ' where ru_id=\'' . $adminru['ru_id'] . '\'';
		$seller_shop_info = $db->getRow($sql);

		if ($seller_shop_info['check_sellername'] != $check_sellername) {
			$shop_info['shopname_audit'] = 0;
		}

		$oss_street_thumb = '';

		if (!empty($street_thumb)) {
			$oss_street_thumb = $store['street_thumb'];
			$shop_info['street_thumb'] = $street_thumb;
			@unlink(ROOT_PATH . $oss_street_thumb);
		}

		$oss_brand_thumb = '';

		if (!empty($brand_thumb)) {
			$oss_brand_thumb = $store['brand_thumb'];
			$shop_info['brand_thumb'] = $brand_thumb;
			@unlink(ROOT_PATH . $oss_brand_thumb);
		}

		if ($GLOBALS['_CFG']['open_oss'] == 1) {
			$bucket_info = get_bucket_info();
			$url = $GLOBALS['ecs']->url();
			$self = explode('/', substr(PHP_SELF, 1));
			$count = count($self);

			if (1 < $count) {
				$real_path = $self[$count - 2];

				if ($real_path == SELLER_PATH) {
					$str_len = 0 - (str_len(SELLER_PATH) + 1);
					$url = substr($GLOBALS['ecs']->url(), 0, $str_len);
				}
			}

			$urlip = get_ip_url($url);
			$url = $urlip . 'oss.php?act=del_file';
			$Http = new Http();
			$post_data = array(
				'bucket'    => $bucket_info['bucket'],
				'keyid'     => $bucket_info['keyid'],
				'keysecret' => $bucket_info['keysecret'],
				'is_cname'  => $bucket_info['is_cname'],
				'endpoint'  => $bucket_info['outside_site'],
				'object'    => array($oss_street_thumb, $oss_brand_thumb, $del_logo_thumb)
				);
			$Http->doPost($url, $post_data);
		}

		$db->autoExecute($ecs->table('seller_shopinfo'), $shop_info, 'UPDATE', 'ru_id=\'' . $adminru['ru_id'] . '\'');
		$lnk[] = array('text' => '返回上一步', 'href' => 'index.php?act=merchants_first');
		sys_msg('更新店铺信息成功', 0, $lnk);
	}
}
else if ($_REQUEST['act'] == 'shop_top') {
	admin_priv('seller_store_other');
	$smarty->assign('ur_here', '店铺头部装修');
	$seller_shop_info = get_seller_info($adminru['ru_id'], array('id', 'seller_theme', 'shop_color'));

	if (0 < $seller_shop_info['id']) {
		$header_sql = 'select content, headtype, headbg_img, shop_color from ' . $GLOBALS['ecs']->table('seller_shopheader') . ' where seller_theme=\'' . $seller_shop_info['seller_theme'] . '\' and ru_id = \'' . $adminru['ru_id'] . '\'';
		$shopheader_info = $GLOBALS['db']->getRow($header_sql);
		$header_content = $shopheader_info['content'];
		create_ueditor_editor('shop_header', $header_content);
		$smarty->assign('form_action', 'shop_top_edit');
		$smarty->assign('shop_info', $seller_shop_info);
		$smarty->assign('shopheader_info', $shopheader_info);
	}
	else {
		$lnk[] = array('text' => '设置店铺信息', 'href' => 'index.php?act=merchants_first');
		sys_msg('请先设置店铺基本信息', 0, $lnk);
	}

	$smarty->assign('current', 'index_top');
	$smarty->display('seller_shop_header.dwt');
}
else if ($_REQUEST['act'] == 'shop_top_edit') {
	$preg = '/<script[\\s\\S]*?<\\/script>/i';
	$shop_header = (!empty($_REQUEST['shop_header']) ? preg_replace($preg, '', stripslashes($_REQUEST['shop_header'])) : '');
	$seller_theme = (!empty($_REQUEST['seller_theme']) ? preg_replace($preg, '', stripslashes($_REQUEST['seller_theme'])) : '');
	$shop_color = (!empty($_REQUEST['shop_color']) ? $_REQUEST['shop_color'] : '');
	$headtype = (isset($_REQUEST['headtype']) ? intval($_REQUEST['headtype']) : 0);
	$img_url = '';

	if ($headtype == 0) {
		$allow_file_types = '|GIF|JPG|PNG|BMP|';

		if ($_FILES['img_url']) {
			$file = $_FILES['img_url'];
			if ((isset($file['error']) && ($file['error'] == 0)) || (!isset($file['error']) && ($file['tmp_name'] != 'none'))) {
				if (!check_file_type($file['tmp_name'], $file['name'], $allow_file_types)) {
					sys_msg(sprintf($_LANG['msg_invalid_file'], $file['name']));
				}
				else {
					$ext = array_pop(explode('.', $file['name']));
					$file_dir = '../seller_imgs/seller_header_img/seller_' . $adminru['ru_id'];

					if (!is_dir($file_dir)) {
						mkdir($file_dir);
					}

					$file_name = $file_dir . '/slide_' . gmtime() . '.' . $ext;

					if (move_upload_file($file['tmp_name'], $file_name)) {
						$img_url = $file_name;
						$oss_img_url = str_replace('../', '', $img_url);
						get_oss_add_file(array($oss_img_url));
					}
					else {
						sys_msg('图片上传失败');
					}
				}
			}
		}
		else {
			sys_msg('必须上传图片');
		}
	}

	$sql = 'SELECT headbg_img FROM ' . $ecs->table('seller_shopheader') . ' WHERE ru_id=\'' . $adminru['ru_id'] . '\' and seller_theme=\'' . $seller_theme . '\'';
	$shopheader_info = $db->getRow($sql);

	if (empty($img_url)) {
		$img_url = $shopheader_info['headbg_img'];
	}

	$sql = 'update ' . $ecs->table('seller_shopheader') . ' set content=\'' . $shop_header . '\', shop_color=\'' . $shop_color . '\', headbg_img=\'' . $img_url . '\', headtype=\'' . $headtype . '\' where ru_id=\'' . $adminru['ru_id'] . '\' and seller_theme=\'' . $seller_theme . '\'';
	$db->query($sql);
	$lnk[] = array('text' => '返回上一步', 'href' => 'index.php?act=shop_top');
	sys_msg('店铺头部装修成功', 0, $lnk);
}
else if ($_REQUEST['act'] == 'license') {
	$is_ajax = $_GET['is_ajax'];
	if (isset($is_ajax) && $is_ajax) {
		include_once ROOT_PATH . 'includes/cls_transport.php';
		include_once ROOT_PATH . 'includes/cls_json.php';
		include_once ROOT_PATH . 'includes/lib_main.php';
		include_once ROOT_PATH . 'includes/lib_license.php';
		$license = license_check();

		switch ($license['flag']) {
		case 'login_succ':
			if (isset($license['request']['info']['service']['ecshop_b2c']['cert_auth']['auth_str'])) {
				make_json_result(process_login_license($license['request']['info']['service']['ecshop_b2c']['cert_auth']));
			}
			else {
				make_json_error(0);
			}

			break;

		case 'login_fail':
		case 'login_ping_fail':
			make_json_error(0);
			break;

		case 'reg_succ':
			$_license = license_check();

			switch ($_license['flag']) {
			case 'login_succ':
				if (isset($_license['request']['info']['service']['ecshop_b2c']['cert_auth']['auth_str']) && ($_license['request']['info']['service']['ecshop_b2c']['cert_auth']['auth_str'] != '')) {
					make_json_result(process_login_license($license['request']['info']['service']['ecshop_b2c']['cert_auth']));
				}
				else {
					make_json_error(0);
				}

				break;

			case 'login_fail':
			case 'login_ping_fail':
				make_json_error(0);
				break;
			}

			break;

		case 'reg_fail':
		case 'reg_ping_fail':
			make_json_error(0);
			break;
		}
	}
	else {
		make_json_error(0);
	}
}
else if ($_REQUEST['act'] == 'check_order') {
	$firstSecToday = local_mktime(0, 0, 0, date('m'), date('d'), date('Y'));
	$lastSecToday = local_mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1;

	if (empty($_SESSION['last_check'])) {
		$_SESSION['last_check'] = gmtime();
		make_json_result('', '', array('new_orders' => 0, 'new_paid' => 0));
	}

	$where = '';
	$where = ' AND (SELECT og.ru_id FROM ' . $GLOBALS['ecs']->table('order_goods') . ' as og' . ' WHERE og.order_id = o.order_id limit 0, 1) = \'' . $adminru['ru_id'] . '\' ';
	$where .= ' AND (select count(*) from ' . $GLOBALS['ecs']->table('order_info') . ' as oi2 WHERE oi2.main_order_id = o.order_id) = 0 ';
	$where .= ' AND o.shipping_status = ' . SS_UNSHIPPED;
	$sql = 'SELECT COUNT(*) FROM ' . $ecs->table('order_info') . ' as o' . ' WHERE o.add_time >= ' . $firstSecToday . ' AND o.add_time <= ' . $lastSecToday . $where;
	$arr['new_orders'] = $db->getOne($sql);
	$sql = 'SELECT COUNT(*) FROM ' . $ecs->table('order_info') . ' as o' . ' WHERE o.pay_time >= ' . $firstSecToday . ' AND o.pay_time <= ' . $lastSecToday . $where;
	$arr['new_paid'] = $db->getOne($sql);
	$_SESSION['last_check'] = gmtime();
	$_SESSION['firstSecToday'] = $firstSecToday;
	$_SESSION['lastSecToday'] = $lastSecToday;
	if (!(is_numeric($arr['new_orders']) && is_numeric($arr['new_paid']))) {
		make_json_error($db->error());
	}
	else {
		make_json_result('', '', $arr);
	}
}
else if ($_REQUEST['act'] == 'change_user_menu') {
	$adminru = get_admin_ru_id();
	$result = array('error' => 0, 'message' => '', 'content' => '');
	$action = (isset($_REQUEST['action']) ? trim($_REQUEST['action']) : '');
	$status = (isset($_REQUEST['status']) ? intval($_REQUEST['status']) : 0);
	$user_menu = get_user_menu_list();
	$change = get_user_menu_status($action);

	if (!$change) {
		$user_menu[] = $action;
		$sql = ' UPDATE ' . $GLOBALS['ecs']->table('seller_shopinfo') . ' set user_menu = \'' . implode(',', $user_menu) . '\' WHERE ru_id = \'' . $adminru['ru_id'] . '\' ';

		if ($GLOBALS['db']->query($sql)) {
			$result['error'] = 1;
		}
	}

	if ($change) {
		$user_menu = array_diff($user_menu, array($action));
		$sql = ' UPDATE ' . $GLOBALS['ecs']->table('seller_shopinfo') . ' set user_menu = \'' . implode(',', $user_menu) . '\' WHERE ru_id = \'' . $adminru['ru_id'] . '\' ';

		if ($GLOBALS['db']->query($sql)) {
			$result['error'] = 2;
		}
	}

	exit(json_encode($result));
}
else if ($_REQUEST['act'] == 'clear_cache') {
	if (file_exists(ROOT_PATH . 'mobile/api/script/clear_cache.php')) {
		require_once ROOT_PATH . 'mobile/api/script/clear_cache.php';
	}

	clear_all_files('', SELLER_PATH);
	sys_msg($_LANG['caches_cleared']);
}
else if ($_REQUEST['act'] == 'tengxun_coordinate') {
	$result = array('error' => 0, 'message' => '', 'content' => '');
	$province = (!empty($_REQUEST['province']) ? intval($_REQUEST['province']) : 0);
	$city = (!empty($_REQUEST['city']) ? intval($_REQUEST['city']) : 0);
	$district = (!empty($_REQUEST['district']) ? intval($_REQUEST['district']) : 0);
	$address = (!empty($_REQUEST['address']) ? trim($_REQUEST['address']) : 0);
	$region = get_seller_region(array('province' => $province, 'city' => $city, 'district' => $district));
	$key = $GLOBALS['_CFG']['tengxun_key'];
	$region .= $address;
	$url = 'http://apis.map.qq.com/ws/geocoder/v1/?address=' . $region . '&key=' . $key;
	$http = new Http();
	$data = $http->doGet($url);
	$data = json_decode($data, true);

	if ($data['status'] == 0) {
		$result['lng'] = $data['result']['location']['lng'];
		$result['lat'] = $data['result']['location']['lat'];
	}
	else {
		$result['error'] = 1;
		$result['message'] = $data['message'];
	}

	exit(json_encode($result));
}

?>
