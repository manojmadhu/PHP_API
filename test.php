<?php

header('Content-type:application/json;charset=utf-8');
include_once './database/database.php';
include_once './barcode/barcode.php';

$uname = "";
$pword = "";

try{
if(isset($_POST['UserName']) && isset($_POST['Password']))
{
    $uname = $_POST['UserName'];
    $pword = $_POST['Password'];

    $dbclass = new Database();
    $Connection = $dbclass->getConnection();
    
    //$barcodes = new Barcode($Connection);
    //$stmt = $barcodes->CreateDatabase();

    echo json_encode($pword);
}
}catch(PDOException $ex){
    echo json_encode("error");
}



// try {
    
//      $dbclass = new Database();
//     $Connection = $dbclass->getConnection();

//     // $barcodes = new Barcode($Connection);
//     // $barcodes->barcode = 123445;
//     // $stmt = $barcodes->InsertBarcodeData();
//  echo '{';
//         echo '"message": "Unable to create product."';
//     echo '}';
//         echo '{"response :"Product was created."}';

//     }
//     catch(PDOException $e)
//     {
//         echo "Connection failed: " . $e->getMessage();
//     }

?>