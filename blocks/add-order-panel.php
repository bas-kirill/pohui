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

if ($sessionRoleName === "admin") {
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
               <hr>
               <input type='submit' name='books' value='Books'>
               <br>
               <input type='submit' name='add-book' value='Add Book'>
               <br>
               <input type='submit' name='edit-book' value='Edit Book'>
               <br>
               <input type='submit' name='delete-book' value='Delete Book'>
               <hr>
               <input type='submit' name='orders' value='Orders'>
               <br>
               <input type='submit' name='add-order' value='Add Order'>
               <br>
               <input type='submit' name='edit-order' value='Edit Order'>
               <br>
               <input type='submit' name='delete-order' value='Delete Order'>
               <br>
            </form>
        </div>";
}

$selectAllCategories = "select category from amazon.categories";
$result = $connection->query($selectAllCategories);
$categoryOptions = array();
while ($row = $result->fetch_assoc()) {
    $categoryName = $row["category"];
    $categoryOption = "<option value='$categoryName'>$categoryName</option>";
    $categoryOptions[] = $categoryOption;
}
$categoryOptionsHtml = "\"" . implode(" ", $categoryOptions) . "\"";

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
            <form id="username-form" method="post">
                Username: <input type="text" name="username" required>
                <input type="submit" value="Find">
            </form>
            <hr>
            
            <script>
                const usernameForm = document.getElementById('username-form');
                usernameForm.addEventListener('submit', function (findUserEvent) {
                    findUserEvent.preventDefault();
                    var usernameData = new FormData(this);
                    fetch("/blocks/find-user-by-username.php", {
                        method: 'POST',
                        body: usernameData
                    })
                    .then(response => {
                        if (response.status == 200) {
                            alert('Username found!')
                            return response.json();
                        } else if (response.status == 400) {
                            alert('Bad Request');
                            throw new Error('Bad Request: ' + response);
                        } else if (response.status == 404) {
                             alert('Username not Found');
                             throw new Error('Not Found: ' + response);
                        } else if (response.status == 500) {
                            alert('Internal Server Error');
                            throw new Error('Internal Server Error: ' + response);
                        } else {
                            throw new Error('Unexpected HTTP response: ' + response.status);
                        }
                    })
                    .then(data => {
                        var orderName = document.createElement('h3');
                        orderName.textContent = 
                    })
                    .catch(error => console.error('Error:', error));
                });
            </script>
        </div>
    </div>
_END;
