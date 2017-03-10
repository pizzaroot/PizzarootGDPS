<?php
include "connection.php";
require "gjpCheck.php";
$sql = "SELECT userID, allowedStars, starsLog FROM gdpsUsers WHERE accountID=$accountID";
$result = mysqli_query($conn, $sql) or die('-1');
$stars = $_POST['stars'];
$demons = $_POST['demons'];
$diamonds = $_POST['diamonds'];
$icon = $_POST['icon'];
$color1 = $_POST['color1'];
$color2 = $_POST['color2'];
$iconType = $_POST['iconType'];
$coins = $_POST['coins'];
$userCoins = $_POST['userCoins'];
$special = $_POST['special'];
if (!is_numeric($stars) or !is_numeric($demons) or !is_numeric($diamonds) or !is_numeric($icon) or !is_numeric($color1) or !is_numeric($color2) or !is_numeric($iconType) or !is_numeric($coins) or !is_numeric($userCoins) or !is_numeric($special)) {
	die('-1');
}
if (mysqli_num_rows($result) != 1) {
	die('-1');
}
while ($row = mysqli_fetch_assoc($result)) {
	$userID = $row['userID'];
	$allowedStars = $row['allowedStars'] + 186;
	$starsLog = $row['starsLog'];
}
if ($stars > $allowedStars) {
	$blocked = 1;
} else {
	$blocked = 0;
}
if ($starsLog != '') {
	$starsLog .= '|';
}
$starsLog .= $time.';'.$client_ip.';'.$stars;
$sql_submit = "UPDATE gdpsUsers SET stars=$stars, demons=$demons, diamonds=$diamonds, icon=$icon, color1=$color1, color2=$color2, iconType=$iconType, coins=$coins, userCoins=$userCoins, special=$special, blocked=$blocked, starsLog='$starsLog' WHERE userID=$userID";
mysqli_query($conn, $sql_submit) or die('-1');
echo $userID;
// TO DO LIST: SHOW ICONS
// TO DO LIST: RECORD STAR HISTORIES
// TO DO LIST: UNREGISTERED USERS
?>