<?php
ob_start();
session_start();
$pagetitle = 'Item Info';
include 'init.php';
if(isset($_SESSION['userprofile'])){
    $userid = $_SESSION['id'];
   $page = '';
   if(isset($_GET['page']) && $_GET['page']!=''){
       $page = $_GET['page'];
   }else{
       echo '<div class="container">';
       echo '<div class="alert alert-danger">Sorry Your Request Is Not Valid</div>';
       echo '</div>';
   }
   if($page == 'Edit'){
       $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
       $stmt = $con->prepare("SELECT * FROM Items WHERE ItemID=? LIMIT 1");
       $stmt->execute(array($itemid));
       $row = $stmt->fetch();
       $count = $stmt->rowCount();
       if($count == 1){
           ?>
           <h1 class="text-center">Edit Item</h1>
           <div class="create-add block">
               <div class="container">
                   <div class="panel panel-primary">
                       <div class="panel-heading">Edit Item</div>
                       <div class="panel-body">
                           <div class="row">
                               <div class="col-md-8">
                                   <form class="form-horizontal main-form" action="?page=Update"
                                         method="post" enctype="multipart/form-data">
                                       <input type="hidden" name="itemid" value="<?php echo $itemid?>"/>
                                       <!-- start name -->
                                       <div class="form-group form-group-lg">
                                           <label class="col-sm-3 control-label">Name</label>
                                           <div class="col-sm-10 col-md-9">
                                               <input
                                                   type="text"
                                                   name="itemname"
                                                   class="form-control live"
                                                   value="<?php echo $row['ItemName'] ?>"
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
                                                   value="<?php echo $row['Description']?>"
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
                                                   value="<?php echo $row['Price']?>"
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
                                                   value="<?php echo $row['Country']?>"
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
                                                   <option value="1" <?php if($row['Status'] == 1){echo 'selected';} ?> >New</option>
                                                   <option value="2" <?php if($row['Status'] == 2){echo 'selected';} ?>>Like New</option>
                                                   <option value="3" <?php if($row['Status'] == 3){echo 'selected';} ?>>Used</option>
                                                   <option value="4" <?php if($row['Status'] == 4){echo 'selected';} ?>>Old</option>
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
                                                       echo '<option value="' . $cat['CategoryID'] . '"';
                                                       if($row['CatID'] == $cat['CategoryID']){echo 'selected';}
                                                       echo '>' . $cat['CategoryName'] . '</option>';
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
                                                   value="<?php echo $row['Tags']?>"
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
                                                   value="Update Item"
                                                   class="btn btn-primary btn-lg"/>
                                           </div>
                                       </div>
                                       <!-- end country -->
                                   </form>
                               </div>
                               <div class="col-md-4">
                                   <div class="thumbnail item-box live-preview">
                                       <span class="price">$ <span class="live-price">0</span> </span>
                                       <?php
                                       if(empty( $row['ItemAvatar'])){
                                           echo '<img src="avatar.png" alt="avatar" class="img-thumbnail img-responsive"/>';
                                       }
                                       else{echo "<img src='admin/uploads/items/" . $row['ItemAvatar']   . "' alt='' class='img-thumbnail img-respnosive'/>";}
                                       ?>
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
           echo '<div class="alert alert-danger">Sorry This Is Not Valid Request</div>';
           echo '</div>';
       }
   }elseif($page == 'Update'){
       if ($_SERVER['REQUEST_METHOD'] == 'POST') {
           echo '<h1 class="text-center">Update Item</h1>';
           echo '<div class="container">';
           //receive dat from form
           $formerrors = array();
           $itemid     = $_POST['itemid'];
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
               $stmt = $con->prepare("UPDATE 
												Items 
											SET 
												ItemName=?,Description=?,Price=?,Country=?,Status=?,UserID=?,CatID=?,Tags=?,Approve=0,ItemAvatar=?
											WHERE 
												ItemID=?");
               $stmt->execute(array($name,$description,$price,$country,$status,$userid,$category,$tags,$avatar,$itemid));
               if ($stmt) {
                   $msg = '<div class="alert alert-success">Item Updated Successfuly But Wait Update Approval</div>';
                   redirect($msg,'back');
               }
           }
           echo '</div>';
       }else{
           echo '<div class="container">';
           $msg = '<div class="alert alert-danger">Sorry Your Request Is Not Valid</div>';
           redirect($msg,'back');
           echo '</div>';
       }


   }elseif($page=='Delete'){
       echo '<h1 class="text-center">Delete Item</h1>';
       echo '<div class="container">';
       //check that coming id is number and get it's integer value
       $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
       //check that this id exist in database
       $check = checkitem('ItemID','Items',$itemid);
       //if count > 0 this mean that this id exis in database
       if($check > 0){
           $stmt = $con->prepare("DELETE FROM Items WHERE ItemID=:zitem");
           $stmt->bindparam(':zitem',$itemid);
           $stmt->execute();
           $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Item Deleted</div>';
           redirect($msg,'back');
       }else{
           $msg = '<div class="alert alert-danger">There Is No Such ID</div>';
           redirect($msg,'back');
       }
   }
}else{
    header('Location:login.php');
    exit();
}
include $tpl . 'footer.php';
ob_end_flush();
?>