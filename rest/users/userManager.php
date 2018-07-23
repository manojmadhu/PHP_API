<?php
	/**
	 * 
	 */
	include_once '../database/database.php';

	class UserManager
	{
		private $connection;

		public function __construct()
		{
			$dbClass = new Database();
	 		$Connection = $dbClass->getConnection();
			$this->connection=$Connection;
		}
		

		//check user name & password
		public function validateUser($uname,$pword){
			try {
				$query = 
					"SELECT ID,NAME,NIC,CONTACTNUMBER,EMAIL FROM tbuser WHERE tbuser.nic = '$uname' AND tbuser.password = '$pword'";
				$stmt = $this->connection->prepare($query,array(PDO::ATTR_CURSOR=>PDO::CURSOR_SCROLL));
				$stmt->execute();
				
				return $stmt;

			} catch (Exception $e) {
				return null;
			}			
		}


		public function RegisterUser($uname,$nic,$contact,$email,$mobile,$imei,$password){
			try {
				$query = 
					"INSERT INTO tbusers (
					tbusers.name,tbusers.nic,tbusers.contactNumber,tbusers.email,tbusers.mobileNumber,tbusers.imeiNumber,tbusers.password)
					VALUES ('$uname','$nic',$contact,'$email',$mobile,'$imei','$password')";
					
					$this->connection->QUERY($query);
				
			} catch (Exception $e) {
				return null;
			}
		}

		public function CheckExistsUser($nic){
			try{

				$query = 
					"SELECT ID FROM tbusers WHERE tbusers.nic = '$nic'";
				$stmt = $this->connection->prepare($query,array(PDO::ATTR_CURSOR=>PDO::CURSOR_SCROLL));
				$stmt->execute();

				return $stmt;

			}catch(Exception $e){
				return null;
			}
		}
	}



?>