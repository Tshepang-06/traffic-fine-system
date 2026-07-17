<?php 
session_start();

$_SESSION['warning_count'] = $_GET['count']?? 0;

if(isset($_GET['reported'])) {
    $_SESSION['reported'] = true;
}

?>