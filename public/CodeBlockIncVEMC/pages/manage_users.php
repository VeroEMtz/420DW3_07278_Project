<?php


declare(strict_types=1);

use CodeBlockIncVEMC\src\services\LoginService;
use CodeBlockIncVEMC\src\controllers\UsersController;
use CodeBlockIncVEMC\src\services\UsersService;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1>User Management</h1>
    <!-- Form for user inputs -->
    <form id="userForm">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" placeholder="Enter username">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Enter password">
        </div>
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" placeholder="Enter email">
        </div>
        <!-- Buttons for CRUD operations -->
        <button type="button" class="btn btn-primary" id="createUsrBtn">Create</button>
        <button type="button" class="btn btn-success" id="updateUsrBtn">Update</button>
        <button type="button" class="btn btn-danger" id="deleteUsrBtn">Delete</button>
        <button type="button" class="btn btn-info" id="listUsrBtn">List All Users</button>
    </form>
    <div id="userList"></div>
</div>

<!-- Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= WEB_JS_DIR . "userspage.js" ?>" defer></script>

<!--
<script type="text/javascript" src="<?= WEB_JS_DIR . "jquery-3.7.1.min.js" ?>" defer></script>
<script type="text/javascript" src="<?= WEB_JS_DIR . "teacher.standard.js" ?>" defer></script>
<script type="text/javascript" src="<?= WEB_JS_DIR . "teacher.page.authors.js" ?>" defer></script>
-->

<script>
    $(document).ready(function () {
        loadUsers();
    });
    
    function loadUsers() {
        $_get('users.php?action=getAll', function (data) {
            var userList = $('#userList');
            userList.empty();
            $.each(data, function (index, user) {
                userList.append('<div class="user" onclick="selectUser(' + user.usrId + ')">' + user.usrName + '</div>');
            });
        });
    }
    function selectUser(userId) {
    }
</script>
</body>
</html>
