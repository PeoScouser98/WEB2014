<?php
require './lib/validate.php';
require './lib/execute_query.php';
include './lib/send-mail.php';

// send new password to user by email
if (isset($_POST['get-recovery-password']) && empty($error)) {
    $account = $_POST['account'];
    $email = $_POST['email'];
    $user = select_single_record("SELECT * FROM `users` WHERE `user_id` = '{$account}'");
    if (!is_null($user)) {
        if ($user['user_id'] == $account && $user['email'] == $email) {
            $newPassword = substr(md5(rand(0, 999999)), 0, 8);
            execute_query("UPDATE users SET user_password = '{$newPassword}' WHERE user_id = '{$account}'");
            send_password_mail($email, $newPassword);
        }
    }
}
$error = [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./node_modules/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="./styles/css/login.css">
    <link rel="stylesheet" href="./styles/css/main.css" />
    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/d2bf59f6fb.js" crossorigin="anonymous"></script>
    <script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>
</head>


<body>
    <div class=" d-flex justify-content-center align-items-center position-fixed top-0 w-100 h-100" style="background-image:linear-gradient(black, white)">
        <form action="./password-recover.php" method="POST" id="login-form" class="d-flex justify-content-center flex-column align-items-center p-5">
            <!-- title -->
            <h1 class="fw-light text-white text-center mb-5" style="font-size: 2.5em">Password Recovery</h1>
            <div class="d-flex justify-content-center flex-column align-items-center gap-2 w-100">
                <!-- account -->
                <div class="mb-3 w-100 w-100 h-auto">
                    <input type="text" class="form-control rounded-pill" name="account" id="" aria-describedby="helpId" placeholder="Account" />
                    <small class="text-danger fw-bold">
                        <?php
                        check_empty('account', 'account');
                        ?>
                    </small>
                </div>
                <!-- Email -->
                <div class="mb-3 w-100 w-100 h-auto">
                    <input type="email" class="form-control rounded-pill" name="email" id="" aria-describedby="helpId" placeholder="Email" />
                    <small class="text-danger fw-bold">
                        <?php
                        check_empty('email', 'email');
                        check_email('email');
                        ?>
                    </small>
                </div>

                <div class="mb-3 w-100 w-100 h-auto d-flex justify-content-center">
                    <input type="submit" class="form-control btn rounded" name="get-recovery-password" id="submit-btn" value="Get New Password" />
                </div>
            </div>
            <div class="mt-3">
                <span class="text-muted">Already have an account?</span>
                <a href="./login.php" class="text-white">Login</a>
            </div>
        </form>
    </div>
</body>

</html>
<?php

//These must be at the top of your script, not inside a function