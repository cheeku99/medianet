<?php
//This script will generate new token and return url
require_once('lib/tokenClass.php');

$tokenClass = new TokenClass();
$token=$tokenClass->generateToken();

if($token=='error'){
    echo "Error in generating token";
}else{
    $url=(isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER[HTTP_HOST]."".$_SERVER[REQUEST_URI];
    $newUrl=str_replace("generateToken.php","getImage.php?token=".$token,$url);
    echo $newUrl;
}
    
?>
