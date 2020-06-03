<?php


session_start();//continue sesison
unset($_SESSION["userid"]);
unset($_SESSION["username"]);
header('Location:index.php');//redirect to login page
exit();