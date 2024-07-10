<?php
session_start();
include "header.php";
include "auth/connect.php";

$userEmail = $_SESSION['userEmail'];

$conn = connect();

if (empty($userEmail)) {
  header('location: login.php');
  exit();
}

if (isset($_POST['submit'])) {
  $appId = mysqli_real_escape_string($conn, $_POST['appId']);
  $adsId = mysqli_real_escape_string($conn, $_POST['adsId']);
  $marqueryNotice = mysqli_real_escape_string($conn, $_POST['marqueryNotice']);
  $appVersion = mysqli_real_escape_string($conn, $_POST['appVersion']);
  $appUpdateUrl = mysqli_real_escape_string($conn, $_POST['appUpdateUrl']);
  $notice = mysqli_real_escape_string($conn, $_POST['notice']);

  $sq = "UPDATE settings SET app_id='$appId', ad_id='$adsId', marquery_notice='$marqueryNotice', app_version='$appVersion', app_update_url='$appUpdateUrl', notice='$notice' WHERE id=1";
  $conn->query($sq);
}

$sql = "SELECT * FROM settings WHERE id=1";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
?>

<!DOCTYPE html>
<html data-theme="light" lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Settings</title>

  <!-- Css -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
</head>

<body class="bg-[#F1F4F8] min-h-screen text-black container mx-auto">
  <main class="bg-white mx-auto w-full m-3 mt-8 xs:w-[70%] max-w-[70%] shadow p-5 border-t-2 border-blue-800">
    <form method="POST">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Settings</h1>
      </div>

      <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="form-control">
          <label class="label">
            <span class="label-text text-black">Admob App Id</span>
          </label>
          <input name="appId" type="text" value="<?php echo $row['app_id']; ?>" class="input input-bordered bg-transparent border-2 border-black text-black" required />
        </div>

        <div class="form-control">
          <label class="label">
            <span class="label-text text-black">Admob Ads Id</span>
          </label>
          <input name="adsId" type="text" value="<?php echo $row['ad_id']; ?>" class="input input-bordered bg-transparent border-2 border-black text-black" required />
        </div>

        <div class="form-control">
          <label class="label">
            <span class="label-text text-black">Live Notice</span>
          </label>
          <input name="marqueryNotice" type="text" value="<?php echo $row['marquery_notice']; ?>" class="input input-bordered bg-transparent border-2 border-black text-black" required />
        </div>

        <div class="form-control">
          <label class="label">
            <span class="label-text text-black">App Version</span>
          </label>
          <input name="appVersion" type="number" value="<?php echo $row['app_version']; ?>" class="input input-bordered bg-transparent border-2 border-black text-black" required />
        </div>

        <div class="form-control">
          <label class="label">
            <span class="label-text text-black">App Update Link</span>
          </label>
          <input name="appUpdateUrl" type="text" value="<?php echo $row['app_update_url']; ?>" class="input input-bordered bg-transparent border-2 border-black text-black" required />
        </div>

        <div class="form-control">
          <label class="label">
            <span class="label-text text-black">Notice</span>
          </label>
          <textarea class="textarea textarea-secondary bg-transparent" placeholder="notice" rows="1" name="notice"><?php echo $row['notice']; ?></textarea>
        </div>

        <div class="form-control mt-6">
          <button type="submit" name="submit" class="bg-blue-800 py-2 shadow rounded text-white">
            Save
          </button>
        </div>
      </section>
    </form>
  </main>
</body>

</html>