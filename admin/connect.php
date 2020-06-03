<?php


$dsn    = 'mysql:host=fdb20.awardspace.net;dbname=3305324_shop';
$user   = '3305324_shop';
$pass   = 'elkomy95';
$option = array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8');
try{
	$con = new PDO($dsn,$user,$pass,$option);
	$con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch(PDOEXCEPTION $e){
	echo 'Failed ' . $e->getMessage();
}
