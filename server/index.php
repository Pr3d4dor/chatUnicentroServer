<?php

include("dbConnection.php");
include("bcrypt.php");

$db = new dbConnection(); 
$conn = $db->connect();

$stmt = $conn->prepare("SELECT login FROM usuarios WHERE login = 'gbine'");
$stmt->execute();
$row = $stmt->fetch();

if (is_array($row)) {
    echo "Teste";
}