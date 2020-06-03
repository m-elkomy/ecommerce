<?php
ob_start();
session_start();
$pagetitle = 'Show Items';
include 'init.php';
if(isset($_SESSION['userprofile'])){
echo '<h1>Your PayPal transaction has been canceled.</h1>';
}else{
    header('Location:login.php');
    exit();
}
include $tpl . 'footer.php';
ob_end_flush();
?>