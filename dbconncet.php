<?php
function connect(){
	$link = mysql_connect("localhost","root","rjntqvzz");//fill in your database stuff
	$db = mysql_select_db("hdrc");
	return $link;
}
?>
