<?php
//zend by QQ:2172298892
echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=";
echo $ec_charset;
echo "\" />\r\n<title>";
echo $lang['checking_title'];
echo "</title>\r\n<link href=\"styles/general.css\" rel=\"stylesheet\" type=\"text/css\" />\r\n<script type=\"text/javascript\" src=\"../js/transport.js\" charset=\"";
echo EC_CHARSET;
echo "\"></script>\r\n<script type=\"text/javascript\" src=\"js/common.js\"></script>\r\n<script type=\"text/javascript\" src=\"js/draggable.js\"></script>\r\n<script type=\"text/javascript\" src=\"js/check.js\"></script>\r\n<script type=\"text/javascript\">\r\nvar \$_LANG = {};\r\n";

foreach ($lang['js_languages'] as $key => $item) {
	echo '$_LANG["';
	echo $key;
	echo '"] = "';
	echo $item;
	echo "\";\r\n";
}

echo "</script>\r\n</head>\r\n<body id=\"checking\" class=\"body\">\r\n<div class=\"main\">\r\n";
include ROOT_PATH . 'upgrade/templates/header.php';
echo "<form method=\"post\">\r\n<div id=\"wrapper\" class=\"wrapper checking\">\r\n    <fieldset>\r\n        <legend>";
echo $lang['basic_config'];
echo "<i class=\"r\"></i><i class=\"b\"></i></legend>\r\n        <ul class=\"list\">\r\n\t\t\t";

foreach ($config_info as $config_item) {
	echo "            <li>\r\n\t\t\t\t<span class=\"detail\">";
	echo $config_item[0];
	echo "</span>\r\n                <span class=\"route green\">";
	echo $config_item[1];
	echo "</span>\r\n            </li>\r\n\t\t\t";
}

echo "        </ul>\r\n    </fieldset>\r\n    \r\n    <fieldset>\r\n        <legend>";
echo $lang['dir_priv_checking'];
echo "<i class=\"r\"></i><i class=\"b\"></i></legend>\r\n        <ul class=\"list\">\r\n\t\t\t";

foreach ($dir_checking as $checking_item) {
	echo "            <li>\r\n\t\t\t\t<span class=\"detail\">";
	echo $checking_item[0];
	echo "</span>\r\n\t\t\t\t";

	if ($checking_item[1] == $lang['can_write']) {
		echo '                <span class="route green">';
		echo $checking_item[1];
		echo "</span>\r\n                ";
	}
	else {
		echo '                <span class="route red">';
		echo $checking_item[1];
		echo "</span>\r\n                ";
	}

	echo "            </li>\r\n            ";
}

echo "        </ul>\r\n    </fieldset>\r\n    \r\n    <fieldset>\r\n        <legend>";
echo $lang['template_writable_checking'];
echo "<i class=\"r\"></i><i class=\"b\"></i></legend>\r\n        <ul class=\"list\">\r\n\t\t\t";

if ($has_unwritable_tpl == 'yes') {
	echo '            ';

	foreach ($template_checking as $checking_item) {
		echo "            <li>\r\n            \t<span class=\"route red\">";
		echo $checking_item;
		echo "</span>\r\n            </li>    \r\n            ";
	}

	echo '            ';
}
else {
	echo "            <li class=\"success\">\r\n            \t<span class=\"green\">";
	echo $template_checking;
	echo "</span>\r\n            </li>\r\n            ";
}

echo "        </ul>\r\n    </fieldset>\r\n    \r\n    ";

if (!empty($rename_priv)) {
	echo "    <fieldset>\r\n        <legend>";
	echo $lang['rename_priv_checking'];
	echo "<i class=\"r\"></i><i class=\"b\"></i></legend>\r\n        <ul class=\"list\">\r\n\t\t\t";

	foreach ($rename_priv as $checking_item) {
		echo '            <li><span class="route red">';
		echo $checking_item;
		echo "</span></li>\r\n            ";
	}

	echo "        </ul>\r\n    </fieldset> \r\n    ";
}

echo "    \r\n    <div class=\"btn_info mt50 mb20\" id=\"install-btn\">\r\n        <input type=\"button\" class=\"button mr15\" value=\"";
echo $lang['prev_step'];
echo $lang['readme_page'];
echo "\" onclick=\"location.href='index.php'\" />\r\n        <input type=\"button\" class=\"button mr15\" value=\"";
echo $lang['recheck'];
echo "\" onclick=\"location.href='index.php?step=check'\" />\r\n        <input type=\"button\" class=\"button\" value=\"";
echo $lang['update_now'];
echo '" ';
echo $disabled;
echo " id=\"js-submit\" />\r\n    </div>\r\n    \r\n    <div id=\"js-monitor_bg\" class=\"js-monitor_bg\"></div>\r\n    <div id=\"js-monitor\">\r\n    \t<div class=\"loading_bg\">\r\n    \t\t<img id=\"js-monitor-loading\" src='images/loading.gif' />\r\n        </div>\r\n        <div class=\"con\">\r\n    \t<fieldset>\r\n            <legend id=\"js-monitor-title\">";
echo $lang['monitor_title'];
echo "<i class=\"r\"></i><i class=\"b\"></i></legend>\r\n            <div class=\"head_title\">\r\n                <span id=\"js-monitor-wait-please\" class=\"please\"></span>\r\n                <span id=\"js-monitor-rollback\" class=\"rollback hide\"></span>\r\n                <i id=\"js-monitor-view-detail\" class=\"view\"></i>\r\n            </div>\r\n            <iframe id=\"js-monitor-notice\" src=\"templates/notice.htm\" height=\"70px\" width=\"100%\" style=\"display:none;\"></iframe>\r\n            <a href=\"javascript:void(0);\" id=\"js-monitor-close\" class=\"js-monitor-close\"></a>\r\n        </fieldset>\r\n        </div>\r\n    </div>\r\n</div>\r\n\r\n<div id=\"copyright\">\r\n    <div id=\"copyright-inside\">\r\n      ";
include ROOT_PATH . 'upgrade/templates/copyright.php';
echo "</div>\r\n</div>\r\n</form>\r\n</div>\r\n</body>\r\n</html>\r\n";

?>
