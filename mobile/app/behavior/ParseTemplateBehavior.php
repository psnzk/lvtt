<?php
//zend by QQ:2172298892
namespace app\behavior;

class ParseTemplateBehavior
{
	public function run(&$content)
	{
		$content = $this->templateContentReplace($content);
	}

	protected function templateContentReplace($content)
	{
		$label = array('/{(\\$[a-zA-Z_]\\w*(?:\\[[\\w\\.\\"\'\\[\\]\\$]+\\])*)}/i' => '<?php echo $1; ?>', '/\\$(\\w+)\\.(\\w+)\\.(\\w+)\\.(\\w+)/is' => '$\\1[\'\\2\'][\'\\3\'][\'\\4\']', '/\\$(\\w+)\\.(\\w+)\\.(\\w+)/is' => '$\\1[\'\\2\'][\'\\3\']', '/\\$(\\w+)\\.(\\w+)/is' => '$\\1[\'\\2\']', '/\\{([A-Z_\\x7f-\\xff][A-Z0-9_\\x7f-\\xff]*)\\}/s' => '\\1/', '/{include\\s*file=\\"(.*)\\"}/i' => '{include file="$1" /}', '/\\{if\\s+(.+?)\\}/' => '<?php if(\\1) { ?>', '/\\{else\\}/' => '<?php } else { ?>', '/\\{elseif\\s+(.+?)\\}/' => '<?php } elseif (\\1) { ?>', '/\\{\\/if\\}/' => '<?php } ?>', '/\\{for\\s+(.+?)\\}/' => '<?php for(\\1) { ?>', '/\\{\\/for\\}/' => '<?php } ?>', '/\\{foreach\\s+(\\S+)\\s+as\\s+(\\S+)\\}/' => '<?php $n=1;if(is_array(\\1)) foreach(\\1 as \\2) { ?>', '/\\{foreach\\s+(\\S+)\\s+as\\s+(\\S+)\\s*=>\\s*(\\S+)\\}/' => '<?php $n=1; if(is_array(\\1)) foreach(\\1 as \\2 => \\3) { ?>', '/\\{\\/foreach\\}/' => '<?php $n++;}unset($n); ?>', '/\\{([a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff:]*\\(([^{}]*)\\))\\}/' => '<?php echo \\1;?>', '/\\{(\\$[a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff:]*\\(([^{}]*)\\))\\}/' => '<?php echo \\1;?>');

		foreach ($label as $key => $value) {
			$content = preg_replace($key, $value, $content);
		}

		return $content;
	}
}


?>
