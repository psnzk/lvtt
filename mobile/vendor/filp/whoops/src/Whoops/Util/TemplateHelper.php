<?php
//dezend by  QQ:2172298892
namespace Whoops\Util;

class TemplateHelper
{
	/**
     * An array of variables to be passed to all templates
     * @var array
     */
	private $variables = array();

	public function escape($raw)
	{
		$flags = ENT_QUOTES;
		if (defined('ENT_SUBSTITUTE') && !defined('HHVM_VERSION')) {
			$flags |= ENT_SUBSTITUTE;
		}
		else {
			$flags |= ENT_IGNORE;
		}

		return htmlspecialchars($raw, $flags, 'UTF-8');
	}

	public function escapeButPreserveUris($raw)
	{
		$escaped = $this->escape($raw);
		return preg_replace('@([A-z]+?://([-\\w\\.]+[-\\w])+(:\\d+)?(/([\\w/_\\.#-]*(\\?\\S+)?[^\\.\\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $escaped);
	}

	public function slug($original)
	{
		$slug = str_replace(' ', '-', $original);
		$slug = preg_replace('/[^\\w\\d\\-\\_]/i', '', $slug);
		return strtolower($slug);
	}

	public function render($template, array $additionalVariables = NULL)
	{
		$variables = $this->getVariables();
		$variables['tpl'] = $this;

		if ($additionalVariables !== null) {
			$variables = array_replace($variables, $additionalVariables);
		}

		call_user_func(function() {
			extract(func_get_arg(1));
			require func_get_arg(0);
		}, $template, $variables);
	}

	public function setVariables(array $variables)
	{
		$this->variables = $variables;
	}

	public function setVariable($variableName, $variableValue)
	{
		$this->variables[$variableName] = $variableValue;
	}

	public function getVariable($variableName, $defaultValue = NULL)
	{
		return isset($this->variables[$variableName]) ? $this->variables[$variableName] : $defaultValue;
	}

	public function delVariable($variableName)
	{
		unset($this->variables[$variableName]);
	}

	public function getVariables()
	{
		return $this->variables;
	}
}


?>
