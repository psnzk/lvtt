<?php

/**
 * ECSHOP 会员中心
 * ============================================================================
 * * 版权所有 2005-2016 上海商创网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecmoban.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: user.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

require(ROOT_PATH . '/includes/lib_area.php');  //ecmoban模板堂 --zhuo
/* 载入语言文件 */ //by guan 晒单评价
require_once(ROOT_PATH . '/' . ADMIN_PATH . '/includes/lib_goods.php');
require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/user.php');
include_once(ROOT_PATH . '/includes/cls_image.php');
$image = new cls_image($_CFG['bgcolor']);

$user_id = $_SESSION['user_id'];
$action  = isset($_REQUEST['act']) ? trim($_REQUEST['act']) : 'default';


	$pay_id = intval($_GET['id']);
	
    include_once(ROOT_PATH .'includes/cls_json.php');
    $json = new JSON();

    $result = array('error'=>0, 'message'=>'', 'content'=>'');

    if(isset($_SESSION['last_order_query']))
    {
        if(time() - $_SESSION['last_order_query'] < 1)
        {
            $result['error'] = 1;
            $result['message'] = $_LANG['order_query_toofast'];
            die($json->encode($result));
        }
    }
    $_SESSION['last_order_query'] = time();

    if (empty($pay_id))
    {
        $result['error'] = 1;
        $result['message'] = $_LANG['invalid_order_sn'];
        die($json->encode($result));
    }
	$sql = "SELECT * ".
           " FROM " . $ecs->table('pay_log').
           " WHERE log_id = '$pay_id' LIMIT 1";
		   
    $row = $db->getRow($sql);
    if (empty($row))
    {
        $result['error'] = 1;
        $result['message'] = $_LANG['invalid_order_sn'];
        die($json->encode($result));
    }
	$order_type = $row['order_type'];
	$url = 'user.php?act=order_detail&order_id='.$row['order_id'];
	if ( $order_type == 1  ){
		$url = 'user.php?act=account_log';
	}
	if( $row['is_paid'] == 1){
		$result['url'] 		= $url;
	}
	$result['is_paid'] 	= $row['is_paid'];
    die($json->encode($result));


?>