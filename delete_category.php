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

    // Fetch the image filename from the database
    $sql = "SELECT img FROM category WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imgFile = $row['img'];

        // Delete the image file from the upload folder
        $imgPath = __DIR__ . "/upload/" . $imgFile;
        if (file_exists($imgPath)) {
            unlink($imgPath);
        }

        // Delete the category from the database
        $sql = "DELETE FROM category WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            // Redirect back to the category page after deletion
            header('location: category.php');
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        // If no record found, redirect to the category page
        header('location: category.php');
        exit();
    }
} else {
    // Redirect if no ID is provided
    header('location: category.php');
    exit();
}
