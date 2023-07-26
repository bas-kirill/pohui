<?php

$host = $_SERVER["DOCUMENT_ROOT"];
require_once $host . "/log/log.php";
require_once $host . "/util/functions.php";
require_once $host . "/db/db.php";

global $connection;

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
} else {
    $adminSectionPanelDiv = "";
}

// check for security that item in users order
$creationTs = $_GET["edit-order"];
$booksWithUsernameAndBookIdSQL = "
        select b.book_id, b.isbn_10, b.title, b.price, b.description, c.category, o.book_position from amazon.orders o
        inner join amazon.users u on o.user_id = u.user_id
        inner join amazon.books b on o.book_id = b.book_id
        inner join amazon.categories c on b.category_id = c.category_id
        where u.username = '$username' and o.order_creation_ts = '$creationTs'
    ";

$totalGoods = 0;
$totalPrice = 0;
$result = $connection->query($booksWithUsernameAndBookIdSQL);
if ($result->num_rows == 0) {
    $dynamicPanel = "<div>Your order is empty</div>";
} else {
    $bookDivs = array();
    while ($row = $result->fetch_assoc()) {
        $bookId = $row["book_id"];
        $isbn = $row["isbn_10"];
        $title = $row["title"];
        $price = $row["price"];
        $description = $row["description"];
        $category = $row["category"];
        $bookPosition = $row["book_position"];
        $bookDiv = "
                <div>
                    Title: $title; Price: $price; Category: $category; Description: $description; Position: $bookPosition
                    <form method='post'>
                        <input type='hidden' name='book-id' value='$bookId'>
                        <input type='hidden' name='book-position' value='$bookPosition'>
                        <input type='hidden' name='creation-ts' value='$creationTs'>
                        <input type='submit' value='Delete'>
                    </form>
                </div>";
        $bookDivs[] = $bookDiv;
        $totalGoods++;
        $totalPrice += $price;
    }
    $orderDivsHtml = implode(" ", $bookDivs);

    $cartSummary = "
        <div>
            <div>Total Goods: $totalGoods</div>
            <div>Total Price: $totalPrice</div>
        </div>";

    $dynamicPanel = "
            <div>Orders:</div>
            $orderDivsHtml
            $cartSummary
        ";
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
            $dynamicPanel
        </div>
    </div>
_END;

?>