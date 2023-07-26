<?php

$host = $_SERVER["DOCUMENT_ROOT"];
require_once $host . "/log/log.php";
require_once $host . "/util/functions.php";
require_once $host . "/db/db.php";

global $connection;

if (isset($_SESSION["username"])) {
    $loggedIn = true;
    $sessionName = $_SESSION["name"];
    $sessionUsername = $_SESSION["username"];
    $sessionUserId = $_SESSION["user_id"];
    $sessionRoleName = $_SESSION["role_type"];
} else {
    $loggedIn = false;
}

if (!$loggedIn) {
    $dynamicPanel = "<div>Need to log in to see page</div>";
    return;
}

if ($sessionRoleName == "admin") {
    $adminSectionPanelDiv = "
        <div id='admin-actions-panel'>
            <form action='/web/navigate.php' method='post'>
               <input type='submit' name='users' value='Users'>
               <br>
               <input type='submit' name='add-users' value='Add User'>
               <br>
               <input type='submit' name='edit-users' value='Edit User'>
               <br>
               <input type='submit' name='delete-users' value='Delete User'>
               <br>
               <input type='submit' name='add-book' value='Add Book'>
               <br>
               <input type='submit' name='edit-book' value='Edit Book'>
               <br>
               <input type='submit' name='delete-book' value='Delete Book'>
               <br>
            </form>
        </div>";
}

$selectAllRolesSQL = "select role_name from amazon.roles";
$result = $connection->query($selectAllRolesSQL);
$roleOptions = array();
while ($row = $result->fetch_assoc()) {
    $roleName = $row["role_name"];
    $roleOption = "<option value='$roleName'>$roleName</option>";
    $roleOptions[] = $roleOption;
}

$roleOptionsHtml = implode(" ", $roleOptions);

$dynamicPanel = "
        <form id='add-user-form' method='post'>
            Name: <input type='text' name='name' required>
            <br>
            Username: <input type='text' name='username' required>
            <br>
            Password: <input type='text' name='password' required>
            <br>
            Role: 
            <select name='role' required>
                $roleOptionsHtml
            </select>
            <br>
            Address: <input type='text' name='address' required>
            <br>
            <input type='submit' value='Submit'>
        </form>
        
        <script>
            const addUserForm = document.getElementById('add-user-form');
            addUserForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const data = new FormData(this);
                fetch('/blocks/add-user.php', {
                    method: 'POST',
                    body: data,
                })
                .then(data => {
                    console.log(data);
                    addUserForm.reset();
                    alert('Successfully created new user!');
                })
                .catch(error => {
                    console.error('Error: ', error);
                    alert('New user creation error');
                });
            });
        </script>
    ";

echo <<<_END
    <style>
        #account-panel {
            display: grid;
            background-color: burlywood;
        }
        
        #actions-panel {
            grid-column: 1;
            background-color: cornflowerblue;
        }
        
        #customer-actions-panel {
            background-color: burlywood;
        }
        
        #admin-actions-panel {
            background-color: coral;
        }
        
        #orders-panel {
            grid-column: 2;
            background-color: aliceblue;
        }
    </style>
    
    <div id="account-panel">
        <div id="actions-panel">
            <div id="customer-actions-panel">
                <div><a href="http://localhost:8888/web/account.php?edit=$sessionUsername">Edit Profile</a></div>
                <div><a href="http://localhost:8888/web/account.php?delete=$sessionUsername">Delete Profile</a></div>
                <form action="logout.php" method="post">
                    <input type="submit" value="Log out">
                </form>
            </div>
            $adminSectionPanelDiv
        </div>
        <div id="orders-panel">
            <div>Name: $sessionName; Username: $sessionUsername; Role Type: $sessionRoleName</div>
            <hr>
            $dynamicPanel
        </div>
    </div>
_END;