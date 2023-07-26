<?php

$host = $_SERVER["DOCUMENT_ROOT"];
require_once $host . "/log/log.php";
require_once $host . "/util/functions.php";
require_once $host . "/db/db.php";

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
            <form id="delete-book-by-isbn-form" method='post'>
                ISBN: <input type='text' name='isbn'>
                <input type='submit' value='Delete'>
            </form>
            
            <script>
                const deleteBookByISBNForm = document.getElementById('delete-book-by-isbn-form');
                deleteBookByISBNForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const data = new FormData(this);
                    fetch('/blocks/delete-book.php', {
                        method: 'POST',
                        body: data
                    })
                    .then(response => {
                        if (response.status === 200) {
                            alert('Book deleted');
                        } else if (response.status === 400) {
                            alert('Bad Request');
                            throw new Error('Bad Request:' + response);
                        } else if (response.status === 404) {
                            alert('Book does not exists');
                            throw new Error('User does not exists: ' + response);
                        } else if (response.status === 500) {
                            alert('Server Error');
                            throw new Error('Server error: ' + response);
                        } else {
                            alert('Unexpected Error');
                            throw new Error('Unexpected HTTP response: ' + response.status);
                        }
                    })
                    .then(data => console.log(data))
                    .catch(error => console.error('Error:', error))
                });
            </script>
        </div>
    </div>
_END;
