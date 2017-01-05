<?php
//dezend by  QQ:2172298892
namespace Monolog\Handler;

class BrowserConsoleHandlerTest extends \Monolog\TestCase
{
	protected function setUp()
	{
		BrowserConsoleHandler::reset();
	}

	protected function generateScript()
	{
		$reflMethod = new \ReflectionMethod('Monolog\\Handler\\BrowserConsoleHandler', 'generateScript');
		$reflMethod->setAccessible(true);
		return $reflMethod->invoke(null);
	}

	public function testStyling()
	{
		$handler = new BrowserConsoleHandler();
		$handler->setFormatter($this->getIdentityFormatter());
		$handler->handle($this->getRecord(\Monolog\Logger::DEBUG, 'foo[[bar]]{color: red}'));
		$expected = "(function (c) {if (c && c.groupCollapsed) {\r\nc.log(\"%cfoo%cbar%c\", \"font-weight: normal\", \"color: red\", \"font-weight: normal\");\r\n}})(console);";
		$this->assertEquals($expected, $this->generateScript());
	}

	public function testEscaping()
	{
		$handler = new BrowserConsoleHandler();
		$handler->setFormatter($this->getIdentityFormatter());
		$handler->handle($this->getRecord(\Monolog\Logger::DEBUG, "[foo] [[\"bar\n[baz]\"]]{color: red}"));
		$expected = "(function (c) {if (c && c.groupCollapsed) {\r\nc.log(\"%c[foo] %c\\\"bar\\n[baz]\\\"%c\", \"font-weight: normal\", \"color: red\", \"font-weight: normal\");\r\n}})(console);";
		$this->assertEquals($expected, $this->generateScript());
	}

	public function testAutolabel()
	{
		$handler = new BrowserConsoleHandler();
		$handler->setFormatter($this->getIdentityFormatter());
		$handler->handle($this->getRecord(\Monolog\Logger::DEBUG, '[[foo]]{macro: autolabel}'));
		$handler->handle($this->getRecord(\Monolog\Logger::DEBUG, '[[bar]]{macro: autolabel}'));
		$handler->handle($this->getRecord(\Monolog\Logger::DEBUG, '[[foo]]{macro: autolabel}'));
		$expected = "(function (c) {if (c && c.groupCollapsed) {\r\nc.log(\"%c%cfoo%c\", \"font-weight: normal\", \"background-color: blue; color: white; border-radius: 3px; padding: 0 2px 0 2px\", \"font-weight: normal\");\r\nc.log(\"%c%cbar%c\", \"font-weight: normal\", \"background-color: green; color: white; border-radius: 3px; padding: 0 2px 0 2px\", \"font-weight: normal\");\r\nc.log(\"%c%cfoo%c\", \"font-weight: normal\", \"background-color: blue; color: white; border-radius: 3px; padding: 0 2px 0 2px\", \"font-weight: normal\");\r\n}})(console);";
		$this->assertEquals($expected, $this->generateScript());
	}

	public function testContext()
	{
		$handler = new BrowserConsoleHandler();
		$handler->setFormatter($this->getIdentityFormatter());
		$handler->handle($this->getRecord(\Monolog\Logger::DEBUG, 'test', array('foo' => 'bar')));
		$expected = "(function (c) {if (c && c.groupCollapsed) {\r\nc.groupCollapsed(\"%ctest\", \"font-weight: normal\");\r\nc.log(\"%c%s\", \"font-weight: bold\", \"Context\");\r\nc.log(\"%s: %o\", \"foo\", \"bar\");\r\nc.groupEnd();\r\n}})(console);";
		$this->assertEquals($expected, $this->generateScript());
	}

	public function testConcurrentHandlers()
	{
		$handler1 = new BrowserConsoleHandler();
		$handler1->setFormatter($this->getIdentityFormatter());
		$handler2 = new BrowserConsoleHandler();
		$handler2->setFormatter($this->getIdentityFormatter());
		$handler1->handle($this->getRecord(\Monolog\Logger::DEBUG, 'test1'));
		$handler2->handle($this->getRecord(\Monolog\Logger::DEBUG, 'test2'));
		$handler1->handle($this->getRecord(\Monolog\Logger::DEBUG, 'test3'));
		$handler2->handle($this->getRecord(\Monolog\Logger::DEBUG, 'test4'));
		$expected = "(function (c) {if (c && c.groupCollapsed) {\r\nc.log(\"%ctest1\", \"font-weight: normal\");\r\nc.log(\"%ctest2\", \"font-weight: normal\");\r\nc.log(\"%ctest3\", \"font-weight: normal\");\r\nc.log(\"%ctest4\", \"font-weight: normal\");\r\n}})(console);";
		$this->assertEquals($expected, $this->generateScript());
	}
}

?>
