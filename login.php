<?php

session_start();
$_SESSION['userEmail'] = '';

include "auth/connect.php";

$conn = connect();

$m = '';


if (isset($_POST["submit"])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $passInput = mysqli_real_escape_string($conn, $_POST['pass']);
    $pass = md5($passInput);

    $sql = "SELECT * FROM users WHERE email = '$email' AND pass = '$pass'";
    $res = $conn->query($sql);

    if (mysqli_num_rows($res) == 1) {
        $user = mysqli_fetch_assoc($res);
        $_SESSION['userEmail'] = $user['email'];

        header('Location: events.php');
    } else {
        $m = 'Credentials mismatch!';
    }
}


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>

    <!-- Css -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="container mx-auto min-h-screen bg-white">
    <main class="flex justify-center items-center  min-h-[60vh] sm:min-h-screen">
        <div class="card  w-full max-w-xs sm:max-w-sm shrink-0 shadow-2xl">
            <div>
                <h5 class="text-2xl font-bold text-black text-center mt-8">Login Now</h5>
                <p class="text-lg font-bold text-red-400 text-center mt-8"><?php if ($m != '') echo $m; ?></p>

            </div>

            <form class="card-body" method="POST">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Email</span>
                    </label>
                    <input name="email" id="uname" type="email" placeholder="Enter your email"
                        class="input input-bordered bg-white border-2 border-black/20 text-black" required />
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Password</span>
                    </label>
                    <input name="pass" type="password" placeholder="Enter your password"
                        class="input input-bordered bg-white border-2 border-black/20 text-black" required />
                    <label class="label mt-3 underline">
                        <a href="signup.php" class="label-text-alt link link-hover">Create new account?</a>
                    </label>
                </div>
                <div class="form-control mt-6">
                    <button type="submit" name="submit" class="btn btn-primary bg-blue-500 text-white">Login</button>
                </div>
            </form>
        </div>
        </div>
    </main>
</body>

</html>