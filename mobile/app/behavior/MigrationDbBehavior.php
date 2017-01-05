<?php
//zend by QQ:2172298892
namespace app\behavior;

class MigrationDbBehavior
{
	private $model;
	private $fs;

	public function run()
	{
		$migration_lock = ROOT_PATH . 'storage/migration.lock';

		if (!is_file($migration_lock)) {
			$this->model = new \app\classes\Mysql();
			$this->fs = new \Symfony\Component\Filesystem\Filesystem();
			$migrations = glob(ROOT_PATH . 'database/migrations/migrate-*.sql');

			foreach ($migrations as $vo) {
				if (substr(basename($vo), 0, 12) == 'migrate-2016') {
					$this->fs->remove($vo);
				}
			}

			$migrate = $this->model->table('shop_config')->where(array('code' => 'migrate_version'))->find();

			if (substr($migrate['value'], 0, 4) == '2016') {
				$data['value'] = strtotime($migrate['value']);
				$this->model->table('shop_config')->where(array('code' => 'migrate_version'))->save($data);
			}

			$migration_hash = array();

			foreach ($migrations as $vo) {
				$migration_hash[] = hash_file('md5', $vo);
			}

			$app_db_list = glob(BASE_PATH . 'http/*/database/*.sql');

			foreach ($app_db_list as $key => $file) {
				if (stripos($file, 'http/wechat/database/db.sql') !== false) {
					$wechat = $app_db_list[$key];
					unset($app_db_list[$key]);
					array_unshift($app_db_list, $wechat);
				}
			}

			foreach ($app_db_list as $key => $original) {
				$hash = hash_file('md5', $original);

				if (!in_array($hash, $migration_hash)) {
					$migration = ROOT_PATH . 'database/migrations/migrate-' . time() . $key . '.sql';
					$migrate_path = dirname($migration);

					if (!is_dir($migrate_path)) {
						if (!mkdir($migrate_path, 511, true)) {
							throw new \Exception('Can not create dir \'' . $migrate_path . '\'', 500);
						}
					}

					if (!is_writable($migrate_path)) {
						chmod($migrate_path, 511);
					}

					if (is_file($original)) {
						$this->fs->copy($original, $migration);
					}
				}
			}

			\ectouch\Migrate::init();
			$this->fs->touch($migration_lock);
		}
	}
}


?>
