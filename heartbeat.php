<?php 
session_start();

$conn = new mysqli("localhost", " ", "root", 3307);
$national_id = $_SESSION['national_id'] ?? '';

conn->query("UPDATE officers SET last_activity = NOW() WHERE national_id = '$national_id'");
$conn->close();

?>