<?php
//dezend by  QQ:2172298892
namespace libraries\send;

class EmailDriver implements SendInterface
{
	protected $config = array('smtp_host' => 'smtp.qq.com', 'smtp_port' => '465', 'smtp_ssl' => false, 'smtp_username' => '', 'smtp_password' => '', 'smtp_from_to' => '', 'smtp_from_name' => 'ECTouch');
	protected $errorMsg = '';
	protected $email;

	public function __construct($config = array())
	{
		$this->config = array_merge($this->config, $config);
		$this->mail = new \libraries\Email($this->config);
	}

	public function push($to, $title, $content, $time = '', $data = array())
	{
		return $this->mail->setMail($title, $content)->sendMail($to);
	}

	public function getError()
	{
		return $this->mail->getError();
	}
}

?>
