<?php
declare(strict_types=1);
/**
 * 420DW3_07278_Project login.php
 * @author Veronica Martinez
 * @since  2024-04-30
 */

namespace VEMC;

//require_once '../../../private/src/CodeBlockIncVEMC/private/src/controllers/LoginController.php';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=chrome">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!--<link rel="stylesheet" href="public/css/bootstrap.min.css">
    <link rel="stylesheet" href="public/css/style.css">-->
</head>
<body>
<div class="container">
    <form action="../src/controllers/LoginController.php" method="post">
        <h1>Login System</h1>
        <div class="login-box">
            <label for="usrname">User name: </label>
            <input type="text" id="usrname" name="usrname" placeholder="introduce your user name" required><br>
        </div>
        <div class="login-box">
            <label for="password">Password: </label>
            <input type="password" id="password" name="password" placeholder="introduce your password" required><br>
        </div>
        <?php
        error_log ('Ready for the button.');
        ?>
        <button type="submit" class="btn">Login</button>
    </form>
</div>
</body>
</html>