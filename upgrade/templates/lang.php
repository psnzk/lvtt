<?php
//zend by QQ:2172298892
echo "<html>\r\n<head>\r\n<title> ";
echo $lang['select_language_title'];
echo " </title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=";
echo $ec_charset;
echo "\" />\r\n<link href=\"styles/general.css\" rel=\"stylesheet\" type=\"text/css\" />\r\n</head>\r\n\r\n<body class=\"body\">\r\n<div class=\"main\">\r\n";
include ROOT_PATH . 'upgrade/templates/header.php';
echo "\r\n<div id=\"wrapper\" class=\"wrapper\">\r\n\t<div class=\"common-head\">\r\n        <a href=\"ucimport.php\" class=\"btn\">";
echo $lang['goto_members_import'];
echo "</a>\r\n        <a href=\"convert.php\" class=\"btn\">";
echo $lang['goto_charset_convert'];
echo "</a>\r\n    </div>\r\n<form target=\"_parent\" action=\"\" method=\"post\">\r\n<fieldset>\r\n    <legend dir=\"ltr\">";
echo $lang['lang_select'];
echo "<i class=\"r\"></i><i class=\"b\"></i></legend>\r\n    <div class=\"items\">\r\n    \t<div class=\"item\">\r\n        \t<div class=\"label\">";
echo $lang['lang_title'];
echo "：</div>\r\n            <div class=\"value\">\r\n            \t<select dir=\"ltr\" onchange=\"this.form.submit();\" name=\"lang\" style=\"width:300px;\">\r\n\t\t\t\t";

foreach ($lang['lang_charset'] as $key => $val) {
	if (($updater_lang . '_' . $ec_charset) == $key) {
		$lang_selected = 'selected="selected" ';
	}
	else {
		$lang_selected = '';
	}

	echo '                		<option ';
	echo $lang_selected;
	echo 'value="';
	echo $key;
	echo '">';
	echo $val;
	echo "</option>\r\n                ";
}

echo "                </select>\r\n            </div>\r\n        </div>\r\n    \t<div class=\"item\">\r\n        \t<div class=\"label\">";
echo $lang['ui_title'];
echo "：</div>\r\n            <div class=\"value\">\r\n            \t<div class=\"checkbox_items\">\r\n                \t<div class=\"checkbox_item\">\r\n                    \t<input type=\"radio\" id=\"ui_1\" name=\"ui\" value=\"ecshop\" class=\"ui_radio\" checked=\"checked\" />\r\n                        <label for=\"ui_1\" class=\"ui_radio_label\">";
echo $lang['ui_ordinary'];
echo "</label>\r\n                    </div>\r\n                    <div class=\"checkbox_item\">\r\n                    \t<input type=\"radio\" id=\"ui_2\" name=\"ui\" value=\"ucenter\" class=\"ui_radio\" />\r\n                        <label for=\"ui_2\" class=\"ui_radio_label\">";
echo $lang['ui_ucenter'];
echo "</label>\r\n                    </div>\r\n                </div>\r\n            </div>\r\n        </div>\r\n    </div>\r\n</fieldset>\r\n</form>\r\n<form target=\"_parent\" action=\"\" method=\"post\">\r\n    <fieldset>\r\n        <legend>";
echo $lang['lang_description'];
echo "<i class=\"r\"></i><i class=\"b\"></i></legend>\r\n        <ul class=\"list\">\r\n            ";
$i = 1;

foreach ($lang['lang_desc'] as $desc) {
	echo '            <li>';
	echo $i;
	$i++;
	echo '、';
	echo $desc;
	echo "</li>\r\n            ";
}

echo "        </ul>\r\n    </fieldset>\r\n    <div class=\"btn_info mt50 mb20\">\r\n        <input type=\"hidden\" name=\"step\" value=\"readme\" />\r\n        <input type=\"hidden\" name=\"lang\" value=\"";
echo $updater_lang . '_' . $ec_charset;
echo "\" />\r\n        <input type=\"submit\" class=\"button\" value=\"";
echo $lang['next_step'];
echo $lang['readme_page'];
echo "\" />\r\n    </div>\r\n</form>\r\n\r\n</div>\r\n\r\n";
include ROOT_PATH . 'upgrade/templates/copyright.php';
echo "</div>\r\n</body>\r\n</html>\r\n";

?>
