<?php

$conn = mysqli_connect("localhost", "root", "", "officer_db", 3307);
mysqli_report(MYSQLI_REPORT_OFF); //turn off automatic exceptions 
if ($_SERVER["REQUEST_METHOD"] == "POST") {

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$surname = $_POST['surname'];
$telephone = $_POST['telephone'];
$national_id = $_POST['national_id'];
$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];
$badge_number = $_POST['badge_number'];
$year_of_employment = $_POST['year_of_employment'];
$station = $_POST['station'];


if(empty($firstname) || empty($lastname) || empty($username) || empty($password)){
   die("Please fill all required fields");
}
   


$sql = "INSERT INTO officers (
firstname,
lastname,
surname,
telephone,
national_id,
email,
username,
password,
badge_number,
year_of_employment,
station
)

VALUES (
'$firstname',
'$lastname',
'$surname',
'$telephone',
'$national_id',
'$email',
'$username',
'$password',
'$badge_number',
'$year_of_employment',
'$station'
)";

if (mysqli_query($conn, $sql)) {
    header("Location: login_site.html");
    exit();
}   
 // check if there is a duplicate error
 //  1062 is used to check for duplicate errors in unique values
if(mysqli_errno($conn) == 1062) {
     echo "Officer with this national_ID already exists!!!";
   
}
if(mysqli_errno($conn) != 1062) {
        echo "Regisration failed, please try again.";
    }

mysqli_close($conn);

}
?>