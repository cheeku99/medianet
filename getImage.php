<?php
    // Get Token Id
    $tokenID = trim($_GET['token']);
    require_once('lib/helperClass.php');
    require_once('lib/tokenClass.php');
    
    //create object of class for validating token and saving the same.
    $tokenClass = new TokenClass();
    $helperClass = new HelperClass();
    $isValidToken=false;
    //Validate Token
    if($tokenID!="")
    $isValidToken=$tokenClass->validateToken($tokenID);

    //Capture info if token is valid
    if($isValidToken){
        $tempData=array();
        //Get IP Address Of Client
        $ipAddress=$helperClass->getClientIpAddress();
        $referer=isset($_SERVER["HTTP_REFERER"])?$_SERVER["HTTP_REFERER"]:"NA";
        $userAgent=isset($_SERVER["HTTP_USER_AGENT"])?$_SERVER["HTTP_USER_AGENT"]:"NA";
        $tempData['ipaddress']=$ipAddress;
        $tempData['user_agent']=$userAgent;
        $tempData['referer']=$referer;
        $tempData['otherinfo']=$_SERVER;
	$tokenClass->saveTokenCompleteDetails($tokenID,$tempData);
    }
    // load the image
 $image = dirname(__FILE__).'/img/transparent.gif';
    header('Content-Type: image/gif');
    // display image
    echo file_get_contents($image);
    
?>
