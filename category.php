<?php
session_start();
include "header.php";

$userEmail = $_SESSION['userEmail'];

if (empty($userEmail)) {
    header('location: login.php');
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Category</title>

    <!-- Css -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
</head>

<body class="bg-white min-h-screen text-black">
    Lorem ipsum dolor sit amet consectetur adipisicing elit. Praesentium, fugiat
    ipsum. Error modi distinctio in et explicabo, alias tempora aliquam
    repudiandae ex, suscipit delectus pariatur! Adipisci harum blanditiis
    aliquam similique.
</body>

</html>