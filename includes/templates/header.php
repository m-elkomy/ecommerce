<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title><?php gettitle()?></title>
		<link rel="stylesheet" href="<?php echo $css ?>bootstrap.min.css"/>
		<link rel="stylesheet" href="<?php echo $css ?>font-awesome.min.css"/>
		<link rel="stylesheet" href="<?php echo $css ?>jquery-ui.min.css"/>
		<link rel="stylesheet" href="<?php echo $css ?>jquery.selectBoxIt.css"/>
		<link rel="stylesheet" href="<?php echo $css ?>front.css"/>
	</head>
	<body>
	<div class="uppper-bar">
		<div class="container">
            <div class="row">
                <div class="col-md-6">
			<?php if(isset($_SESSION['userprofile'])){
                $getuser = $con->prepare("SELECT * FROM Users WHERE UserName=?");
                $getuser->execute(array($sessionuser));
                $info = $getuser->fetch();
                if(empty( $info['Avatar'])){
                    echo '<img src="avatar.png" alt="avatar" class="my-image img-circle img-thumbnail"/>';
                }
                else{echo "<img src='admin/uploads/avatar/" . $info['Avatar']   . "' alt='avatar' class='my-image img-circle img-thumbnail' />";}
			    ?>
                <div class="btn-group my-info">
                    <span class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <?php echo  $sessionuser ?>
                        <span class="caret"></span>
                    </span>
                        <ul class="dropdown-menu">
                            <li><a href="profile.php?>">My Profile</a></li>
                            <li><a href="newadd.php">New Item</a></li>
                            <li><a href="profile.php#adds">My Items</a></li>
                            <li><a href="mycar.php">My Car</a></li>
                            <li><a href="userlogout.php">Logout</a></li>
                        </ul>


                </div>

                <?php
				}else{
			?>
				<a href="login.php"><span>Login / SignUp</span></a>
			<?php } ?>
	        	</div>
                <div class="col-md-6 search">
                    <div class="input-group">
                      <span class="input-group-btn">
                          <button class="btn btn-default" type="button">Go!</button>
                      </span>
                      <input type="text" class="form-control" placeholder="Search for...">
                   </div><!-- /input-group -->
                </div>
            </div>
        </div>
	</div>
	<nav class="navbar navbar-inverse">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#myapp-nav" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php"><?php echo lang('HOME')?></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="myapp-nav">
      <ul class="front-nav nav navbar-nav navbar-right">
          <?php
          $getcats = getall("*","Categories","WHERE Parent=0","","CategoryID","ASC");
          foreach($getcats as $cat) {
              echo "<li>
                    <a href='categories.php?pageid=" . $cat['CategoryID'] . "' >"
                    . $cat['CategoryName'] . "<span class='caret'></span></a>";
                              $stmt = $con->prepare("SELECT * FROM Categories WHERE Parent=?");
                              $stmt->execute(array($cat['CategoryID']));
                              $categ = $stmt->fetchAll();
                              $count = $stmt->rowCount();
                              echo '<ul class="dropdown-menu">';
                              foreach($categ as $categy){
                                  if($count>0){

                                      echo "<li><a href='categories.php?pageid=".$categy['CategoryID']."'>";
                                      echo $categy['CategoryName'] ;
                                      echo "</a></li>";

                                  }
                              }
                           echo '</ul>';
              echo "</li>";
          }
          ?>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>



