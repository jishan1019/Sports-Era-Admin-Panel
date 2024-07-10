<?php
session_start();
include "header.php";
include "auth/connect.php";

$userEmail = $_SESSION['userEmail'];

$conn = connect();
$imageBaseUrl = getImgUrl();

if (empty($userEmail)) {
    header('location: login.php');
    exit();
}

$sql = "SELECT * FROM category";
$res = $conn->query($sql);
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
    <main class="w-full md:w-[70%] shadow mx-auto mt-8 p-3 h-[75vh] border border-gray-100 ">
        <div class="overflow-x-auto">
            <table class="table">
                <!-- head -->
                <thead class="bg-blue-800">
                    <tr>
                        <th>#</th>
                        <th class="text-white">Img</th>
                        <th class="text-white">Name</th>
                        <th class="text-white">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $index = 1;
                    while ($row = $res->fetch_assoc()) {
                        echo "<tr>";
                        echo "<th>" . $index . "</th>";
                        echo "<td ><img src='" . $imageBaseUrl . "/upload/" . $row['img'] . "' alt='image' width='40' height='40' /></td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>
                        <a href='delete_category.php?id=" . $row['id'] . "' class='text-white py-2 px-3 bg-red-500 rounded'>Delete</a>
                        </td>";
                        echo "</tr>";
                        $index++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
</body>

</html>