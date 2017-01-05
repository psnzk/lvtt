<?php
//dezend by  QQ:2172298892
foreach ($frames as $i => $frame) {
	echo '  <div class="frame ';
	echo $i == 0 ? 'active' : '';
	echo '" id="frame-line-';
	echo $i;
	echo "\">\r\n      <div class=\"frame-method-info\">\r\n        <span class=\"frame-index\">";
	echo count($frames) - $i - 1;
	echo ".</span>\r\n        <span class=\"frame-class\">";
	echo $tpl->escape($frame->getClass() ?: '');
	echo "</span>\r\n        <span class=\"frame-function\">";
	echo $tpl->escape($frame->getFunction() ?: '');
	echo "</span>\r\n      </div>\r\n\r\n    <span class=\"frame-file\">\r\n      ";
	echo $frame->getFile(true) ?: '<#unknown>';
	echo "<!--\r\n   --><span class=\"frame-line\">";
	echo (int) $frame->getLine();
	echo "</span>\r\n    </span>\r\n  </div>\r\n";
}

?>
