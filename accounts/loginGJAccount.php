<?php
include "../connection.php";
require "../functions.php";
if(!isset($_POST["userName"]) or !isset($_POST["password"]) or !isset($_POST["udid"]) or !isset($_POST["secret"])) {
	die('-1');
}
$userName = $_POST["userName"];
$password = $_POST["password"];
$udid = $_POST["udid"];
$secret = $_POST["secret"];
if ($secret != "Wmfv3899gc9") {
	die('-1');
}
if (!ctype_alnum(str_replace(' ', '', $userName))) {
	die('-1');
}
if (!ctype_alnum(str_replace('-', '', $udid))) {
	die('-1');
}
if (strpos($udid, '--') !== false) {
    die('-1');
}
$time = time();
$client_ip = get_client_ip();
$sql = "SELECT accountID, password, iplog, failedLog, blockedIPs FROM gdpsUserAccounts WHERE username='$userName' OR changedName='$userName'";
$result = mysqli_query($conn, $sql) or die('-1');
if (mysqli_num_rows($result) == 0) {
	die('-1');
}
if (mysqli_num_rows($result) >= 2) {
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
$new_ipLog = $iplog.'|'.$time.'~'.$client_ip;
$sql_submit = "UPDATE gdpsUserAccounts SET iplog='$new_ipLog', lastonline=$time, lastlogin=$time  WHERE accountID=$accountID";
mysqli_query($conn, $sql_submit) or die('-1');
// User Table
$sql2 = "SELECT userID FROM gdpsUsers WHERE accountID=$accountID";
$result2 = mysqli_query($conn, $sql2) or die('-1');
if (mysqli_num_rows($result2) > 0) {
	while ($row2 = mysqli_fetch_assoc($result2)) {
		$userID = $row2['userID'];
	}
} else {
	$sql2 = "INSERT INTO gdpsUsers (accountID, udid, userName, userIP) VALUES ($accountID, '$udid', '$userName', '$client_ip')";
	$result2 = mysqli_query($conn, $sql2) or die('-1');
	$userID = mysqli_insert_id($conn);
}
// Show Data
echo $accountID.','.$userID;
// Merge Accounts
?>