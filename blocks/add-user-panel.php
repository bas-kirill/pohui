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
    echo "<div>Need to log in to see page</div>";
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
               <input type='submit' name='delete-order' value='Edit Order'>
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
                <input type='submit' value='Add'>
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
                    .then(response => {
                        if (response.status === 200) {
                            console.log('Success', response);
                            alert('Created successfully');
                            return response.json();
                        } else if (response.status === 409) {
                            console.error('Conflict error:', response);
                            alert('User exists');
                        } else if (response.status === 500) {
                            console.error('Server error:', response);
                            alert('Error has occured');
                        } else {
                            throw new Error('Unexpected HTTP response: ' + response.status);
                        }
                    })
                    .then(data => {
                        
                    })
                    .catch(error => {
                        console.error('Error: ', error);
                    });
                });
            </script>
        </div>
    </div>
_END;
