<?php
//dezend by  QQ:2172298892
if (!defined('IN_ECS')) {
	exit('Hacking attempt');
}

$cron_lang = ROOT_PATH . 'languages/' . $GLOBALS['_CFG']['lang'] . '/cron/auto_sms.php';

if (file_exists($cron_lang)) {
	global $_LANG;
	include_once $cron_lang;
}

if (isset($set_modules) && ($set_modules == true)) {
	$i = (isset($modules) ? count($modules) : 0);
	$modules[$i]['code'] = basename(__FILE__, '.php');
	$modules[$i]['desc'] = 'auto_sms_desc';
	$modules[$i]['author'] = 'ECSHOP TEAM';
	$modules[$i]['website'] = 'http://www.ecmoban.com';
	$modules[$i]['version'] = '1.0.0';
	$modules[$i]['config'] = array(
	array('name' => 'auto_sms_count', 'type' => 'select', 'value' => '10')
	);
	return NULL;
}

$where = ' where 1 ';
$sort = ' order by item_id DESC ';
$limit = (!empty($cron['auto_sms_count']) ? $cron['auto_sms_count'] : 5);
$user_id = (empty($_SESSION['user_id']) ? 0 : $_SESSION['user_id']);
$adminru = get_admin_ru_id();

if (!empty($user_id)) {
	$where .= ' and user_id= ' . $user_id;
}

if ((0 < $user_id) || $adminru) {
	$sql = ' select * from ' . $GLOBALS['ecs']->table('auto_sms') . $where . $sort . ' LIMIT ' . $limit;
	$item_list = $GLOBALS['db']->getAll($sql);

	if (0 < count($item_list)) {
		foreach ($item_list as $key => $val) {
			$sql = ' select * from ' . $GLOBALS['ecs']->table('order_info') . ' where order_id=\'' . $val['order_id'] . '\' ';
			$row = $GLOBALS['db']->getRow($sql);

			if ($val['ru_id'] == 0) {
				$sms_shop_mobile = $_CFG['sms_shop_mobile'];
				$service_email = $_CFG['service_email'];
				$shop_name = $GLOBALS['_CFG']['shop_name'];
			}
			else {
				$sql = 'SELECT mobile FROM ' . $GLOBALS['ecs']->table('seller_shopinfo') . ' WHERE ru_id = \'' . $val['ru_id'] . '\'';
				$sms_shop_mobile = $GLOBALS['db']->getOne($sql);
				$sql = 'SELECT seller_email FROM ' . $GLOBALS['ecs']->table('seller_shopinfo') . ' WHERE ru_id = \'' . $val['ru_id'] . '\'';
				$service_email = $GLOBALS['db']->getOne($sql);
				$seller_name = get_shop_name($val['ru_id'], 1);
				$shop_name = $seller_name;
			}

			if (($_CFG['sms_order_placed'] == '1') && ($sms_shop_mobile != '') && ($val['item_type'] == 1)) {
				$msg = ($row['pay_status'] == PS_UNPAYED ? $_LANG['order_placed_sms'] : $_LANG['order_placed_sms'] . '[' . $_LANG['sms_paid'] . ']');

				if ($GLOBALS['_CFG']['sms_type'] == 0) {
					include_once ROOT_PATH . 'includes/cls_sms.php';
					$sms = new sms();

					if ($sms->send($sms_shop_mobile, sprintf($msg, $row['consignee'], $row['mobile']), '', 13, 1)) {
						$sql = ' delete from ' . $GLOBALS['ecs']->table('auto_sms') . ' where item_id=\'' . $val['item_id'] . '\' ';
						$GLOBALS['db']->query($sql);
					}
				}
				else if ($GLOBALS['_CFG']['sms_type'] == 1) {
					$str_centent = array('shop_name' => '', 'user_name' => $shop_name, 'order_msg' => $msg, 'mobile_phone' => $sms_shop_mobile);
					$result = get_order_info_lang($str_centent);
					$resq = $GLOBALS['ecs']->ali_yu($result);
				}
			}

			if (((($val['ru_id'] == 0) && ($_CFG['send_service_email'] == '1')) || ((0 < $val['ru_id']) && ($_CFG['seller_email'] == '1'))) && ($service_email != '') && ($val['item_type'] == 2)) {
				$sql = ' select * from ' . $GLOBALS['ecs']->table('order_goods') . ' where order_id=\'' . $val['order_id'] . '\' ';
				$cart_goods = $GLOBALS['db']->getAll($sql);
				$tpl = get_mail_template('remind_of_new_order');
				$smarty->assign('order', $row);
				$smarty->assign('goods_list', $cart_goods);
				$smarty->assign('shop_name', $_CFG['shop_name']);
				$smarty->assign('send_date', local_date($GLOBALS['_CFG']['time_format'], gmtime()));
				$content = $smarty->fetch('str:' . $tpl['template_content']);

				if (send_mail($_CFG['shop_name'], $service_email, $tpl['template_subject'], $content, $tpl['is_html'])) {
					$sql = ' delete from ' . $GLOBALS['ecs']->table('auto_sms') . ' where item_id=\'' . $val['item_id'] . '\' ';
					$GLOBALS['db']->query($sql);
				}
			}
		}
	}
}

?>
