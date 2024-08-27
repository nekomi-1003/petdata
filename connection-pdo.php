<?php
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "dbanimal";

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>
