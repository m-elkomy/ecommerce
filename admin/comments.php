<?php 
	ob_start();
	session_start();//start session
	//check if session is set
	if(isset($_SESSION['username'])){
		$pagetitle = 'Comments';//page title
		include 'init.php';
				
		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if($do == 'Manage'){
			$stmt = $con->prepare("SELECT 
										comments.* ,
										users.UserName 
									AS 
										user,
										items.ItemName 
									AS 
										item 
									FROM 
										comments 
									INNER JOIN 
										users 
									ON 
										comments.UserID = users.UserID 
									INNER JOIN 
										items 
									ON 
										comments.ItemID = items.ItemID");
			$stmt->execute();
			$rows = $stmt->fetchAll();
			if(empty($rows)){
				echo '<div class="container">';
				echo '<div class="message alert alert-info">There Is No Recored To Show</div>';
				echo '</div>';
			}else{
		?>
			<h1 class="text-center">Manage Comments</h1>
			<div class="container">
				<div class="table-responsive main-table text-center">
					<table class="table table-bordered">
						<tr>
							<td>#ID</td>
							<td>Comment</td>
							<td>Item</td>
							<td>User</td>
							<td>Adding Date</td>
							<td>Control</td>
						</tr>
						<?php
							foreach($rows as $row){
								echo "<tr>";
									echo "<td>" . $row['CommentID'] . "</td>";
									echo "<td><textarea class='form-control'>" . $row['Comment']   . "</textarea></td>";
									echo "<td>" . $row['item']      . "</td>";
									echo "<td>" . $row['user']      . "</td>";
									echo "<td>" . $row['Date']      . "</td>";
									echo "<td>
											<a href='?do=Edit&commentid=".$row['CommentID']."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
											<a href='?do=Delete&commentid=".$row['CommentID']."' class='confirm btn btn-danger'><i class='fa fa-close'></i> Delete</a>";
											if($row['Status'] == 0){
											echo '<a href="?do=Approve&commentid='.$row['CommentID'].'" class="activate btn btn-info"><i class="fa fa-check"></i> Approve<a>';
										}
									echo "</td>";
								echo "</tr>";
							}
						?>
					</table>
				</div>
			</div>
		<?php
			}
		}elseif($do == 'Edit'){//edit comment 
			//check that comig id is number and get it's integer value
			$commentid = isset($_GET['commentid']) && is_numeric($_GET['commentid']) ? intval($_GET['commentid']) : 0;
			$stmt = $con->prepare("SELECT * FROM Comments WHERE CommentID=?");
			$stmt->execute(array($commentid));
			$row = $stmt->fetch();
			$count = $stmt->rowCount();
			if($count>0){
		?>
			<h1 class="text-center">Edit Comment</h1>
			<div class="container">
				<form class="form-horizontal" action="?do=Update" method="post">
					<input type="hidden" name="commentid" value="<?php echo $commentid?>"/>
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Comment</label>
						<div class="col-sm-10 col-md-6">
							<textarea class="form-control" name="comment"><?php echo $row['Comment']?></textarea>
						</div>
					</div>
					<div class="form-group form-group-lg">
						<div class="col-sm-10 col-sm-offset-2">
							<input type="submit" value="Update Comment" class="btn btn-primary btn-lg"/>
						</div>
					</div>
				</form>
			</div>
		<?php
			}else{
				$msg = '<div class="alert alert-danger">There Is No Such ID</div>';
				redirect($msg,'back');
			}
		}elseif($do == 'Update'){
			//check that user coming to this page using post request
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				echo '<h1 class="text-center">Update Comment</h1>';
				echo '<div class="container">';
				//receive data from form
				$comment_id  = $_POST['commentid'];
				$comment     = $_POST['comment'];
				if(!empty($comment)){
					$stmt = $con->prepare("UPDATE Comments SET Comment=? WHERE CommentID=?");
					$stmt->execute(array($comment,$comment_id));
					$msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Row Updated</div>';
					redirect($msg,'back');
				}else{
					$msg = '<div class="alert alert-danger">Comment Can Not Be Empty</div>';
					redirect($msg,'back');
				}
				
				echo '</div>';
			}else{
				echo '<div class="container">';
				$msg = '<div class="alert alert-danger">You Can Not Browse This Page Directly</div>';
				redirect($msg,'back');
				echo '</div>';
			}
		}elseif($do == 'Delete'){
			echo '<h1 class="text-center">Delete Comment</h1>';
			echo '<div class="container">';
				//check that coming id is number and get it's integer value
				$commentid = isset($_GET['commentid']) && is_numeric($_GET['commentid']) ? intval($_GET['commentid']) : 0;	
				//check that this id exist in database
				$check = checkitem('CommentID','Comments',$commentid);
				//if count > 0 this mean that this id exis in database
				if($check > 0){
					$stmt = $con->prepare("DELETE FROM Comments WHERE CommentID=:zcomment");
					$stmt->bindparam(':zcomment',$commentid);
					$stmt->execute();
					$msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Row Deleted</div>';
					redirect($msg,'back');
				}else{
					$msg = '<div class="alert alert-danger">There Is No Such ID</div>';
					redirect($msg,'back');
				}
			echo '</div>';
		}elseif($do == 'Approve'){
			echo '<h1 class="text-center">Approve Comment</h1>';
			echo '<div class="container">';
			//check that coming id is number and get it's integer value
			$commentid = isset($_GET['commentid']) && is_numeric($_GET['commentid']) ? intval($_GET['commentid']) : 0;
			//check that this id exist in database
			$check = checkitem('CommentID','Comments',$commentid);
			if($check >0){
				$stmt = $con->prepare("UPDATE Comments SET Status=1 WHERE CommentID=:zcomment");
				$stmt->bindparam(':zcomment',$commentid);
				$stmt->execute();
				$msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Row Approved</div>';
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