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
    $matchName = mysqli_real_escape_string($conn, $_POST['matchName']);
    $matchType = mysqli_real_escape_string($conn, $_POST['matchType']);
    $matchTime = mysqli_real_escape_string($conn, $_POST['matchTime']);
    $matchDate = mysqli_real_escape_string($conn, $_POST['matchDate']);
    $matchIsLive = mysqli_real_escape_string($conn, $_POST['matchIsLive']);
    $matchLink1 = mysqli_real_escape_string($conn, $_POST['matchLink1']);
    $matchLink2 = mysqli_real_escape_string($conn, $_POST['matchLink2']);

    $matchStatus = $matchIsLive == "Live" ? 1 : 0;


    $img1 = $_FILES['team1Img'];
    $img2 = $_FILES['team2Img'];
    $iName1 = $img1['name'];
    $iName2 = $img2['name'];
    $tempName1 = $img1['tmp_name'];
    $tempName2 = $img2['tmp_name'];
    $format1 = explode('.', $iName1);
    $format2 = explode('.', $iName2);

    $actualName1 = strtolower($format1[0]);
    $actualName2 = strtolower($format2[0]);

    $actualFormat1 = strtolower($format1[1]);
    $actualFormat2 = strtolower($format2[1]);

    $allowedFormats = ['jpg', 'png', 'jpeg', 'gif'];


    if (in_array($actualFormat1, $allowedFormats) && in_array($actualFormat2, $allowedFormats)) {
        $location1 = 'upload/' . $actualName1 . '.' . $actualFormat1;
        $imgName1 =  $actualName1 . '.' . $actualFormat1;

        $location2 = 'upload/' . $actualName2 . '.' . $actualFormat2;
        $imgName2 =  $actualName2 . '.' . $actualFormat2;


        $sql = "INSERT INTO events(name, left_img, right_img, type, isLive, time,date, link1,link2, create_at) VALUES ('$matchName', '$imgName1', '$imgName2', '$matchType', '$matchStatus', '$matchTime','$matchDate', '$matchLink1' , '$matchLink2' , current_timestamp())";

        if ($conn->query($sql) === true) {
            move_uploaded_file($tempName1, $location1);
            move_uploaded_file($tempName2, $location2);
        }
    }
}



$sql = "SELECT * FROM events";
$res = $conn->query($sql);
?>

<!DOCTYPE html>
<html data-theme="light" lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Events</title>

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
                        <th class="text-white">Team 1</th>
                        <th class="text-white">Match</th>
                        <th class="text-white">Team 2</th>
                        <th class="text-white">Type</th>
                        <th class="text-white">Time</th>
                        <th class="text-white">Status</th>
                        <th class="text-white">Update To</th>
                        <th class="text-white">Action</th>
                        <th class="text-white">Match Link 1</th>
                        <th class="text-white">Match Link 2</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $index = 1;
                    while ($row = $res->fetch_assoc()) {
                        echo "<tr>";
                        echo "<th>" . $index . "</th>";
                        echo "<td ><img src='" . $imageBaseUrl . "/upload/" . $row['left_img'] . "' alt='image' width='40' height='40' /></td>";
                        echo "<td class='min-w-24'>" . $row['name'] . "</td>";
                        echo "<td ><img src='" . $imageBaseUrl . "/upload/" . $row['right_img'] . "' alt='image' width='40' height='40' /></td>";
                        echo "<td class='min-w-24'>" . $row['type'] . "</td>";
                        echo "<td class='mr-auto min-w-32'><span class='text-center'>
                        " . $row['time'] . '<br/>' . $row['date'] . "
                        </span></td>";
                        echo "<td class='font-semibold text-red-600'>" . ($row['isLive'] == 1 ? 'Live' : 'Upcoming') . "</td>";
                        echo "<td>
                        <a href='update_events.php?id=" . $row['id'] . "' class='text-white py-2 px-3 bg-green-400 rounded'>" . ($row['isLive'] == 1 ? 'Upcoming' : 'Live') . "</a>
                        </td>";
                        echo "<td>
                        <a href='delete_events.php?id=" . $row['id'] . "' class='text-white py-2 px-3 bg-red-500 rounded'>Delete</a>
                        </td>";
                        echo "<td>" . (strlen($row['link1']) > 100 ? substr($row['link1'], 0, 100) . '...' : $row['link1']) . "</td>";
                        echo "<td>" . (strlen($row['link2']) > 100 ? substr($row['link2'], 0, 100) . '...' : $row['link2']) . "</td>";
                        echo "</tr>";
                        $index++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <footer class="relative">
        <p class="h-[65px] w-16 bg-blue-800 text-white text-2xl cursor-pointer rounded-full flex items-center justify-center absolute right-8 bottom-0" onclick="my_modal_5.showModal()">
            <span>+</span>
        </p>

        <dialog id="my_modal_5" class="modal modal-bottom sm:modal-middle">
            <div class="modal-box">
                <form method="POST" enctype="multipart/form-data">

                    <button id="closeModal" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>

                    <h3 class="text-lg font-bold">Add Events</h3>


                    <div class="form-control mt-4">
                        <input name="matchName" type="text" placeholder="Enter Match Name (Ban Vs SL)" class="input input-bordered bg-transparent border-1 border-black text-black" required />
                    </div>

                    <div class="form-control mt-4">
                        <input name="matchType" type="text" placeholder="Enter Match Type (Semi final)" class="input input-bordered bg-transparent border-1 border-black text-black" required />
                    </div>

                    <div class="form-control mt-4">
                        <input name="matchTime" type="text" placeholder="Enter Match Time (2pm)" class="input input-bordered bg-transparent border-1 border-black text-black" required />
                    </div>

                    <div class="form-control mt-4">
                        <input name="matchDate" type="text" placeholder="Enter Match Date (04/07/2024)" class="input input-bordered bg-transparent border-1 border-black text-black" required />
                    </div>


                    <div>
                        <select name="matchIsLive" class="select select-secondary w-full mt-4" required>
                            <option disabled selected>Select Match Status</option>
                            <option>Live</option>
                            <option>Upcoming</option>
                        </select>
                    </div>

                    <div class="form-control mt-4">
                        <input name="matchLink1" type="text" placeholder="Enter Match Link 1" class="input input-bordered bg-transparent border-1 border-black text-black" required />
                    </div>

                    <div class="form-control mt-4">
                        <input name="matchLink2" type="text" placeholder="Enter Match Link 2" class="input input-bordered bg-transparent border-1 border-black text-black" required />
                    </div>


                    <div class="form-control mt-4">
                        <label class="label">
                            <span class="label-text text-black">Choose Team 1 Icon</span>
                        </label>
                        <input name="team1Img" type="file" class="file-input file-input-bordered file-input-primary w-full" />
                    </div>


                    <div class="form-control mt-4">
                        <label class="label">
                            <span class="label-text text-black">Choose Team 2 Icon</span>
                        </label>
                        <input name="team2Img" type="file" class="file-input file-input-bordered file-input-primary w-full" />
                    </div>


                    <button name="submit" type="submit" class="btn mt-5">Add</button>
                </form>
            </div>
        </dialog>
    </footer>

</body>


<script>
    const closeModalBtn = document.getElementById('closeModal');
    document.getElementById('navTitle').innerText = "Events";

    const modal = document.getElementById('my_modal_5');
    closeModalBtn.addEventListener('click', () => {
        modal.close();
    });
</script>




</html>