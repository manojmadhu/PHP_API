<?php
/**
* 
*/

include_once '../database/database.php';
include_once 'pointCalculation.php';

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
		try{

			$calculator = new PointCalculator($barcode);					
			$point = $calculator->getPoint();

			$stmt = $this->connection->prepare("INSERT INTO tbbarcode (transId,barcode,scanDate,status,points) VALUES (?,?,?,?,?)");
			$stmt->bindParam(1,$trid);
			$stmt->bindParam(2,$barcode);
			$stmt->bindParam(3,$scantime);
			$stmt->bindParam(4,$status);
			$stmt->bindParam(5,$point);
			$stmt->execute();	
			return $point;
			
		}catch(Exception $e) {
 			//echo 'Message: ' .$e->getMessage();
		}			
	}

	// public function ReadBarcode(){
	// 	$query = "SELECT * FROM tbbarcodes";
	// 	$stmt = $this->connection->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	// 	$stmt->execute();
	// 	return $stmt;
	// }


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
		$query = "UPDATE tbtransaction SET STATUS = ? , transPoint=(SELECT SUM(points) FROM tbbarcode where transId = ?) WHERE ID = ?";
		$stmt = $this->connection->prepare($query);
		$stmt->bindParam(1,$STATUS_);
		$stmt->bindParam(2,$trid_);
		$stmt->bindParam(3,$trid_);
		$stmt->execute();
		return $stmt;
	}

	public function RetrivewPackPoint($pack_){
		$query = "SELECT POINTS FROM tbpoint_schema WHERE PACK = ?";
		$stmt = $this->connection->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->bindParam(1,$pack_);
		$stmt->execute();
		return $stmt;
	}

	public function RetrivewRedeemed_points($uid_){
		try{
			$query = "SELECT (CASE WHEN SUM(points) is null THEN 0 ELSE SUM(points) END) AS Rpoints FROM tbredeemed_points WHERE uid = ?";
			$stmt = $this->connection->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$stmt->bindParam(1,$uid_);
			$stmt->execute();
			return $stmt;	
		}catch(Exception $ex){
			//echo 'error '.$ex->getMessage();
		}		
	}

	public function RetrivewTotal_points($uid_){
		try{
			$query="SELECT (CASE WHEN SUM(transPoint) is null then 0 else SUM(transPoint) END) as Tpoints 
					from tbtransaction where USER = ? AND STATUS <> 'REJECT'";
			$stmt = $this->connection->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$stmt->bindParam(1,$uid_);
			$stmt->execute();
			return $stmt;
		}catch(Exception $ex){
			//$ex->getMessage();
		}
	}
		

}

?>