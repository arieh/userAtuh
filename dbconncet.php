<?php
function connect(){
	$link = mysql_connect("localhost","root","1234");//fill in your database stuff
	$db = mysql_select_db("some_db");
	return $link;
	//test commitment
}
?>
