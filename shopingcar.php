<?php
    ob_start();
    session_start();
    $pagetitle = 'Show Items';
    include 'init.php';
    if(isset($_SESSION['userprofile'])){
        //check that coming id is number and get it's integer value
        $itemid = $_POST['itemid'];
        $getitem = $con->prepare("SELECT * FROM Items WHERE ItemID=?");
        $getitem->execute(array($itemid));
        $getitemfetch = $getitem->fetch();
        $count = $getitem->rowCount();
        if($count == 1){
                //receive data from form
                $amount = $_POST['amount'];
                $filteramount = filter_var($amount,FILTER_VALIDATE_INT);
                if(intval($filteramount) && $filteramount!= 0 && !empty($filteramount)){
                //check that this userid exist in database
                $stmt = $con->prepare("INSERT INTO `shopingcar`(ItemID,UserID,Amount,Price) VALUES (:zitem,:zuser,:zamount,:zprice) ");
                $stmt->execute(array(
                    ':zitem'  => $itemid,
                    ':zuser'  => $_SESSION['id'],
                    ':zamount'=> $amount,
                    ':zprice' => $amount * $getitemfetch['Price'],
                ));
                $count = $stmt->rowCount();
                    ?>
                    <div class="container">';
                            <div class="alert alert-success"><?php echo $count ?> Item Added To Car</div>
                    </div>
                    <?php
                }else{
                    echo '<div class="container">';
                            echo '<div class="alert alert-danger">Amount Must Be Number > 0</div>';
                    echo '</div>';
                }

            }else{
                echo '<div class="container">';
                echo '<div class="alert alert-danger">Sorry There Is Error With Your Request</div>';
                echo '</div>';
            }
    }else{
        echo '<div class="container">';
        echo '<div class="alert alert-danger">You Must <a href="login.php">Register</a> Or <a href="login.php">Login</a> </div>';
        echo '</div>';
        header("Refresh:5; url=login.php");
        exit();
    }
include $tpl . 'footer.php';
ob_end_flush();
?>