<?php
//zend by QQ:2172298892
function get_url_query($url = '')
{
	$info = parse_url($url);

	if (isset($info['query'])) {
		parse_str($info['query'], $params);
	}

	return $params;
}


?>
