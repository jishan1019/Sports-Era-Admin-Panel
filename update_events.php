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
    $eventId = $_GET['id'];

    $currentEventData = "SELECT * FROM events WHERE id = '$eventId'";
    $result = $conn->query($currentEventData);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $currentStatus = $row['isLive'];

        $newStatus = ($currentStatus == 1) ? 0 : 1;


        $updateQuery = "UPDATE events SET isLive = '$newStatus' WHERE id = '$eventId'";
        if ($conn->query($updateQuery) === TRUE) {

            header("Location: events.php");
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "Event not found.";
    }
}

$conn->close();
