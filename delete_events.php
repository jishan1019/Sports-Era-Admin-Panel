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

    // Fetch left_img and right_img from the database
    $sql = "SELECT left_img, right_img FROM events WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $leftImgFile = $row['left_img'];
        $rightImgFile = $row['right_img'];

        // Delete left_img if it exists
        $leftImgPath = __DIR__ . "/upload/" . $leftImgFile;
        if (file_exists($leftImgPath)) {
            unlink($leftImgPath);
        }

        // Delete right_img if it exists
        $rightImgPath = __DIR__ . "/upload/" . $rightImgFile;
        if (file_exists($rightImgPath)) {
            unlink($rightImgPath);
        }

        // Delete the record from the database
        $sql = "DELETE FROM events WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            header('location: events.php');
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        // Redirect if no record found
        header('location: events.php');
        exit();
    }
} else {
    // Redirect if id is not set
    header('location: events.php');
    exit();
}