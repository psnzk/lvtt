<?php

/**
 * ECTouch Open Source Project
 * ============================================================================
 * Copyright (c) 2012-2014 http://ectouch.cn All rights reserved.
 * ----------------------------------------------------------------------------
 * 文件名称：respond.php
 * ----------------------------------------------------------------------------
 * 功能描述：支付接口通知文件
 * ----------------------------------------------------------------------------
 * Licensed ( http://www.ectouch.cn/docs/license.txt )
 * ----------------------------------------------------------------------------
 */

/* 访问控制 */

define('APP_NAME', 'respond');
$_GET['code'] = 'wxpay_jspay';
$_GET['type'] = 'notify';
$_GET['postStr'] = base64_encode($GLOBALS["HTTP_RAW_POST_DATA"]);

require __DIR__ . '/index.php';
