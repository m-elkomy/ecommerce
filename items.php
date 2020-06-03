<?php
    ob_start();
    session_start();
    $pagetitle = 'Show Items';
    include 'init.php';
    //check that coming id is number and get it's integer value
    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
    //check that this userid exist in database
    $stmt = $con->prepare("SELECT 
                                          Items.*,
                                          Categories.CategoryName,
                                          Users.UserName,Users.UserID
                                    FROM 
                                          Items 
                                    INNER JOIN 
                                          Categories 
                                    ON 
                                          Items.CatID=Categories.CategoryID
                                    INNER JOIN
                                          Users
                                    ON 
                                          Items.UserID=Users.UserID
                                    WHERE ItemID=? AND Approve = 1");
    $stmt->execute(array($itemid));
    $count = $stmt->rowCount();
    if($count>0){
        $item = $stmt->fetch();
    ?>
        <h1 class="text-center"><?php echo $item['ItemName'] ?></h1>
        <div class="container">
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
                            <span>Category : </span><a href="categories.php?pageid=<?php echo $item['CatID']?>"><?php echo $item['CategoryName'] ?></a>
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
                                        echo 'There Is No Tag To Preview';
                                    }
                                }
                            ?>
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="cutom-hr">
            <!-- start add comment -->
            <?php if(isset($_SESSION['userprofile'])){
                    $stmt = $con->prepare("SELECT * FROM Users WHERE RegStatus=1 AND UserName=?");
                    $stmt->execute(array($_SESSION['userprofile']));
                    $info = $stmt->fetch();
                    $count = $stmt->rowCount();
                    if($count>0){
                ?>
            <div class="row">
                <div class="col-sm-offset-3">
                    <div class="add-comment">
                    <h3>Add Your Comment</h3>
                    <form action="<?php echo $_SERVER['PHP_SELF'] .'?itemid='.$item['ItemID'].'' ?>" method="Post">
                        <textarea required name="coment"></textarea>
                        <input class="btn btn-primary" type="submit" value="Add Comment"/>
                    </form>
                        <?php
                    }else{
                        echo '<div class="alert alert-info">Sorry You Are Under Upproval</div>';
                    }
                        if($_SERVER['REQUEST_METHOD']=='POST'){
                            $comment = filter_var($_POST['coment'],FILTER_SANITIZE_STRING);
                            $userid  = $_SESSION['id'];
                            $itemid  = $item['ItemID'];

                            if(!empty($comment)){
                                $stmt = $con->prepare("INSERT INTO Comments(Comment,Status,Date,UserID,ItemID)VALUES(:zcom,0,now(),:zuser,:zitem)");
                                $stmt->execute(array(
                                    ':zcom'  => $comment,
                                    ':zuser' => $userid,
                                    ':zitem' => $itemid
                                ));
                                if($stmt){
                                    echo '<div class="com alert alert-success">Comment Added But Still Under Uproval</div>';
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
                }else{
                    echo '<a href="login.php">Login</a> Or <a href="login.php">Register</a>  To Add Comment';
                }
            ?>
            <hr class="cutom-hr">
            <?php
            $stmt = $con->prepare("SELECT 
                                            comments.* ,
                                            users.UserName 
                                        AS 
                                            user
                                        FROM 
                                            comments 
                                        INNER JOIN 
                                            users 
                                        ON 
                                            comments.UserID = users.UserID 
                                        WHERE ItemID=?
                                        AND Status=1
                                        ");
            $stmt->execute(array($item['ItemID']));
            $rows = $stmt->fetchAll();
            ?>
                <?php foreach($rows as $row){ ?>
                       <div class="comment-box">
                           <div class="row">
                               <div class="col-sm-2 text-center">
                                   <?php
                                   $stmt = $con->prepare("SELECT * FROM Users WHERE UserName=?");
                                   $stmt->execute(array($row['user']));
                                   $get_user = $stmt->fetch();
                                   if(empty( $get_user['Avatar'])){
                                       echo '<img src="avatar.png" alt="avatar" class="img-responsive img-thumbnail center-block img-circle"/>';
                                   }
                                   else{echo "<img src='admin/uploads/avatar/" . $get_user['Avatar']   . "' alt='avatar' class='img-responsive img-thumbnail center-block img-circle' />";}
                                   echo $row['user']  ?>
                               </div>
                               <div class="col-sm-9">
                                   <p class="lead"><?php echo $row['Comment'] ?></p>
                               </div>
                           </div>
                       </div>
                        <hr class="cutom-hr"/>
                <?php }?>
        </div>
    <?php
    }else{
        echo '<div class="container">';
        echo '<div class="alert alert-danger">There Is No Such ID Or This Item Is Upder Approval</div>';
        echo '</div>';
    }
    include $tpl . 'footer.php';
    ob_end_flush();
    ?>