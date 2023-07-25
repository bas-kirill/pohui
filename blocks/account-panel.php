<?php

require_once "../log/log.php";
require_once "../util/functions.php";
require_once "../db/db.php";
require_once "../blocks/pojo.php";

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
    debugToConsole("not logged in");
    echo "<div>You need to log to see page</div>";
    return;
}

if (isset($_POST["edit-address"]) && isset($_POST["edit-name"])) {
    $address = $_POST["edit-address"];
    $name = $_POST["edit-name"];

    debugToConsole($address);
    debugToConsole($name);
    debugToConsole($username);

    if ($address == "" && $name == "") {
        echo "<div>Submitted empty fields</div>";
        return;
    }

    if ($address != "" && $name != "") {
        $updateUserProfileSQL = "
            update amazon.users set name = '$name', delivery_address='$address' 
            where username = '$username'";

        $result = queryMySql($updateUserProfileSQL);
        echo "<div>User profile with username='$username' updated with new address '$address' and name '$name'</div>";
        return;
    }

    if ($address != "") {
        $updateUserProfileSQL = "update amazon.users set delivery_address = '$address' where username = '$username'";
        $result = queryMySql($updateUserProfileSQL);
        echo "<div>User profile with username='$username' updated with new address '$address'</div>";
        return;
    }

    if ($name != "") {
        $updateUserProfileSQL = "update amazon.users set name = '$name' where username = '$username'";
        $result = queryMySql($updateUserProfileSQL);
        echo "<div>User profile with username='$username' u;dated with new name '$name'</div>";
        return;
    }

    return;
}

if (isset($_POST["delete-approved"])) {
    $deleteUserProfileSQL = "delete from amazon.users where username = '$username'";
    $result = queryMySql($deleteUserProfileSQL);
    echo "<div>User with username='$username' deleted</div>";
    destroySession();
    return;
}

if (isset($_POST["add-users-name"])) {
    $name = $_POST["add-users-name"];
    $username = $_POST["add-users-username"];
    $password = $_POST["add-users-password"];
    $address = $_POST["add-users-password"];
    $roleType = $_POST["add-users-role-type"];

    debugToConsole($name);

    $addNewUserSQL = "
        insert into amazon.users (`name`, username, `password`, delivery_address, role_type) 
        value ('$name', '$username', '$password', '$address', '$roleType')";

    log_debug($addNewUserSQL);

    $result = queryMySql($addNewUserSQL);
    echo "<div>Created new users with name='$name', username='$username', password='$password', address='$address', role='$roleType'</div>";
    return;
}

if (isset($_POST["edit-users-name"])) {
    $name = $_POST["add-users-name"];
    $username = $_POST["add-users-username"];
    $password = $_POST["add-users-password"];
    $address = $_POST["add-users-password"];
    $roleType = $_POST["add-users-role-type"];

    // todo: сделать проверки
    $addNewUserSQL = "
        update amazon.users set name = '$name', password = '$password',
                                delivery_address = '$address', role_type = '$roleType'
        where username = '$username';
    ";

    $result = queryMySql($addNewUserSQL);
    echo "<div>Created new users with name='$name', username='$username', password='$password', address='$address', role='$roleType'</div>";
    return;
}

if (isset($_POST["delete-users-username"])) {
    $username = $_POST["delete-users-username"];
    $deleteUserSQL = "delete from amazon.users where username = '$username'";
    $result = queryMySql($deleteUserSQL);
    echo "<div>Deleted users='$username'</div>";
    return;
}

if (isset($_POST["add-book-title"])) {
    $title = $_POST["add-book-title"];
    $description = $_POST["add-book-description"];
    $price = $_POST["add-book-price"];
    $categoryId = $_POST["add-book-category-id"];
    $addNewBookSQL = "
        insert into amazon.books (title, description, price, creation_timestamp, category_id)
        values ('$title', '$description', $price, 'current_timestamp', $categoryId)";
    $result = queryMySql($addNewBookSQL);
    echo "<div>Created new book with title = '$title'</div>";
    return;
}

if (isset($_POST["edit-book"])) {
    return;
}

if (isset($_POST["delete-book"])) {
    $title = $_POST["delete-book-title"];
    $deleteBookSQL = "delete from amazon.books where title = '$title'";
    $result = queryMySql($deleteBookSQL);
    echo "<div>Deleted book with title '$title'</div>";
    return;
}

if (isset($_POST["book-id"]) && isset($_POST["book-position"]) && isset($_POST["creation-ts"])) {
    $bookId = $_POST["book-id"];
    $bookPosition = $_POST["book-position"];
    $orderCreationTs = $_POST["creation-ts"];
    $deleteSelectedBookIdsSQL = "
        delete from amazon.orders
        where user_id = '$userId' and
              order_creation_ts = '$orderCreationTs' and
              book_id = '$bookId' and 
              book_position = '$bookPosition'";
    $result = queryMySql($deleteSelectedBookIdsSQL);
}

$adminSectionPanelDiv = "";
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

if ($_GET["edit"]) {
    $dynamicPanel = "
        <div>Edit '$username' Profile</div>
        <form method='post'>
            <span class='fieldname'>Address:</span><input type='text' name='edit-address'>
            <span class='fieldname'>Name:</span><input type='text' name='edit-name'>
            <input type='submit' value='Submit'>
        </form>
    ";
} else if ($_GET["delete"]) {
    $dynamicPanel = "
        <span>Are you sure that you want to delete account?</span>
        <form method='post'>
            <input type='submit' name='delete-approved' value='Yes'>
        </form>
    ";
} else if ($_GET["edit-users"]) {

} else if (isset($_GET["delete-users"])) {

} else if (isset($_GET["add-book"])) {

} else if (isset($_GET["edit-book"])) {

} else if (isset($_GET["delete-book"])) {

} else if (isset($_GET["edit-order"])) {

} else {
    $ordersForUserSQL = "
        select b.book_id, b.isbn_10, b.title, b.description, b.price, c.category, o.order_creation_ts, o.book_position
        from amazon.orders o
        inner join amazon.books b on o.book_id = b.book_id
        inner join amazon.users u on o.user_id = u.user_id
        inner join amazon.categories c on b.category_id = c.category_id
        where u.username = '$username'
        order by o.order_creation_ts desc
    ";

    $totalGoods = 0;
    $totalPrice = 0;
    $result = queryMySql($ordersForUserSQL);
    if ($result->num_rows == 0) {
        $dynamicPanel = "<div>Your order is empty</div>";
    } else {
        $orderDivs = array();
        $creationTsToBooks = array();
        while ($row = $result->fetch_assoc()) {
            $isbn = $row["isbn_10"];
            $title = $row["title"];
            $price = $row["price"];
            $description = $row["description"];
            $category = $row["category"];
            $creationTs = $row["order_creation_ts"];
            $position = $row["book_position"];

            $book = new Book($isbn, $title, $price, $description, $category, $position);
            if (isset($creationTsToBooks[$creationTs]) && !in_array($creationTs, $creationTsToBooks)) {
                $creationTsToBooks[$creationTs][] = $book;
            } elseif (!isset($creationTsToBooks[$creationTs])) {
                $creationTsToBooks[$creationTs] = array($book);
            }
        }

        foreach ($creationTsToBooks as $creationTs => $books) {
            usort($books, function ($lhs, $rhs) {
                return $lhs->position < $rhs->position;
            });
        }

        $totalGoods = 0;
        $totalPrice = 0;
        $orderIdx = 1;
        $orderDivs = array();
        foreach ($creationTsToBooks as $creationTs => $books) {
            $bookDivs = array();
            $orderTotalGoods = 0;
            $orderTotalPrice = 0;
            $bookIdx = 1;
            foreach ($books as $book) {
                $bookDiv = "
                    <div>
                       $bookIdx) ISBN: $book->isbn; Title: $book->title; Price: $book->price; Category: $book->category; Description: $book->description; Position: $book->position;
                    </div>
                ";
                $bookDivs[] = $bookDiv;
                $orderTotalGoods++;
                $orderTotalPrice += $book->price;
                $bookIdx++;
            }
            $totalGoods += $orderTotalGoods;
            $totalPrice += $orderTotalPrice;

            $bookDivsHtml = implode(" ", $bookDivs);

            $orderDiv = "
                <div>
                    <div>Order #$orderIdx (Creation time: $creationTs):</div>
                    <div>
                        $bookDivsHtml
                    </div>
                    <div>
                        <form method='get'>
                            <input type='hidden' name='edit-order' value='$creationTs'>
                            <input type='submit' value='Edit Order'>
                        </form>
                    </div>
                    <div>
                        <div>Order Total Goods: $orderTotalGoods</div>
                        <div>Order Total Price: $orderTotalPrice</div>
                    </div>
                    <hr>
                </div>
            ";
            $orderDivs[] = $orderDiv;
            $orderIdx++;
        }

        $orderDivsHtml = implode(" ", $orderDivs);

        $dynamicPanel = "
            <div><h3>Orders:</h3></div>
            <div>
                $orderDivsHtml
            </div>
            <div>
                <div>Total Goods: $totalGoods</div>
                <div>Total Price: $totalPrice</div>
            </div>
        ";
    }
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