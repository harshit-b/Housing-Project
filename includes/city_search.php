<?php
    session_start();
    require("../includes/database_connect.php");
    include "../includes/database_connect.php";

    $city = $_GET['city'];

    echo $city;
