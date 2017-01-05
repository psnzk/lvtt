<?php
define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');
require(ROOT_PATH . 'includes/lib_payment.php');
require_once(ROOT_PATH .'includes/modules/payment/wxpay_jspay.php');

$payment = new wxpay_jspay();
$payment->respond();
exit;
?>