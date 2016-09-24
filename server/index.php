<?php

include("dbConnection.php");
include("bcrypt.php");

$db = new dbConnection(); 
$conn = $db->connect();

$stmt = $conn->prepare("SELECT login, senha FROM usuarios WHERE login = :login LIMIT 1");
$stmt->execute(array('login' => "gbine"));
$row = $stmt->fetch();

if (is_array($row)) {
    echo $row['senha'];
}