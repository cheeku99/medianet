<?php
    // Get Token Id
    require_once('lib/tokenClass.php');
    $tokenId=$_GET['token'];
    //create object of class for validating token and saving the same.
	if($tokenId!=""){
		$tokenClass = new TokenClass();
    		$allTokenData=$tokenClass->getIndiviualTokenData($tokenId);
if(empty($allTokenData)){
	echo "No records found";
}else
		echo json_encode($allTokenData);
	}
    

    
?>

