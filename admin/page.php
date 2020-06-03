<?php 
	ob_start();
	session_start();//start session
	//check if session is set
	if(isset($_SESSION['username'])){
		$pagetitle = '';//page title
		include 'init.php';
				
		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if($do == 'Manage'){
			echo 'manage page';
		}elseif($do == 'Add'){
			echo 'Add page';
		}elseif($do == 'Insert'){
			echo 'insert page';
		}elseif($do == 'Edit'){
			echo 'Edit page';
		}elseif($do == 'Update'){
			echo 'update page';
		}elseif($do == 'Delete'){
			echo 'Delete page';
		}

		include $tpl . 'footer.php';
	}else{
		header('Location:index.php');//redirect to login page
		exit();
	}
	ob_end_flush();
	?>