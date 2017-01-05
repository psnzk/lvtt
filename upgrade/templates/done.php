<?php
//zend by QQ:2172298892
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<title>";
echo $lang['upgrade_done_title'];
echo "</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=";
echo $ec_charset;
echo "\" />\r\n<link href=\"styles/general.css\" rel=\"stylesheet\" type=\"text/css\" />\r\n</head>\r\n\r\n<body class=\"body\">\r\n<div class=\"main\">\r\n";
include ROOT_PATH . 'upgrade/templates/header.php';
echo "\r\n<div class=\"wrapper\">\r\n\t<div class=\"w_content\">\r\n    \t<div class=\"end_left\"><img src=\"images/tangshu1.png\"></div>\r\n        <div class=\"end_right\">\r\n            <h1>";
printf($lang['done'], VERSION);
echo "</h1>\r\n            <ul>\r\n                <li><a href=\"../\">";
echo $lang['go_to_view_my_ecshop'];
echo "</a></li>\r\n                <li><a href=\"../admin\">";
echo $lang['go_to_view_control_panel'];
echo "</a></li>\r\n            </ul>\r\n        </div>\r\n\t</div>\r\n</div>\r\n\r\n";
include ROOT_PATH . 'upgrade/templates/copyright.php';
echo "</div>\r\n</body>\r\n</html>";

?>
