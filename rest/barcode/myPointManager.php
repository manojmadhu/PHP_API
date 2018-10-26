<?php
header('Content-type:application/json;charset=utf-8');
include_once 'barcodeManager.php';
$userID="";$Tpoint="0";$Rpoint="0";

try{

	if(isset($_POST['uid'])){
		$userID = $_POST['uid'];
		$barcodeManager = new BarcodeManager();
		$stmtRpoint = $barcodeManager->RetrivewRedeemed_points($userID);
		if($stmtRpoint->rowcount() > 0){
			while ($row=$stmtRpoint->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) {
				# code...
				$Rpoint = $row[0];
			}
		}

		$stmtTpoint = $barcodeManager->RetrivewTotal_points($userID);
		if($stmtTpoint->rowcount()>0){
			while ($row=$stmtTpoint->fetch(PDO::FETCH_NUM,PDO::FETCH_ORI_NEXT)) {
				# code...
				$Tpoint = $row[0];
			}
		}

		$pointArray = array(
			"state"=>"1",
			"Rpoints"=>$Rpoint,
			"Tpoints"=>$Tpoints
		);

		echo json_encode($pointArray);
	}
}catch(Exception $e){
	//for error code.. //$e->getMessage();
	echo '{"state":"0"}';	
}

?>