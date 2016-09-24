<?php

include("dbConnection.php");
include("bcrypt.php");

$db = new dbConnection(); 
$conn = $db->connect();

$stmt = $conn->prepare("SELECT * FROM usuarios");
$stmt->execute();
$row = $stmt->fetch();

var_dump($row);