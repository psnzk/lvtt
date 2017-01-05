<?php
//dezend by  QQ:2172298892
function get_user_surplus($user_id)
{
	$sql = 'SELECT user_money FROM ' . $GLOBALS['ecs']->table('users') . ' WHERE user_id = \'' . $user_id . '\'';
	$res = $GLOBALS['db']->getOne($sql);
	return $res;
}

function update_user_account($id, $amount, $admin_note, $is_paid)
{
	$sql = 'UPDATE ' . $GLOBALS['ecs']->table('user_account') . ' SET ' . 'admin_user  = \'' . $_SESSION['admin_name'] . '\', ' . 'amount      = \'' . $amount . '\', ' . 'paid_time   = \'' . gmtime() . '\', ' . 'admin_note  = \'' . $admin_note . '\', ' . 'is_paid     = \'' . $is_paid . '\' WHERE id = \'' . $id . '\'';
	return $GLOBALS['db']->query($sql);
}

function account_list()
{
	$result = get_filter();

	if ($result === false) {
		$filter['user_id'] = !empty($_REQUEST['user_id']) ? intval($_REQUEST['user_id']) : 0;
		$filter['keywords'] = empty($_REQUEST['keywords']) ? '' : trim($_REQUEST['keywords']);
		if (isset($_REQUEST['is_ajax']) && ($_REQUEST['is_ajax'] == 1)) {
			$filter['keywords'] = json_str_iconv($filter['keywords']);
		}

		$filter['process_type'] = isset($_REQUEST['process_type']) ? intval($_REQUEST['process_type']) : -1;
		$filter['payment'] = empty($_REQUEST['payment']) ? '' : trim($_REQUEST['payment']);
		$filter['is_paid'] = isset($_REQUEST['is_paid']) ? intval($_REQUEST['is_paid']) : -1;
		$filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'add_time' : trim($_REQUEST['sort_by']);
		$filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
		$filter['start_date'] = empty($_REQUEST['start_date']) ? '' : local_strtotime($_REQUEST['start_date']);
		$filter['end_date'] = empty($_REQUEST['end_date']) ? '' : local_strtotime($_REQUEST['end_date']) + 86400;
		$where = ' WHERE 1 ';

		if (0 < $filter['user_id']) {
			$where .= ' AND ua.user_id = \'' . $filter['user_id'] . '\' ';
		}

		if ($filter['process_type'] != -1) {
			$where .= ' AND ua.process_type = \'' . $filter['process_type'] . '\' ';
		}
		else {
			$where .= ' AND ua.process_type ' . db_create_in(array(SURPLUS_SAVE, SURPLUS_RETURN));
		}

		if ($filter['payment']) {
			$where .= ' AND ua.payment = \'' . $filter['payment'] . '\' ';
		}

		if ($filter['is_paid'] != -1) {
			$where .= ' AND ua.is_paid = \'' . $filter['is_paid'] . '\' ';
		}

		$leftJoin = '';

		if ($filter['keywords']) {
			$where .= ' AND u.user_name LIKE \'%' . mysql_like_quote($filter['keywords']) . '%\'';
			$leftJoin = 'LEFT JOIN' . $GLOBALS['ecs']->table('users') . ' AS u ON ua.user_id = u.user_id';
		}

		if (!empty($filter['start_date']) && !empty($filter['end_date'])) {
			$where .= 'AND paid_time >= ' . $filter['start_date'] . ' AND paid_time < \'' . $filter['end_date'] . '\'';
		}

		$sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('user_account') . ' AS ua ' . $leftJoin . $where;
		$filter['record_count'] = $GLOBALS['db']->getOne($sql);
		$filter = page_and_size($filter);
		$sql = 'SELECT ua.*, u.user_name FROM ' . $GLOBALS['ecs']->table('user_account') . ' AS ua LEFT JOIN ' . $GLOBALS['ecs']->table('users') . ' AS u ON ua.user_id = u.user_id' . $where . 'ORDER by ' . $filter['sort_by'] . ' ' . $filter['sort_order'] . ' LIMIT ' . $filter['start'] . ', ' . $filter['page_size'];
		$filter['keywords'] = stripslashes($filter['keywords']);
		set_filter($filter, $sql);
	}
	else {
		$sql = $result['sql'];
		$filter = $result['filter'];
	}

	$list = $GLOBALS['db']->getAll($sql);

	foreach ($list as $key => $value) {
		$list[$key]['surplus_amount'] = price_format(abs($value['amount']), false);
		$list[$key]['add_date'] = local_date($GLOBALS['_CFG']['time_format'], $value['add_time']);
		$list[$key]['process_type_name'] = $GLOBALS['_LANG']['surplus_type_' . $value['process_type']];
	}

	$arr = array('list' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
	return $arr;
}

define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';

if (empty($_REQUEST['act'])) {
	$_REQUEST['act'] = 'list';
}
else {
	$_REQUEST['act'] = trim($_REQUEST['act']);
}

if ($_REQUEST['act'] == 'list') {
	admin_priv('surplus_manage');
	$user_id = (!empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0);
	$payment = array();
	$sql = 'SELECT pay_id, pay_name FROM ' . $ecs->table('payment') . ' WHERE enabled = 1 AND pay_code != \'cod\' ORDER BY pay_id';
	$res = $db->query($sql);

	while ($row = $db->fetchRow($res)) {
		$payment[$row['pay_name']] = $row['pay_name'];
	}

	if (isset($_REQUEST['process_type'])) {
		$smarty->assign('process_type_' . intval($_REQUEST['process_type']), 'selected="selected"');
	}

	if (isset($_REQUEST['is_paid'])) {
		$smarty->assign('is_paid_' . intval($_REQUEST['is_paid']), 'selected="selected"');
	}

	$smarty->assign('ur_here', $_LANG['09_user_account']);
	$smarty->assign('id', $user_id);
	$smarty->assign('payment_list', $payment);
	$smarty->assign('action_link', array('text' => $_LANG['surplus_add'], 'href' => 'user_account.php?act=add'));
	$list = account_list();
	$smarty->assign('list', $list['list']);
	$smarty->assign('filter', $list['filter']);
	$smarty->assign('record_count', $list['record_count']);
	$smarty->assign('page_count', $list['page_count']);
	$smarty->assign('full_page', 1);
	assign_query_info();
	$smarty->display('user_account_list.dwt');
}
else {
	if (($_REQUEST['act'] == 'add') || ($_REQUEST['act'] == 'edit')) {
		admin_priv('surplus_manage');
		$ur_here = ($_REQUEST['act'] == 'add' ? $_LANG['surplus_add'] : $_LANG['surplus_edit']);
		$form_act = ($_REQUEST['act'] == 'add' ? 'insert' : 'update');
		$id = (isset($_GET['id']) ? intval($_GET['id']) : 0);
		$user_account = array();
		$payment = array();
		$sql = 'SELECT pay_id, pay_name FROM ' . $ecs->table('payment') . ' WHERE enabled = 1 AND pay_code != \'cod\' ORDER BY pay_id';
		$res = $db->query($sql);

		while ($row = $db->fetchRow($res)) {
			$row['pay_name'] = strip_tags($row['pay_name']);
			$payment[$row['pay_name']] = $row['pay_name'];
		}

		if ($_REQUEST['act'] == 'edit') {
			$user_account = $db->getRow('SELECT * FROM ' . $ecs->table('user_account') . ' WHERE id = \'' . $id . '\'');
			$user_account['amount'] = str_replace('-', '', $user_account['amount']);
			$sql = 'SELECT user_name FROM ' . $ecs->table('users') . ' WHERE user_id = \'' . $user_account['user_id'] . '\'';
			$user_name = $db->getOne($sql);
		}
		else {
			$surplus_type = '';
			$user_name = '';
		}

		$smarty->assign('ur_here', $ur_here);
		$smarty->assign('form_act', $form_act);
		$smarty->assign('payment_list', $payment);
		$smarty->assign('action', $_REQUEST['act']);
		$smarty->assign('user_surplus', $user_account);
		$smarty->assign('user_name', $user_name);

		if ($_REQUEST['act'] == 'add') {
			$href = 'user_account.php?act=list';
		}
		else {
			$href = 'user_account.php?act=list&' . list_link_postfix();
		}

		$smarty->assign('action_link', array('href' => $href, 'text' => $_LANG['09_user_account']));
		assign_query_info();
		$smarty->display('user_account_info.dwt');
	}
	else {
		if (($_REQUEST['act'] == 'insert') || ($_REQUEST['act'] == 'update')) {
			admin_priv('surplus_manage');
			$id = (isset($_POST['id']) ? intval($_POST['id']) : 0);
			$is_paid = (!empty($_POST['is_paid']) ? intval($_POST['is_paid']) : 0);
			$amount = (!empty($_POST['amount']) ? floatval($_POST['amount']) : 0);
			$process_type = (!empty($_POST['process_type']) ? intval($_POST['process_type']) : 0);
			$user_name = (!empty($_POST['user_id']) ? trim($_POST['user_id']) : '');
			$admin_note = (!empty($_POST['admin_note']) ? trim($_POST['admin_note']) : '');
			$user_note = (!empty($_POST['user_note']) ? trim($_POST['user_note']) : '');
			$payment = (!empty($_POST['payment']) ? trim($_POST['payment']) : '');
			$user_id = $db->getOne('SELECT user_id FROM ' . $ecs->table('users') . ' WHERE user_name = \'' . $user_name . '\'');

			if ($user_id == 0) {
				$link[] = array('text' => $_LANG['go_back'], 'href' => 'javascript:history.back(-1)');
				sys_msg($_LANG['username_not_exist'], 0, $link);
			}

			if ($process_type == 1) {
				$user_account = get_user_surplus($user_id);

				if ($user_account < $amount) {
					$link[] = array('text' => $_LANG['go_back'], 'href' => 'javascript:history.back(-1)');
					sys_msg($_LANG['surplus_amount_error'], 0, $link);
				}
			}

			if ($_REQUEST['act'] == 'insert') {
				if ($process_type == 1) {
					$amount = -1 * $amount;
				}

				$sql = 'INSERT INTO ' . $ecs->table('user_account') . ' VALUES (\'\', \'' . $user_id . '\', \'' . $_SESSION['admin_name'] . '\', \'' . $amount . '\', \'' . gmtime() . '\', \'' . gmtime() . '\', \'' . $admin_note . '\', \'' . $user_note . '\', \'' . $process_type . '\', \'' . $payment . '\', \'' . $is_paid . '\')';
				$db->query($sql);
				$id = $db->insert_id();
			}
			else {
				$sql = 'UPDATE ' . $ecs->table('user_account') . ' SET ' . 'admin_note   = \'' . $admin_note . '\', ' . 'user_note    = \'' . $user_note . '\', ' . 'payment      = \'' . $payment . '\' ' . 'WHERE id      = \'' . $id . '\'';
				$db->query($sql);
			}

			if ($is_paid == 1) {
				$change_desc = (0 < $amount ? $_LANG['surplus_type_0'] : $_LANG['surplus_type_1']);
				$change_type = (0 < $amount ? ACT_SAVING : ACT_DRAWING);
				log_account_change($user_id, $amount, 0, 0, 0, $change_desc, $change_type);
			}

			if (($process_type == 0) && ($is_paid == 0)) {
				include_once ROOT_PATH . 'includes/lib_order.php';
				$payment_info = array();
				$payment_info = $db->getRow('SELECT * FROM ' . $ecs->table('payment') . ' WHERE pay_name = \'' . $payment . '\' AND enabled = \'1\'');
				$pay_fee = pay_fee($payment_info['pay_id'], $amount, 0);
				$total_fee = $pay_fee + $amount;
				$sql = 'INSERT INTO ' . $ecs->table('pay_log') . ' (order_id, order_amount, order_type, is_paid)' . ' VALUES (\'' . $id . '\', \'' . $total_fee . '\', \'' . PAY_SURPLUS . '\', 0)';
				$db->query($sql);
			}

			if ($_REQUEST['act'] == 'update') {
				admin_log($user_name, 'edit', 'user_surplus');
			}
			else {
				admin_log($user_name, 'add', 'user_surplus');
			}

			if ($_REQUEST['act'] == 'insert') {
				$href = 'user_account.php?act=list';
			}
			else {
				$href = 'user_account.php?act=list&' . list_link_postfix();
			}

			$link[0]['text'] = $_LANG['back_list'];
			$link[0]['href'] = $href;
			$link[1]['text'] = $_LANG['continue_add'];
			$link[1]['href'] = 'user_account.php?act=add';
			sys_msg($_LANG['attradd_succed'], 0, $link);
		}
		else if ($_REQUEST['act'] == 'check') {
			admin_priv('surplus_manage');
			$id = (isset($_GET['id']) ? intval($_GET['id']) : 0);

			if ($id == 0) {
				ecs_header("Location: user_account.php?act=list\n");
				exit();
			}

			$account = array();
			$account = $db->getRow('SELECT * FROM ' . $ecs->table('user_account') . ' WHERE id = \'' . $id . '\'');
			$account['add_time'] = local_date($_CFG['time_format'], $account['add_time']);
			if (($account['process_type'] == 1) || ($account['payment'] == '银行汇款/转帐')) {
				$sql = 'SELECT real_name, bank_card, self_num  FROM ' . $ecs->table('users_real') . ' WHERE user_id = \'' . $account['user_id'] . '\'';
				$account['real'] = $db->getRow($sql);
				$account['processType'] = 1;
			}

			$account['fields'] = $db->getRow('SELECT * FROM ' . $ecs->table('user_account_fields') . ' WHERE user_id = \'' . $account['user_id'] . '\' and account_id=\'' . $id . '\'');

			if ($account['process_type'] == 0) {
				$process_type = $_LANG['surplus_type_0'];
			}
			else if ($account['process_type'] == 1) {
				$process_type = $_LANG['surplus_type_1'];
			}
			else if ($account['process_type'] == 2) {
				$process_type = $_LANG['surplus_type_2'];
			}
			else {
				$process_type = $_LANG['surplus_type_3'];
			}

			$sql = 'SELECT user_name FROM ' . $ecs->table('users') . ' WHERE user_id = \'' . $account['user_id'] . '\'';
			$user_name = $db->getOne($sql);
			$smarty->assign('ur_here', $_LANG['check']);
			$account['user_note'] = htmlspecialchars($account['user_note']);
			$smarty->assign('surplus', $account);
			$smarty->assign('process_type', $process_type);
			$smarty->assign('user_name', $user_name);
			$smarty->assign('id', $id);
			$smarty->assign('action_link', array('text' => $_LANG['09_user_account'], 'href' => 'user_account.php?act=list&' . list_link_postfix()));
			assign_query_info();
			$smarty->display('user_account_check.dwt');
		}
		else if ($_REQUEST['act'] == 'action') {
			admin_priv('surplus_manage');
			$id = (isset($_POST['id']) ? intval($_POST['id']) : 0);
			$is_paid = (isset($_POST['is_paid']) ? intval($_POST['is_paid']) : 0);
			$admin_note = (isset($_POST['admin_note']) ? trim($_POST['admin_note']) : '');
			if (($id == 0) || empty($admin_note)) {
				ecs_header("Location: user_account.php?act=list\n");
				exit();
			}

			$account = array();
			$account = $db->getRow('SELECT * FROM ' . $ecs->table('user_account') . ' WHERE id = \'' . $id . '\'');
			$amount = $account['amount'];

			if ($account['is_paid'] == 0) {
				if (($is_paid == '1') && ($account['process_type'] == '1')) {
					$user_account = get_user_surplus($account['user_id']);
					$fmt_amount = str_replace('-', '', $amount);

					if ($user_account < $fmt_amount) {
						$link[] = array('text' => $_LANG['go_back'], 'href' => 'javascript:history.back(-1)');
						sys_msg($_LANG['surplus_amount_error'], 0, $link);
					}

					update_user_account($id, $amount, $admin_note, $is_paid);
					log_account_change($account['user_id'], $amount, 0, 0, 0, $_LANG['surplus_type_1'], ACT_DRAWING);
				}
				else {
					if (($is_paid == '1') && ($account['process_type'] == '0')) {
						update_user_account($id, $amount, $admin_note, $is_paid);
						log_account_change($account['user_id'], $amount, 0, 0, 0, $_LANG['surplus_type_0'], ACT_SAVING);
					}
					else if ($is_paid == '0') {
						$sql = 'UPDATE ' . $ecs->table('user_account') . ' SET ' . 'admin_user    = \'' . $_SESSION['admin_name'] . '\', ' . 'admin_note    = \'' . $admin_note . '\', ' . 'is_paid       = 0 WHERE id = \'' . $id . '\'';
						$db->query($sql);
					}
				}

				admin_log('(' . addslashes($_LANG['check']) . ')' . $admin_note, 'edit', 'user_surplus');
				$link[0]['text'] = $_LANG['back_list'];
				$link[0]['href'] = 'user_account.php?act=list&' . list_link_postfix();
				sys_msg($_LANG['attradd_succed'], 0, $link);
			}
		}
		else if ($_REQUEST['act'] == 'query') {
			$list = account_list();
			$smarty->assign('list', $list['list']);
			$smarty->assign('filter', $list['filter']);
			$smarty->assign('record_count', $list['record_count']);
			$smarty->assign('page_count', $list['page_count']);
			$sort_flag = sort_flag($list['filter']);
			$smarty->assign($sort_flag['tag'], $sort_flag['img']);
			make_json_result($smarty->fetch('user_account_list.dwt'), '', array('filter' => $list['filter'], 'page_count' => $list['page_count']));
		}
		else if ($_REQUEST['act'] == 'remove') {
			check_authz_json('surplus_manage');
			$id = @intval($_REQUEST['id']);
			$sql = 'SELECT u.user_name FROM ' . $ecs->table('users') . ' AS u, ' . $ecs->table('user_account') . ' AS ua ' . ' WHERE u.user_id = ua.user_id AND ua.id = \'' . $id . '\' ';
			$user_name = $db->getOne($sql);
			$sql = 'DELETE FROM ' . $ecs->table('user_account') . ' WHERE id = \'' . $id . '\'';

			if ($db->query($sql, 'SILENT')) {
				admin_log(addslashes($user_name), 'remove', 'user_surplus');
				$url = 'user_account.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
				ecs_header('Location: ' . $url . "\n");
				exit();
			}
			else {
				make_json_error($db->error());
			}
		}
	}
}

?>
