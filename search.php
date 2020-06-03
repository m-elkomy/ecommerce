<?php
ob_start();
session_start();
$pagetitle = 'Profile View';
include 'init.php';
    echo '<div class="container">';
    $query = $_POST['searchquery'];
    $term = filter_var($query,FILTER_SANITIZE_STRING);
    if(!empty($term)){
    $sql = $con->prepare("SELECT items.* FROM items WHERE ItemName LIKE '%".$term."%' OR Description LIKE '%".$term."%'");
    $sql->execute();
        while ($rows=$sql->fetch(PDO::FETCH_ASSOC)) {
            echo '<div class="col-sm-6 col-md-3">';
            echo '<div class="thumbnail item-box">';
            echo '<span class="price">$' . $rows['Price'] . '</span>';
            echo '<div class="image">';
            if (empty($rows['ItemAvatar'])) {
                echo '<img src="avatar.png" alt="avatar" class="home-image img-responsive img-thumbnail"/>';
            } else {
                echo "<img src='admin/uploads/items/" . $rows['ItemAvatar'] . "' alt='avatar' class='home-image img-responsive img-thumbnail' />";
            }
            echo '</div>';
            echo '<div class="caption">';
            echo '<h3><a href="items.php?itemid=' . $rows['ItemID'] . '">' . $rows['ItemName'] . '</a></h3>';
            echo '<div class="desc">'  . $rows['Description'] . '</div>';
            echo '<div class="date">'  . $rows['Adding_Date'] . '</div>';
            echo '<div class="clearfix"></div>';
            echo '<div class="buy">
                                                <form action="shopingcar.php" method="post">
                                                <input type="number" name="amount" class="form-control" placeholder="Amount" min="0"/>
                                                <input type="hidden" name="itemid" value="'.$rows['ItemID'].'"/>
                                                <input type="submit" value="Add To Car" class="btn btn-success"/>
                                                </form>
                            </div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';

        }
        }else{
        echo '<div class="alert alert-info">Search Emtpy</div>';
    }
    echo '</div>';
include $tpl . 'footer.php';
ob_end_flush();
?>