<?php
$servername = "localhost";
$username = "pizzaroot";
$password = "";
$db = "my_pizzaroot";

$conn = mysqli_connect($servername, $username, $password, $db);

if (!$conn) {
	die('-1');
}
?>