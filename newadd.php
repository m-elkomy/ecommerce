<?php
ob_start();
session_start();
$pagetitle = 'Create New Item';
include 'init.php';
if(isset($_SESSION['userprofile'])){
    $stmt = $con->prepare("SELECT * FROM Users WHERE RegStatus=1 AND UserName=?");
    $stmt->execute(array($_SESSION['userprofile']));
    $count = $stmt->rowCount();
    if($count>0) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //receive dat from form
            $formerrors       = array();
            //upload variable
            $avatar     = $_FILES['avatar'];
            $avatarname = $_FILES['avatar']['name'];
            $avatarsize = $_FILES['avatar']['size'];
            $tmpname    = $_FILES['avatar']['tmp_name'];
            $avatartype = $_FILES['avatar']['type'];
            //list of allowed filed to upload
            $allowedext = array("jpg","jpeg","png","gif");
            //get avatar extention
            $avatarext  = strtolower($avatarname);
            $extention  = explode('.','$avatarext');
            $end        = end($extention);
            $name = filter_var($_POST['itemname'], FILTER_SANITIZE_STRING);
            $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
            $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
            $country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
            $status = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
            $category = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
            $tags = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);
            if (strlen($name) < 4) {
                $formerrors[] = 'Name Can Not Be Less Than 4 Character';
            }
            if (strlen($description) < 10) {
                $formerrors[] = 'Item Description Must Be At Least 10 Character';
            }
            if (strlen($country) < 2) {
                $formerrors[] = 'Country Must Be Greater Than 2 Character';
            }
            if (empty($status)) {
                $formerrors[] = 'Item Status Must Be Not Empty';
            }
            if (empty($category)) {
                $formerrors[] = 'You Should Choose Category';
            }
            if (empty($price)) {
                $formerrors[] = 'Price Must Be Not Empty';
            }
            if(!empty($avatarname) && !in_array($end,$allowedext)){
                $formerror[] = 'This Extention Is Not Allowed';
            }
            if(empty($avatarname)){
                $formerror[] = 'Avatar Is Required';
            }
            if(empty($avatarsize)>4194304){
                $formerror[] = 'Avatar Can Not Be Large Than 4 MB';
            }
            if (empty($formerrors)) {
                $avatar = rand(0,10000000000) . '_' . $avatarname;
                move_uploaded_file($tmpname,"admin\uploads\items\\" . $avatar);
                $stmt = $con->prepare("INSERT INTO 
                                                      Items(ItemName,Description,Price,Adding_Date,Country,Status,CatID,UserID,ItemAvatar,Tags)
                                            VALUE
                                                (:zname,:zdesc,:zprice,now(),:zcountry,:zstatus,:zcat,:zuser,:zavatar,:ztags)");
                $stmt->execute(array(
                    ':zname'    => $name,
                    ':zdesc'    => $description,
                    ':zprice'   => $price,
                    ':zcountry' => $country,
                    ':zstatus'  => $status,
                    ':zcat'     => $category,
                    ':zuser'    => $_SESSION['id'],
                    ':zavatar'  => $avatar,
                    ':ztags'    => $tags));
                if ($stmt) {
                    $successmsg = 'Item Added Successfuly';
                }
            }
        }
        ?>
        <h1 class="text-center"><?php echo $pagetitle; ?></h1>
        <div class="create-add block">
            <div class="container">
                <div class="panel panel-primary">
                    <div class="panel-heading"><?php echo $pagetitle; ?></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-8">
                                <form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF'] ?>"
                                      method="post" enctype="multipart/form-data">
                                    <!-- start name -->
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Name</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input
                                                    type="text"
                                                    name="itemname"
                                                    class="form-control live"
                                                    placeholder="Name Of The Item"
                                                    required="required"
                                                    autocomplete="off"
                                                    data-class=".live-title"/>
                                        </div>
                                    </div>
                                    <!-- end name -->
                                    <!-- start description -->
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Description</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input
                                                    type="text"
                                                    name="description"
                                                    class="form-control live"
                                                    placeholder="Describe The Item"
                                                    required="required"
                                                    autocomplete="off"
                                                    data-class=".live-desc"/>
                                        </div>
                                    </div>
                                    <!-- end description -->
                                    <!-- start prcie -->
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Price</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input
                                                    type="text"
                                                    name="price"
                                                    class="form-control live"
                                                    placeholder="Price Of The Item"
                                                    required="required"
                                                    autocomplete="off"
                                                    data-class=".live-price"/>
                                        </div>
                                    </div>
                                    <!-- end price -->
                                    <!-- start country -->
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Country</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input
                                                    type="text"
                                                    name="country"
                                                    class="form-control"
                                                    placeholder="Country Of Made"
                                                    required="required"
                                                    autocomplete="off"/>
                                        </div>
                                    </div>
                                    <!-- end country -->
                                    <!-- start Status -->
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Status</label>
                                        <div class="col-sm-10 col-md-9">
                                            <select name="status" required="required">
                                                <option value="">....</option>
                                                <option value="1">New</option>
                                                <option value="2">Like New</option>
                                                <option value="3">Used</option>
                                                <option value="4">Old</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- end Status -->
                                    <!-- start Category -->
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Category</label>
                                        <div class="col-sm-10 col-md-9">
                                            <select name="category" required="required">
                                                <option value="">....</option>
                                                <?php
                                                $cats = getall('*', 'Categories', '', '', 'CategoryID');
                                                foreach ($cats as $cat) {
                                                    echo '<option value="' . $cat['CategoryID'] . '">' . $cat['CategoryName'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- end category -->
                                    <!-- start item image -->
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Item Image</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input
                                                    type="file"
                                                    name="avatar"
                                                    id="imgInp"
                                                    class="form-control"
                                                    required="required"
                                                    autocomplete="off"/>
                                        </div>
                                    </div>
                                    <!-- end item image -->
                                    <!-- start tags -->
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Tags</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input
                                                    type="text"
                                                    name="tags"
                                                    class="form-control"
                                                    placeholder="Separate Tags With Comma(,)"
                                                    autocomplete="off"/>
                                        </div>
                                    </div>
                                    <!-- end tags -->
                                    <!-- start submit -->
                                    <div class="form-group form-group-lg">
                                        <div class="col-sm-10 col-sm-offset-3">
                                            <input
                                                    type="submit"
                                                    value="Add Item"
                                                    class="btn btn-primary btn-lg"/>
                                        </div>
                                    </div>
                                    <!-- end country -->
                                </form>
                            </div>
                            <div class="col-md-4">
                                <div class="thumbnail item-box live-preview">
                                    <span class="price">$ <span class="live-price">0</span> </span>
                                    <img src="avatar.png" alt="avatar" id="live-avatar" class="img-responsive"/>
                                    <div class="caption">
                                        <h3 class="live-title">test</h3>
                                        <p class="live-desc">desc</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- start loop error -->
                        <?php
                        if (!empty($formerrors)) {
                            foreach ($formerrors as $error) {
                                echo '<div class="alert alert-danger">' . $error . '</div>';
                            }
                        }
                        if (isset($successmsg)) {
                            $msg = '<div class="alert alert-success">' . $successmsg . '</div>';
                            redirect($msg,'back');
                        }
                        ?>
                        <!-- end loop error -->
                    </div>
                </div>
            </div>
        </div>

        <?php
    }else{
        echo '<div class="container">';
        echo '<div class="alert alert-info">Sorry You Can Not make New Add Because Are Under Uproval </div>';
        echo '</div>';
    }
}else{
    header('Location:login.php');
    exit();
}
include $tpl . 'footer.php';
ob_end_flush();
?>