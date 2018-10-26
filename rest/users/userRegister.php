<?php
	header('Content-type:application/json;charset=utf-8');
	include_once 'userManager.php';
	include_once '../database/database.php';

	$uname = "";$contactnum="";$email="";$imei="";$mobilenum="";$nic="";$pword="";

	try {
		 if(isset($_POST['Name']) && isset($_POST['Email']) && isset($_POST['Contact']) && isset($_POST['Mobile']) && isset($_POST['Nic']) &&
		  isset($_POST['Password']) && isset($_POST['Imei']))		
		{

			$uname = $_POST['Name'];
			$contactnum = $_POST['Contact'];
			$email = $_POST['Email'];
			$mobilenum = $_POST['Mobile'];
			$nic = $_POST['Nic'];
			$imei = $_POST['Imei'];
			$pword = $_POST['Password'];

			$dbclass = new Database();
			$connection = $dbclass->getConnection();

			$userClass = new UserManager($connection);

			$stmt_validate = $userClass->CheckExistsUser($nic);
			$count = $stmt_validate->rowcount();

			$retVal = ($count>0) ? '{"State":"0"}' : 
					$stmt = $userClass->RegisterUser($uname,$nic,$contactnum,$email,$mobilenum,$imei,$pword). '{"State":"1"}';

			echo ($retVal);


		}

	} catch (Exception $e) {
		echo $e;
	}

?>
