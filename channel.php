<?php
session_start();
include "header.php";
include "auth/connect.php";

$userEmail = $_SESSION['userEmail'];

$conn = connect();
$imageBaseUrl = getImgUrl();

$m = "";

if (empty($userEmail)) {
    header('location: login.php');
    exit();
}

if (isset($_POST['submit'])) {
    $categoryName = mysqli_real_escape_string($conn, $_POST['categoryName']);
    $channelName = mysqli_real_escape_string($conn, $_POST['channelName']);

    $img = $_FILES['channelImg'];
    $iName = $img['name'];
    $tempName = $img['tmp_name'];
    $format = explode('.', $iName);
    $actualName = strtolower($format[0]);
    $actualFormat = strtolower($format[1]);
    $allowedFormats = ['jpg', 'png', 'jpeg', 'gif'];

    if (in_array($actualFormat, $allowedFormats)) {
        $location = 'upload/' . $actualName . '.' . $actualFormat;
        $imgName =  $actualName . '.' . $actualFormat;

        $sql = "INSERT INTO channel(name,category, img, create_at) VALUES ( '$channelName','$categoryName', '$imgName', current_timestamp())";

        if ($conn->query($sql) === true) {
            move_uploaded_file($tempName, $location);
            $m = "Channel Created!";
        }
    }
}



$sql = "SELECT * FROM channel";
$res = $conn->query($sql);


// Fetch categories
$sq = "SELECT name FROM category";
$result = $conn->query($sq);


?>

<!DOCTYPE html>
<html data-theme="light" lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Channel</title>

    <!-- Css -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>


</head>

<body class="bg-white min-h-screen text-black container mx-auto">
    <main class="w-full md:w-[70%] shadow mx-auto mt-8 p-3 h-[75vh] border border-gray-100 ">
        <div class="overflow-auto">
            <table class="table">
                <!-- head -->
                <thead class="bg-blue-800">
                    <tr>
                        <th>#</th>
                        <th class="text-white">Img</th>
                        <th class="text-white">Name</th>
                        <th class="text-white">Category</th>
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
                        echo "<td>" . $row['category'] . "</td>";
                        echo "<td>
                        <a href='delete_channel.php?id=" . $row['id'] . "' class='text-white py-2 px-3 bg-red-500 rounded'>Delete</a>
                        </td>";
                        echo "</tr>";
                        $index++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <footer class="relative">
        <p class="md:h-16 md:w-16 h-10 w-10 bg-blue-800 text-white text-2xl cursor-pointer rounded-full flex items-center justify-center absolute right-8 bottom-0"
            onclick="my_modal_5.showModal()">
            <span>+</span>
        </p>

        <dialog id="my_modal_5" class="modal modal-bottom sm:modal-middle">
            <div class="modal-box">
                <form method="POST" enctype="multipart/form-data">

                    <button id="closeModal" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>

                    <h3 class="text-lg font-bold">Add Channel</h3>

                    <div class="form-control mt-4">
                        <input name="channelName" type="text" placeholder="Enter Channel name"
                            class="input input-bordered bg-transparent border-1 border-black text-black" required />
                    </div>

                    <div>
                        <select name="categoryName" class="select select-secondary w-full mt-4" required>
                            <option disabled selected>Select category</option>
                            <?php
                            if ($result->num_rows > 0) {
                                // Output data of each row
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value=\"" . $row["name"] . "\">" . $row["name"] . "</option>";
                                }
                            } else {
                                echo "<option disabled>No categories available</option>";
                            }
                            ?>
                        </select>
                    </div>


                    <div class="form-control mt-4">
                        <label class="label">
                            <span class="label-text text-black">Choose Channel Img</span>
                        </label>
                        <input name="channelImg" type="file"
                            class="file-input file-input-bordered file-input-primary w-full" />
                    </div>

                    <button name="submit" type="submit" class="btn btn-md mt-5">Add</button>
                </form>
            </div>
        </dialog>
    </footer>

</body>


<script>
const closeModalBtn = document.getElementById('closeModal');
document.getElementById('navTitle').innerText = "All Channel";

const modal = document.getElementById('my_modal_5');
closeModalBtn.addEventListener('click', () => {
    modal.close();
});
</script>




</html>