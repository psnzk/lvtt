<?php
//dezend by  QQ:2172298892
echo "<div class=\"details\">\r\n  <div class=\"data-table-container\" id=\"data-tables\">\r\n    ";

foreach ($tables as $label => $data) {
	echo '      <div class="data-table" id="sg-';
	echo $tpl->escape($tpl->slug($label));
	echo "\">\r\n        ";

	if (!empty($data)) {
		echo '            <label>';
		echo $tpl->escape($label);
		echo "</label>\r\n            <table class=\"data-table\">\r\n              <thead>\r\n                <tr>\r\n                  <td class=\"data-table-k\">Key</td>\r\n                  <td class=\"data-table-v\">Value</td>\r\n                </tr>\r\n              </thead>\r\n            ";

		foreach ($data as $k => $value) {
			echo "              <tr>\r\n                <td>";
			echo $tpl->escape($k);
			echo "</td>\r\n                <td>";
			echo $tpl->escape(print_r($value, true));
			echo "</td>\r\n              </tr>\r\n            ";
		}

		echo "            </table>\r\n        ";
	}
	else {
		echo '            <label class="empty">';
		echo $tpl->escape($label);
		echo "</label>\r\n            <span class=\"empty\">empty</span>\r\n        ";
	}

	echo "      </div>\r\n    ";
}

echo "  </div>\r\n\r\n  ";
echo "  <div class=\"data-table-container\" id=\"handlers\">\r\n    <label>Registered Handlers</label>\r\n    ";

foreach ($handlers as $i => $handler) {
	echo '      <div class="handler ';
	echo $handler === $handler ? 'active' : '';
	echo "\">\r\n        ";
	echo $i;
	echo '. ';
	echo $tpl->escape(get_class($handler));
	echo "      </div>\r\n    ";
}

echo "  </div>\r\n\r\n</div>\r\n";

?>
