<?php

//error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$sessionuser = '';
if(isset($_SESSION['userprofile'])){
	$sessionuser = $_SESSION['userprofile'];
}


//include connect to database
include 'admin/connect.php';


//initalize routes for direcotry
$langu = 'includes/languages/';//languages directory
$func  = 'includes/functions/';//function directory
$tpl   = 'includes/templates/';//templates directory
$css   = 'layout/css/';        //css directory
$js    = 'layout/js/';         //js direcotry

//include important file
include $func  . 'function.php';
include $langu . 'english.php';
include $tpl   . 'header.php';
