<?php

$host = $_SERVER["DOCUMENT_ROOT"];
require_once $host . "/log/log.php";
require_once $host . "/util/functions.php";
require_once $host . "/db/db.php";

global $connection;

if (isset($_SESSION["username"])) {
    $loggedIn = true;
    debugToConsole($_SESSION);
    $sessionName = $_SESSION["name"];
    $sessionUsername = $_SESSION["username"];
    $sessionUserId = $_SESSION["user_id"];
    $sessionRoleType = $_SESSION["role_type"];
} else {
    $loggedIn = false;
}

if (!$loggedIn) {
    echo "<div>Need to log in to see page</div>";
    return;
}

if ($sessionRoleType == "admin") {
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

$selectAllBooksSQL = "
    select book_id, title, description, price, creation_timestamp, isbn_10, category
    from amazon.books
    inner join amazon.categories on books.category_id = categories.category_id";
$result = $connection->query($selectAllBooksSQL);

$bookDivs = array();
while ($row = $result->fetch_assoc()) {
    $bookId = $row["book_id"];
    $title = $row["title"];
    $description = $row["description"];
    $price = $row["price"];
    $creationTs = $row["creation_timestamp"];
    $isbn = $row["isbn_10"];
    $category = $row["category"];
    $bookDiv = "
        <div>
            Book Id: $bookId; Title: $title; Description: $description; Price: $price; Creation Ts: $creationTs; ISBN: $isbn; Category: $category
        </div>
    ";
    $bookDivs[] = $bookDiv;
}

$userDivsHtml = implode(" ", $bookDivs);

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
            <div>Name: $sessionName; Username: $sessionUsername; Role Type: $sessionRoleType</div>
            <hr>
            <div>
                $userDivsHtml
            </div>
        </div>
    </div>
_END;
