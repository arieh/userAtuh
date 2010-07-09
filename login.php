<?php
require_once("classes/keyHandler.class.php");
require_once("dbconncet.php");

$link = connect();

$gen = new keyHandler($link,'/configs/userAtuh.ini');

$name = (isset($_POST['userName']))? $_POST['userName'] : '';
$enc  = (isset($_POST['encrypt'])) ? $_POST['encrypt']  : '';

if ($gen->authenticate($name,$enc,true)) {
	echo "in";
}else{
	echo "out";
}
?>
