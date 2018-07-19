<?php

include_once './database/database.php';
include_once './barcode/barcode.php';
include_once './users/userManager.php';
 	
 	$dbclass = new Database();
    $Connection = $dbclass->getConnection();
  
    // $barcodes = new Barcode($Connection);
    // $stmt = $barcodes->ReadBarcode();

    // $users = new UserManager($Connection);
    // $stmt = $users->validateUser("913330960v","11223344");

	echo $stmt->rowcount();

	// $result = $stmt->fetchAll();
	// echo(json_encode($result));

	$barcodes = array();

	while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) {		 
		 $barcodesArray = array(
		 	"id"=>$row[0],
		 	"barcode"=>$row[1],
		 	"scanDate"=>$row[2],
		 	"transId"=>$row[3]
		 );
		 array_push($barcodes,$barcodesArray);		
	}    
    echo json_encode($barcodes);

?>