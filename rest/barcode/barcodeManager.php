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

	public function InsertBarcodeData($trid,$barcode,$scantime,$status){
		$stmt = $this->connection->prepare("INSERT INTO tbbarcode (transId,barcode,scanDate,status) VALUES (?,?,?,?)");
		$stmt->bindParam(1,$trid);
		$stmt->bindParam(2,$barcode);
		$stmt->bindParam(3,$scantime);
		$stmt->bindParam(4,$status);	
		$stmt->execute();		
	}

	public function ReadBarcode(){
		$query = "SELECT * FROM tbbarcodes";
		$stmt = $this->connection->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute();
		return $stmt;
	}


	public function ValidateScanBarcode($barcode_){
		$query = "SELECT ID FROM tbbarcode WHERE barcode = ?";
		$stmt = $this->connection->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->bindParam(1,$barcode_);
		$stmt->execute();
		return $stmt;
	}

	public function InsertRejectBarcode($trid_,$barcode_){
		$query = "INSERT INTO tbreject_barcode (barcode,transDate,transId) VALUES (?,NOW(),?)";		
		$stmt = $this->connection->prepare($query);
		$stmt->bindParam(1,$barcode_);
		$stmt->bindParam(2,$trid_);		
		$stmt->execute();
		return $stmt;
	}

	public function UpdateTransactionState($trid_,$STATUS_){
		$query = "UPDATE tbtransaction SET STATUS = ? WHERE ID = ?";
		$stmt = $this->connection->prepare($query);
		$stmt->bindParam(1,$STATUS_);
		$stmt->bindParam(2,$trid_);
		$stmt->execute();
		return $stmt;
	}
}

?>