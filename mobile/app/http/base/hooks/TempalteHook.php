<?php
//dezend by  QQ:2172298892
namespace http\base\hooks;

class TempalteHook
{
	public function templateParse($template)
	{
		$label = array('/\\$(\\w+)\\.(\\w+)\\.(\\w+)\\.(\\w+)/is' => '$\\1[\'\\2\'][\'\\3\'][\'\\4\']', '/\\$(\\w+)\\.(\\w+)\\.(\\w+)/is' => '$\\1[\'\\2\'][\'\\3\']', '/\\$(\\w+)\\.(\\w+)/is' => '$\\1[\'\\2\']', '/{(\\$[a-zA-Z_]\\w*(?:\\[[\\w\\.\\"\'\\[\\]\\$]+\\])*)}/i' => '<?php echo $1; ?>', '/\\{([A-Z_\\x7f-\\xff][A-Z0-9_\\x7f-\\xff]*)\\}/s' => '<?php echo \\1;?>', '/{include\\s*file=\\"(.*)\\"}/i' => '<?php $__Template->display($this->getTpl("$1")); ?>', '/\\{if\\s+(.+?)\\}/' => '<?php if(\\1) { ?>', '/\\{else\\}/' => '<?php } else { ?>', '/\\{elseif\\s+(.+?)\\}/' => '<?php } elseif (\\1) { ?>', '/\\{\\/if\\}/' => '<?php } ?>', '/\\{for\\s+(.+?)\\}/' => '<?php for(\\1) { ?>', '/\\{\\/for\\}/' => '<?php } ?>', '/\\{foreach\\s+(\\S+)\\s+as\\s+(\\S+)\\}/' => '<?php $n=1;if(is_array(\\1)) foreach(\\1 as \\2) { ?>', '/\\{foreach\\s+(\\S+)\\s+as\\s+(\\S+)\\s*=>\\s*(\\S+)\\}/' => '<?php $n=1; if(is_array(\\1)) foreach(\\1 as \\2 => \\3) { ?>', '/\\{\\/foreach\\}/' => '<?php $n++;}unset($n); ?>', '/\\{([a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff:]*\\(([^{}]*)\\))\\}/' => '<?php echo \\1;?>', '/\\{(\\$[a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff:]*\\(([^{}]*)\\))\\}/' => '<?php echo \\1;?>');

		foreach ($label as $key => $value) {
			$template = preg_replace($key, $value, $template);
		}

		return $template;
	}
}


?>
