<?php
//dezend by  QQ:2172298892
echo '<div class="frame-code-container ';
echo !$has_frames ? 'empty' : '';
echo "\">\r\n  ";

foreach ($frames as $i => $frame) {
	echo '    ';
	$line = $frame->getLine();
	echo '      <div class="frame-code ';
	echo $i == 0 ? 'active' : '';
	echo '" id="frame-code-';
	echo $i;
	echo "\">\r\n        <div class=\"frame-file\">\r\n          ";
	$filePath = $frame->getFile();
	echo '          ';
	if ($filePath && ($editorHref = $handler->getEditorHref($filePath, (int) $line))) {
		echo "            Open:\r\n            <a href=\"";
		echo $editorHref;
		echo '" class="editor-link"';
		echo $handler->getEditorAjax($filePath, (int) $line) ? ' data-ajax' : '';
		echo ">\r\n              <strong>";
		echo $tpl->escape($filePath ?: '<#unknown>');
		echo "</strong>\r\n            </a>\r\n          ";
	}
	else {
		echo '            <strong>';
		echo $tpl->escape($filePath ?: '<#unknown>');
		echo "</strong>\r\n          ";
	}

	echo "        </div>\r\n        ";

	if ($line !== NULL) {
		$range = $frame->getFileLines($line - 8, 10);

		if ($range) {
			$range = array_map(function($line) {
				return empty($line) ? ' ' : $line;
			}, $range);
			$start = key($range) + 1;
			$code = join("\n", $range);
			echo '            <pre class="code-block prettyprint linenums:';
			echo $start;
			echo '">';
			echo $tpl->escape($code);
			echo "</pre>\r\n          ";
		}

		echo '        ';
	}

	echo "\r\n        ";
	$comments = $frame->getComments();
	echo '        <div class="frame-comments ';
	echo empty($comments) ? 'empty' : '';
	echo "\">\r\n          ";

	foreach ($comments as $commentNo => $comment) {
		echo '            ';
		extract($comment);
		echo '            <div class="frame-comment" id="comment-';
		echo $i . '-' . $commentNo;
		echo "\">\r\n              <span class=\"frame-comment-context\">";
		echo $tpl->escape($context);
		echo "</span>\r\n              ";
		echo $tpl->escapeButPreserveUris($comment);
		echo "            </div>\r\n          ";
	}

	echo "        </div>\r\n\r\n      </div>\r\n  ";
}

echo "</div>\r\n";

?>
