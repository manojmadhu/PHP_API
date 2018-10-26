<?php

/**
	points calculation class.
	1. retrivew pack point * qty
 */
include_once 'barcodeManager.php';

class PointCalculator
{
	private $barcode=0,$points=0;
	
	public function __construct($barcode_){
		$this->barcode = $barcode_;
	}
	public function setPoints($points_){
		$this->points = $points_;
	}
	public function getPoint(){
		$this->calculatePoints();
		return $this->points;
	}

	public function calculatePoints(){
		$packSize = substr($this->barcode, 11,3);
		$qty = substr($this->barcode, 24,2);


		$barcodeManager = new BarcodeManager();
		$stmtPack = $barcodeManager->RetrivewPackPoint($packSize);
		if($stmtPack->rowcount() > 0){
			while ($row=$stmtPack->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)){
				$this->points = $row[0];
				$this->setPoints($qty * $this->points);								
			}
		}
		
	}

}


?>