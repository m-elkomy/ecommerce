<?php
    ob_start();
	session_start();
	$pagetitle = 'Home Page';
	include 'init.php';
	?>
    <div class="container">
	<div class="row">
	<?php
		$allads = getall('*','Items','WHERE Approve = 1','','ItemID');
		if(!empty($allads)){
		foreach($allads as $ads){
			echo '<div class="col-md-3">';
				echo '<div class="thumbnail item-box">';
					echo '<span class="price">' . $ads['Price'] . ' $</span>';
					echo '<div class="image">';
					if (empty($ads['ItemAvatar'])) {
                            echo '<img src="avatar.png" alt="avatar" class="home-image img-responsive img-thumbnail"/>';
                        } else {
                            echo "<img src='admin/uploads/items/" . $ads['ItemAvatar'] . "' alt='avatar' class='home-image img-responsive img-thumbnail' />";
                        }
                        echo '</div>';
					echo '<div class="caption">';
						echo '<h3><a href="items.php?itemid='.$ads['ItemID'].'">' . $ads['ItemName'] . '</a></h3>';
						echo '<div class="desc">'  . $ads['Description'] . '</div>';
                        echo '<div class="date">'  . $ads['Adding_Date'] . '</div>';
                        echo '<div class="clearfix"></div>';
                            echo '<div class="buy">
                                                <form action="shopingcar.php" method="post">
                                                <input type="number" name="amount" class="form-control" placeholder="Amount" min="0"/>
                                                <input type="hidden" name="itemid" value="'.$ads['ItemID'].'"/>
                                                <input type="submit" value="Add To Car" class="btn btn-success"/>
                                                </form>
                            </div>';
					echo '</div>';
				echo '</div>';
			echo '</div>';
		}
        }else{
		    echo '<div class="alert aler-info">There Is No Items To Show</div>';
        }
	?>
	</div>
</div>
<?php
include $tpl . 'footer.php';
ob_end_flush();?>