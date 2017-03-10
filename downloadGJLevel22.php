<?php
include "connection.php";
require "XORCipher.php";
require "functions.php";
$feaID = 0;
$secret = $_POST["secret"];
if ($secret != "Wmfd2893gb7") {
	die('-1');
}
$levelID = $_POST["levelID"];
$client_ip = get_client_ip();
$current_time = time();
if (!is_numeric($levelID)) {
	die('-1');
}
$sql = "SELECT * FROM gdpsLevels WHERE levelID=$levelID";
$result = mysqli_query($conn, $sql) or die('-1');
if (mysqli_num_rows($result) == 0) {
	die('-1');
}
while ($row = mysqli_fetch_assoc($result)) {
	// Record to downloadLevelLog
	if ($row['stars'] != 0) {
		$sql2 = "SELECT downloadLevelLog FROM gdpsUsers WHERE userIP='$client_ip'";
		$result2 = mysqli_query($conn, $sql2) or die('-1');
		if (mysqli_num_rows($result2) == 0) {
			die('-1');
		}
		while ($row2 = mysqli_fetch_assoc($result2)) {
			$downloadLevelLog = $row2['downloadLevelLog'];
		}
		if ($downloadLevelLog == '') {
			$newdownloadLevelLog = $current_time.';'.$levelID.';'.$row['stars'];
			$allowedStars = $row['stars'];
		} else {
			if (strpos($downloadLevelLog, ';'.$levelID.';') !== false) {
				$newdownloadLevelLog = $downloadLevelLog;
			} else {
				$newdownloadLevelLog = $downloadLevelLog.'|'.$current_time.';'.$levelID.';'.$row['stars'];
			}
			$newdownloadLevelLogArr = explode('|', $newdownloadLevelLog);
			$allowedStars = 0;
			foreach ($newdownloadLevelLogArr as $eachnewdownloadLevelLog) {
				$allowedStars += explode(';', $eachnewdownloadLevelLog)[2];
			}
		}
		$sql_submit2 = "UPDATE gdpsUsers SET allowedStars=$allowedStars, downloadLevelLog='$newdownloadLevelLog' WHERE userIP='$client_ip'";
		mysqli_query($conn, $sql_submit2) or die('-1');
	}
	
	$downloaderIPs = $row['downloaderIPs'];
	if ($downloaderIPs == '') {
		$newdownloaderIPs = $client_ip;
		$downloads = 1;
	} else {
		$downloaderIParr = explode(',', $downloaderIPs);
		if (in_array($client_ip, $downloaderIParr)) {
			$newdownloaderIPs .= $downloaderIPs;
			$downloads = count($downloaderIParr);
		} else {
			$newdownloaderIPs .= ','.$client_ip;
			$downloads = count($downloaderIParr) + 1;
		}
	}
	$sql_submit = "UPDATE gdpsLevels SET downloads=$downloads, downloaderIPs='$newdownloaderIPs' WHERE levelID=$levelID";
	mysqli_query($conn, $sql_submit) or die('-1');
	if ($row['stars'] == 10) {
		$demon = 1;
	} else {
		$demon = 0;
	}
	$xor = new XORCipher();
	$xorPass = base64_encode($xor->cipher($row['password'], 26364));
	$time = makeTime($row["time"]);
	$lastUpdate = makeTime($row["lastUpdate"]);
	$demonDiff = '';
	$levelString = $row["levelString"];
	echo "1:".$row["levelID"].":2:".$row["levelName"].":3:".$row["levelDesc"].":4:".$levelString.":5:".$row["levelVersion"].":6:".$row["userID"].":8:10:9:".$row["difficulty"].":10:".$downloads.":11:1:12:".$row["audioTrack"].":13:".$row["gameVersion"].":14:".$row["likes"].":17:".$demon.":43:".$demonDiff.":25:".$row["auto"].":18:".$row["stars"].":19:".$row["featured"].":42:".$row["epic"].":45:0:15:".$row["levelLength"].":30:".$row["originalLevel"].":31:0:28:".$time.":29:".$lastUpdate.":35:".$row["songID"].":36:".$row["extraString"].":37:".$row["coins"].":38:".$row["coinsVerified"].":39:".$row["requestedStars"].":46:1:47:2:27:".$xorPass;
	$hashstring = $row["userID"].",".$row["stars"].",".$demon.",".$levelID.",".$row["coins"].",".$row["featured"].",".$row['password'].",".$feaID;
}
echo "#";
$hash = "aaaaa";
$len = strlen($levelString);
$divided = intval($len / 40);
$p = 0;
for($k = 0; $k < $len ; $k += $divided){
	if($p > 39) break;
	$hash[$p] = $levelString[$k]; 
	$p++;
}
echo sha1($hash."xI25fpAapCQg");
echo "#";
echo sha1($hashstring."xI25fpAapCQg");
echo "#";
echo $hashstring;

// TO DO LIST: DAILY LEVELS
?>