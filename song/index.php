<!DOCTYPE html>
<html>
<body>
<?php
include '../connection.php';

if (isset($_POST["submit"])) {
	$fileType = pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION);
	if($fileType != "mp3") {
		die('Only .mp3 files are allowed');
	}
	if ($_FILES["fileToUpload"]["size"] > 10000000) {
		die('Maximum size: 10MB');
	}
	$songName = mysql_real_escape_string($_POST['songName']);
	require 'loginCheck.php';
	$sql = "SELECT * FROM gdpsUsers WHERE userID=$userID";
	$result = mysqli_query($conn, $sql) or die('-1');
	while ($row = mysqli_fetch_assoc($result)) {
		$completeUsername = $row['userName'];
	}
	$file_hash = hash_file('sha256', $_FILES["fileToUpload"]["tmp_name"]);
	$sql2 = "SELECT * FROM gdpsSongs WHERE hash='$file_hash'";
	$result2 = mysqli_query($conn, $sql2) or die('-1');
	if (mysqli_num_rows($result2) > 0) {
		die('Song already exists');
	}	
	$sql_submit = "INSERT INTO gdpsSongs (downloadLink, songName, authorID, authorName, time, hash) VALUES ('http://pizzaroot.altervista.org/database/song/temp_id/file_hash.mp3', '$songName', $userID, '$completeUsername', $time, '$file_hash')";
	mysqli_query($conn, $sql_submit) or die('-1');
	$inserted_songID = mysqli_insert_id($conn);
	mkdir("$inserted_songID");
	move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "$inserted_songID/$file_hash.mp3");
	$newfile = fopen("$inserted_songID/index.html", "w") or die('-1');
	$content = '<html><head><title>'.$songName.'</title></head><body>Author: '.$completeUsername.'<br><a href="http://pizzaroot.altervista.org/database/song/'.$inserted_songID.'/'.$file_hash.'.mp3">Listen</a></body></html>';
	fwrite($newfile, $content);
	fclose($newfile);
	$sql_submit = "UPDATE gdpsSongs SET downloadLink='http://pizzaroot.altervista.org/database/song/$inserted_songID/$file_hash.mp3' WHERE songID=$inserted_songID";
	mysqli_query($conn, $sql_submit) or die('-1');
	echo "SongID: $inserted_songID<br>";
}
?>
<form action="" method="POST" enctype="multipart/form-data">
    Select .mp3 file to upload:<br>
    <input type="file" name="fileToUpload" accept=".mp3"><br>
    Username: <input type="text" name="userName"><br>
    Password: <input type="text" name="password"><br>
    Song Name: <input type="text" name="songName"><br>
    <input type="hidden" name="udid" value="song-upload"><br>
    <input type="hidden" name="secret" value="Wmfv3899gc9"><br>
    <input type="submit" value="Upload Song" name="submit">
</form><br>
<h1>10 Recent Uploads</h1>
<?php
$sql = "SELECT * FROM gdpsSongs ORDER BY songID DESC LIMIT 10";
$result = mysqli_query($conn, $sql) or die('');
while ($row = mysqli_fetch_assoc($result)) {
	echo '<a href="http://pizzaroot.altervista.org/database/song/'.$row['songID'].'/">'.$row['songName']."</a><br>";
}
echo '<br>';
?>
<script type="text/javascript">
/* <![CDATA[ */
document.write('<s'+'cript type="text/javascript" src="http://en.ad.altervista.org/js.ad/size=300X250/?ref='+encodeURIComponent(location.hostname+location.pathname)+'&r='+new Date().getTime()+'"></s'+'cript>');
/* ]]> */
</script>
<script type="text/javascript">
/* <![CDATA[ */
document.write('<s'+'cript type="text/javascript" src="http://en.ad.altervista.org/js.ad/size=728X90/?ref='+encodeURIComponent(location.hostname+location.pathname)+'&r='+new Date().getTime()+'"></s'+'cript>');
/* ]]> */
</script>
</body>
</html>