<?php
//dezend by  QQ:2172298892
require 'include.php';
$params = array('username' => 'b', 'password' => 'c');
$a = $c->post('/user/login', $params);
var_dump($a);

?>
