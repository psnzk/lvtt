<?php
//dezend by  QQ:2172298892
namespace base;

class Template
{
	/**
     * 模板配置
     * @var array
     */
	protected $config = array();
	/**
     * 布局模板
     * @var null
     */
	protected $label;
	/**
     * 模板赋值数组
     * @var array
     */
	protected $vars = array();
	/**
     * 缓存对象
     * @var null
     */
	protected $cache;

	public function __construct($config)
	{
		$this->config = $config;
		$this->assign('__Template', $this);
		$this->label = array('/\\$(\\w+)\\.(\\w+)\\.(\\w+)\\.(\\w+)/is' => '$\\1[\'\\2\'][\'\\3\'][\'\\4\']', '/\\$(\\w+)\\.(\\w+)\\.(\\w+)/is' => '$\\1[\'\\2\'][\'\\3\']', '/\\$(\\w+)\\.(\\w+)/is' => '$\\1[\'\\2\']', '/{(\\$[a-zA-Z_]\\w*(?:\\[[\\w\\.\\"\'\\[\\]\\$]+\\])*)}/i' => '<?php echo $1; ?>', '/\\{([A-Z_\\x7f-\\xff][A-Z0-9_\\x7f-\\xff]*)\\}/s' => '<?php echo \\1;?>', '/{include\\s*file=\\"(.*)\\"}/i' => '<?php $__Template->display("$1"); ?>', '/\\{if\\s+(.+?)\\}/' => '<?php if(\\1) { ?>', '/\\{else\\}/' => '<?php } else { ?>', '/\\{elseif\\s+(.+?)\\}/' => '<?php } elseif (\\1) { ?>', '/\\{\\/if\\}/' => '<?php } ?>', '/\\{for\\s+(.+?)\\}/' => '<?php for(\\1) { ?>', '/\\{\\/for\\}/' => '<?php } ?>', '/\\{foreach\\s+(\\S+)\\s+as\\s+(\\S+)\\}/' => '<?php $n=1;if(is_array(\\1)) foreach(\\1 as \\2) { ?>', '/\\{foreach\\s+(\\S+)\\s+as\\s+(\\S+)\\s*=>\\s*(\\S+)\\}/' => '<?php $n=1; if(is_array(\\1)) foreach(\\1 as \\2 => \\3) { ?>', '/\\{\\/foreach\\}/' => '<?php $n++;}unset($n); ?>', '/\\{([a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff:]*\\(([^{}]*)\\))\\}/' => '<?php echo \\1;?>', '/\\{(\\$[a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff:]*\\(([^{}]*)\\))\\}/' => '<?php echo \\1;?>');
		$this->cache = new Cache($this->config['TPL_CACHE']);
	}

	public function assign($name, $value = '')
	{
		if (is_array($name)) {
			foreach ($name as $k => $v) {
				$this->vars[$k] = $v;
			}
		}
		else {
			$this->vars[$name] = $value;
		}
	}

	public function display($tpl = '', $return = false, $isTpl = true)
	{
		if ($return) {
			ob_start();
			ob_implicit_flush(0);
		}

		extract($this->vars, EXTR_OVERWRITE);
		eval ('?>' . $this->compile($tpl, $isTpl));

		if ($return) {
			$content = ob_get_clean();
			return $content;
		}
	}

	public function compile($tpl, $isTpl = true)
	{
		if ($isTpl) {
			$tplFile = $this->config['TPL_PATH'] . $tpl . $this->config['TPL_SUFFIX'];

			if (!file_exists($tplFile)) {
				throw new \Exception('Template file \'' . $tplFile . '\' not found', 500);
			}

			$tplKey = md5(realpath($tplFile));
		}
		else {
			$tplKey = md5($tpl);
		}

		$ret = unserialize($this->cache->get($tplKey));
		if (empty($ret['template']) || ($isTpl && ($ret['compile_time'] < filemtime($tplFile)))) {
			$template = ($isTpl ? file_get_contents($tplFile) : $tpl);

			if (false === Hook::listen('templateParse', array($template), $template)) {
				foreach ($this->label as $key => $value) {
					$template = preg_replace($key, $value, $template);
				}
			}

			$ret = array('template' => $template, 'compile_time' => time());
			$cache_value = serialize($ret);
			$cache_expire = (isset($this->config['EXPIRE']) ? $this->config['EXPIRE'] : c('CACHE_EXPIRE'));
			$this->cache->set($tplKey, $cache_value, $cache_expire);
		}

		return $ret['template'];
	}

	private function getTpl($tpl = '')
	{
		$tpl = (empty($tpl) ? strtolower(CONTROLLER_NAME) . c('TPL.TPL_DEPR') . strtolower(ACTION_NAME) : $tpl);
		$base_themes = ROOT_PATH . 'statics/';
		$base_views = ROOT_PATH . 'resources/views/';
		$base_custom = ROOT_PATH . 'app/custom/' . APP_NAME . '/views/' . $tpl . c('TPL.TPL_SUFFIX');
		$extends_tpl = 'library/' . $tpl . c('TPL.TPL_SUFFIX');

		if (file_exists($base_custom)) {
			$tpl = 'app/custom/' . APP_NAME . '/views/' . $tpl;
		}
		else if (file_exists($base_themes . $extends_tpl)) {
			$tpl = 'statics/library/' . $tpl;
		}
		else if (file_exists($base_views . 'base/' . $tpl . c('TPL.TPL_SUFFIX'))) {
			$tpl = 'resources/views/base/' . $tpl;
		}
		else if (file_exists($base_views . APP_NAME . '/' . $tpl . c('TPL.TPL_SUFFIX'))) {
			$tpl = 'resources/views/' . APP_NAME . '/' . $tpl;
		}
		else {
			$tpl = 'app/http/' . APP_NAME . '/views/' . $tpl;
		}

		return $tpl;
	}
}


?>
