<?php
//zend by QQ:2172298892
$shop_date = array('shop_id');
$shop_where = 'user_id = \'' . $merchant_id . '\'';
$shop_id = get_table_date('merchants_shop_information', $shop_where, $shop_date);
if (($merchant_id == 0) || ($shop_id < 1)) {
	header("Location: index.php\n");
	exit();
}

$pc_page = get_seller_templates($merchant_id, 1, $tem);

if ($_CFG['site_domain'] != '') {
	$pc_page['out'] = str_replace('data/gallery_album/', $_CFG['site_domain'] . 'data/gallery_album/', $pc_page['out'], $i);
	$pc_page['out'] = str_replace('data/seller_templates/', $_CFG['site_domain'] . 'data/seller_templates/', $pc_page['out'], $i);
}

assign_template();
$shop_name = get_shop_name($merchant_id, 1);
$grade_info = get_seller_grade($merchant_id);
$store_conut = get_merchants_store_info($merchant_id);
$store_info = get_merchants_store_info($merchant_id, 1);
$position = assign_ur_here(0, $shop_name);
$smarty->assign('page_title', $position['title']);
$smarty->assign('ur_here', $position['ur_here']);
$smarty->assign('helps', get_shop_help());
$smarty->assign('pc_page', $pc_page);
$build_uri = array('urid' => $merchant_id, 'append' => $shop_name);
$domain_url = get_seller_domain_url($merchant_id, $build_uri);
$merchants_url = $domain_url['domain_name'];
$smarty->assign('merchants_url', $merchants_url);

if (0 < $merchant_id) {
	$merchants_goods_comment = get_merchants_goods_comment($merchant_id);
}

$smarty->assign('merch_cmt', $merchants_goods_comment);
$smarty->assign('shop_name', $shop_name);
$categories_pro = get_category_tree_leve_one();
$smarty->assign('categories_pro', $categories_pro);
$categories_pro = get_category_tree_leve_one();
$smarty->assign('categories_pro', $categories_pro);
$store_category = get_user_store_category($merchant_id);
$smarty->assign('store_category', $store_category);
$sql = 'select ss.*,sq.* from ' . $ecs->table('seller_shopinfo') . ' as ss ' . ' left join' . $ecs->table('seller_qrcode') . ' as sq on sq.ru_id=ss.ru_id ' . ' where ss.ru_id=\'' . $merchant_id . '\'';
$basic_info = $db->getRow($sql);
$logo = str_replace('../', '', $basic_info['qrcode_thumb']);
$size = '155x155';
$url = $ecs->url();
$data = $url . 'mobile/index.php?r=store/index/shop_info&id=' . $merchant_id;
$errorCorrectionLevel = 'Q';
$matrixPointSize = 4;
$filename = 'seller_imgs/seller_qrcode/seller_qrcode_' . $merchant_id . '.png';
QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize);
$QR = imagecreatefrompng($filename);

if ($logo !== false) {
	$logo = imagecreatefromstring(file_get_contents($logo));
	$QR_width = imagesx($QR);
	$QR_height = imagesy($QR);
	$logo_width = imagesx($logo);
	$logo_height = imagesy($logo);
	$logo_qr_width = $QR_width / 5;
	$scale = $logo_width / $logo_qr_width;
	$logo_qr_height = $logo_height / $scale;
	$from_width = ($QR_width - $logo_qr_width) / 2;
	imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
}

imagepng($QR, $filename);
imagedestroy($QR);
$smarty->assign('seller_qrcode_img', $filename);
$smarty->assign('seller_qrcode_text', $basic_info['shop_name']);
$basic_info['shop_logo'] = str_replace('../', '', $basic_info['shop_logo']);
if (($GLOBALS['_CFG']['open_oss'] == 1) && $basic_info['shop_logo']) {
	$bucket_info = get_bucket_info();
	$basic_info['shop_logo'] = $bucket_info['endpoint'] . $basic_info['shop_logo'];
}
else {
	$basic_info['shop_logo'] = $_CFG['site_domain'] . $basic_info['shop_logo'];
}

if ($basic_info['kf_qq']) {
	$kf_qq = array_filter(preg_split('/\\s+/', $basic_info['kf_qq']));
	$kf_qq = explode('|', $kf_qq[0]);

	if (!empty($kf_qq[1])) {
		$basic_info['kf_qq'] = $kf_qq[1];
	}
	else {
		$basic_info['kf_qq'] = '';
	}
}
else {
	$basic_info['kf_qq'] = '';
}

if ($basic_info['kf_ww']) {
	$kf_ww = array_filter(preg_split('/\\s+/', $basic_info['kf_ww']));
	$kf_ww = explode('|', $kf_ww[0]);

	if (!empty($kf_ww[1])) {
		$basic_info['kf_ww'] = $kf_ww[1];
	}
	else {
		$basic_info['kf_ww'] = '';
	}
}
else {
	$basic_info['kf_ww'] = '';
}

$cat_list = cat_list(0, 1, 0, 'merchants_category', array(), 0, $merchant_id);
$smarty->assign('cat_store_list', $cat_list);
$smarty->assign('basic_info', $basic_info);
$smarty->assign('grade_info', $grade_info);
$smarty->assign('site_domain', $_CFG['site_domain']);
$smarty->assign('merchant_id', $merchant_id);
$smarty->display('preview.dwt');

?>
