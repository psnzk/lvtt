<?php
//dezend by  QQ:2172298892
namespace libraries;

class Migrate
{
	static public $version = 0;
	static public $migrate_path = '';
	static public $migrate_version = '';
	static public $migrate_files = array();
	static private $conn = '';

	static public function setPath()
	{
		self::$migrate_path = ROOT_PATH . 'database/migrations/';
		self::$migrate_version = ROOT_PATH . 'storage/migrations/' . MIGRATE_VERSION_FILE;
	}

	static public function init()
	{
		self::setPath();
		self::connect();
		self::create_migrate();
		self::update_db();
	}

	static public function create_migrate()
	{
		$sql = 'SELECT `value` FROM `' . c('DB.default.DB_PREFIX') . 'shop_config` where `code`=\'migrate_version\'';
		$result = self::query($sql);

		if (is_null($result)) {
			if (file_exists(self::$migrate_version)) {
				self::$version = floatval(file_get_contents(self::$migrate_version));
			}
			else {
				self::$version = 0;
			}

			$sql = 'INSERT INTO ' . c('DB.default.DB_PREFIX') . "shop_config (`parent_id`, `code`, `type`, `value`, `sort_order`) \r\n            VALUES (9, 'migrate_version', 'hidden', '" . self::$version . '\', 1)';
			$result = self::execute($sql);
			if ($result && file_exists(self::$migrate_version)) {
				@unlink(self::$migrate_version);
			}
		}
		else {
			self::$version = $result[0];
		}
	}

	static public function get_migrations()
	{
		$dir = opendir(self::$migrate_path);

		while ($file = readdir($dir)) {
			if (substr($file, 0, strlen(MIGRATE_FILE_PREFIX)) == MIGRATE_FILE_PREFIX) {
				self::$migrate_files[] = $file;
			}
		}

		asort(self::$migrate_files);
	}

	static public function get_version_from_file($file)
	{
		return floatval(substr($file, strlen(MIGRATE_FILE_PREFIX)));
	}

	static public function update_db()
	{
		self::get_migrations();
		$errors = array();
		$last_file = false;
		$last_version = false;

		foreach (self::$migrate_files as $file) {
			$file_version = self::get_version_from_file($file);
			if (($last_version !== false) && ($last_version === $file_version)) {
				$errors[] = $last_file . ' --- ' . $file;
			}

			$last_version = $file_version;
			$last_file = $file;
		}

		if (0 < count($errors)) {
			echo "数据迁移文件存在多个相同的版本.\n";

			foreach ($errors as $error) {
				echo ' ' . $error . "\n";
			}

			exit();
		}

		foreach (self::$migrate_files as $file) {
			$file_version = self::get_version_from_file($file);

			if ($file_version <= self::$version) {
				continue;
			}

			$sqls = file_get_contents(self::$migrate_path . $file);
			$sqls = self::selectsql($sqls);
			$str = null;
			$num = 1;
			self::execute('set names utf8');
			self::execute('BEGIN');

			foreach ((array) $sqls as $val) {
				if (empty($val)) {
					continue;
				}

				if (is_string($val)) {
					if (!self::execute($val)) {
						$num = 0;
					}
				}
			}

			if ($num == 0) {
				self::execute('ROLLBACK');
			}
			else if ($num == 1) {
				self::execute('COMMIT');
			}

			$sql = 'UPDATE ' . c('DB.default.DB_PREFIX') . 'shop_config SET value = \'' . $file_version . '\' WHERE code = \'migrate_version\'';
			$query = self::execute($sql);

			if (!$query) {
				exit('Data migration failed');
			}
		}
	}

	static public function query($str)
	{
		$result = self::execute($str);
		return mysqli_fetch_row($result);
	}

	static public function execute($str)
	{
		return mysqli_query(self::$conn, $str);
	}

	static public function connect()
	{
		$db = c('DB.default');
		(self::$conn = mysqli_connect($db['DB_HOST'], $db['DB_USER'], $db['DB_PWD'], $db['DB_NAME'], $db['DB_PORT'])) || exit('Error:cannot connect to database!!!' . mysql_error());
	}

	static public function selectsql($sqls)
	{
		$statement = null;
		$newStatement = null;
		$commenter = array('#', '--');
		$sqls = explode(';', trim($sqls));

		foreach ($sqls as $sql) {
			if (preg_match('/^(\\/\\*)(.)+/i', $sql)) {
				$sql = preg_replace('/(\\/\\*){1}([.|\\s|\\S])*(\\*\\/){1}/', '', $sql);
			}

			$sentence = explode('/n', $sql);

			foreach ($sentence as $subSentence) {
				$subSentence = str_replace('{pre}', c('DB.default.DB_PREFIX'), $subSentence);

				if ('' != trim($subSentence)) {
					$isComment = false;

					foreach ($commenter as $comer) {
						if (preg_match('/^(' . $comer . ')/', trim($subSentence))) {
							$isComment = true;
							break;
						}
					}

					if (!$isComment) {
						$newStatement[] = $subSentence;
					}
				}
			}

			$statement = $newStatement;
		}

		return $statement;
	}
}

define('MIGRATE_VERSION_FILE', '.version');
define('MIGRATE_FILE_PREFIX', 'migrate-');
define('MIGRATE_FILE_POSTFIX', '.php');

?>
