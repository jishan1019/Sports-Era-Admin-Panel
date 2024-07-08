<?php 

session_start();

$userid = $_SESSION['userId'];

if (empty($userid)) {
    header('location: login.php');
    exit();
}else{
    header('location: dashboard.php');
}


?>