<?php
ob_start();
session_start();
$pagetitle = 'Show Items';
include 'init.php';
if(isset($_SESSION['userprofile'])){
    $itemNo            = $_REQUEST['item_number'];
    $itemTransaction   = $_REQUEST['tx']; // Paypal transaction ID
    $itemPrice         = $_REQUEST['amt']; // Paypal received amount
    $itemCurrency      = $_REQUEST['cc']; // Paypal received currency type

    $price = '20.00';
    $currency='USD';

    if($itemPrice==$price && $itemCurrency==$currency)
    {
        echo "Payment Successful";
    }
    else
    {
        echo "Payment Failed";
    }
}else{
    header('Location:login.php');
    exit();
}
include $tpl . 'footer.php';
ob_end_flush();
?>