<?php
//dezend by  QQ:2172298892
define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';
require ROOT_PATH . '/includes/cls_json.php';
$json = new JSON();

if ($_REQUEST['act'] == 'operate') {
	$result = array('dialog_type' => '', 'app' => '', 'content' => '');
	$dialog_type = (empty($_REQUEST['dialog_type']) ? '' : trim($_REQUEST['dialog_type']));
	$app = (empty($_REQUEST['app']) ? '' : trim($_REQUEST['app']));
	$message = (empty($_REQUEST['message']) ? '' : trim($_REQUEST['message']));
	$smarty->assign('dialog_type', $dialog_type);
	$smarty->assign('app', $app);
	$smarty->assign('message', $message);
	$result['dialog_type'] = $dialog_type;
	$result['app'] = $app;
	$result['content'] = $GLOBALS['smarty']->fetch('dialog.dwt');
	exit($json->encode($result));
}

?>
