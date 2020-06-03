<?php 
	ob_start();
	session_start();//start session
	//check if session is set
	if(isset($_SESSION['username'])){
		$pagetitle = 'Dashboard';//page title
		include 'init.php';
		$numusers = 5;
		$latestuser = getlatest("*","Users","UserID",$numusers);
		$numitem = 5;
		$latestitem = getlatest("*","Items","ItemID",$numitem);
		?>

		<div class="container text-center main-states">
			<h1>Dashboard</h1>
			<div class="row">
				<div class="col-md-3">
					<div class="state st-member">
						<i class="fa fa-users"></i>
						<div class="info">
							Total Member
							<span><a href="members.php"><?php echo countitem('UserID','Users')?></a></span>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="state st-pending">
						<i class="fa fa-user-plus"></i>
						<div class="info">
							Pending Member
							<span><a href="members.php?do=Manage&Page=Pending"><?php echo checkitem('RegStatus','Users',0)?>	</a></span>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="state st-item">
						<i class="fa fa-tag"></i>
						<div class="info">
						Total Items
						<span><a href="items.php"><?php echo countitem('ItemID','Items')?></a></span>
					</div>
				</div>
				</div>
				<div class="col-md-3">
					<div class="state st-comment">
						<i class="fa fa-comments"></i>
						<div class="info">
						Total Comments
						<span><a href="Comments.php"><?php echo countitem('CommentID','Comments')?></a></span>
					</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container latest">
			<div class="row">
				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading"><i class="fa fa-users"></i> 
							Latest <?php echo $numusers ?> Registerd Users
							<span class="toggle-info pull-right"><i class="fa fa-minus"></i></span>
						</div>
						<div class="panel-body">
							<ul class="list-unstyled latest-users">
							<?php
								if(!empty($latestuser)){
								foreach($latestuser as $user){
									echo "<li>" . $user['UserName'] . "<a href='members.php?do=Edit&userid=".$user['UserID']."' class='pull-right btn btn-success'><i class='fa fa-edit'></i> Edit</a></li>";
								}
								}else{
									echo '<div class="message alert alert-info">There Is No Users To Show</div>';
									echo '<a href="member.php?do=Add" class="add btn btn-primary"><i class="fa fa-plus"></i> Add New Item</a>';
								}
							?>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading"><i class="fa fa-tag"></i> 
							Latest <?php echo $numitem?> Items
							<span class="toggle-info pull-right"><i class="fa fa-minus"></i></span>
						</div>
						<div class="panel-body">
							<ul class="list-unstyled latest-users">
							<?php
								if(!empty($latestitem)){
								foreach($latestitem as $item){
									echo "<li>" . $item['ItemName'] . "<a href='items.php?do=Edit&itemid=".$item['ItemID']."' class='pull-right btn btn-success'><i class='fa fa-edit'></i> Edit</a></li>";
								}
								}else{
									echo '<div class="message alert alert-info">There Is No Items To Show</div>';
									echo '<a href="Items.php?do=Add" class="add btn btn-primary"><i class="fa fa-plus"></i> Add New Item</a>';
								}
							?>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<!-- start comment -->
			<div class="row">
				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading"><i class="fa fa-comments"></i> 
							Latest <?php echo $numcomment = 5 ?> Comments
							<span class="toggle-info pull-right"><i class="fa fa-minus"></i></span>
						</div>
						<div class="panel-body">
							<ul class="list-unstyled latest-users">
							<?php

								$stmt = $con->prepare("SELECT 
															comments.*,
															users.UserName 
														FROM 
															comments 
														INNER JOIN 
															users 
														ON 
															comments.UserID=users.UserID LIMIT $numcomment");
								$stmt->execute();
								$comments = $stmt->fetchAll();
								if(!empty($comments)){
								foreach($comments as $comment){
									echo '<div class="comment-box">';
									echo '<span class="com-n">' . $comment['UserName'] . '</span>';
									echo '<p class="com-c">'    . $comment['Comment']  . '</p>';
									echo '</div>';
								}
								}else{
									echo '<div class="message alert alert-info">There Is No Comments To Show</div>';

								}
							?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php
		include $tpl . 'footer.php';
	}else{
		header('Location:index.php');//redirect to login page
		exit();
	}
	ob_end_flush();
	?>