<?php


session_start();//continue sesison
unset($_SESSION["profileid"]);
unset($_SESSION["userprofile"]);
header('Location:index.php');//redirect to login page
exit();