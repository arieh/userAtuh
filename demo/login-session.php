<?php
require_once("../classes/KeyHandler.class.php");
require_once("../classes/UserAtuhDbaSession.class.php");
require_once("dbconncet.php");

$link = connect();
$db = new UserAtuhDbaSession($link,'../configs/user-atuh.ini');
$gen = new KeyHandler($db);

$name = (isset($_POST['userName']))? $_POST['userName'] : '';
$enc  = (isset($_POST['encrypt'])) ? $_POST['encrypt']  : '';

if ($gen->authenticate($name,$enc,true)) {
	echo "in";
}else{
	echo "out";
}
?>
