<?php
//header('Content-type:application/json;charset=utf-8');
	
	include_once 'barcode/barcodeManager.php';

	$MainObject = file_get_contents('php://input');
	$jsonData = json_decode($MainObject,true);
	$arrLength = sizeof($jsonData);

	static $UID='UID';
	static $BARCODE_DATA = 'DATA';

	static $BARCODE='BARCODE';
	static $SCAN_TIME='SCAN_DATE_TIME';

	$barcodeManager = new BarcodeManager();	

	$_uid = $jsonData[$UID];
	$_barcodeData = $jsonData[$BARCODE_DATA];

	if (!empty($_uid) && sizeof($_barcodeData)>0) {
		
		$_Iid = $barcodeManager->InsertTransactionHeader($_uid);

		foreach ($_barcodeData as $key => $value) {		
		$_barcode = $value[$BARCODE];
		$_scantime = $value[$SCAN_TIME];		

		//Check barcode is already in Database
		$stmt_validate = $barcodeManager->ValidateScanBarcode($_barcode);
		$rCount = $stmt_validate->rowcount();//Row count
		
		//Check condition and insert into barcode table	
		$retVal = (rCount>0) ? '0' : 
				$setValue = $barcodeManager->InsertBarcodeData($_Iid,$_barcode,$_scantime);

		}
	}
	
	


	
?>