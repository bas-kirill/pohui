<?php

$host = $_SERVER["DOCUMENT_ROOT"];
require_once $host . "/log/log.php";
require_once $host . "/util/functions.php";
require_once $host . "/db/db.php";

if (isset($_SESSION["username"])) {
    $loggedIn = true;
    $name = $_SESSION["name"];
    $username = $_SESSION["username"];
    $userId = $_SESSION["user_id"];
    $roleType = $_SESSION["role_type"];
} else {
    $loggedIn = false;
}

if (!$loggedIn) {
    $dynamicPanel = "<div>Need to log in to see page</div>";
    return;
}

if ($roleType == "admin") {
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

$dynamicPanel = "
        <form method='post'>
            Name: <input type='text' name='add-users-name' required>
            Username: <input type='text' name='add-users-username' required>
            Password: <input type='text' name='add-users-password' required>
            Role: <input type='text' name='add-users-role-type' required>    <!-- Set up enum values -->
            Address: <input type='text' name='add-users-address' required>
            <input type='submit' value='Submit'>
        </form>
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
                <div><a href="http://localhost:8888/web/account.php?edit=$username">Edit Profile</a></div>
                <div><a href="http://localhost:8888/web/account.php?delete=$username">Delete Profile</a></div>
                <form action="logout.php" method="post">
                    <input type="submit" value="Log out">
                </form>
            </div>
            $adminSectionPanelDiv
        </div>
        <div id="orders-panel">
            <div>Name: $name; Username: $username; Role Type: $roleType</div>
            <hr>
            $dynamicPanel
        </div>
    </div>
_END;
