<?php
    ob_start();
    session_start();
    $pagetitle = 'Categories';
    include 'init.php';?>

<div class="container">
	<h1 class="text-center">Show Category</h1>
	<div class="row">
	<?php
        if(isset($_GET['pageid']) && is_numeric($_GET['pageid'])){
            $category = intval($_GET['pageid']);
            $getall = getall("*","Items","WHERE CatID={$category}","AND Approve =1","ItemID");
            foreach($getall as $item){
                echo '<div class="col-md-3">';
                    echo '<div class="thumbnail item-box">';
                        echo '<span class="price">' . $item['Price'] . ' $</span>';
                        echo '<div class="image">';
                         if (empty($item['ItemAvatar'])) {
                            echo '<img src="avatar.png" alt="avatar" class="home-image img-responsive center-block img-thumbnail"/>';
                        } else {
                            echo "<img src='admin/uploads/items/" . $item['ItemAvatar'] . "' alt='avatar' class='home-image img-thumbnail' />";
                        }
                        echo '</div>';
                        echo '<div class="caption">';
                            echo '<h3><a href="items.php?itemid='.$item['ItemID'].'">' . $item['ItemName'] . '</a></h3>';
                            echo '<div class="desc">'  . $item['Description'] . '</div>';
                            echo '<div class="date">'  . $item['Adding_Date'] . '</div>';
                            echo '<div class="clearfix"></div>';
                            echo '<div class="buy">
                                            <a href="#" class="btn btn-success btn-lg">Add To Car</a>
                                            <input type="number" class="form-control" placeholder="Amount" min="0"/>
                              </div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            }
		}else{
            echo '<div class="alert alert-danger">You Must Enter Page ID</div>';
        }
    ?>
	</div>
</div>

<?php include $tpl . 'footer.php';
ob_end_flush();?>