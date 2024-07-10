<?php
session_start();
include "auth/connect.php";

$userEmail = $_SESSION['userEmail'];

if (empty($userEmail)) {
    header('location: login.php');
    exit();
}

$conn = connect();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "SELECT img FROM channel WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imgFile = $row['img'];


        $imgPath = __DIR__ . "/upload/" . $imgFile;
        if (file_exists($imgPath)) {
            unlink($imgPath);
        }


        $sql = "DELETE FROM channel WHERE id = $id";
        if ($conn->query($sql) === TRUE) {

            header('location: channel.php');
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {

        header('location: channel.php');
        exit();
    }
} else {

    header('location: channel.php');
    exit();
}