<?php 
	ob_start();
	session_start();//start session
	//check if session is set
	if(isset($_SESSION['username'])){
		$pagetitle = 'Items';//page title
		include 'init.php';
				
		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if($do == 'Manage'){//manage page 
			$stmt = $con->prepare("SELECT 
										items.* ,
										users.UserName 
									AS 
										UserName,
										categories.CategoryName 
									AS 
										CatName 
									FROM 
										items 
									INNER JOIN 
										users 
									ON 
										items.UserID = users.UserID 
									INNER JOIN 
										categories 
									ON 
										items.CatID = categories.CategoryID
										ORDER BY ItemID DESC");
			$stmt->execute();
			$items = $stmt->fetchAll();
			if(empty($items)){
				echo '<div class="container">';
				echo '<div class="message alert alert-info">There Is No Items To Show</div>';
				echo '<a href="?do=Add" class="add btn btn-primary"><i class="fa fa-plus"></i> Add New Item</a>';
				echo '</div>';
			}else{
		?>
			<h1 class="text-center">Manag Items</h1>
			<div class="container">
				<div class="table-responsive main-table text-center">
					<table class="table table-bordered">
						<tr>
							<td>#ID</td>
							<td>Item Name</td>
							<td>Description</td>
							<td>Price</td>
							<td>Country</td>
							<td>Adding Date</td>
							<td>Category</td>
							<td>Member</td>
							<td>Control</td>
						</tr>
						<?php
							foreach($items as $item){
								echo "<tr>"; 
									echo "<td>" . $item['ItemID'] . "</td>";
									echo "<td>" . $item['ItemName'] . "</td>";
									echo "<td><textarea class='form-control'>" . $item['Description'] . "</textarea></td>";
									echo "<td>" . $item['Price'] . "</td>";
									echo "<td>" . $item['Country'] . "</td>";
									echo "<td>" . $item['Adding_Date'] . "</td>";
									echo "<td>" . $item['CatName'] . "</td>";
									echo "<td>" . $item['UserName'] . "</td>";
									echo "<td>
										<a href='?do=Edit&itemid=".$item['ItemID']."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
										<a href='?do=Delete&itemid=".$item['ItemID']."' class='confirm btn btn-danger'><i class='fa fa-close'></i> Delete</a>";
										if($item['Approve'] == 0){
											echo '<a href="?do=Approve&itemid='.$item['ItemID'].'" class="activate btn btn-info"><i class="fa fa-check"></i> Approve<a>';
										}
									echo "</td>";
								echo "</tr>";
							}
						?>
					</table>
				</div>
				<a href="?do=Add" class="add btn btn-primary"><i class="fa fa-plus"></i> Add New Item</a>
			</div>
		<?php
			}
		}elseif($do == 'Add'){//add page ?>
			<h1 class="text-center">Add New Item</h1>
			<div class="container">
				<form class="form-horizontal" action="?do=Insert" method="post">
					<!-- start name -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Name</label>
						<div class="col-sm-10 col-md-6">
							<input
								type="text"
								name="itemname"
                                pattern=".{5,}"
                                title="Item Name Should Be > 5 Chars"
								class="form-control"
								placeholder="Name Of The Item"
								required="required"
								autocomplete="off"/>
						</div>
					</div>
					<!-- end name -->
					<!-- start description -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Description</label>
						<div class="col-sm-10 col-md-6">
							<input
								type="text"
								name="description"
								class="form-control"
								placeholder="Describe The Item"
								required="required"
								autocomplete="off"/>
						</div>
					</div>
					<!-- end description -->
					<!-- start prcie -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Price</label>
						<div class="col-sm-10 col-md-6">
							<input
								type="text"
								name="price"
								class="form-control"
								placeholder="Price Of The Item"
								required="required"
								autocomplete="off"/>
						</div>
					</div>
					<!-- end price -->
					<!-- start country -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Country</label>
						<div class="col-sm-10 col-md-6">
							<input
								type="text"
                                name="country"
								pattern=".{3,}"
                                title="Country Name Should Be > 3 Chars"
                                class="form-control"
								placeholder="Country Of Made"
								required="required"
								autocomplete="off"/>
						</div>
					</div>
					<!-- end country -->
					<!-- start Status -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Status</label>
						<div class="col-sm-10 col-md-6">
							<select name="status">
								<option value="0">....</option>
								<option value="1">New</option>
								<option value="2">Like New</option>
								<option value="3">Used</option>
								<option value="4">Old</option>
							</select>
						</div>
					</div>
					<!-- end Status -->
					<!-- start member -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Member</label>
						<div class="col-sm-10 col-md-6">
							<select name="member">
								<option value="0">....</option>
								<?php
                                    $allmember = getall("*","Users","","","UserID");
									foreach($allmember as $user){
										echo '<option value="'.$user['UserID'].'">' . $user['UserName'] . '</option>';
									}
								?>
							</select>
						</div>
					</div>
					<!-- end member -->
					<!-- start Category -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Category</label>
						<div class="col-sm-10 col-md-6">
							<select name="category">
								<option value="0">....</option>
								<?php
                                    $allcats = getall("*","Categories","WHERE Parent=0","","CategoryID");
									foreach($allcats as $cat){
										echo '<option value="'.$cat['CategoryID'].'">' . $cat['CategoryName'] . '</option>';
                                        $childcats = getall("*","Categories","WHERE Parent={$cat['CategoryID']}","","CategoryID");
									    foreach($childcats as $child){
                                            echo '<option value="'.$child['CategoryID'].'"> ---- ' . $child['CategoryName'] . '</option>';
                                        }
									}
								?>
							</select>
						</div>
					</div>
					<!-- end category -->
                    <!-- start tags -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Tags</label>
                        <div class="col-sm-10 col-md-6">
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
						<div class="col-sm-10 col-sm-offset-2">
							<input
								type="submit"
								value="Add Item"
								class="btn btn-primary btn-lg"/>
						</div>
					</div>
					<!-- end tags -->
				</form>
			</div>
		<?php
		}elseif($do == 'Insert'){
			//check that user coming to this page using post requst
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				echo '<h1 class="text-center">Insert Item</h1>';
				echo '<div class="container">';
				//receive data from form
				$name        = $_POST['itemname'];
				$description = $_POST['description'];
				$price       = $_POST['price'];
				$country     = $_POST['country'];
				$status      = $_POST['status'];
				$member      = $_POST['member'];
				$category    = $_POST['category'];
				$tags        = $_POST['tags'];
				//validate form
				$formerror = array();
				if(empty($name)){
					$formerror[] = 'Name Can Not Be Empty';
				}
				if(empty($description)){
					$formerror[] = 'Description Can Not Be Empty';
				}
				if(empty($price)){
					$formerror[] = 'Price Can Not Be Empty';
				}
				if(empty($country)){
					$formerror[] = 'Country Can Not Be Empty';
				}
				if($status==0){
					$formerror[] = 'You Must Choose Status';
				}
				if($member==0){
					$formerror[] = 'You Must Choose Member';
				}
				if($category==0){
					$formerror[] = 'You Must Choose Category';
				}
				foreach($formerror as $error){
					$msg = '<div class="alert alert-danger">' . $error . '</div>';
					redirect($msg,'back');
				}
				if(empty($formerror)){
					$stmt = $con->prepare("INSERT INTO 
                                                              Items(ItemName,Description,Price,Adding_Date,Country,Status,CatID,UserID,Tags)
                                                      VALUE
                                                              (:zname,:zdesc,:zprice,now(),:zcountry,:zstatus,:zcat,:zuser,:ztags)");
					$stmt->execute(array(
						':zname'    => $name,
						':zdesc'    => $description,
						':zprice'   => $price,
						':zcountry' => $country,
						':zstatus'  => $status,
						':zcat'     => $category,
						':zuser'    => $member,
                        ':ztags'    => $tags));
					$msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Item Inserted</div>';
					redirect($msg,'back');
				}
				echo '</div>';
			}else{
				echo '<div class="container">';
				$msg = '<div class="alert alert-danger">You Can Not Browse This Page Directly</div>';
				redirect($msg,'back');
				echo '</div>';
			}
		}elseif($do == 'Edit'){
			//check that coming id is number and get it's integer value
			$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
			//check that this userid exist in database
			$stmt = $con->prepare("SELECT * FROM Items WHERE ItemID=? LIMIT 1");
			$stmt->execute(array($itemid));
			$row = $stmt->fetch();
			$count = $stmt->rowCount();
			//if count > 0 this mean that this id exist in database
			if($count>0){
		?>
			<h1 class="text-center">Edit Item</h1>
			<div class="container">
				<form class="form-horizontal" action="?do=Update" method="post">
					<input type="hidden" name="itemid" value="<?php echo $itemid?>"/>
					<!-- start name -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Name</label>
						<div class="col-sm-10 col-md-6">
							<input
								type="text"
								name="itemname"
                                pattern=".{5,}"
                                title="Item Name Should Be > 5 Chars"
								class="form-control"
								placeholder="Name Of The Item"
								required="required"
								value="<?php echo $row['ItemName']?>"
								autocomplete="off"/>
						</div>
					</div>
					<!-- end name -->
					<!-- start description -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Description</label>
						<div class="col-sm-10 col-md-6">
							<input
								type="text"
								name="description"
								class="form-control"
								placeholder="Describe The Item"
								required="required"
								value="<?php echo $row['Description']?>"
								autocomplete="off"/>
						</div>
					</div>
					<!-- end description -->
					<!-- start prcie -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Price</label>
						<div class="col-sm-10 col-md-6">
							<input
								type="text"
								name="price"
								class="form-control"
								placeholder="Price Of The Item"
								required="required"
								value="<?php echo $row['Price']?>"
								autocomplete="off"/>
						</div>
					</div>
					<!-- end price -->
					<!-- start country -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Country</label>
						<div class="col-sm-10 col-md-6">
							<input
								type="text"
								name="country"
                                pattern=".{3,}"
                                title="Country Name Should Be > 3 Chars"
								class="form-control"
								placeholder="Country Of Made"
								required="required"
								value="<?php echo $row['Country']?>"
								autocomplete="off"/>
						</div>
					</div>
					<!-- end country -->
					<!-- start Status -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Status</label>
						<div class="col-sm-10 col-md-6">
							<select name="status">
								<option value="0">....</option>
								<option value="1" <?php if($row['Status'] == 1){echo 'selected';} ?>>New</option>
								<option value="2" <?php if($row['Status'] == 2){echo 'selected';} ?>>Like New</option>
								<option value="3" <?php if($row['Status'] == 3){echo 'selected';} ?>>Used</option>
								<option value="4" <?php if($row['Status'] == 4){echo 'selected';} ?>>Old</option>
							</select>
						</div>
					</div>
					<!-- end Status -->
					<!-- start member -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Member</label>
						<div class="col-sm-10 col-md-6">
							<select name="member">
								<option value="0">....</option>
								<?php
									$stmt = $con->prepare("SELECT * FROM Users");
									$stmt->execute();
									$users = $stmt->fetchAll();
									foreach($users as $user){
										echo '<option value="'.$user['UserID'].'"';
										if($row['UserID'] == $user['UserID']){echo 'selected';}
										echo '>' . $user['UserName'] . '</option>';
									}
								?>
							</select>
						</div>
					</div>
					<!-- end member -->
					<!-- start Category -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Category</label>
						<div class="col-sm-10 col-md-6">
							<select name="category">
								<option value="0">....</option>
								<?php
									$stmt = $con->prepare("SELECT * FROM Categories");
									$stmt->execute();
									$cats = $stmt->fetchAll();
									foreach($cats as $cat){
										echo '<option value="'.$cat['CategoryID'].'"';
										if($row['CatID'] == $cat['CategoryID']){echo 'selected';}
										echo '>' . $cat['CategoryName'] . '</option>';
									}
								?>
							</select>
						</div>
					</div>
					<!-- end category -->
                    <!-- start tags -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Tags</label>
                        <div class="col-sm-10 col-md-6">
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
						<div class="col-sm-10 col-sm-offset-2">
							<input
								type="submit"
								value="Update Item"
								class="btn btn-primary btn-lg"/>
						</div>
					</div>
					<!-- end submit -->
				</form>
			<?php
                $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
			//check that this id exist in database
			$stmt = $con->prepare("SELECT
										items.*,
										comments.*,
										users.UserName AS user
									FROM
										items
									INNER JOIN
										comments
									ON
										items.ItemID = comments.ItemID
									INNER JOIN
										users
									ON
										items.UserID = users.UserID
									WHERE items.ItemID=?");
			$stmt->execute(array($itemid));
			$rows = $stmt->fetchAll();
			if(empty($rows)){
				echo '<div class="container">';
				echo '<div class="message alert alert-info">There Is No Comments To Show</div>';
				echo '</div>';
			}else{
		?>
			<h1 class="text-center">Manage Comments</h1>
			<div class="container">
				<div class="table-responsive main-table text-center">
					<table class="table table-bordered">
						<tr>
							<td>#ID</td>
							<td>Item</td>
							<td>Comment</td>
							<td>User</td>
							<td>Adding Date</td>
							<td>Control</td>
						</tr>
						<?php
							foreach($rows as $row){
								echo "<tr>";
									echo "<td>" . $row['ItemID'] . "</td>";
									echo "<td>" . $row['ItemName']      . "</td>";
									echo "<td>" . $row['Comment']   . "</td>";
									echo "<td>" . $row['user']      . "</td>";
									echo "<td>" . $row['Date']      . "</td>";
									echo "<td>
											<a href='comments.php?do=Edit&commentid=".$row['CommentID']."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
											<a href='comments.php?do=Delete&commentid=".$row['CommentID']."' class='confirm btn btn-danger'><i class='fa fa-close'></i> Delete</a>";
											if($row['Status'] == 0){
											echo '<a href="comments.php?do=Approve&commentid='.$row['CommentID'].'" class="activate btn btn-info"><i class="fa fa-check"></i> Approve<a>';
										}
									echo "</td>";
								echo "</tr>";
							}
						?>
					</table>
				</div>
			</div>
			<?php } ?>
			</div>

		<?php
			}else{
				echo '<div class="container">';
				$msg = '<div class="alert alert-danger">There Is No Such ID</div>';
				redirect($msg,'back');
				echo '</div>';
			}
		}elseif($do == 'Update'){
			//check that user coming to this page using post requst
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				echo '<h1 class="text-center">Update Item</h1>';
				echo '<div class="container">';
				//receive data from form
				$item_id     = $_POST['itemid'];
				$name        = $_POST['itemname'];
				$description = $_POST['description'];
				$price       = $_POST['price'];
				$country     = $_POST['country'];
				$status      = $_POST['status'];
				$member      = $_POST['member'];
				$category    = $_POST['category'];
				$tags        = $_POST['tags'];
				//validate form
				$formerror = array();
				if(empty($name)){
					$formerror[] = 'Name Can Not Be Empty';
				}
				if(empty($description)){
					$formerror[] = 'Description Can Not Be Empty';
				}
				if(empty($price)){
					$formerror[] = 'Price Can Not Be Empty';
				}
				if(empty($country)){
					$formerror[] = 'Country Can Not Be Empty';
				}
				if($status==0){
					$formerror[] = 'You Must Choose Status';
				}
				if($member==0){
					$formerror[] = 'You Must Choose Member';
				}
				if($category==0){
					$formerror[] = 'You Must Choose Category';
				}
				foreach($formerror as $error){
					$msg = '<div class="alert alert-danger">' . $error . '</div>';
					redirect($msg,'back');
				}
				if(empty($formerror)){
					$stmt = $con->prepare("UPDATE 
												Items 
											SET 
												ItemName=?,Description=?,Price=?,Country=?,Status=?,UserID=?,CatID=?,Tags=? 
											WHERE 
												ItemID=?");
					$stmt->execute(array($name,$description,$price,$country,$status,$member,$category,$tags,$item_id));
					$msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Item Updated</div>';
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
			echo '</div>';
		}elseif($do == 'Approve'){
			echo '<h1 class="text-center">Approve Item</h1>';
			echo '<div class="container">';
			//check that coming id is number and get it's integer value
			$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
			//check that this id exist in database
			$check = checkitem('ItemID','Items',$itemid);
			if($check >0){
				$stmt = $con->prepare("UPDATE Items SET Approve=1 WHERE ItemID=:zitem");
				$stmt->bindparam(':zitem',$itemid);
				$stmt->execute();
				$msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' <Item></Item> Approved</div>';
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