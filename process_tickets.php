<?php 

session_start(); //this is a temporary location am getting it

$conn = new mysqli("localhost", "root", "", "officer_db", 3307);
if ($conn->connect_error) {
    die("connection failed:" . $conn->connect_error);
}

$driver_name = $_POST['driver_name'] ?? '';
$driver_id = $_POST['driver_id'] ?? '';
$car_plate = $_POST['car_num'] ?? '';
$ticket_type = $_POST['ticket_type'] ??'';
$fine_amount = $_POST['fine'] ?? 0;
$location = $_POST['location'] ?? '';
$notes      = $_POST['notes'] ?? '';
$national_id = $_SESSION['national_id'] ?? '';

$sql = "INSERT INTO tickets (driver_name, driver_id, national_id, car_plate, ticket_type, fine_amount, location, notes, payment_status, created_at)
      VALUES ('$driver_name', '$driver_id', '$national_id', '$car_plate', '$ticket_type', '$fine_amount', '$location', '$notes', 'Unpaid', NOW())";

if($conn->query($sql) === TRUE) {
    $_SESSION['warning_count'] = 0; // reset when ticket is given
    $_SESSION['reported'] = false; // then if officer issues a ticket turn reported to false
    $_SESSION['last_ticket_time'] = date('Y-m-d H:i:s');
    header('Location: dash.php');
    exit();

} 
else {
    echo "Error: ". $conn->error;
}

$conn->close();

?>