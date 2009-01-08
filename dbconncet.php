<?php
function connect(){
	$link = mysql_connect("localhost","root","1234");//fill in your database stuff
	$db = mysql_select_db("hdrc");
	return $link;
	//test commitment
}
?>
