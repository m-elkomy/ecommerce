<?php 
	session_start();//start session
	$nonavbar = '';//not add navbar to this page
	$pagetitle = 'Login';//page title
	//check if session is set
	if(isset($_SESSION['username'])){
		header('Location:dashboard.php');//redirect to dashboard page
	}
	include 'init.php';
	//check that user coming to this page using post request
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		//receive data from form
		$username   = $_POST['user'];
		$password   = $_POST['pass'];
		$hashedpass = sha1($password);
		//check that this username and password exist in database
		$stmt = $con->prepare("SELECT 
									UserID,UserName,Password 
								FROM 
									Users 
								WHERE 
									UserName=? 
								AND 
									Password=? 
								AND 
									GroupID=1 
								LIMIT 1");
		$stmt->execute(array($username,$hashedpass));
		$row = $stmt->fetch();
		$count = $stmt->rowCount();
		//if count > 0 this mean that this username and password exist in database
		if($count >0){
			$_SESSION['username'] = $username;//register session with username
			$_SESSION['userid']   = $row['UserID'];//register session with userid
			header('Location:dashboard.php');//redirect to dashboard page
			exit();
		}
	}
?>

<form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
	<h4 class="text-center">Admin Login</h4>
	<input type="text" name="user" class="form-control" placeholder="User Name" autocomplete="off"/>
	<input type="password" name="pass" class="form-control" placeholder="Password" autocomplete="new-password"/>
	<input type="submit" value="Login" class="btn btn-primary btn-block"/>
</form>
<?php include $tpl . 'footer.php';?>