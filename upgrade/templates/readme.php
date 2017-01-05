<?php
//zend by QQ:2172298892
echo "<html>\r\n<head>\r\n<title> ";
echo $lang['readme_title'];
echo " </title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=";
echo $ec_charset;
echo "\" />\r\n<link href=\"styles/general.css\" rel=\"stylesheet\" type=\"text/css\" />\r\n</head>\r\n\r\n<body class=\"body\">\r\n<div class=\"main\">\r\n";
include ROOT_PATH . 'upgrade/templates/header.php';
echo "\r\n<div id=\"wrapper\" class=\"wrapper\">\r\n<fieldset>\r\n\t<legend>";
echo $lang['method'];
echo "<i class=\"r\"></i><i class=\"b\"></i></legend>\r\n    <p class=\"tit\">";
printf($lang['notice'], $new_version);
echo "</p>\r\n    <ul class=\"list\">\r\n        <li>1、";
echo $lang['usage1'];
echo "</li>\r\n        <li>2、";
echo $lang['usage2'];
echo "</li>\r\n        <li>3、";
printf($lang['usage3'], $old_version);
echo "</li>\r\n        <li>4、";
printf($lang['usage4'], $new_version);
echo "</li>\r\n        <li>5、";
echo $lang['usage5'];
echo "</li>\r\n        <li>6、";
echo $lang['usage6'];
echo "</li>\r\n    </ul>\r\n</fieldset>\r\n<div class=\"btn_info mt50 mb20\">\r\n    <input type=\"submit\" id=\"js-submit\" class=\"button\" value=\"";
echo $lang['next_step'];
echo $lang['check_system_environment'];
echo '" onClick="top.location=\'index.php?step=check&ui=';
echo $ui;
echo "'\" />\r\n</div>\r\n<fieldset>\r\n\t<legend>";
echo $lang['faq'];
echo "<i class=\"r\"></i><i class=\"b\"></i></legend>\r\n    <iframe src=\"templates/faq_";
echo $updater_lang;
echo '_';
echo $ec_charset;
echo ".htm\" width=\"100%\" height=\"500\"></iframe>\r\n</fieldset>\r\n</div>\r\n";
include ROOT_PATH . 'upgrade/templates/copyright.php';
echo "</div>\r\n</body>\r\n</html>\r\n";

?>
