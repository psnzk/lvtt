<?php
//dezend by  QQ:2172298892
namespace libraries;

class Upload
{
	/**
	 * 上传配置
	 * @var array
	 */
	protected $config = array(
		'maxSize'      => 1048576,
		'allowExts'    => array(),
		'rootPath'     => './upload/',
		'savePath'     => '',
		'saveRule'     => 'md5_file',
		'driver'       => 'Local',
		'driverConfig' => array()
		);
	/**
     * 上传文件信息
     * @var array
     */
	protected $uploadFileInfo = array();
	/**
	 * 错误消息
	 * @var string
	 */
	protected $errorMsg = '';

	public function __construct($config = array())
	{
		$this->config = array_merge($this->config, $config);
		$this->setDriver();
	}

	public function upload($key = '')
	{
		if (empty($_FILES)) {
			$this->errorMsg = '没有文件上传！';
			return false;
		}

		if (empty($key)) {
			$files = $_FILES;
		}
		else {
			$files[$key] = $_FILES[$key];
		}

		if (!$this->uploader->rootPath($this->config['rootPath'])) {
			$this->errorMsg = $this->uploader->getError();
			return false;
		}

		$savePath = $this->config['rootPath'] . $this->config['savePath'];

		if (!$this->uploader->checkPath($savePath)) {
			$this->errorMsg = $this->uploader->getError();
			return false;
		}

		$num = 0;

		foreach ($files as $key => $file) {
			if ($file['error'] == 4) {
				continue;
			}

			$saveRuleFunc = $this->config['saveRule'];
			$pathinfo = pathinfo($file['name']);
			$file['key'] = $key;
			$file['extension'] = strtolower($pathinfo['extension']);
			$file['savepath'] = $savePath;
			$file['savename'] = $saveRuleFunc($file['tmp_name']) . '.' . $file['extension'];
			$file['driver'] = $this->config['driver'];

			if (!$this->check($file)) {
				return false;
			}

			$info = $this->uploader->saveFile($file, $config);

			if (!$info) {
				$this->errorMsg = $this->uploader->getError();
				return false;
			}

			$this->uploadFileInfo[$num] = $info;
			$this->uploadFileInfo[$key] = $info;
		}

		return true;
	}

	protected function check($file)
	{
		if ($file['error'] !== 0) {
			$this->errorMsg = '文件上传失败！';
			return false;
		}

		$this->allowExts = array_map('strtolower', $this->config['allowExts']);

		if (!in_array($file['extension'], $this->config['allowExts'])) {
			$this->errorMsg = '上传文件类型不允许！';
			return false;
		}

		if ($this->config['maxSize'] < $file['size']) {
			$this->errorMsg = '上传文件大小超出限制！';
			return false;
		}

		if (!is_uploaded_file($file['tmp_name'])) {
			$this->errorMsg = '非法上传文件！';
			return false;
		}

		if (in_array($file['extension'], array('gif', 'jpg', 'jpeg', 'bmp', 'png', 'swf')) && (false === getimagesize($file['tmp_name']))) {
			$this->errorMsg = '非法图像文件！';
			return false;
		}

		return true;
	}

	protected function setDriver()
	{
		$uploadDriver = 'libraries' . '\\upload\\' . ucfirst($this->config['driver']) . 'Driver';
		$this->uploader = new $uploadDriver($this->config);

		if (!$this->uploader) {
			throw new \Exception('Upload Driver \'' . $this->config['driver'] . '\' not found\'', 500);
		}
	}

	public function getUploadFileInfo()
	{
		return $this->uploadFileInfo;
	}

	public function getError()
	{
		return $this->errorMsg;
	}
}


?>
