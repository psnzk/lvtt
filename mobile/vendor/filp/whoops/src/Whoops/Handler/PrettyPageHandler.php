<?php
//dezend by  QQ:2172298892
namespace Whoops\Handler;

class PrettyPageHandler extends Handler
{
	/**
     * Search paths to be scanned for resources, in the reverse
     * order they're declared.
     *
     * @var array
     */
	private $searchPaths = array();
	/**
     * Fast lookup cache for known resource locations.
     *
     * @var array
     */
	private $resourceCache = array();
	/**
     * The name of the custom css file.
     *
     * @var string
     */
	private $customCss;
	/**
     * @var array[]
     */
	private $extraTables = array();
	/**
     * @var bool
     */
	private $handleUnconditionally = false;
	/**
     * @var string
     */
	private $pageTitle = 'Whoops! There was an error.';
	/**
     * A string identifier for a known IDE/text editor, or a closure
     * that resolves a string that can be used to open a given file
     * in an editor. If the string contains the special substrings
     * %file or %line, they will be replaced with the correct data.
     *
     * @example
     *  "txmt://open?url=%file&line=%line"
     * @var mixed $editor
     */
	protected $editor;
	/**
     * A list of known editor strings
     * @var array
     */
	protected $editors = array('sublime' => 'subl://open?url=file://%file&line=%line', 'textmate' => 'txmt://open?url=file://%file&line=%line', 'emacs' => 'emacs://open?url=file://%file&line=%line', 'macvim' => 'mvim://open/?url=file://%file&line=%line', 'phpstorm' => 'phpstorm://open?file=%file&line=%line');

	public function __construct()
	{
		if (ini_get('xdebug.file_link_format') || extension_loaded('xdebug')) {
			$this->editors['xdebug'] = function($file, $line) {
				return str_replace(array('%f', '%l'), array($file, $line), ini_get('xdebug.file_link_format'));
			};
		}

		$this->searchPaths[] = __DIR__ . '/../Resources';
	}

	public function handle()
	{
		if (!$this->handleUnconditionally()) {
			if (php_sapi_name() === 'cli') {
				if (isset($_ENV['whoops-test'])) {
					throw new \Exception('Use handleUnconditionally instead of whoops-test' . ' environment variable');
				}

				return Handler::DONE;
			}
		}

		$helper = new \Whoops\Util\TemplateHelper();
		$templateFile = $this->getResource('views/layout.html.php');
		$cssFile = $this->getResource('css/whoops.base.css');
		$zeptoFile = $this->getResource('js/zepto.min.js');
		$jsFile = $this->getResource('js/whoops.base.js');

		if ($this->customCss) {
			$customCssFile = $this->getResource($this->customCss);
		}

		$inspector = $this->getInspector();
		$frames = $inspector->getFrames();
		$code = $inspector->getException()->getCode();

		if ($inspector->getException() instanceof \ErrorException) {
			$code = \Whoops\Util\Misc::translateErrorCode($inspector->getException()->getSeverity());
		}

		$vars = array(
			'page_title'      => $this->getPageTitle(),
			'stylesheet'      => file_get_contents($cssFile),
			'zepto'           => file_get_contents($zeptoFile),
			'javascript'      => file_get_contents($jsFile),
			'header'          => $this->getResource('views/header.html.php'),
			'frame_list'      => $this->getResource('views/frame_list.html.php'),
			'frame_code'      => $this->getResource('views/frame_code.html.php'),
			'env_details'     => $this->getResource('views/env_details.html.php'),
			'title'           => $this->getPageTitle(),
			'name'            => explode('\\', $inspector->getExceptionName()),
			'message'         => $inspector->getException()->getMessage(),
			'code'            => $code,
			'plain_exception' => \Whoops\Exception\Formatter::formatExceptionPlain($inspector),
			'frames'          => $frames,
			'has_frames'      => !!count($frames),
			'handler'         => $this,
			'handlers'        => $this->getRun()->getHandlers(),
			'tables'          => array('GET Data' => $_GET, 'POST Data' => $_POST, 'Files' => $_FILES, 'Cookies' => $_COOKIE, 'Session' => isset($_SESSION) ? $_SESSION : array(), 'Server/Request Data' => $_SERVER, 'Environment Variables' => $_ENV)
			);

		if (isset($customCssFile)) {
			$vars['stylesheet'] .= file_get_contents($customCssFile);
		}

		$extraTables = array_map(function($table) {
			return $table instanceof \Closure ? $table() : $table;
		}, $this->getDataTables());
		$vars['tables'] = array_merge($extraTables, $vars['tables']);
		$helper->setVariables($vars);
		$helper->render($templateFile);
		return Handler::QUIT;
	}

	public function addDataTable($label, array $data)
	{
		$this->extraTables[$label] = $data;
	}

	public function addDataTableCallback($label, $callback)
	{
		if (!is_callable($callback)) {
			throw new \InvalidArgumentException('Expecting callback argument to be callable');
		}

		$this->extraTables[$label] = function() use($callback) {
			try {
				$result = call_user_func($callback);
				return is_array($result) || $result instanceof \Traversable ? $result : array();
			}
			catch (\Exception $e) {
				return array();
			}
		};
	}

	public function getDataTables($label = NULL)
	{
		if ($label !== null) {
			return isset($this->extraTables[$label]) ? $this->extraTables[$label] : array();
		}

		return $this->extraTables;
	}

	public function handleUnconditionally($value = NULL)
	{
		if (func_num_args() == 0) {
			return $this->handleUnconditionally;
		}

		$this->handleUnconditionally = (bool) $value;
	}

	public function addEditor($identifier, $resolver)
	{
		$this->editors[$identifier] = $resolver;
	}

	public function setEditor($editor)
	{
		if (!is_callable($editor) && !isset($this->editors[$editor])) {
			throw new \InvalidArgumentException('Unknown editor identifier: ' . $editor . '. Known editors:' . implode(',', array_keys($this->editors)));
		}

		$this->editor = $editor;
	}

	public function getEditorHref($filePath, $line)
	{
		$editor = $this->getEditor($filePath, $line);

		if (!$editor) {
			return false;
		}

		if (!isset($editor['url']) || !is_string($editor['url'])) {
			throw new \UnexpectedValueException('Whoops\\Handler\\PrettyPageHandler::getEditorHref' . ' should always resolve to a string or a valid editor array; got something else instead.');
		}

		$editor['url'] = str_replace('%line', rawurlencode($line), $editor['url']);
		$editor['url'] = str_replace('%file', rawurlencode($filePath), $editor['url']);
		return $editor['url'];
	}

	public function getEditorAjax($filePath, $line)
	{
		$editor = $this->getEditor($filePath, $line);
		if (!isset($editor['ajax']) || !is_bool($editor['ajax'])) {
			throw new \UnexpectedValueException('Whoops\\Handler\\PrettyPageHandler::getEditorAjax' . ' should always resolve to a bool; got something else instead.');
		}

		return $editor['ajax'];
	}

	protected function getEditor($filePath, $line)
	{
		if (($this->editor === null) && !is_string($this->editor) && !is_callable($this->editor)) {
			return false;
		}
		else {
			if (is_string($this->editor) && isset($this->editors[$this->editor]) && !is_callable($this->editors[$this->editor])) {
				return array('ajax' => false, 'url' => $this->editors[$this->editor]);
			}
			else {
				if (is_callable($this->editor) || (isset($this->editors[$this->editor]) && is_callable($this->editors[$this->editor]))) {
					if (is_callable($this->editor)) {
						$callback = call_user_func($this->editor, $filePath, $line);
					}
					else {
						$callback = call_user_func($this->editors[$this->editor], $filePath, $line);
					}

					return array('ajax' => isset($callback['ajax']) ? $callback['ajax'] : false, 'url' => is_array($callback) ? $callback['url'] : $callback);
				}
			}
		}

		return false;
	}

	public function setPageTitle($title)
	{
		$this->pageTitle = (string) $title;
	}

	public function getPageTitle()
	{
		return $this->pageTitle;
	}

	public function addResourcePath($path)
	{
		if (!is_dir($path)) {
			throw new \InvalidArgumentException('\'' . $path . '\' is not a valid directory');
		}

		array_unshift($this->searchPaths, $path);
	}

	public function addCustomCss($name)
	{
		$this->customCss = $name;
	}

	public function getResourcePaths()
	{
		return $this->searchPaths;
	}

	protected function getResource($resource)
	{
		if (isset($this->resourceCache[$resource])) {
			return $this->resourceCache[$resource];
		}

		foreach ($this->searchPaths as $path) {
			$fullPath = $path . '/' . $resource;

			if (is_file($fullPath)) {
				$this->resourceCache[$resource] = $fullPath;
				return $fullPath;
			}
		}

		throw new \RuntimeException('Could not find resource \'' . $resource . '\' in any resource paths.' . '(searched: ' . join(', ', $this->searchPaths) . ')');
	}

	public function getResourcesPath()
	{
		$allPaths = $this->getResourcePaths();
		return end($allPaths) ?: null;
	}

	public function setResourcesPath($resourcesPath)
	{
		$this->addResourcePath($resourcesPath);
	}
}

?>
