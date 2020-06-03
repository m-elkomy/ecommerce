<?php
    session_start();
    $pagetitle = 'Tags';
    include 'init.php';
    ?>

    <div class="container">
        <div class="row">
            <?php
            if(isset($_GET['name'])){
                $tag = $_GET['name'];
                echo '<h1 class="text-center">' . strtoupper($tag). '</h1>';
                $tagitem = getall("*","Items","WHERE Tags LIKE '%$tag%'","AND Approve =1","ItemID");
                foreach($tagitem as $item){
                    echo '<div class="col-md-3">';
                    echo '<div class="thumbnail item-box">';
                    echo '<span class="price">' . $item['Price'] . ' $</span>';
                    echo '<img src="avatar.png" alt="avatar" class="img-responsive"/>';
                    echo '<div class="caption">';
                    echo '<h3><a href="items.php?itemid='.$item['ItemID'].'">' . $item['ItemName'] . '</a></h3>';
                    echo '<p>'  . $item['Description'] . '</p>';
                    echo '<div class="date">'  . $item['Adding_Date'] . '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            }else{
                echo '<div class="alert alert-danger">You Must Enter Tag Name</div>';
            }
            ?>
        </div>
    </div>

<?php include $tpl . 'footer.php';?>