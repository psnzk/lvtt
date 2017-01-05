<?php
//dezend by  QQ:2172298892
echo "<div class=\"exception\">\r\n  <h3 class=\"exc-title\">\r\n    ";

foreach ($name as $i => $nameSection) {
	echo '      ';

	if ($i == (count($name) - 1)) {
		echo '        <span class="exc-title-primary">';
		echo $tpl->escape($nameSection);
		echo "</span>\r\n      ";
	}
	else {
		echo '        ';
		echo $tpl->escape($nameSection) . ' \\';
		echo '      ';
	}

	echo '    ';
}

echo '    ';

if ($code) {
	echo '      <span title="Exception Code">(';
	echo $tpl->escape($code);
	echo ")</span>\r\n    ";
}

echo "  </h3>\r\n\r\n  <div class=\"help\">\r\n    <button title=\"show help\">HELP</button>\r\n\r\n    <div id=\"help-overlay\">\r\n      <div id=\"help-framestack\">Callstack information; navigate with mouse or keyboard using <kbd>Ctrl+&uparrow;</kbd> or <kbd>Ctrl+&downarrow;</kbd></div>\r\n      <div id=\"help-clipboard\">Copy-to-clipboard button</div>\r\n      <div id=\"help-exc-message\">Exception message and its type</div>\r\n      <div id=\"help-code\">Code snippet where the error was thrown</div>\r\n      <div id=\"help-request\">Server state information</div>\r\n      <div id=\"help-appinfo\">Application provided context information</div>\r\n    </div>\r\n  </div>\r\n\r\n  <button id=\"copy-button\" class=\"clipboard\" data-clipboard-target=\"plain-exception\" title=\"copy exception into clipboard\"></button>\r\n  <span id=\"plain-exception\">";
echo $tpl->escape($plain_exception);
echo "</span>\r\n\r\n  <p class=\"exc-message\">\r\n    ";
echo $tpl->escape($message);
echo "  </p>\r\n</div>\r\n";

?>
