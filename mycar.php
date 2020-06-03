<?php
ob_start();
session_start();
$pagetitle = 'Show Items';
include 'init.php';
if(isset($_SESSION['userprofile'])){
$userid = $_SESSION['id'];

//check that this userid exist in database
$stmt = $con->prepare("SELECT 
                                          shopingcar.*,
                                          Items.*,
                                          Users.*
                                    FROM 
                                          shopingcar 
                                    INNER JOIN 
                                          Items 
                                    ON 
                                          shopingcar.ItemID=Items.ItemID
                                    INNER JOIN
                                          Users
                                    ON 
                                          shopingcar.UserID=Users.UserID
                                    WHERE shopingcar.UserID=?");
$stmt->execute(array($userid));
$items = $stmt->fetchAll();
$count = $stmt->rowCount();
if($count>0){
    ?>
    <h1 class="text-center">My Car</h1>
    <div class="container">
    <?php
     if(!empty($items)){
         foreach($items as $item){?>
        <div class="row">
            <div class="col-md-3">
                <?php
                echo '<div class="show-item">';
                if (empty($item['ItemAvatar'])) {
                    echo '<img src="avatar.png" alt="avatar" class="img-responsive center-block img-thumbnail"/>';
                } else {
                    echo "<img src='admin/uploads/items/" . $item['ItemAvatar'] . "' alt='avatar' class='img-responsive center-block img-thumbnail' />";
                }
                echo '</div>';?>
            </div>
            <div class="col-md-9 item-info">
                <h2><?php echo $item['ItemName']?></h2>
                <p><?php echo $item['Description']?></p>
                <ul class="list-unstyled">
                    <li>
                        <i class="fa fa-calendar fa-fw"></i>
                        <span>Adding Date : </span><?php echo $item['Adding_Date']?>
                    </li>
                    <li>
                        <i class="fa fa-money fa-fw"></i>
                        <span>Price : </span>$ <?php echo $item['Price']?>
                    </li>
                    <li>
                        <i class="fa fa-building fa-fw"></i>
                        <span>Made In : </span><?php echo $item['Country']?>
                    </li>
                    <li>
                        <i class="fa fa-tag fa-fw"></i>
                        <span>Category : </span>
                        <?php
                            $stmt = $con->prepare("SELECT 
                                                                  Items.*,Categories.CategoryName 
                                                            FROM 
                                                                  Items 
                                                            INNER JOIN 
                                                                    Categories 
                                                            ON 
                                                                    Items.CatID = Categories.CategoryID 
                                                            WHERE ItemID=?");
                            $stmt->execute(array($item['ItemID']));
                            $getcat = $stmt->fetch();
                        ?>
                        <a href="categories.php?pageid=<?php echo $getcat['CatID']?>"><?php echo $getcat['CategoryName'] ?></a>
                    </li>
                    <li>
                        <i class="fa fa-user fa-fw"></i>
                        <span>Added By : </span><a href="profileview.php?userid=<?php echo $item['UserID']?>"><?php echo $item['UserName'] ?></a>
                    </li>
                    <li class="tag-item">
                        <i class="fa fa-user fa-fw"></i>
                        <span>Tags : </span>
                        <?php
                        $alltags = explode(',',$item['Tags']);
                        foreach($alltags as $tag){
                            $tag = str_replace(' ' ,'',$tag);
                            $lowertag = strtolower($tag);
                            if(!empty($tag)){
                                echo "<a href='tags.php?name={$lowertag}'>" . $tag . '</a>';
                            }else{
                                echo 'There Is No Tags To Preview';
                            }
                        }
                        ?>
                    </li>
                </ul>
            </div>
        </div>
        <hr class="cutom-hr">
     <?php
         }

     }else{
          echo '<div class="container">';
            echo '<div class="alert alert-danger">There Is No Item To Show</div>';
            echo '</div>';
     }
}else{
    echo '<div class="container">';
    echo '<div class="alert alert-danger">Cat Is Empty</div>';
    echo '</div>';
}
}else{
    header('Location:login.php');
    exit();
}
include $tpl . 'footer.php';
ob_end_flush();
?>