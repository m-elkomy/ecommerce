<?php

/*
** get all function
*/
function getall($field,$table,$where=NULL,$and=NULL,$orderfield,$ordering='DESC'){
    global $con;
    $getall = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");
    $getall->execute();
    $all = $getall->fetchAll();

    return $all;
}



/*
** gettitle function
** check if pagetitle is set echi it
** else echo default
*/
function gettitle(){
	global $pagetitle;
	if(isset($pagetitle)){
		echo $pagetitle;
	}else{
		echo 'Default';
	}
}

/*
** redirect function
** take 3 pareameter
** msg = message to display
** url = link to go to
** sec = number of seconed before redirect
*/
function redirect($msg,$url = null,$sec=3){
    if($url === null){
        $url  = 'index.php';
        $link = 'Home Page';
    }else{
        if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !=''){
            $url  = $_SERVER['HTTP_REFERER'];
            $link = 'Preivous Page';
        }else{
            $url  = 'index.php';
            $link = 'Home Page';
        }
    }
    echo $msg;
    echo '<div class="alert alert-info">You Will Redirect To ' . $link . ' In ' . $sec . ' Seconed</div>';
    header("refresh:$sec;url=$url");
    exit();
}

/*
** function check user status
*/
function checkuserstatus($user){
	global $con;
	$stmt = $con->prepare("SELECT 
								UserName,RegStatus 
							FROM 
								Users 
							WHERE 
								UserName=? 
							AND 
								RegStatus=0");
	$stmt->execute(array($user));
	$status = $stmt->rowCount();
	return $status;

}

/*
** check item function
** take 3 pareameter
** item = item to check 
** table = table to check in
** value = value of item
*/
function checkitem($item,$table,$value){
	global $con;
	$statement = $con->prepare("SELECT $item FROM $table WHERE $item=?");
	$statement->execute(array($value));
	$count = $statement->rowCount();
	return $count;
}


/* we used instead of it get all
** get category function

function getcats(){
	global $con;
	$getcats = $con->prepare("SELECT * FROM categories ORDER BY CategoryID ASC");
	$getcats->execute();
	$cat = $getcats->fetchAll();
	return $cat;
}


 get item function

function getitems($where,$value,$approve=NULL){
    global $con;
    $sql = $approve==null ? 'AND Approve = 1' : $sql = NULL;
    $getitems = $con->prepare("SELECT * FROM Items WHERE $where=? $sql ORDER BY ItemID DESC");
    $getitems->execute(array($value));
    $item = $getitems->fetchAll();
    return $item;
}
*/

