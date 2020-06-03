<?php

//include connect to database
include 'connect.php';


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
	

//adding navbar to all page except the one has nonavba va
if(!isset($nonavbar)){
	include $tpl . 'navbar.php';
}