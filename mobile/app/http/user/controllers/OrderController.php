<?php
//dezend by  QQ:2172298892
namespace http\user\controllers;

class OrderController extends \http\base\controllers\FrontendController
{
	public $user_id;

	public function __construct()
	{
		parent::__construct();
		$this->user_id = $_SESSION['user_id'];
		$this->actionchecklogin();
		l(require LANG_PATH . c('shop.lang') . '/user.php');
		l(require LANG_PATH . c('shop.lang') . '/flow.php');
		$files = array('order', 'clips', 'payment', 'transaction');
		$this->load_helper($files);
	}

	public function actionIndex()
	{
		$size = 10;
		$page = i('page', 1, 'intval');
		$status = i('status', 0, 'intval');

		if (IS_POST) {
			$order_list = get_user_orders($this->user_id, $size, $page, $status);
			exit(json_encode(array('order_list' => $order_list['list'], 'totalPage' => $order_list['totalpage'])));
		}

		$this->assign('status', $status);
		$this->assign('page_title', l('order_list_lnk'));
		$this->display('user_order');
	}

	public function actionDetail()
	{
		$order_id = i('order_id', 0, 'intval');
		$noTime = gmtime();
		$date = array('order_status', 'shipping_status', 'pay_status', 'shipping_time', 'auto_delivery_time');
		$orderInfo = get_table_date('order_info', 'order_id = \'' . $order_id . '\' and user_id = \'' . $this->user_id . '\'', $date);

		if ($GLOBALS['_CFG']['open_delivery_time'] == 1) {
			if (($orderInfo['order_status'] == 5) && ($orderInfo['shipping_status'] == 1) && ($orderInfo['pay_status'] == 2)) {
				$delivery_time = $orderInfo['shipping_time'] + (24 * 3600 * $orderInfo['auto_delivery_time']);

				if ($delivery_time < $noTime) {
					$sql = 'update {pre}order_info set order_status = 5, shipping_status = 2, pay_status = 2 where order_id = \'' . $order_id . '\'';
					$this->db->query($sql);
				}
			}
		}

		$order = get_order_detail($order_id, $this->user_id);

		if ($order === false) {
			$this->err->show(l('back_home_lnk'), './');
			exit();
		}

		$sql = 'SELECT id, store_id,pick_code  FROM' . $this->ecs->table('store_order') . ' WHERE order_id = \'' . $order_id . '\'';
		$stores = $this->db->getRow($sql);
		$order['store_id'] = $stores['store_id'];
		$order['pick_code'] = $stores['pick_code'];

		if (0 < $order['store_id']) {
			$sql = 'SELECT o.*,p.region_name as province,c.region_name as city,d.region_name as district FROM' . $this->ecs->table('offline_store') . ' AS o ' . 'LEFT JOIN ' . $this->ecs->table('region') . ' AS p ON p.region_id = o.province ' . 'LEFT JOIN ' . $this->ecs->table('region') . ' AS c ON c.region_id = o.city ' . 'LEFT JOIN ' . $this->ecs->table('region') . ' AS d ON d.region_id = o.district WHERE o.id = \'' . $order['store_id'] . '\'';
			$offline_store = $this->db->getRow($sql);
			$this->assign('offline_store', $offline_store);
			$this->assign('store_id', $stores['id']);
		}

		$ru_id = $this->db->getRow('SELECT ru_id FROM ' . $this->ecs->table('order_goods') . ' WHERE order_id = ' . $order['order_id']);

		if ($ru_id) {
			$order['shop_name'] = get_shop_name($ru_id['ru_id'], 1);
			$order['shopUrl'] = u('store/index/index', array('id' => $ru_id));
		}

		if (($order['extension_code'] != 'group_buy') && ($order['extension_code'] != 'exchange_goods')) {
			$this->assign('allow_to_cart', 1);
		}

		$goods_list = order_goods($order_id);
		$goods_count = 0;
		$package_goods_count = 0;
		$package_list_total = 0;

		foreach ($goods_list as $key => $value) {
			$goods_list[$key]['market_price'] = price_format($value['market_price'], false);
			$goods_list[$key]['goods_price'] = price_format($value['goods_price'], false);
			$goods_list[$key]['subtotal'] = price_format($value['subtotal'], false);

			if ($value['is_real'] == 0) {
				$goods_list[$key]['virtual_info'] = get_virtual_goods_info($value['rec_id']);
			}

			if ($value['extension_code'] == 'package_buy') {
				$package_goods_count++;

				foreach ($value['package_goods_list'] as $package_goods_val) {
					$package_list_total += $package_goods_val['rank_price'] * $package_goods_val['goods_number'];
				}

				$goods_list[$key]['package_list_total'] = $package_list_total;
				$goods_list[$key]['package_list_saving'] = $value['subtotal'] - $package_list_total;
				$goods_list[$key]['format_package_list_total'] = price_format($goods_list[$key]['package_list_total']);
				$goods_list[$key]['format_package_list_saving'] = price_format($goods_list[$key]['package_list_saving']);
			}
			else {
				$goods_count++;
			}
		}

		if (0 < $order['order_amount']) {
			if (($order['order_status'] == OS_UNCONFIRMED) || ($order['order_status'] == OS_CONFIRMED)) {
				$user = user_info($order['user_id']);

				if (0 < ($user['user_money'] + $user['credit_line'])) {
					$this->assign('allow_edit_surplus', 1);
					$this->assign('max_surplus', sprintf(l('max_surplus'), $user['user_money']));
				}
			}
		}

		if ((0 < $order['order_amount']) && (($order['pay_status'] == PS_UNPAYED) || ($order['pay_status'] == PS_PAYED_PART)) && ($order['shipping_status'] == SS_UNSHIPPED)) {
			$payment_list = available_payment_list(false, 0, true);

			if (is_array($payment_list)) {
				foreach ($payment_list as $key => $payment) {
					if (substr($payment['pay_code'], 0, 4) == 'pay_') {
						unset($payment_list[$key]);
						continue;
					}

					if (($payment['pay_code'] == 'wxpay') && (!is_wechat_browser() || empty($_SESSION['openid']))) {
						unset($payment_list[$key]);
					}

					if (($payment['pay_id'] == $order['pay_id']) || ($payment['pay_code'] == 'balance')) {
						unset($payment_list[$key]);
					}
				}
			}

			$this->assign('payment_list', $payment_list);
		}

		$os = l('os');
		$ps = l('ps');
		$ss = l('ss');

		if ($order['order_status'] == OS_UNCONFIRMED) {
			$order['handler'] = '<span class="box-flex text-right"></span><a class="btn-default box-flex" type="button" href="' . u('user/order/cancel', array('order_id' => $order['order_id'])) . '" onclick="if (!confirm(\'' . l('confirm_cancel') . '\')) return false;">' . l('cancel') . '</a>';
		}
		else if ($order['order_status'] == OS_SPLITED) {
			if ($order['shipping_status'] == SS_SHIPPED) {
				@$order['handler'] = '<span class="box-flex text-right"></span><a class="btn-submit" href="' . u('user/order/affirmreceived', array('order_id' => $order['order_id'])) . '" onclick="if (!confirm(\'' . l('confirm_received') . '\')) return false;">' . l('received') . '</a>';
			}
			else if ($order['shipping_status'] == SS_RECEIVED) {
				@$order['handler'] = '<span class="order-checkout-text box">' . l('ss_received') . '</span>';
			}
			else if ($order['pay_status'] == PS_UNPAYED) {
				@$order['handler'] = '<span class="box-flex text-right"></span><a class="btn-submit" href="' . u('user/order/detail', array('order_id' => $order['order_id'])) . '" >' . l('pay_money') . '</a>';
			}
			else {
				$order['handler'] = '<span class="order-checkout-text box">' . $ss[$order['shipping_status']] . '</span>';
			}
		}
		else {
			if (($order['order_status'] == OS_CONFIRMED) && ($order['pay_status'] == PS_UNPAYED)) {
				$order['handler'] = '<span class=\'box-flex\'></span><a class=\'btn-default box-flex\' type=\'button\' >' . $ps[$order['pay_status']] . '</a>';
			}
			else if ($order['pay_status'] == PS_PAYED_PART) {
				if ($order['extension_code'] == 'presale') {
					$result = presale_settle_status($order['extension_id']);

					if ($result['settle_status'] == 1) {
						$order['msg'] = '尾款支付时间:';
						$start_time = trim($result['start_time']);
						$end_time = trim($result['end_time']);
						@$order['handler'] = '<span class=box-flex text-right>' . $start_time . '至' . $end_time . '</span>';
					}

					if ($result['settle_status'] == 0) {
						$order['msg'] = '尾款支付时间:';
						$start_time = trim($result['start_time']);
						$end_time = trim($result['end_time']);
						$order['hidden_pay_button'] = 1;
						@$order['handler'] = '<span class=box-flex text-right>' . $start_time . '至' . $end_time . '</span>';
					}

					if ($result['settle_status'] == -1) {
						$order['hidden_pay_button'] = 1;
						$order['msg'] = '超出尾款支付时间';
						$end_time = trim($result['end_time']);
						@$order['handler'] = '<span class=\'box-flex text-right\'>' . $end_time . '</span>';
					}
				}
			}
			else {
				$order['handler'] = $order['handler'] = '<span class="order-checkout-text box">' . $os[$order['order_status']] . '</span>';
			}
		}

		$order['order_status'] = $os[$order[order_status]];
		$order['pay_status'] = $ps[$order[pay_status]];
		$order['shipping_status'] = $ss[$order['shipping_status']];
		$order['c'] = get_region_name($order['country']);
		$order['detail_address'] .= $order['c']['region_name'];
		$order['p'] = get_region_name($order['province']);
		$order['detail_address'] .= $order['p']['region_name'];
		$order['cc'] = get_region_name($order['city']);
		$order['detail_address'] .= $order['cc']['region_name'];
		$order['dd'] = get_region_name($order['district']);
		$order['detail_address'] .= $order['dd']['region_name'];
		$order['detail_address'] .= $order['address'];
		$sql = 'SELECT * FROM ' . $this->ecs->table('shipping_point') . ' WHERE id IN (SELECT point_id FROM ' . $this->ecs->table('order_info') . ' WHERE order_id=\'' . $order_id . '\')';
		$order['point'] = $this->db->getRow($sql);

		if ($order['point']) {
			$order['point']['pickDate'] = $order['shipping_dateStr'];
		}

		$this->assign('order', $order);
		$this->assign('goods_list', $goods_list);
		$this->assign('goods_count', $goods_count);
		$this->assign('package_goods_count', $package_goods_count);
		$this->assign('page_title', l('order_detail'));
		$this->display('user_order_checkout');
	}

	public function actionApplyReturnList()
	{
		if (isset($_REQUEST['rec_id'])) {
			$recr_id = intval($_REQUEST['rec_id']);
		}
		else {
			exit('invalid parameter');
		}

		$_REQUEST['order_id'] = intval($_REQUEST['order_id']);
		$sql = ' SELECT order_id FROM ' . $GLOBALS['ecs']->table('order_info') . ' WHERE order_id = \'' . $_REQUEST['order_id'] . '\' AND shipping_status > 0 ';
		$return_allowable = $GLOBALS['db']->getOne($sql);
		$this->assign('return_allowable', $return_allowable);
		$goods_info = rec_goods($recr_id);
		$this->assign('goods', $goods_info);
		$this->display('user_return_list');
	}

	public function actionOrderTracking()
	{
		$order_id = i('order_id', 0, 'intval');
		$order = get_order_detail($order_id, $this->user_id);

		if ($order === false) {
			$this->err->show(l('back_home_lnk'), './');
			exit();
		}

		if ($order['invoice_no']) {
			preg_match('/^<a.*href="(.*?)">/is', $order['invoice_no'], $url);

			if ($url[1]) {
				$this->redirect($url[1]);
			}
		}

		show_message(l('msg_unfilled_or_receive'), l('user_center'), u('user/index/index'));
	}

	public function actionAffirmReceived()
	{
		$user_id = $this->user_id;
		$order_id = (isset($_GET['order_id']) ? intval($_GET['order_id']) : 0);

		if (affirm_received($order_id, $user_id)) {
			ecs_header('Location: ' . u('user/order/index'));
			exit();
		}
		else {
			show_message(l('msg_unfilled_or_receive'));
		}
	}

	public function actionCancel()
	{
		$order_id = (isset($_GET['order_id']) ? intval($_GET['order_id']) : 0);

		if (cancel_order($order_id, $this->user_id)) {
			ecs_header('Location: ' . u('user/order/index'));
			exit();
		}
		else {
			$this->err->show(l('order_list_lnk'), u('user/order/index'));
		}
	}

	public function actionchecklogin()
	{
		if (!$this->user_id) {
			$url = urlencode(__HOST__ . $_SERVER['REQUEST_URI']);

			if (IS_POST) {
				$url = urlencode($_SERVER['HTTP_REFERER']);
			}

			ecs_header('Location: ' . u('user/login/index', array('back_act' => $url)));
			exit();
		}
	}

	public function Get_Order_Where_Count($user_id = 0, $show_type = 0, $where = '')
	{
		$sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('order_info') . ' as oi' . ' WHERE oi.user_id = \'' . $user_id . '\' and oi.is_delete = \'' . $show_type . '\'' . ' and (select count(*) from ' . $GLOBALS['ecs']->table('order_info') . ' as oi_2 where oi_2.main_order_id = oi.order_id) = 0 ' . $where;
		return $GLOBALS['db']->getOne($sql);
	}

	public function actionChangePayment()
	{
		$pay_id = intval($_POST['pay_id']);
		$order_id = intval($_POST['order_id']);
		if (($pay_id <= 0) || ($order_id <= 0)) {
			$this->redirect(u('index/index'));
		}

		$payment_info = payment_info($pay_id);

		if (empty($payment_info)) {
			$this->redirect(u('index/index'));
		}

		$order = order_info($order_id);
		if (empty($order) || ($_SESSION['user_id'] != $order['user_id'])) {
			$this->redirect(u('index/index'));
		}

		if ((($order['pay_status'] == PS_UNPAYED) || ($order['pay_status'] == PS_PAYED_PART)) && ($order['shipping_status'] == SS_UNSHIPPED) && (0 < $order['goods_amount']) && ($order['pay_id'] != $pay_id)) {
			$order_amount = $order['order_amount'] - $order['pay_fee'];
			$pay_fee = pay_fee($pay_id, $order_amount);
			$order_amount += $pay_fee;
			$data['pay_id'] = $pay_id;
			$data['pay_name'] = $payment_info['pay_name'];
			$data['pay_fee'] = $pay_fee;
			$data['order_amount'] = $order_amount;
			$where['order_id'] = $order_id;
			$this->model->table('order_info')->data($data)->where($where)->update();
		}

		$this->redirect(u('detail', array('order_id' => $order_id)));
	}

	public function actionSurplusPay()
	{
		$order_id = i('post.order_id', '', 'intval');
		$type = i('get.type');

		if ($order_id <= 0) {
			ecs_header("Location: ./\n");
			exit();
		}

		$order = order_info($order_id);
		if (($type == 'presale') && ($order['pay_status'] == PS_PAYED_PART)) {
			$result = presale_settle_status($order['extension_id']);
			if (($result['settle_status'] == 0) || ($result['settle_status'] == -1)) {
				ecs_header("Location: ./\n");
				exit();
			}
		}

		if ($_SESSION['user_id'] != $order['user_id']) {
			ecs_header("Location: ./\n");
			exit();
		}

		if (($order['pay_status'] != PS_UNPAYED) || ($order['order_amount'] <= 0)) {
			if ($order['pay_status'] != PS_PAYED_PART) {
				$GLOBALS['err']->add(l('error_order_is_paid'));
				$GLOBALS['err']->show(l('order_detail'), u('user/order/detail', array('order_id' => $order_id)));
			}
		}

		$surplus = floatval($_POST['surplus']);

		if ($surplus <= 0) {
			$GLOBALS['err']->add(l('error_surplus_invalid'));
			$GLOBALS['err']->show(l('order_detail'), u('user/order/detail', array('order_id' => $order_id)));
		}

		if ($order['order_amount'] < $surplus) {
			$surplus = $order['order_amount'];
		}

		$order['surplus'] += $surplus;
		$order['order_amount'] -= $surplus;

		if (0 < $order['order_amount']) {
			$cod_fee = 0;

			if (0 < $order['shipping_id']) {
				$regions = array($order['country'], $order['province'], $order['city'], $order['district']);
				$shipping = shipping_area_info($order['shipping_id'], $regions);

				if ($shipping['support_cod'] == '1') {
					$cod_fee = $shipping['pay_fee'];
				}
			}

			$pay_fee = 0;

			if (0 < $order['pay_id']) {
				$pay_fee = pay_fee($order['pay_id'], $order['order_amount'], $cod_fee);
			}

			$order['pay_fee'] = $pay_fee;
			$order['order_amount'] += $pay_fee;
		}

		if ($order['order_amount'] == 0) {
			$amount = $order['goods_amount'] + $order['shipping_fee'];
			$paid = $order['money_paid'] + $order['surplus'];
			if (($_POST['pay_status'] == 'presale') && ($paid < $amount)) {
				$order['pay_status'] = PS_PAYED_PART;
				$order['order_amount'] = $amount - $paid;
			}
			else {
				$order['pay_status'] = PS_PAYED;
			}

			if ($order['order_status'] == OS_UNCONFIRMED) {
				$order['order_status'] = OS_CONFIRMED;
				$order['confirm_time'] = gmtime();
			}

			$order['pay_time'] = gmtime();
		}

		$order = addslashes_deep($order);
		update_order($order_id, $order);
		$change_desc = sprintf(l('pay_order_by_surplus'), $order['order_sn']);
		log_account_change($order['user_id'], -1 * $surplus, 0, 0, 0, $change_desc);
		$this->redirect(u('user/order/detail', array('order_id' => $order_id)));
		exit();
	}
}

?>
