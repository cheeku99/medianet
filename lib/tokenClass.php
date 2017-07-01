<?php

class TokenClass extends SQLite3 {

    private $token;

    /**
     * Initialize the TokenClass, generate token and save the same in DB
     * class
     * 
     *
     */
    public function __construct() {
        $this->open(dirname(__FILE__) . '/tracker.db');
    }

    /**
     * Generate Token
     */
    public function generateToken() {
        $tokenLength = 25;
        //Generate a random string.
        $token = openssl_random_pseudo_bytes($tokenLength);

        //Convert the binary data into hexadecimal representation.
        $token = bin2hex($token);
        $statusMsg = $this->saveTokenInDb($token);
        if ($statusMsg == "Token generated successfully") {
            return $token;
        } else {
            return 'error';
        }
    }

    public function saveTokenInDb($token) {
        if (trim($token) != "") {
            $insertQuery = "insert into emailtoken(token) values('" . $token . "')";
            $ret = $this->exec($insertQuery);
            if (!$ret) {
                return $this->lastErrorMsg();
            } else {
                return "Token generated successfully";
            }
        }
    }

    public function validateToken($token) {
        $sql = "SELECT * from emailtoken where token='" . addslashes($token) . "'";
        $result = $this->query($sql);
        $ret = $this->query($sql);
        $tokenData = array();
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $tokenData = $row;
        }
        return $tokenData;
    }

    public function fetchAllToken() {
        $sql = "SELECT * from emailtoken";
        $ret = $this->query($sql);
        $tempArr = array();
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $tempArr[] = $row;
        }
    }

    public function getAllTokenData() {
	$sql = "select e.token as tokenId,e.generatedon,t.* from emailtoken e left join tokendata t on e.token=t.token";
        $ret = $this->query($sql);
        $tempArr = array();

        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
//echo "<pre>";print_r($row);
            $ipAddress = $row['ipaddress'];
            
                $tempTokenTime = strtotime($row['generatedon']);
                $tempTokenOpenTime = strtotime($row['createdon']);
                $timeDifference = $tempTokenOpenTime - $tempTokenTime;
                $timeDifference = ceil($timeDifference / 60);
	        if(!isset($tempArr[$row['tokenId']]['30minunique']))
		     $tempArr[$row['tokenId']]['30minunique']=0;
	        if(!isset($tempArr[$row['tokenId']]['5minunique']))
		     $tempArr[$row['tokenId']]['5minunique']=0;
	        if(!isset($tempArr[$row['tokenId']]['grt30minunique']))
		     $tempArr[$row['tokenId']]['grt30minunique']=0;
		if(!isset($tempArr[$row['tokenId']]['5min']))
		     $tempArr[$row['tokenId']]['5min']=0;
	        if(!isset($tempArr[$row['tokenId']]['30min']))
		     $tempArr[$row['tokenId']]['30min']=0;
	        if(!isset($tempArr[$row['tokenId']]['grt30min']))
		     $tempArr[$row['tokenId']]['grt30min']=0;
                if (!in_array($ipAddress, $tempArr[$row['tokenId']]['ipaddress']) && !empty($ipAddress)) {
                    if ($timeDifference <= 5) {
                            $tempArr[$row['tokenId']]['5minunique'] += 1;
                    }
                    else if ($timeDifference > 5 && $timeDifference <= 30) {
                            $tempArr[$row['tokenId']]['30minunique'] += 1;
                    }else {

                            $tempArr[$row['tokenId']]['grt30minunique'] += 1;
		    }
	            $tempArr[$row['tokenId']]['ipaddress'][]=$ipAddress;
                }else if($ipAddress!="") {
                    if ($timeDifference <= 5) {
                            $tempArr[$row['tokenId']]['5min'] += 1;
                    }
                    else if ($timeDifference > 5 && $timeDifference <= 30) {
                            $tempArr[$row['tokenId']]['30min'] += 1;
                    }else{

                            $tempArr[$row['tokenId']]['grt30min'] += 1;
		    }
                }
		
            
        }
	return $tempArr;
    }

    public function saveTokenCompleteDetails($token, $tokenDetails) {
        if (is_array($tokenDetails) && !empty($tokenDetails)) {
            $insertQuery = "insert into tokendata(token,ipaddress,useragent,referer,otherinformation) values('" . $token . "','" . $tokenDetails['ipaddress'] . "','" . $tokenDetails['user_agent'] . "','" . $tokenDetails['referer'] . "','" . json_encode($tokenDetails['otherinfo']) . "')";
            $ret = $this->exec($insertQuery);
            if (!$ret) {
                return $this->lastErrorMsg();
            } else {
                return "Token generated successfully";
            }
        }
    }

    public function getIndiviualTokenData($tokenId){
	$sql = "select * from tokendata where token='".$tokenId."'";
        $ret = $this->query($sql);
	$tempArray=array();
	while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
	   $tempArray[$row['token']][]=$row;
	}
	return $tempArray;
    }

}

?>
