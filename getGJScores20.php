<?php
include "connection.php";
$type = $_POST["type"];
if ($type == "top") {
	$sql = "SELECT * FROM gdpsUsers WHERE blocked=0 ORDER BY stars DESC, demons DESC LIMIT 100";
} elseif ($type == "creator") {
	$sql = "SELECT * FROM gdpsUsers WHERE blocked=0 AND creatorPoints > 0 ORDER BY creatorPoints DESC, stars DESC LIMIT 100";
} else {
	die('-1');
}
$result = mysqli_query($conn, $sql) or die('-1');
if (mysqli_num_rows($result) == 0) {
	die('-1');
}
$rank = 1;
$finalstring = '';
while ($row = mysqli_fetch_assoc($result)) {
	if ($finalstring != '') {
		$finalstring .= '|';
	}
	$finalstring .= "1:".$row["userName"].":2:".$row["userID"].":13:".$row["coins"].":17:".$row["userCoins"].":6:".$rank.":9:".$row["icon"].":10:".$row["color1"].":11:".$row["color2"].":14:".$row["iconType"].":15:".$row["special"].":16:".$row['accountID'].":3:".$row["stars"].":8:".$row["creatorPoints"].":4:".$row["demons"].":7:".$row["accountID"].":46:".$row["diamonds"];
	$rank++;
}
echo $finalstring;

// TO DO LIST: GLOBAL AND FRIEND LEADERBOARD
?>