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
            <form id='add-book-form' method='post'>
                Title: <input type='text' name='title' required>
                <br>
                Description: <input type='text' name='description' required>
                <br>
                Price: <input type='number' name='price' required>
                <br>
                ISBN: <input type="text" name="isbn" required>
                <br>
                Category: <input type='text' name='category' required>
                <br>
                <input type='submit' value='Add'>
            </form>
            
            <script>
                const addBookForm = document.getElementById('add-book-form');
                addBookForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const body = new FormData(this);
                    fetch('/blocks/add-book.php', {
                        method: 'POST',
                        body: body
                    })
                    .then(response => {
                        if (response.status === 200) {
                            alert('Added successfully');
                            return response.json();
                        } else if(response.status === 400) {
                            alert("Incorrect Request");
                            throw new Error('Bad Request:' + response);
                        } else if (response.status === 409) {
                            alert('Book exists');
                            throw new Error('Conflict Error:' + response);
                        } else if (response.status === 500) {
                            alert('Server Error');
                            throw new Error('Server Error:' + response);
                        } else {
                            throw new Error('Unexpected HTTP response: ' + response.status);
                        }
                    })
                    .then(data => {
                        console.log(data);
                    })
                    .catch(error => {
                        console.error('Error: ', error);
                    });
                });
            </script>
        </div>
    </div>
_END;
