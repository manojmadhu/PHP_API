<?php
/**
* 
*/

include_once 'database/database.php';

class BarcodeManager
{
	private $connection;
	
	public function __construct()
	{
			$dbClass = new Database();
	 		$Connection = $dbClass->getConnection();
			$this->connection=$Connection;
	}

	public function InsertTransactionHeader($user){
		$stmt = $this->connection->prepare("INSERT INTO tbtransaction (user,transDate) VALUES (?,NOW())");
		$stmt->bindParam(1,$user);	
		$stmt->execute();
		return $this->connection->LASTINSERTID();
	}

	public function InsertBarcodeData($trid,$barcode,$scantime){
		$stmt = $this->connection->prepare("INSERT INTO tbbarcodes (transId,barcode,scanDate) VALUES (?,?,?)");
		$stmt->bindParam(1,$trid);
		$stmt->bindParam(2,$barcode);
		$stmt->bindParam(3,$scantime);		
		$stmt->execute();		
	}

	public function ReadBarcode(){
		$query = "SELECT * FROM tbbarcodes";
		$stmt = $this->connection->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute();
		return $stmt;
	}


	public function ValidateScanBarcode($barcode_){
		$query = "SELECT ID FROM tbbarcodes WHERE barcode = ?";
		$stmt = $this->connection->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->bindParam(1,$barcode_);
		$stmt->execute();
		return $stmt;
	}

}

?>