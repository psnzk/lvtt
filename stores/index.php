<?php
//dezend by  QQ:2172298892
define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';
if (($_REQUEST['act'] == 'merchants_first') || ($_REQUEST['act'] == 'shop_top') || ($_REQUEST['act'] == 'merchants_second')) {
	$smarty->assign('action_type', 'index');
}
else {
	$smarty->assign('action_type', '');
}

$store_id = $_SESSION['stores_id'];
$ru_id = $GLOBALS['db']->getOne(' SELECT ru_id FROM ' . $GLOBALS['ecs']->table('offline_store') . ' WHERE id = \'' . $store_id . '\' ');

if ($_REQUEST['act'] == '') {
	header('location:goods.php?act=list');
	$smarty->display('index.dwt');
}
else if ($_REQUEST['act'] == 'upload_store_img') {
	$result = array('error' => 0, 'message' => '', 'content' => '');
	include_once ROOT_PATH . '/includes/cls_image.php';
	$image = new cls_image($_CFG['bgcolor']);

	if ($_FILES['img']['name']) {
		$dir = 'store_user';
		$img_name = $image->upload_image($_FILES['img'], $dir);

		if ($img_name) {
			$result['error'] = 1;
			$result['content'] = '../' . $img_name;
			$store_user_img = $GLOBALS['db']->getOne(' SELECT store_user_img FROM ' . $GLOBALS['ecs']->table('store_user') . ' WHERE id = \'' . $store_user_id . '\' ');
			@unlink('../' . $store_user_img);
			$sql = ' UPDATE ' . $GLOBALS['ecs']->table('store_user') . ' SET store_user_img = \'' . $img_name . '\' WHERE id = \'' . $store_user_id . '\' ';
			$GLOBALS['db']->query($sql);
		}
	}

	exit(json_encode($result));
}
else if ($_REQUEST['act'] == 'upload_stores_img') {
	$result = array('error' => 0, 'message' => '', 'content' => '');
	include_once ROOT_PATH . '/includes/cls_image.php';
	$image = new cls_image($_CFG['bgcolor']);

	if ($_FILES['stores_img']['name']) {
		$dir = 'offline_store';
		$img_name = $image->upload_image($_FILES['stores_img'], $dir);

		if ($img_name) {
			$result['error'] = 1;
			$result['content'] = '../' . $img_name;
			$stores_img = $GLOBALS['db']->getOne(' SELECT stores_img FROM ' . $GLOBALS['ecs']->table('offline_store') . ' WHERE id = \'' . $store_id . '\' ');
			@unlink('../' . $stores_img);
			$sql = ' UPDATE ' . $GLOBALS['ecs']->table('offline_store') . ' SET stores_img = \'' . $img_name . '\' WHERE id = \'' . $store_id . '\' ';
			$GLOBALS['db']->query($sql);
		}
	}

	exit(json_encode($result));
}
else if ($_REQUEST['act'] == 'clear_cache') {
	if (file_exists(ROOT_PATH . 'mobile/api/script/clear_cache.php')) {
		require_once ROOT_PATH . 'mobile/api/script/clear_cache.php';
	}

	clear_all_files('', STORES_PATH);
	sys_msg($_LANG['caches_cleared']);
}

?>
