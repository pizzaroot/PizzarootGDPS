<?php
include "connection.php";
require "XORCipher.php";
require "functions.php";
if(!isset($_POST["accountID"]) or !isset($_POST["gjp"])) {
	die('-1');
}
$accountID = $_POST['accountID'];
if(!is_numeric($accountID)) {
	die('-1');
}
$xor = new XORCipher();
$password = $xor->cipher(base64_decode($_POST['gjp']), 37526);
$time = time();
$client_ip = get_client_ip();
$sql = "SELECT * FROM gdpsUserAccounts WHERE accountID=$accountID";
$result = mysqli_query($conn, $sql) or die('-1');
if (mysqli_num_rows($result) == 0) {
	die('-1');
}
while ($row = mysqli_fetch_assoc($result)) {
	$iplog = $row['iplog'];
	$bcrypted_pass = $row['password'];
	$failedLog = $row['failedLog'];
	$blockedIPs = $row['blockedIPs'];
	$accountID = $row['accountID'];
}
if (!password_verify($password, $bcrypted_pass)) {
	//$decisionTime = time() - 3600;

	$failedarr = explode('|', $failedLog);
	// Send private message that someone attempted to login to your account 

	
	// Record failed time and ip
	if ($failedLog != '') {
		$failedLog .= '|';
	}
	$failedLog .= $time.'~'.$client_ip;
	$sql_submit = "UPDATE gdpsUserAccounts SET failedLog='$failedLog' WHERE accountID=$accountID";
	mysqli_query($conn, $sql_submit) or die('-1');
	die('-1');
}
if (strpos($blockedIPs, $client_ip) !== false) {
    die('-1');
}
if ($iplog != '') {
	$ip_history = [];
	$iplogarray = explode('|', $iplog);
	foreach ($iplogarray as $eachiplog) {
		$ip_history[] = explode('~', $eachiplog)[1];
	}
	if (in_array($client_ip, $ip_history)) {
		// Send email that there is a new ip login and if you are going to block them
	}
} else {
	die('-1');
}
$sql_submit2 = "UPDATE gdpsUserAccounts SET lastonline=$time WHERE accountID=$accountID";
mysqli_query($conn, $sql_submit2) or die('-1');
$sql_submit2 = "UPDATE gdpsUsers SET userIP='$client_ip' WHERE accountID=$accountID";
mysqli_query($conn, $sql_submit2) or die('-1');
?>