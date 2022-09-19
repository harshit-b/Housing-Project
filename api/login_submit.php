<?php
session_start();

require("../includes/database_connect.php");
include "../includes/database_connect.php";

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email='$email'";

$result = mysqli_query($conn, $sql);
if (!$result || $result == null) {
    $response = array("success" => false, "message" => "Connection failed: " . $mysqli_error($conn));
    echo json_encode($response);
    return;
}

$row = mysqli_fetch_assoc($result);

if (!$row) {
    $response = array("success" => false, "message" => "Login failed :( , Email not found!");
    echo json_encode($response);
    return;
} else {
    // $password_decrypt = sha1($row['password']);
    if ($row['password'] == $password) {
        $_SESSION['user_id'] = $row['id'];
        $response = array("success" => true, "message" => "Login Successful :)");
        echo json_encode($response);
    } else {
        $response = array("success" => false, "message" => "Login failed, your password is incorrect :(");
        echo json_encode($response);
        return;
    }
}

// echo $_SESSION['user_id'];
mysqli_close($conn);
