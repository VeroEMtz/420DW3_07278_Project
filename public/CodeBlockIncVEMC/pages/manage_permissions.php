<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Permissions Management</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1>User Permissions Management</h1>
    <!-- Form for permission inputs -->
    <form id="permissionForm">
        <div class="form-group">
            <label for="userSelect">Select User</label>
            <select class="form-control" id="userSelect">
                <!-- Populate with existing users -->
                <!-- You may fetch this dynamically from the server -->
                <option>User 1</option>
                <option>User 2</option>
                <!-- Add more options as needed -->
            </select>
        </div>
        <div class="form-group">
            <label for="permissionSelect">Select Permission</label>
            <select class="form-control" id="permissionSelect">
                <!-- Populate with available permissions -->
                <option>Permission 1</option>
                <option>Permission 2</option>
                <!-- Add more options as needed -->
            </select>
        </div>
        <!-- Buttons for CRUD operations -->
        <button type="button" class="btn btn-primary" id="createPermBtn">Create</button>
        <button type="button" class="btn btn-success" id="updatePermBtn">Update</button>
        <button type="button" class="btn btn-danger" id="deletePermBtn">Delete</button>
        <button type="button" class="btn btn-info" id="listPermBtn">List All Permissions</button>
    </form>
    <div id="permissionList"></div>
</div>

<!-- Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Your custom JavaScript for handling button actions and AJAX requests -->
<script src="your_script.js"></script>

</body>
</html>
