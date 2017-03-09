<?php
include "connection.php";
$levelsstring = "";
$creatorsstring = "";
$songsstring  = "";
$hash = "";
$type = $_POST['type'];
$str = $_POST['str'];
$page = $_POST["page"];
if (!is_numeric($page)) {
	die('-1');
}
$offset = $page * 10;
if ($type == '0') {
	if (is_numeric($str)) {
		$sql = "SELECT * FROM gdpsLevels WHERE levelID=$str OR (unlisted=0 AND levelName LIKE '%$str%') ORDER BY likes DESC LIMIT 10 OFFSET $offset";
	} else {
		$str = mysql_real_escape_string($str);
		$sql = "SELECT * FROM gdpsLevels WHERE unlisted=0 AND levelName LIKE '%$str%' ORDER BY likes DESC LIMIT 10 OFFSET $offset";
	}
} else {
	die('-1');
}
$result = mysqli_query($conn, $sql) or die('-1');
$result2 = mysqli_query($conn, str_replace(' LIMIT 10 OFFSET '.$offset, '', $sql)) or die('-1');
if (mysqli_num_rows($result) == 0) {
	die('-1');
}
$levelcount = mysqli_num_rows($result2);
while ($row = mysqli_fetch_assoc($result)) {
	if ($levelsstring != "") {
		$levelsstring .= "|";
	}
	if ($row['stars'] == 10) {
		$demon = 1;
	} else {
		$demon = 0;
	}
	$demonDiff = '';
	$userID = $row["userID"];
	if (!is_numeric($userID)) {
		die('-1');
	}
	$songID = $row["songID"];
	$levelsstring .= "1:".$row["levelID"].":2:".$row["levelName"].":5:".$row["levelVersion"].":6:".$userID.":8:10:9:".$row["difficulty"].":10:".$row["downloads"].":12:".$row["audioTrack"].":13:".$row["gameVersion"].":14:".$row["likes"].":17:".$demon.":43:".$demonDiff.":25:".$row["auto"].":18:".$row["stars"].":19:".$row["featured"].":42:".$row["epic"].":45:".$row["objects"].":3:".$row["levelDesc"].":15:".$row["levelLength"].":30:".$row["originalLevel"].":31:0:37:".$row["coins"].":38:".$row["coinsVerified"].":39:".$row["requestedStars"].":46:1:47:2".":35:".$songID."";
	$hash .= $row["levelID"][0].$row["levelID"][strlen($row["levelID"])-1].$row["stars"].$row["coins"];
	$sql2 = "SELECT * FROM gdpsUsers WHERE userID=$userID";
	$result3 = mysqli_query($conn, $sql2) or die('-1');
	if (mysqli_num_rows($result3) != 1) {
		die('-1');
	}
	if ($creatorsstring != "") {
		$creatorsstring .= "|";
	}
	while ($row2 = mysqli_fetch_assoc($result3)) {
		$creatorsstring .= $row2['userID'].':'.$row2['userName'].':'.$row2['accountID'];
	}
	if ($songID != 0) {
		if ($songsstring != "") {
			$songsstring .= "~:~";
		}
		$songsstring = $songsstring."1~|~".$songID."~|~2~|~U suk dikis~|~3~|~69~|~4~|~PornHub~|~5~|~69.69~|~6~|~~|~10~|~".urlencode("http://pizzaroot.altervista.org/unarmed.mp3")."~|~7~|~~|~8~|~0";
	}
}
echo $levelsstring;
echo "#".$creatorsstring;
echo "#".$songsstring;
echo "#".$levelcount.":".$offset.":10";
echo "#".sha1($hash."xI25fpAapCQg");
?>