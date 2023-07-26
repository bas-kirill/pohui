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
               <hr>
               <input type='submit' name='books' value='Books'>
               <br>
               <input type='submit' name='add-book' value='Add Book'>
               <br>
               <input type='submit' name='edit-book' value='Edit Book'>
               <br>
               <input type='submit' name='delete-book' value='Delete Book'>
               <br>
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
            <form id="delete-user-by-username-form" method='post'>
                Username: <input type='text' name='username'>
                <input type='submit' value='Delete'>
            </form>
            
            <script>
                const deleteUserByUsernameForm = document.getElementById('delete-user-by-username-form');
                deleteUserByUsernameForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const data = new FormData(this);
                    console.log(data.get('username'));
                    fetch('/blocks/delete-user.php', {
                        method: 'POST',
                        body: data
                    })
                    .then(response => {
                        if (response.status === 200) {
                            alert('User deleted');
                        } else if (response.status === 404) {
                            alert('User does not exists');
                            throw new Error('User does not exists: ' + response);
                        } else if (response.status === 500) {
                            alert('Server Error');
                            throw new Error('Server error: ' + response);
                        } else {
                            alert('Unexpected Error');
                            throw new Error('Unexpected HTTP response: ' + response.status);
                        }
                    })
                    .catch(error => console.error('Error:', error))
                });
            </script>
        </div>
    </div>
_END;
