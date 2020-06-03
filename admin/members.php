<?php 
	ob_start();
	session_start();//start session
	//check if session is set
	if(isset($_SESSION['username'])){
		$pagetitle = 'Members';//page title
		include 'init.php';
				
		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if($do == 'Manage'){//manage page 
			$query = '';
			if(isset($_GET['Page']) && $_GET['Page'] == 'Pending'){
				$query = 'AND RegStatus = 0';
			}else{
				$query = 'AND RegStatus = 1';
			}
			$stmt = $con->prepare("SELECT * FROM Users WHERE GroupID!=1 $query");
			$stmt->execute();
			$rows = $stmt->fetchAll();
			if(empty($rows)){
				echo '<div class="container">';
				echo '<div class="message alert alert-info">There Is No Members To Show</div>';
				echo '<a href="?do=Add" class="add btn btn-primary"><i class="fa fa-plus"></i> Add New Member</a>';
				echo '</div>';
			}else{
		?>
			<h1 class="text-center">Manage Members</h1>
			<div class="container">
				<div class="table-responsive main-table text-center manage-members">
					<table class="table table-bordered">
						<tr>
							<td>#ID</td>
							<td>Avatar</td>
							<td>User Name</td>
							<td>Full Name</td>
							<td>Email</td>
							<td>Registration Date</td>
							<td>Control</td>
						</tr>
						<?php
							foreach($rows as $row){
								echo "<tr>";
									echo "<td>" . $row['UserID']   . "</td>";
									echo "<td>";
									if(empty( $row['Avatar'])){
									    echo '<img src="../avatar.png" alt="avatar" class="img-thumbnail img-responsive"/>';
                                    }
									else{echo "<img src='uploads/avatar/" . $row['Avatar']   . "' alt='' class='img-thumbnail img-respnosive'/>";}
									echo "</td>";
									echo "<td>" . $row['UserName'] . "</td>";
									echo "<td>" . $row['FullName'] . "</td>";
									echo "<td>" . $row['Email']    . "</td>";
									echo "<td>" . $row['Date']     . "</td>";
									echo "<td>
											<a href='?do=Edit&userid=".$row['UserID']."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
											<a href='?do=Delete&userid=".$row['UserID']."' class='confirm btn btn-danger'><i class='fa fa-close'></i> Delete</a>";
											if($row['RegStatus'] == 0){
												echo '<a href="members.php?do=Activate&userid='.$row['UserID'].'" class="activate btn btn-info"><i class="fa fa-check"></i> Activate</a>';
											}
									echo "</td>";
								echo "</tr>";
							}
						?>
					</table>
				</div>
				<a href="?do=Add" class="add btn btn-primary"><i class="fa fa-plus"></i> Add New Member</a>
			</div>
		<?php
			}
		}elseif($do == 'Add'){//add page ?>
			<h1 class="text-center">Add New Member</h1>
			<div class="container">
				<form class="form-horizontal" action="?do=Insert" method="post" enctype="multipart/form-data">
					<!-- start user name -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">User Name</label>
						<div class="col-sm-10 col-md-6">
							<input
								type="text"
								name="username"
								class="form-control"
								placeholder="User Name To Login To Control Panel"
								required="required"
								autocomplete="off"/>
						</div>
					</div>
					<!-- end user name -->
					<!-- start Password -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Password</label>
						<div class="col-sm-10 col-md-6">
							<input
								type="password"
								name="password"
								class="password form-control"
								placeholder="Password Must Be Strong And Easy To Remember"
								required="required"
								autocomplete="new-password"/>
							<i class="show-pass fa fa-eye fa-2x"></i>
						</div>
					</div>
					<!-- end password -->
					<!-- start Full name -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Full Name</label>
						<div class="col-sm-10 col-md-6">
							<input
								type="text"
								name="fullname"
								class="form-control"
								placeholder="Full Name To Showin IN Control Panel"
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
                                    placeholder="Full Name To Showin IN Control Panel"
                                    required="required"
                                    autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end profile image -->
					<!-- start Email -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Email</label>
						<div class="col-sm-10 col-md-6">
							<input
								type="email"
								name="email"
								class="form-control"
								placeholder="Email To Contact"
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
								value="Add Member"
								class="btn btn-primary btn-lg"/>
						</div>
					</div>
					<!-- end submit -->
				</form>
			</div>
		<?php
		}elseif($do == 'Insert'){
			//check that user coming to this page using post request
			if($_SERVER['REQUEST_METHOD']=='POST'){
				echo '<h1 class="text-center">Insert Member</h1>';
				echo '<div class="container">';
				    //upload variable
                    $avatar     = $_FILES['avatar'];
                    $avatarname = $_FILES['avatar']['name'];
                    $avatarsize = $_FILES['avatar']['size'];
                    $tmpname    = $_FILES['avatar']['tmp_name'];
                    $avatartype = $_FILES['avatar']['type'];
                    //list of allowed filed to upload
                    $allowedexe  = array("jpg","jpeg","png","gif");
                    //get avatar extention
                    $avatarexe = strtolower(end(explode('.',$avatarname)));
					//receive data from form
					$username   = $_POST['username'];
					$password   = $_POST['password'];
					$hashedpass = sha1($password);
					$fullname   = $_POST['fullname'];
					$email      = $_POST['email'];
					//validate form
					$formerror = array();
					if(strlen($username)<4){
						$formerror[] = 'User Name Can Not Be Less Than 4 Character';
					}
					if(strlen($username)>20){
						$formerror[] = 'User Name Can Not Be Greater Than 20 Character';
					}
					if(empty($password)){
						$formerror[] = 'Password Can Not Be Empty';
					}
					if(empty($fullname)){
						$formerror[] = 'Full Name Can Not Be Empty';
					}
					if(empty($email)){
						$formerror[] = 'Email Can Not Be Empty';
					}
                    if(!empty($avatarname) && !in_array($avatarexe,$allowedexe)){
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
					    move_uploaded_file($tmpname,"uploads\avatar\\" . $avatar);
						$check = checkitem('UserName','Users',$username);
						if($check == 0){
						$stmt = $con->prepare("INSERT INTO 
												Users
													(UserName,Password,FullName,Email,RegStatus,Date,Avatar)
												VALUE
													(:zuser,:zpass,:zfull,:zemail,1,now(),:zavatar)");
						$stmt->execute(array(
							':zuser'   => $username,
							':zpass'   => $hashedpass,
							':zfull'   => $fullname,
							':zemail'  => $email,
                            ':zavatar' => $avatar,
						));
						$msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' User Inserted</div>';
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
		}elseif($do == 'Edit'){//edit page 

			//check that coming id is number and get it's integer value
			$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
			//check that this userid exist in database
			$stmt = $con->prepare("SELECT * FROM Users WHERE UserID=? LIMIT 1");
			$stmt->execute(array($userid));
			$row = $stmt->fetch();
			$count = $stmt->rowCount();
			//if count > 0 this mean that this id exist in database
			if($count>0){
		?>
			<h1 class="text-center">Edit Member</h1>
			<div class="container">
				<form class="form-horizontal" action="?do=Update" method="post">
					<input type="hidden" name="userid" value="<?php echo $userid?>"/>
					<!-- start user name -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">User Name</label>
						<div class="col-sm-10 col-md-6">
							<input
								type="text"
								name="username"
								class="form-control"
								placeholder="User Name To Login To Control Panel"
								value="<?php echo $row['UserName']?>"
								required="required"
								autocomplete="off"/>
						</div>
					</div>
					<!-- end user name -->
					<!-- start password -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Password</label>
						<div class="col-sm-10 col-md-6">
							<input type="hidden" name="oldpass" value="<?php echo $row['Password']?>"/>
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
								type="text"
								name="fullname"
								class="form-control"
								placeholder="Full Name Shown In Control Panel"
								value="<?php echo $row['FullName']?>"
								required="required"
								autocomplete="off"/>
						</div>
					</div>
					<!-- end full name -->
					<!-- start email -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Email</label>
						<div class="col-sm-10 col-md-6">
							<input
								type="email"
								name="email"
								class="form-control"
								placeholder="Email To Contact"
								value="<?php echo $row['Email']?>"
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
				echo '<div class="container">';
				$msg = '<div class="alert alert-danger">There Is No Such ID</div>';
				redirect($msg,'back');
				echo '</div>';
			}
		}elseif($do == 'Update'){
			//check that user coming to this page using post request
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				echo '<h1 class="text-center">Update Member</h1>';
				echo '<div class="container">';
				//receive data from form
				$user_id  = $_POST['userid'];
				$username = $_POST['username'];
				$fullname = $_POST['fullname'];
				$email    = $_POST['email'];
				//password trick
				$pass = empty($_POST['newpass']) ?  $_POST['oldpass'] : sha1($_POST['newpass']);
				//validate form
				$formerror = array();
				if(strlen($username)<4){
					$formerror[] = 'User Name Can Not Be Less Than 4 Character';
				}
				if(strlen($username)>20){
					$formerror[] = 'User Name Can Not Be Greater Than 20 Character';
				}
				if(empty($fullname)){
					$formerror[] = 'Full Name Can Not Be Empty';
				}
				if(empty($email)){
					$formerror[] = 'Email Can Not Be Empty';
				}
				foreach($formerror as $error){
					$msg = '<div class="alert alert-danger">' . $error . '</div>';
					redirect($msg,'back');
				}
				if(empty($formerror)){
					$stmt = $con->prepare("SELECT * FROM Users WHERE UserName=? AND UserID!=?");
					$stmt->execute(array($username,$user_id));
					$count = $stmt->rowCount();
					if($count==0){
					$stmt = $con->prepare("UPDATE Users SET UserName=?,Password=?,FullName=?,Email=? WHERE UserID=?");
					$stmt->execute(array($username,$pass,$fullname,$email,$user_id));
					$msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Row Updated</div>';
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
		}elseif($do == 'Delete'){
			echo '<h1 class="text-center">Delete Member</h1>';
			echo '<div class="container">';
				//check that coming id is number and get it's integer value
				$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;	
				//check that this id exist in database
				$check = checkitem('UserID','Users',$userid);
				//if count > 0 this mean that this id exis in database
				if($check > 0){
					$stmt = $con->prepare("DELETE FROM Users WHERE UserID=:zuser");
					$stmt->bindparam(':zuser',$userid);
					$stmt->execute();
					$msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Row Deleted</div>';
					redirect($msg,'back');
				}else{
					$msg = '<div class="alert alert-danger">There Is No Such ID</div>';
					redirect($msg,'back');
				}
			echo '</div>';
		}elseif($do == 'Activate'){
			echo '<h1 class="text-center">Activate Member</h1>';
			echo '<div class="container">';
			//check that coming id is number and get it's integer value
			$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
			//check that this id exist in database
			$check = checkitem('UserID','Users',$userid);
			if($check >0){
				$stmt = $con->prepare("UPDATE Users SET RegStatus=1 WHERE UserID=:zuser");
				$stmt->bindparam(':zuser',$userid);
				$stmt->execute();
				$msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Row Activated</div>';
				redirect($msg,'back');
			}else{
				$msg = '<div class="alert alert-danger">There Is No Such ID</div>';
				redirect($msg,'back');
			}
			echo '</div>';
		}

		include $tpl . 'footer.php';
	}else{
		header('Location:index.php');//redirect to login page
		exit();
	}
	ob_end_flush();
	?>