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

	$RSTATE = false;
	$OSTATE = false;

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
				

			if ($rCount>0) {
				#Already scanned barcodes
				$barcodeManager->InsertBarcodeData($_Iid,$_barcode,$_scantime,'REJECT');
				$barcodeManager->InsertRejectBarcode($_Iid,$_barcode);
				$retVal = '{"Points":"0","State":"0"}';
				$RSTATE = true;
			}else {
				#Newly scanned barcodes
				$pointval = $barcodeManager->InsertBarcodeData($_Iid,$_barcode,$_scantime,'DONE');
				$retVal = json_encode(array('Points' => $pointval,'State'=>"1"));				 
				$OSTATE = true;
			}

			//Managing transaction status, transaction header table
			if ($RSTATE && $OSTATE) {
				# for partially completed
				$barcodeManager->UpdateTransactionState($_Iid,'PARTIAL');
			}else if ($RSTATE==true && $OSTATE==false) {
				# for rejects
				$barcodeManager->UpdateTransactionState($_Iid,'REJECT');
			}else if ($RSTATE==false && $OSTATE==true) {
				# for full completed
				$barcodeManager->UpdateTransactionState($_Iid,'DONE');
			}
			

			#retun status
			echo $retVal;		
		}
	}
	
	


	
?>