<?php 
	ob_start();
	session_start();
	$pagetitle = 'Login';
	if(isset($_SESSION['userprofile'])){
		header('Location:index.php');
	}
	include 'init.php';
	//check that user coming to this page using post request
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_POST['login'])){
			//receive data from form
			$user   = $_POST['username'];
			$pass   = $_POST['password'];
			$hashedpass = sha1($pass);
			//check that this username and password exist in database
			$stmt = $con->prepare("SELECT * 
									FROM 
										Users 
									WHERE 
										UserName=? 
									AND 
										Password=?");
			$stmt->execute(array($user,$hashedpass));
			$get = $stmt->fetch();
			$count = $stmt->rowCount();
			//if count > 0 this mean that this username and password exist in database
			if($count >0){
				$_SESSION['userprofile']   = $user;//register session with username
                $_SESSION['id']     = $get['UserID'];//register userid in session
				header('Location:index.php');//redirect to dashboard page
				exit();
			}
		}else{
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
			$username   = $_POST['username'];
			$fullname   = $_POST['fullname'];
			$password   = $_POST['password'];
			$password2  = $_POST['password2'];
			$email      = $_POST['email'];
			$formerrors = array();
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

			if(isset($password) && isset($password2)){
				if(empty($password)){
					$formerrors[] = 'Sorry Password Can Not Be Empty';
				}
				$pass1 = sha1($password);
				$pass2 = sha1($password2);
				if($pass1 !== $pass2){
					$formerrors[] = 'Sorry Password Must Be Matched';
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

			if(empty($formerrors)){
                        $avatar = rand(0,10000000000) . '_' . $avatarname;
                        move_uploaded_file($tmpname,"admin\uploads\avatar\\" . $avatar);
			            $check = checkitem('UserName','Users',$username);
						if($check == 0){
						$stmt = $con->prepare("INSERT INTO 
												Users
													(UserName,FullName,Password,Email,RegStatus,Date,Avatar)
												VALUES
													(:zuser,:zfull,:zpass,:zemail,0,now(),:zavatar)");
						$stmt->execute(array(
							':zuser'   => $username,
							':zfull'   => $fullname,
							':zpass'   => sha1($password),
							':zemail'  => $email,
                            'zavatar'  => $avatar,));
						$successmsg = 'Congrates You Are Now Registerd User';
						}else{
							$formerrors[] = 'Sorry This User Name Is Exist';
						}
					}
		}
	}

?>

<div class="container login-page">
	<h1 class="text-center"><span class="selected" data-class="login">Login</span> | <span data-class="signup">SignUp</span></h1>
	<form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
		<div class="input-container">
		<input
			type="text"
			name="username"

			class="form-control"
			placeholder="User Name"
			required="required"
			autocomplete="off"/>
		</div>
		<div class="input-container">
		<input
			type="password"
			name="password"
			class="form-control"
			placeholder="Password"
			required="required"
			autocomplete="new-password"/>
		</div>
		<input
			type="submit"
			value="Login"
			name="login"
			class="btn btn-primary btn-block"/>
	</form>

	<form class="signup" action="<?php echo $_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
		<div class="input-container">
		<input
			type="text"
			name="username"
			pattern=".{4,}"
			title="User Name Must Be More Than 4 Character"
			class="form-control"
			placeholder="User Name"
			required="required"
			autocomplete="off"/>
		</div>
        <div class="input-container">
            <input
                    pattern=".{4,}"
                    title="Full Name Must Be Large Than 4 Character"
                    type="text"
                    name="fullname"
                    class="form-control"
                    placeholder="Full Name To Showin IN Control Panel"
                    required="required"
                    autocomplete="off"/>
        </div>
		<div class="input-container">
		<input
			type="password"
			name="password"
			minlength="4"
			class="form-control"
			placeholder="Password"
			required="required"
			autocomplete="new-password"/>
		</div>
		<div class="input-container">
		<input
			type="password"
			name="password2"
			minlength="4"
			class="form-control"
			placeholder="Password"
			required="required"
			autocomplete="new-password"/>
		</div>
		<div class="input-container">
		<input
			type="email"
			name="email"
			class="form-control"
			placeholder="Email"
			required="required"
			autocomplete="off"/>
		</div>
        <div class="input-container">
            <input
                    type="file"
                    name="avatar"
                    id="upload"
                    class="form-control"
                    placeholder="Avatar"
                    required="required"
                    autocomplete="off"/>
        </div>
		<input
			type="submit"
			value="SignUp"
			name="signup"
			class="btn btn-primary btn-block"/>
	</form>
</div>
<div class="errors text-center">

	<?php
		if(!empty($formerrors)){
			foreach ($formerrors as $errors) {
				echo '<div class="message">' . $errors . '</div>';
			}
		}

		if(isset($successmsg)){
			echo '<div class="message success-msg">'. $successmsg .'</div>';
		}
	?>
</div>

<?php include $tpl . 'footer.php';
	ob_end_flush();
?>