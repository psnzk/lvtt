<?php
//zend by QQ:2172298892
function return_url($code)
{
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

	return $url . 'respond.php?code=' . $code;
}

function get_payment($code)
{
	$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('payment') . ' WHERE pay_code = \'' . $code . '\' AND enabled = \'1\'';
	$payment = $GLOBALS['db']->getRow($sql);

	if ($payment) {
		$config_list = unserialize($payment['pay_config']);

		foreach ($config_list as $config) {
			$payment[$config['name']] = $config['value'];
		}
	}

	return $payment;
}

function get_order_id_by_sn($order_sn, $voucher = 'false')
{
	if ($voucher == 'true') {
		if (is_numeric($order_sn)) {
			return $GLOBALS['db']->getOne('SELECT log_id FROM ' . $GLOBALS['ecs']->table('pay_log') . ' WHERE order_id=' . $order_sn . ' AND order_type=1');
		}
		else {
			return '';
		}
	}
	else {
		if (is_numeric($order_sn)) {
			$sql = 'SELECT order_id FROM ' . $GLOBALS['ecs']->table('order_info') . ' WHERE order_sn = \'' . $order_sn . '\'';
			$order_id = $GLOBALS['db']->getOne($sql);
		}

		if (!empty($order_id)) {
			$pay_log_id = $GLOBALS['db']->getOne('SELECT log_id FROM ' . $GLOBALS['ecs']->table('pay_log') . ' WHERE order_id=\'' . $order_id . '\'');
			return $pay_log_id;
		}
		else {
			return '';
		}
	}
}

function get_goods_name_by_id($order_id)
{
	$sql = 'SELECT goods_name FROM ' . $GLOBALS['ecs']->table('order_goods') . ' WHERE order_id = \'' . $order_id . '\'';
	$goods_name = $GLOBALS['db']->getCol($sql);
	return implode(',', $goods_name);
}

function check_money($log_id, $money)
{
	if (is_numeric($log_id)) {
		$sql = 'SELECT order_amount FROM ' . $GLOBALS['ecs']->table('pay_log') . ' WHERE log_id = \'' . $log_id . '\'';
		$amount = $GLOBALS['db']->getOne($sql);
	}
	else {
		return false;
	}

	if ($money == $amount) {
		return true;
	}
	else {
		return false;
	}
}

function order_paid($log_id, $pay_status = PS_PAYED, $note = '')
{
	$log_id = intval($log_id);

	if (0 < $log_id) {
		$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('pay_log') . ' WHERE log_id = \'' . $log_id . '\'';
		$pay_log = $GLOBALS['db']->getRow($sql);
		if ($pay_log && ($pay_log['is_paid'] == 0)) {
			$sql = 'UPDATE ' . $GLOBALS['ecs']->table('pay_log') . ' SET is_paid = \'1\' WHERE log_id = \'' . $log_id . '\'';
			$GLOBALS['db']->query($sql);

			if ($pay_log['order_type'] == PAY_ORDER) {
				$sql = 'SELECT main_order_id, order_id, user_id, order_sn, consignee, address, tel, mobile, shipping_id, pay_status, extension_code, extension_id, goods_amount, ' . 'shipping_fee, insure_fee, pay_fee, tax, pack_fee, card_fee, surplus, money_paid, integral_money, bonus, order_amount, discount, pay_id ' . 'FROM ' . $GLOBALS['ecs']->table('order_info') . ' WHERE order_id = \'' . $pay_log['order_id'] . '\'';
				$order = $GLOBALS['db']->getRow($sql);
				$main_order_id = $order['main_order_id'];
				$order_id = $order['order_id'];
				$order_sn = $order['order_sn'];
				$pay_fee = order_pay_fee($order['pay_id'], $pay_log['order_amount']);
				update_zc_project($order_id);

				if ($order['extension_code'] == 'presale') {
					$money_paid = $order['money_paid'] + $order['order_amount'];

					if ($order['pay_status'] == 0) {
						$order_amount = ($order['goods_amount'] + $order['shipping_fee'] + $order['insure_fee'] + $order['pay_fee'] + $order['tax']) - $order['money_paid'] - $order['order_amount'];
						$sql = 'UPDATE ' . $GLOBALS['ecs']->table('order_info') . ' SET order_status = \'' . OS_CONFIRMED . '\', ' . ' confirm_time = \'' . gmtime() . '\', ' . ' pay_status = \'' . PS_PAYED_PART . '\', ' . ' pay_time = \'' . gmtime() . '\', ' . ' pay_fee = \'' . $pay_fee . '\', ' . ' money_paid = \'' . $money_paid . '\',' . ' order_amount = \'' . $order_amount . '\' ' . 'WHERE order_id = \'' . $order_id . '\'';
						$GLOBALS['db']->query($sql);
						order_action($order_sn, OS_CONFIRMED, SS_UNSHIPPED, PS_PAYED_PART, $note, $GLOBALS['_LANG']['buyer']);
						update_pay_log($order_id);
					}
					else {
						$sql = 'UPDATE ' . $GLOBALS['ecs']->table('order_info') . ' SET pay_status = \'' . PS_PAYED . '\', ' . ' pay_time = \'' . gmtime() . '\', ' . ' pay_fee = \'' . $pay_fee . '\', ' . ' money_paid = \'' . $money_paid . '\',' . ' order_amount = 0 ' . 'WHERE order_id = \'' . $order_id . '\'';
						$GLOBALS['db']->query($sql);
						order_action($order_sn, OS_CONFIRMED, SS_UNSHIPPED, PS_PAYED, $note, $GLOBALS['_LANG']['buyer']);
					}
				}
				else {
					$sql = 'UPDATE ' . $GLOBALS['ecs']->table('order_info') . ' SET order_status = \'' . OS_CONFIRMED . '\', ' . ' confirm_time = \'' . gmtime() . '\', ' . ' pay_status = \'' . $pay_status . '\', ' . ' pay_fee = \'' . $pay_fee . '\', ' . ' pay_time = \'' . gmtime() . '\', ' . ' money_paid = money_paid + order_amount,' . ' order_amount = 0 ' . 'WHERE order_id = \'' . $order_id . '\'';
					$GLOBALS['db']->query($sql);
					order_action($order_sn, OS_CONFIRMED, SS_UNSHIPPED, $pay_status, $note, $GLOBALS['_LANG']['buyer']);
				}

				$sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('order_info') . ' WHERE main_order_id = \'' . $order_id . '\'';
				$child_num = $GLOBALS['db']->getOne($sql);
				if (($main_order_id == 0) && (0 < $child_num)) {
					$sql = 'SELECT order_id, order_sn, pay_id, order_amount ' . 'FROM ' . $GLOBALS['ecs']->table('order_info') . ' WHERE main_order_id = \'' . $order_id . '\'';
					$order_res = $GLOBALS['db']->getAll($sql);

					foreach ($order_res as $row) {
						$child_pay_fee = order_pay_fee($row['pay_id'], $row['order_amount']);
						$sql = 'UPDATE ' . $GLOBALS['ecs']->table('order_info') . ' SET order_status = \'' . OS_CONFIRMED . '\', ' . ' confirm_time = \'' . gmtime() . '\', ' . ' pay_status = \'' . $pay_status . '\', ' . ' pay_time = \'' . gmtime() . '\', ' . ' pay_fee = \'' . $child_pay_fee . '\', ' . ' money_paid = order_amount,' . ' order_amount = 0 ' . 'WHERE order_id = \'' . $row['order_id'] . '\'';
						$GLOBALS['db']->query($sql);
						order_action($row['order_sn'], OS_CONFIRMED, SS_UNSHIPPED, $pay_status, $note, $GLOBALS['_LANG']['buyer']);
					}
				}

				$sql = 'SELECT ru_id FROM ' . $GLOBALS['ecs']->table('order_goods') . ' WHERE order_id = \'' . $order_id . '\'';
				$ru_id = $GLOBALS['db']->getOne($sql, true);
				$shop_name = get_shop_name($ru_id, 1);

				if ($ru_id == 0) {
					$sms_shop_mobile = $GLOBALS['_CFG']['sms_shop_mobile'];
				}
				else {
					$sql = 'SELECT mobile FROM ' . $GLOBALS['ecs']->table('seller_shopinfo') . ' WHERE ru_id = \'' . $ru_id . '\'';
					$sms_shop_mobile = $GLOBALS['db']->getOne($sql);
				}

				if (($GLOBALS['_CFG']['sms_order_payed'] == '1') && ($sms_shop_mobile != '')) {
					$msg = sprintf($GLOBALS['_LANG']['order_payed_sms'], $order_sn, $order['consignee'], $order['mobile']);

					if ($GLOBALS['_CFG']['sms_type'] == 0) {
						include_once ROOT_PATH . 'includes/cls_sms.php';
						$sms = new sms();
						$sms->send($sms_shop_mobile, $msg, '', 13, 1);
					}
					else if ($GLOBALS['_CFG']['sms_type'] == 1) {
						$order_region = get_flow_user_region($order_id);
						$smsParams = array('shop_name' => $shop_name, 'order_sn' => $order_sn, 'consignee' => $order['consignee'], 'order_region' => $order_region, 'address' => $order['address'], 'order_mobile' => $order['mobile'], 'mobile_phone' => $sms_shop_mobile);
						$result = sms_ali($smsParams, 'sms_order_payed');
						$resp = $GLOBALS['ecs']->ali_yu($result);
					}
				}

				$virtual_goods = get_virtual_goods($order_id);

				if (!empty($virtual_goods)) {
					$msg = '';

					if (!virtual_goods_ship($virtual_goods, $msg, $order_sn, true)) {
						$GLOBALS['_LANG']['pay_success'] .= '<div style="color:red;">' . $msg . '</div>' . $GLOBALS['_LANG']['virtual_goods_ship_fail'];
					}

					if ($order['shipping_id'] == -1) {
						$sql = 'UPDATE ' . $GLOBALS['ecs']->table('order_info') . ' SET shipping_status = \'' . SS_SHIPPED . '\', shipping_time = \'' . gmtime() . '\'' . ' WHERE order_id = \'' . $order_id . '\'';
						$GLOBALS['db']->query($sql);
						order_action($order_sn, OS_CONFIRMED, SS_SHIPPED, $pay_status, $note, $GLOBALS['_LANG']['buyer']);
						$integral = integral_to_give($order);
						log_account_change($order['user_id'], 0, 0, intval($integral['rank_points']), intval($integral['custom_points']), sprintf($GLOBALS['_LANG']['order_gift_integral'], $order['order_sn']));
					}
				}
			}
			else if ($pay_log['order_type'] == PAY_SURPLUS) {
				$sql = 'SELECT `id` FROM ' . $GLOBALS['ecs']->table('user_account') . ' WHERE `id` = \'' . $pay_log['order_id'] . '\' AND `is_paid` = 1  LIMIT 1';
				$res_id = $GLOBALS['db']->getOne($sql);

				if (empty($res_id)) {
					$sql = 'UPDATE ' . $GLOBALS['ecs']->table('user_account') . ' SET paid_time = \'' . gmtime() . '\', is_paid = 1' . ' WHERE id = \'' . $pay_log['order_id'] . '\' LIMIT 1';
					$GLOBALS['db']->query($sql);
					$sql = 'SELECT user_id, amount FROM ' . $GLOBALS['ecs']->table('user_account') . ' WHERE id = \'' . $pay_log['order_id'] . '\'';
					$arr = $GLOBALS['db']->getRow($sql);
					$_LANG = array();
					include_once ROOT_PATH . 'languages/' . $GLOBALS['_CFG']['lang'] . '/user.php';
					log_account_change($arr['user_id'], $arr['amount'], 0, 0, 0, $_LANG['surplus_type_0'], ACT_SAVING);
				}
			}
			else if ($pay_log['order_type'] == PAY_APPLYGRADE) {
				$sql = ' UPDATE ' . $GLOBALS['ecs']->table('seller_apply_info') . ' SET is_paid = 1 ,pay_time = \'' . gmtime() . '\' ,pay_status = 1 WHERE apply_id= \'' . $pay_log['order_id'] . '\'';
				$GLOBALS['db']->query($sql);
			}
			else if ($pay_log['order_type'] == PAY_TOPUP) {
				$sql = 'SELECT ru_id FROM ' . $GLOBALS['ecs']->table('seller_account_log') . ' WHERE log_id = \'' . $pay_log['order_id'] . '\' LIMIT 1';
				$account_log = $GLOBALS['db']->getRow($sql);
				$sql = ' UPDATE ' . $GLOBALS['ecs']->table('seller_account_log') . ' SET is_paid = 1 WHERE log_id = \'' . $pay_log['order_id'] . '\'';
				$GLOBALS['db']->query($sql);
				$sql = ' UPDATE ' . $GLOBALS['ecs']->table('seller_shopinfo') . ' SET seller_money = seller_money + ' . $pay_log['order_amount'] . ' WHERE ru_id = \'' . $account_log['ru_id'] . '\'';
				$GLOBALS['db']->query($sql);
			}
		}
		else {
			$post_virtual_goods = get_virtual_goods($pay_log['order_id'], true);

			if (!empty($post_virtual_goods)) {
				$msg = '';
				$sql = 'SELECT pay_time, order_sn FROM ' . $GLOBALS['ecs']->table('order_info') . ' WHERE order_id = \'' . $pay_log['order_id'] . '\'';
				$row = $GLOBALS['db']->getRow($sql);
				$intval_time = gmtime() - $row['pay_time'];
				if ((0 <= $intval_time) && ($intval_time < (3600 * 12))) {
					$virtual_card = array();

					foreach ($post_virtual_goods as $code => $goods_list) {
						if ($code == 'virtual_card') {
							foreach ($goods_list as $goods) {
								if ($info = virtual_card_result($row['order_sn'], $goods)) {
									$virtual_card[] = array('goods_id' => $goods['goods_id'], 'goods_name' => $goods['goods_name'], 'info' => $info);
								}
							}

							$GLOBALS['smarty']->assign('virtual_card', $virtual_card);
						}
					}
				}
				else {
					$msg = '<div>' . $GLOBALS['_LANG']['please_view_order_detail'] . '</div>';
				}

				$GLOBALS['_LANG']['pay_success'] .= $msg;
			}

			$virtual_goods = get_virtual_goods($pay_log['order_id'], false);

			if (!empty($virtual_goods)) {
				$GLOBALS['_LANG']['pay_success'] .= '<br />' . $GLOBALS['_LANG']['virtual_goods_ship_fail'];
			}
		}
	}
}

function order_payment_info($pay_id)
{
	$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('payment') . ' WHERE pay_id = \'' . $pay_id . '\' AND enabled = 1';
	return $GLOBALS['db']->getRow($sql);
}

function order_pay_fee($payment_id, $order_amount, $cod_fee = NULL)
{
	$pay_fee = 0;
	$payment = order_payment_info($payment_id);
	$rate = ($payment['is_cod'] && !is_null($cod_fee) ? $cod_fee : $payment['pay_fee']);

	if (strpos($rate, '%') !== false) {
		$val = floatval($rate) / 100;
		$pay_fee = (0 < $val ? ($order_amount * $val) / (1 - $val) : 0);
	}
	else {
		$pay_fee = floatval($rate);
	}

	return round($pay_fee, 2);
}

function update_pay_log($order_id)
{
	$order_id = intval($order_id);

	if (0 < $order_id) {
		$sql = 'SELECT order_amount FROM ' . $GLOBALS['ecs']->table('order_info') . ' WHERE order_id = \'' . $order_id . '\'';
		$order_amount = $GLOBALS['db']->getOne($sql);

		if (!is_null($order_amount)) {
			$sql = 'SELECT log_id FROM ' . $GLOBALS['ecs']->table('pay_log') . ' WHERE order_id = \'' . $order_id . '\'' . ' AND order_type = \'' . PAY_ORDER . '\'' . ' AND is_paid = 0';
			$log_id = intval($GLOBALS['db']->getOne($sql));

			if (0 < $log_id) {
				$sql = 'UPDATE ' . $GLOBALS['ecs']->table('pay_log') . ' SET order_amount = \'' . $order_amount . '\' ' . 'WHERE log_id = \'' . $log_id . '\' LIMIT 1';
			}
			else {
				$sql = 'INSERT INTO ' . $GLOBALS['ecs']->table('pay_log') . ' (order_id, order_amount, order_type, is_paid)' . 'VALUES(\'' . $order_id . '\', \'' . $order_amount . '\', \'' . PAY_ORDER . '\', 0)';
			}

			$GLOBALS['db']->query($sql);
		}
	}
}

if (!defined('IN_ECS')) {
	exit('Hacking attempt');
}

?>
