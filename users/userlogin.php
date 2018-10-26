<?php
    header('Content-type:application/json;charset=utf-8');
	include_once '../database/database.php';
	include_once 'userManager.php';

	$uname="";
	$pword="";

	try {

	 	if(isset($_POST['UserName']) && isset($_POST['Password'])){
	 		$uname = $_POST['UserName'];
	 		$pword = $_POST['Password'];

	 		$userClass = new UserManager();
	 		$stmtLogin = $userClass->validateUser($uname,$pword);

	 		$isuser = $stmtLogin->rowcount();

	 	if($isuser>0){
	 		$userArray = array();
	 		while ($row = $stmtLogin->fetch(PDO::FETCH_NUM,PDO::FETCH_ORI_NEXT)) {
	 			$temp = array(
	 				"id"=>$row[0],
	 				"uname"=>$row[1],
	 				"nic"=>$row[2],
	 				"contact"=>$row[3],
	 				"email"=>$row[4]
	 			);
	 			array_push($userArray,$temp);
	 		}
	 			echo json_encode($temp);
	 		}else{
	 			echo '{'.'"id":"0"'.'}';
	 		}
	 	}

	 } catch (Exception $e) {
		echo '{'.'"id":"0"'.'}';
	 } catch(PDOException $ep){
		echo '{'.'"id":"0"'.'}';
	 }

?>