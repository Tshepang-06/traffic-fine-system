
<?php 
session_start();

$conn = new mysqli("localhost", "root", "", "officer_db", 3307);

if ($conn->connect_error) {
    die("Failed to connect: " . $conn->connect_error);
}

$username = trim($_POST['username']);
$password = trim($_POST['password']);


$sql = "SELECT * FROM officers
        WHERE username='$username'
        AND password='$password'";

$result = $conn -> query($sql);

if ($result->num_rows > 0) {
    $officer = $result->fetch_assoc();
    $_SESSION['national_id'] = $officer['national_id'];
    $_SESSION['name'] = $officer['firstname'];

    $last_activity = $officer['last_activity'];

if(!$last_activity) { //when logging in for first time reset the follow data
    
    $_SESSION['login_time'] = date('Y-m-d H:i:s');
    $_SESSION['warning_count'] = 0;
    $_SESSION['reported'] = false;
    $_SESSION['last_ticket_time'] = null;

} else {
    //use this data if officer has logged in before
    $_SESSION['login_time'] = $last_activity;
}

     header("Location: dash.php");
     exit();
} else {
    echo "Wrong username or password";
}
 $conn->close();

?>
