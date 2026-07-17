<?php 
session_start();

$conn = new mysqli ("localhost", "root", "", "officer_db", 3307);
if($conn-> connect_error) {
    die("Connection failed: ". $conn->connect_error);
}

//getting values sent from the timer
// data being requested by the url(fetch) in the javascript section
$national_id = $_GET['national_id'] ?? ''; // get to append url query
$location = $_GET['location'] ?? '';
$type = $_GET['type'] ?? '';

$conn ->query("
        INSERT INTO law_breakers (national_id, location, alert_type)
        VALUES ('$national_id','$location', '$type')
");

$conn->close();

?>