<?php
ob_start();
session_start();
$pagetitle = 'Profile Edit';
include 'init.php';
if(isset($_SESSION['userprofile'])){
    $profile = isset($_GET['profile']) ? $_GET['profile'] : header("Location:profile.php");
    if($profile=='Edit'){
        $stmt = $con->prepare("SELECT * FROM Users WHERE UserName=? LIMIT 1");
        $stmt->execute(array($_SESSION['userprofile']));
        $fetchuser = $stmt->fetch();
        $count = $stmt->rowCount();
        if($count==1){
            ?>
            <h1 class="text-center">Edit Profile</h1>
			<div class="container">
				<form class="form-horizontal profile-info" action="?profile=Update" method="post" enctype="multipart/form-data">
					<input type="hidden" name="user" value="<?php echo $_SESSION['userprofile'];?>"/>
					<!-- start user name -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">User Name</label>
						<div class="col-sm-10 col-md-6">
							<input
                                pattern=".{5,}"
                                title="User Name Must Be > 5 Character"
								type="text"
								name="username"
								class="form-control"
								placeholder="User Name To Login To Control Panel"
								value="<?php echo $fetchuser['UserName']?>"
								required="required"
								autocomplete="off"/>
						</div>
					</div>
					<!-- end user name -->
					<!-- start password -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Password</label>
						<div class="col-sm-10 col-md-6">
							<input type="hidden" name="oldpass" value="<?php echo $fetchuser['Password']?>"/>
							<input
								type="password"
								name="newpass"
								class="form-control"
								placeholder="Leave This Field Blank If You Do Not Want To Update Password"
								autocomplete="new-password"/>
						</div>
					</div>
					<!-- end password -->
					<!-- start full name -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Full Name</label>
						<div class="col-sm-10 col-md-6">
							<input
                                pattern=".{5,}"
                                title="Full Name Must Be > 5 Character"
								type="text"
								name="fullname"
								class="form-control"
								placeholder="Full Name Shown In Control Panel"
								value="<?php echo $fetchuser['FullName']?>"
								required="required"
								autocomplete="off"/>
						</div>
					</div>
					<!-- end full name -->
                    <!-- start profile image -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">User Image</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                    type="file"
                                    name="avatar"
                                    class="form-control"
                                    required="required"
                                    autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end profile image -->
					<!-- start email -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Email</label>
						<div class="col-sm-10 col-md-6">
							<input
								type="email"
								name="email"
								class="form-control"
								placeholder="Email To Contact"
								value="<?php echo $fetchuser['Email']?>"
								required="required"
								autocomplete="off"/>
						</div>
					</div>
					<!-- end email -->
					<!-- start submit -->
					<div class="form-group form-group-lg">
						<div class="col-sm-10 col-sm-offset-2">
							<input
								type="submit"
								value="Update Member"
								class="btn btn-primary btn-lg"/>
						</div>
					</div>
					<!-- end submit -->
				</form>
			</div>
    <?php
        }else{
            echo '<div class="alert alert-danger">Sorry There Is An Error</div>';
        }
    }elseif($profile=='Update'){
        //check that user coming to this page using post request
        if($_SERVER['REQUEST_METHOD']=='POST'){
            echo '<h1 class="text-center">Update Profile</h1>';
            echo '<div class="container">';
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
            $extention  = explode('.',$avatarext);
            $end        = end($extention);
            //receive data from form
            $user       = $_POST['user'];
            $username   = $_POST['username'];
            $pass = empty($_POST['newpass']) ? $_POST['oldpass'] : sha1($_POST['newpass']);
            $fullname   = $_POST['fullname'];
            $email      = $_POST['email'];
            //validate form
            $formerror = array();
            if(isset($username)){
                $filteruser = filter_var($username,FILTER_SANITIZE_STRING);
                if(strlen($filteruser)<4){
                    $formerrors[] = 'User Name Can Not Be Less Than 4 Character';
                }
            }
            if(isset($fullname)){
                $filteruser = filter_var($fullname,FILTER_SANITIZE_STRING);
                if(strlen($filteruser)<4){
                    $formerrors[] = 'Full Name Can Not Be Less Than 4 Character';
                }
            }

            if(isset($email)){
                $filteemail = filter_var($email,FILTER_SANITIZE_EMAIL);
                if(filter_var($filteemail,FILTER_VALIDATE_EMAIL)!=true){
                    $formerrors[] = 'This Email Is Not Valid';
                }
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
            foreach($formerror as $error){
                $msg = '<div class="alert alert-danger">' . $error . '</div>';
                redirect($msg,'back');
            }
            if(empty($formerror)){
                $avatar = rand(0,10000000000) . '_' . $avatarname;
                move_uploaded_file($tmpname,"admin\uploads\avatar\\" . $avatar);
                $check = checkitem('UserName','Users',$username);
                if($check == 0){
                    $stmt = $con->prepare("UPDATE Users SET UserName=?,Password=?,FullName=?,Email=?,Avatar=? WHERE UserName=?");
                    $stmt->execute(array($username,$pass,$fullname,$email,$avatar,$user));
                    $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' User Update</div>';
                    redirect($msg,'back');
                }else{
                    $msg = '<div class="alert alert-danger">Sorry This User Name Is Exist</div>';
                    redirect($msg,'back');
                }
            }
            echo '</div>';
        }else{
            echo '<div class="container">';
            $msg = '<div class="alert alert-danger">You Can Not Browse This Page Directly</div>';
            redirect($msg,'back');
            echo '</div>';
        }
    }
}else{
    header('Location:login.php');
    exit();
}
include $tpl . 'footer.php';
ob_end_flush();
?>