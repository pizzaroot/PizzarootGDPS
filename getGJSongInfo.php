<?php
include "connection.php";
$songID = $_POST["songID"];
if (!is_numeric($songID)) {
	die('-1');
}
$sql = "SELECT * FROM gdpsSongs WHERE songID=$songID";
$result = mysqli_query($conn, $sql) or die('-1');
if (mysqli_num_rows($result) == 0) {
	die('-1');
}
while ($row = mysqli_fetch_assoc($result)) {
	echo "1~|~".$row["songID"]."~|~2~|~".$row["songName"]."~|~3~|~".$row["authorID"]."~|~4~|~".$row["authorName"]."~|~5~|~6.9~|~6~|~~|~10~|~".urlencode($row["downloadLink"])."~|~7~|~~|~8~|~0";
}
?>