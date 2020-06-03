<?php 
	ob_start();
	session_start();//start session
	//check if session is set
	if(isset($_SESSION['username'])){
		$pagetitle = 'Categories';//page title
		include 'init.php';
				
		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if($do == 'Manage'){//manage categories 
			$sort = 'ASC';
			$sortarray = array('ASC','DESC');
			if(isset($_GET['sort']) && in_array($_GET['sort'],$sortarray)){
				$sort = $_GET['sort'];
			}
			$stmt = $con->prepare("SELECT * FROM Categories WHERE Parent=0 ORDER BY Ordering $sort");
			$stmt->execute();
			$cats = $stmt->fetchAll();
			if(empty($cats)){
				echo '<div class="container">';
				echo '<div class="message alert alert-info">There Is No Category To Show</div>';
				echo '<a href="?do=Add" class="add btn btn-primary"><i class="fa fa-plus"></i> Add New Category</a>';
				echo '</div>';
			}else{
		?>
			<h1 class="text-center">Manage Categories</h1>
			<div class="container category">
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="fa fa-edit"></i> Manage Categroeies
						<div class="ordering pull-right">
							<i class="fa fa-sort"></i> Ordering : [
							<a class='<?php if($sort=='ASC'){echo 'active';} ?>' href='?do=Manage&sort=ASC'>ASC</a> | 
							<a class='<?php if($sort=='DESC'){echo 'active';}?>' href='?do=Manage&sort=DESC'>DESC</a> ]
							<i class="fa fa-eye"></i> View : [
							<span class="active" data-view="full">Full</span> | 
							<span data-view="classic">Classic</span> ]
						</div>
					</div>
					<div class="panel-body">
						<?php
							foreach($cats as $cat){
							    $categid = $cat['CategoryID'];
								echo '<div class="cats">';
								echo '<div class="hidden-buttons pull-right">';
									echo '<a href="Categories.php?do=Edit&catid='.$cat['CategoryID'].'" class="btn btn-xs btn-success"><i class="fa fa-edit"></i> Edit</a>';
									echo '<a href="Categories.php?do=Delete&catid='.$cat['CategoryID'].'" class="confirm btn btn-xs btn-danger"><i class="fa fa-close"></i> Delete</a>';
								echo '</div>';
								echo '<h3>'   . $cat['CategoryName'] . '</h3>';
								echo '<div class="full-view">';
								if($cat['Description']==''){echo '<p>This Category Has No Description</p>';}
								else{echo '<p>' . $cat['Description'] .'</p>';}
								if($cat['Visibility']==1){echo '<span class="visibile"><i class="fa fa-eye"></i> Hidden</span>';}
								if($cat['Allow_Comment']==1){echo '<span class="Comments"><i class="fa fa-close"></i> Commenting Disabled</span>';}
								if($cat['Allow_Ads'] == 1){echo '<span class="ads"><i class="fa fa-close"></i> Ads Disabled</span>';}
								echo '</div>';
								//get chilld cats
                                $childcats = getall("*","categories","where Parent={$categid}","","CategoryID","ASC");
                                if(!empty($childcats)){
                                    echo "<h4 class='child-head'>Child Categories</h4>";
                                    echo "<ul class='list-unstyled childe-cats'>";
                                    foreach($childcats as $cats){
                                        echo "<li class='child-link'>
                                                    <a href='Categories.php?do=Edit&catid=".$cats['CategoryID']."'>" . $cats['CategoryName'] . "</a>
                                                    <a href='Categories.php?do=Delete&catid=".$cats['CategoryID']."' class='confirm show-delete'> Delete</a>
                                               </li>";
                                    }
                                    echo "</ul>";
                                }
                                echo '</div>';
                                echo '<hr/>';
							}

						?>
					</div>
				</div>
				<a href="?do=Add" class="add btn btn-primary"><i class="fa fa-plus"></i> Add New Category</a>	
			</div>
		<?php
			}
		}elseif($do == 'Add'){//add page ?>
			<h1 class="text-center">Add New Category</h1>
			<div class="container">
				<form class="form-horizontal" action="?do=Insert" method="post">
					<!-- start name -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Name</label>
						<div class="col-sm-10 col-md-6">
							<input
                                pattern=".{5,}"
                                title="Cat Name Must Be > 5 Chars"
								type="text"
								name="name"
								class="form-control"
								placeholder="Name Of The Category"
								autocomplete="off"
								required="required"/>
						</div>
					</div>
					<!-- end name -->
					<!-- start descritpion -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Description</label>
						<div class="col-sm-10 col-md-6">
							<input
								type="text"
								name="description"
								class="form-control"
								placeholder="Describe The Category"/>
						</div>
					</div>
					<!-- end description -->
					<!-- start ordering -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Ordering</label>
						<div class="col-sm-10 col-md-6">
							<input
								type="number"
                                min="0"
								name="ordering"
								class="form-control"
								placeholder="Number To Order The Category Must Be Large Than 0"/>
						</div>
					</div>
					<!-- end ordering -->
                    <!-- start parent category -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Parent</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="parent">
                                <option value="0">None</option>
                                <?php
                                    $allcats = getall("*","Categories","WHERE Parent = 0","","CategoryID","ASC");
                                    foreach($allcats as $cat){
                                        echo "<option value='".$cat['CategoryID']."'>" . $cat['CategoryName'] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- end parent category -->
					<!-- start visibility -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Visibility</label>
						<div class="col-sm-10 col-md-6">
							<div>
								<input id="vis-yes" type="radio" name="visibility" value="0" checked/>
								<label for="vis-yes">Yes</label>
							</div>
							<div>
								<input id="vis-no" type="radio" name="visibility" value="1"/>
								<label for="vis-no">No</label>
							</div>
						</div>
					</div>
					<!-- end visibility -->
					<!-- start Allow Comment -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Allow Commenting</label>
						<div class="col-sm-10 col-md-6">
							<div>
								<input id="com-yes" type="radio" name="commenting" value="0" checked/>
								<label for="com-yes">Yes</label>
							</div>
							<div>
								<input id="com-no" type="radio" name="commenting" value="1"/>
								<label for="com-no">No</label>
							</div>
						</div>
					</div>
					<!-- end allow Commenting -->
					<!-- start Allow Ads -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Allow Ads</label>
						<div class="col-sm-10 col-md-6">
							<div>
								<input id="ads-yes" type="radio" name="ads" value="0" checked/>
								<label for="ads-yes">Yes</label>
							</div>
							<div>
								<input id="ads-no" type="radio" name="ads" value="1"/>
								<label for="ads-no">No</label>
							</div>
						</div>
					</div>
					<!-- end allow Ads -->
					<!-- start submit -->
					<div class="form-group form-group-lg">
						<div class="col-sm-10 col-sm-offset-2">
							<input
								type="submit"
								value="Add Category"
								class="btn btn-primary btn-lg"/>
						</div>
					</div>
					<!-- end submit -->
				</form>
			</div>
		<?php
		}elseif($do == 'Insert'){
			//check that user coming to this page using post requres
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				echo '<h1 class="text-center">Insert Category</h1>';
				echo '<div class="container">';
				//receive data from form
				$name        = $_POST['name'];
				$description = $_POST['description'];
				$ordering    = $_POST['ordering'];
				$parent      = $_POST['parent'];
				$visibility  = $_POST['visibility'];
				$commenting  = $_POST['commenting'];
				$ads         = $_POST['ads'];
				if(!empty($name) && $ordering>0){
					$check = checkitem('CategoryName','Categories',$name);
					if($check == 0){
					$stmt = $con->prepare("INSERT INTO 
											Categories
												(CategoryName,Description,Parent,Ordering,Visibility,Allow_Comment,Allow_Ads)
											VALUES
												(:zname,:zdesc,:zparent,:zord,:zvis,:zcom,:zads)");
					$stmt->execute(array(
						':zname'     => $name,
						':zdesc'     => $description,
                        ':zparent'   => $parent,
						':zord'      => $ordering,
						':zvis'      => $visibility,
						':zcom'      => $commenting,
						':zads'      => $ads));
					$msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Category Inserted</div>';
					redirect($msg,'back');
					}else{
						$msg = '<div class="alert alert-danger">This Categoy Name Is Exist </div>';
						redirect($msg,'back');
					}
				}else{
					$msg = '<div class="alert alert-danger">Name Of The Category Can Not Be Empty Or Ordering Should Be > 0</div>';
					redirect($msg,'back');
				}
				echo '</div>';
			}else{
				echo '<div class="container">';
				$msg = '<div class="alert alert-danger">You Can Not Browse This Page Directly</div>';
				redirect($msg,'back');
				echo '</div>';
			}
		}elseif($do == 'Edit'){//edit page 
			//check that comig id is number and get it's integer value
			$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
			//check that this id exist in database
			$stmt = $con->prepare("SELECT * FROM Categories WHERE CategoryID=?");
			$stmt->execute(array($catid));
			$cat = $stmt->fetch();
			$count = $stmt->rowCount();
			if($count > 0){
		?>
			<h1 class="text-center">Edit Category</h1>
			<div class="container">
				<form class="form-horizontal" action="?do=Update" method="post">
					<input type="hidden" name="catid" value="<?php echo $catid;?>"/>
					<!-- start name -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Name</label>
						<div class="col-sm-10 col-md-6">
							<input
                                pattern=".{5,}"
                                title="Cat Name Must Be > 5 Chars"
								type="text"
								name="name"
								class="form-control"
								placeholder="Name Of The Category"
								autocomplete="off"
								value="<?php echo $cat['CategoryName'] ?>"
								required="required"/>
						</div>
					</div>
					<!-- end name -->
					<!-- start descritpion -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Description</label>
						<div class="col-sm-10 col-md-6">
							<input
								type="text"
								name="description"
								class="form-control"
								placeholder="Describe The Category"
								value="<?php echo $cat['Description'] ?>"/>
						</div>
					</div>
					<!-- end description -->
					<!-- start ordering -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Ordering</label>
						<div class="col-sm-10 col-md-6">
							<input
								type="number"
                                min="0"
								name="ordering"
								class="form-control"
								placeholder="Number To Order The Category Must Be > 0"
								value="<?php echo $cat['Ordering'] ?>"/>
						</div>
					</div>
					<!-- end ordering -->
                    <!-- start parent category -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Parent</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="parent">
                                <option value="0">None</option>
                                <?php
                                $allcats = getall("*","Categories","WHERE Parent = 0","","CategoryID","ASC");
                                foreach($allcats as $c){
                                    echo "<option value='".$c['CategoryID']."'";
                                    if($cat['Parent']==$c['CategoryID']){echo 'selected';}
                                    echo ">" . $c['CategoryName'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- end parent category -->
					<!-- start visibility -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Visibility</label>
						<div class="col-sm-10 col-md-6">
							<div>
								<input id="vis-yes" type="radio" name="visibility" value="0" 
								<?php if($cat['Visibility'] ==0){echo 'checked';}?> />
								<label for="vis-yes">Yes</label>
							</div>
							<div>
								<input id="vis-no" type="radio" name="visibility" value="1"
								<?php if($cat['Visibility'] ==1){echo 'checked';}?>/>
								<label for="vis-no">No</label>
							</div>
						</div>
					</div>
					<!-- end visibility -->
					<!-- start Allow Comment -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Allow Commenting</label>
						<div class="col-sm-10 col-md-6">
							<div>
								<input id="com-yes" type="radio" name="commenting" value="0" 
								<?php if($cat['Allow_Comment'] ==0){echo 'checked';}?>/>
								<label for="com-yes">Yes</label>
							</div>
							<div>
								<input id="com-no" type="radio" name="commenting" value="1"
								<?php if($cat['Allow_Comment'] ==1){echo 'checked';}?>/>
								<label for="com-no">No</label>
							</div>
						</div>
					</div>
					<!-- end allow Commenting -->
					<!-- start Allow Ads -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Allow Ads</label>
						<div class="col-sm-10 col-md-6">
							<div>
								<input id="ads-yes" type="radio" name="ads" value="0" 
								<?php if($cat['Allow_Ads'] ==0){echo 'checked';}?>/>
								<label for="ads-yes">Yes</label>
							</div>
							<div>
								<input id="ads-no" type="radio" name="ads" value="1"
								<?php if($cat['Allow_Ads'] ==1){echo 'checked';}?>/>
								<label for="ads-no">No</label>
							</div>
						</div>
					</div>
					<!-- end allow Ads -->
					<!-- start submit -->
					<div class="form-group form-group-lg">
						<div class="col-sm-10 col-sm-offset-2">
							<input
								type="submit"
								value="Update Category"
								class="btn btn-primary btn-lg"/>
						</div>
					</div>
					<!-- end submit -->
				</form>
			</div>
		<?php
			}else{
				$msg = '<div class="alert alert-danger">There Is No Such ID</div>';
				redirect($msg,'back');
			}
		}elseif($do == 'Update'){
			//check that user coming to this page using post requres
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				echo '<h1 class="text-center">Update Category</h1>';
				echo '<div class="container">';
				//receive data from form
				$cat_id      = $_POST['catid'];
				$name        = $_POST['name'];
				$description = $_POST['description'];
				$ordering    = $_POST['ordering'];
				$visibility  = $_POST['visibility'];
				$commenting  = $_POST['commenting'];
				$ads         = $_POST['ads'];
				$parent      = $_POST['parent'];
				if(!empty($name) && $ordering>0){
				    $stmt = $con->prepare("SELECT * FROM Categories WHERE CatName=? AND CatID!=?");
				    $stmt->execute(array($name,$cat_id));
				    $count = $stmt->rowCount();
				    if($count==0){
                        $stmt = $con->prepare("UPDATE Categories SET CategoryName=?,Description=?,Ordering=?,Parent=?,Visibility=?,Allow_Comment=?,Allow_Ads=? WHERE CategoryID=?");
                        $stmt->execute(array($name,$description,$ordering,$parent,$visibility,$commenting,$ads,$cat_id));
                        $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Category Updated</div>';
                        redirect($msg,'back');
                    }else{
                        $msg = '<div class="alert alert-danger">This Categoy Name Is Exist </div>';
                        redirect($msg,'back');
                    }
				}else{
					$msg = '<div class="alert alert-danger">Name Of The Category Can Not Be Empty Or Ordering Must Be > 0</div>';
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
			echo '<h1 class="text-center">Delete Category</h1>';
			echo '<div class="container">';
				//check that coming id is number and get it's integer value
				$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;	
				//check that this id exist in database
				$check = checkitem('CategoryID','Categories',$catid);
				//if count > 0 this mean that this id exis in database
				if($check > 0){
					$stmt = $con->prepare("DELETE FROM Categories WHERE CategoryID=:zcat");
					$stmt->bindparam(':zcat',$catid);
					$stmt->execute();
					$msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Category Deleted</div>';
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