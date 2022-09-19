<?php
$db_hostname = "127.0.0.1";
$db_username = "root";
$db_password = "";
$db_name = "pg_life";
$db_port = "3307";

$conn = mysqli_connect($db_hostname . ":" . $db_port, $db_username, $db_password, $db_name);
if (!$conn) {
    echo "Connection Failed: " . mysqli_connect_error();
    exit;
}
