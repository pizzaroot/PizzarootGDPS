<?php
include "../connection.php";
require "../functions.php";
if(!isset($_POST["userName"]) or !isset($_POST["password"]) or !isset($_POST["email"]) or !isset($_POST["secret"])) {
	die('-1');
}
$userName = $_POST["userName"];
$password = $_POST["password"];
$email = $_POST["email"];
$secret = $_POST["secret"];
if ($secret != "Wmfv3899gc9") {
	die('-1');
}
if (!ctype_alnum($userName)) {
	die('-1');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
	
} else {
	die('-1');
}
$bcrypted_pass = password_hash($password, PASSWORD_BCRYPT);
$sql = "SELECT * FROM gdpsUserAccounts WHERE username='$userName'";
$result = mysqli_query($conn, $sql) or die('-1');
if (mysqli_num_rows($result) > 0) {
	die('-2');
}
$time = time();
$client_ip = get_client_ip();
$sql_submit = "INSERT INTO gdpsUserAccounts (username, password, email, regtime, lastonline, iplog) VALUES ('$userName', '$bcrypted_pass', '$email', $time, $time, '$time~$client_ip')";
mysqli_query($conn, $sql_submit) or die('-1');
echo '1';
?>