<?php
include "connection.php";
require "gjpCheck.php";

// Check if all parameters are set
if(!isset($_POST["gameVersion"]) or !isset($_POST["binaryVersion"]) or !isset($_POST["userName"]) or !isset($_POST["levelID"]) or !isset($_POST["levelName"]) or !isset($_POST["levelDesc"]) or !isset($_POST["levelVersion"]) or !isset($_POST["levelLength"]) or !isset($_POST["audioTrack"]) or !isset($_POST["auto"]) or !isset($_POST["password"]) or !isset($_POST["original"]) or !isset($_POST["twoPlayer"]) or !isset($_POST["songID"]) or !isset($_POST["objects"]) or !isset($_POST["coins"]) or !isset($_POST["requestedStars"]) or !isset($_POST["unlisted"]) or !isset($_POST["extraString"]) or !isset($_POST["seed"]) or !isset($_POST["seed2"]) or !isset($_POST["levelString"]) or !isset($_POST["levelInfo"]) or !isset($_POST["secret"])) {
	die('-1');
}
$secret = $_POST["secret"];
if ($secret != 'Wmfd2893gb7') {
	die('-1');
}
$gameVersion = $_POST["gameVersion"];
$levelID = $_POST["levelID"];
$levelVersion = $_POST["levelVersion"];
$levelLength = $_POST["levelLength"];
$audioTrack = $_POST["audioTrack"];
$auto = $_POST["auto"];
$password = $_POST["password"];
$original = $_POST["original"];
$twoPlayer = $_POST["twoPlayer"];
$songID = $_POST["songID"];
$objects = $_POST["objects"];
$coins = $_POST["coins"];
$requestedStars = $_POST["requestedStars"];
$unlisted = $_POST["unlisted"];
// Only accept numeric values
if (!is_numeric($gameVersion) or !is_numeric($levelID) or !is_numeric($levelVersion) or !is_numeric($levelLength) or !is_numeric($audioTrack) or !is_numeric($auto) or !is_numeric($password) or !is_numeric($original) or !is_numeric($twoPlayer) or !is_numeric($songID) or !is_numeric($objects) or !is_numeric($coins) or !is_numeric($requestedStars) or !is_numeric($unlisted)) {
	die('-1');
}
$userName = mysql_real_escape_string($_POST["userName"]);
$levelName = mysql_real_escape_string($_POST["levelName"]);
$levelDesc = mysql_real_escape_string($_POST["levelDesc"]);
$extraString = mysql_real_escape_string($_POST["extraString"]);
$levelString = mysql_real_escape_string($_POST["levelString"]);
$levelInfo = mysql_real_escape_string($_POST["levelInfo"]);
$udid = mysql_real_escape_string($_POST["udid"]);
$time = time();
$client_ip = get_client_ip();
// TO DO LIST: ALLOW UNREGISTERED USER TO UPLOAD LEVEL

$sql = "SELECT userID FROM gdpsUsers WHERE accountID=$accountID";
$result = mysqli_query($conn, $sql) or die('-1');
if (mysqli_num_rows($result) == 0) {
	die('-1');
}
while ($row = mysqli_fetch_assoc($result)) {
	$userID = $row['userID'];
}
// Decoding Level Data
try {
	$levelStringD = str_replace("-","+",$levelString);
	$levelStringD = str_replace("_","/",$levelStringD);
	$levelStringD = gzdecode(base64_decode($levelStringD));
	$objectsReal = substr_count($levelStringD, ';') - 1;
} catch (Exception $e) {
	die('-1');
}

// Check if the request is for updating level or creating level
$sql2 = "SELECT * FROM gdpsLevels WHERE userID=$userID AND levelName='$levelName'";
$result = mysqli_query($conn, $sql2) or die('-1');
$stringLength = strlen($levelString);
if (mysqli_num_rows($result) == 0) {
	$sql_submit = "INSERT INTO gdpsLevels (levelName, levelDesc, levelString, extraString, levelInfo, stringLength, objects, objectsReal, levelVersion, levelLength, userID, audioTrack, gameVersion, requestedStars, coins, password, originalLevel, twoPlayer, unlisted, songID, userIP, time, lastUpdate, updateLog, impossible) VALUES ('$levelName', '$levelDesc', '$levelString', '$extraString', '$levelInfo', $stringLength, $objects, $objectsReal, $levelVersion, $levelLength, $userID, $audioTrack, $gameVersion, $requestedStars, $coins, $password, $original, $twoPlayer, $unlisted, $songID, '$client_ip', $time, $time, '$time~$client_ip', 0);";
} else if (mysqli_num_rows($result) == 1) {
	// TO DO LIST: ALLOW UPDATING A LEVEL
	die('-1');
} else {
	die('-1');
}
mysqli_query($conn, $sql_submit) or die('-1');
echo mysqli_insert_id($conn);
?>