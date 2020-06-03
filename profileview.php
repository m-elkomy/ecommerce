<?php
ob_start();
session_start();
$pagetitle = 'Profile View';
include 'init.php';
if(isset($_GET['userid'])){
    $userid = $_GET['userid'];
    $getuser = $con->prepare("SELECT * FROM Users WHERE UserID=?");
    $getuser->execute(array($userid));
    $info = $getuser->fetch();
    $count = $getuser->rowCount();
    if($count == 1){
    ?>
    <h1 class="text-center"><?php echo $info['UserName'] ?> Profile</h1>
    <div class="information block">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-6 ">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><?php echo $info['UserName'] ?> Information</div>
                        <div class="panel-body">
                            <ul class='list-unstyled'>
                                <li>
                                    <i class="fa fa-unlock-alt fa-fw"></i>
                                    <span>Name</span> : <?php echo $info['UserName'] ?>
                                </li>
                                <li>
                                    <i class="fa fa-envelope-o fa-fw"></i>
                                    <span>Email</span> : <?php echo $info['Email'] ?>
                                </li>
                                <li>
                                    <i class="fa fa-user fa-fw"></i>
                                    <span>Full Name</span> : <?php echo $info['FullName'] ?>
                                </li>
                                <li>
                                    <i class="fa fa-calendar fa-fw"></i>
                                    <span>Date</span> : <?php echo $info['Date'] ?>
                                </li>
                                <li>
                                    <i class="fa fa-tags fa-fw"></i>
                                    <span>Favourit Cat</span> :
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 ">
                    <div class="image">
                        <?php
                        if (empty($info['Avatar'])) {
                            echo '<img src="avatar.png" alt="avatar" class="img-responsive  img-thumbnail"/>';
                        } else {
                            echo "<img src='admin/uploads/avatar/" . $info['Avatar'] . "' alt='avatar' class='img-responsive img-thumbnail' />";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="adds" class="myads block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo $info['UserName'] ?> Items</div>
                <div class="panel-body">
                    <?php
                    $getitem = getall("*", "Items", "WHERE UserID={$info['UserID']}", "", "CatID");
                    if (!empty($getitem)) {
                        foreach ($getitem as $item) {
                            echo '<div class="col-md-3">';
                            echo '<div class="thumbnail item-box">';
                            if ($item['Approve'] == 0) {
                                echo '<span class="approval-status">Not Approved</span>';
                            }
                            echo '<span class="price">' . $item['Price'] . ' $</span>';
                            echo '<div class="image">';
                            if (empty($item['ItemAvatar'])) {
                                echo '<img src="avatar.png" alt="avatar" class="home-image img-responsive center-block img-thumbnail"/>';
                            } else {
                                echo "<img src='admin/uploads/items/" . $item['ItemAvatar'] . "' alt='avatar' class='home-image img-thumbnail' />";
                            }
                            echo '</div>';
                            echo '<div class="caption">';
                            echo '<h3><a href="items.php?itemid=' . $item['ItemID'] . '">' . $item['ItemName'] . '</a></h3>';
                            echo '<p>' . $item['Description'] . '</p>';
                            echo '<div class="date">' . $item['Adding_Date'] . '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="message alert alert-info">There Is No Recored To Show, </div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

        <div class="comments block">
            <div class="container">
                <div class="panel panel-primary">
                    <div class="panel-heading">Latest Comments</div>
                    <div class="panel-body">
                        <?php
                        $stmt = $con->prepare("SELECT 
                                                        comments.* ,
                                                        users.UserName,
                                                        Items.ItemName
                                                    FROM 
                                                        comments 
                                                    INNER JOIN 
                                                        users 
                                                    ON 
                                                        comments.UserID = users.UserID
                                                    INNER JOIN Items 
                                                    ON   comments.ItemID=Items.ItemID
                                                    WHERE comments.UserID=?");
                        $stmt->execute(array($userid));
                        $rows = $stmt->fetchAll();
                        if(!empty($rows)){
                            ?>
                            <?php foreach($rows as $row){ ?>
                                <div class="comment-box">
                                    <div class="row">
                                        <div class="col-sm-2 text-center">
                                            <?php
                                            $stmt = $con->prepare("SELECT * FROM Items WHERE ItemName=?");
                                            $stmt->execute(array($row['ItemName']));
                                            $get_item = $stmt->fetch();
                                            if(empty( $get_item['Avatar'])){
                                                echo '<img src="avatar.png" alt="avatar" class="img-responsive img-thumbnail center-block img-circle"/>';
                                            }
                                            else{echo "<img src='admin/uploads/avatar/" . $get_item['ItemAvatar']   . "' alt='avatar' class='img-responsive img-thumbnail center-block img-circle' />";}
                                            echo $row['ItemName']  ?>
                                        </div>
                                        <div class="col-sm-9">
                                            <p class="lead"><?php echo $row['Comment'];if($row['Status']==0){echo '<span class="not-approved">Not Approved Yet</span>';} ?></p>
                                        </div>
                                    </div>
                                </div>
                                <hr class="cutom-hr"/>
                            <?php }
                        }else{
                            echo '<div class="message alert alert-info">There Is No Recored To Show</div>';
                        }?>
                    </div>
                </div>
            </div>
        </div>
        </div>
    <?php
    }
    }else{
        header('Location:login.php');
        exit();
    }
include $tpl . 'footer.php';
ob_end_flush();
?>