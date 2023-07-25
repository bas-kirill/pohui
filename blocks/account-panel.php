<?php

require_once "../log/log.php";
require_once "../util/functions.php";
require_once "../db/db.php";
require_once "../blocks/pojo.php";

if (isset($_SESSION["username"])) {
    $loggedIn = true;
    $username = $_SESSION["username"];
} else {
    $loggedIn = false;
}

if (!$loggedIn) {
    debugToConsole("not logged in");
    die();
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

if (isset($_POST["add-user-name"])) {
    $name = $_POST["add-user-name"];
    $username = $_POST["add-user-username"];
    $password = $_POST["add-user-password"];
    $address = $_POST["add-user-password"];
    $roleType = $_POST["add-user-role-type"];

    debugToConsole($name);

    $addNewUserSQL = "
        insert into amazon.users (`name`, username, `password`, delivery_address, role_type, order_id) 
        value ('$name', '$username', '$password', '$address', '$roleType', null)";

    log_debug($addNewUserSQL);

    $result = queryMySql($addNewUserSQL);
    echo "<div>Created new user with name='$name', username='$username', password='$password', address='$address', role='$roleType'</div>";
    return;
}

if (isset($_POST["edit-user-name"])) {
    $name = $_POST["add-user-name"];
    $username = $_POST["add-user-username"];
    $password = $_POST["add-user-password"];
    $address = $_POST["add-user-password"];
    $roleType = $_POST["add-user-role-type"];

    // todo: сделать проверки
    $addNewUserSQL = "
        update amazon.users set name = '$name', password = '$password',
                                delivery_address = '$address', role_type = '$roleType'
        where username = '$username';
    ";

    $result = queryMySql($addNewUserSQL);
    echo "<div>Created new user with name='$name', username='$username', password='$password', address='$address', role='$roleType'</div>";
    return;
}

if (isset($_POST["delete-user-username"])) {
    $username = $_POST["delete-user-username"];
    $deleteUserSQL = "delete from amazon.users where username = '$username'";
    $result = queryMySql($deleteUserSQL);
    echo "<div>Deleted user with username = '$username'</div>";
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

if (isset($_POST["edit-order-delete-position"])) {
    $bookISBN = $_POST["edit-order-delete-position"];

    $selectOrderForUserWithPositionSQL = "
        select o.book_id from amazon.orders o
        inner join amazon.users u on o.user_id = u.user_id
        inner join amazon.books b on o.book_id = b.book_id
        where u.username = '$username' and b.isbn_10 = '$bookISBN'";

    $result = queryMySql($selectOrderForUserWithPositionSQL);
    $booksIds = array();
    while ($row = $result->fetch_assoc()) {
        $bookId = $row["book_id"];
        $booksIds[] = $bookId;
    }

    $bookIds = implode(", ", $booksIds);

    $deleteSelectedBookIdsSQL = "delete from amazon.orders where book_id in ($bookIds)";
    $result = queryMySql($deleteSelectedBookIdsSQL);
}


$selectUserSQL = "select name, username, role_type from amazon.users where username='$username'";

$result = queryMySql($selectUserSQL);

if ($result->num_rows == 0) {
    log_error("got 0 users by '$username', but user authorized!");
    die();
}

$row = $result->fetch_assoc();
$name = $row["name"];
$username = $row["username"];
$roleType = $row["role_type"];

$adminSectionPanelDiv = "";
if ($roleType == "admin") {
    $adminSectionPanelDiv = "
        <div id='admin-actions-panel'>
            <form method='get'><input type='submit' name='add-user' value='Add User'></form>
            <form method='get'><input type='submit' name='edit-user' value='Edit User'></form>
            <form method='get'><input type='submit' name='delete-user' value='Delete User'></form>
            <form method='get'><input type='submit' name='add-book' value='Add Book'></form>
            <form method='get'><input type='submit' name='edit-book' value='Edit Book'></form>
            <form method='get'><input type='submit' name='delete-book' value='Delete Book'></form>
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
} else if ($_GET["add-user"]) {
    $dynamicPanel = "
        <form method='post'>
            Name: <input type='text' name='add-user-name' required>
            Username: <input type='text' name='add-user-username' required>
            Password: <input type='text' name='add-user-password' required>
            Role: <input type='text' name='add-user-role-type' required>    <!-- Set up enum values -->
            Address: <input type='text' name='add-user-address' required>
            <input type='submit' value='Submit'>
        </form>
    ";
} else if ($_GET["edit-user"]) {
    $dynamicPanel = "
        <form method='post'>
            Name: <input type='text' name='edit-user-name'>
            Username: <input type='text' name='edit-user-username'>
            Password: <input type='text' name='edit-user-password'>
            Role: <input type='text' name='edit-user-role-type'>    <!-- Set up enum values -->
            Address: <input type='text' name='edit-user-address'>
            <input type='submit' value='Submit'>
        </form>
    ";
} else if (isset($_GET["delete-user"])) {
    $dynamicPanel = "
        <form method='post'>
            Username: <input type='text' name='delete-user-username'>
            <input type='submit' value='Submit'>
        </form>
    ";
} else if (isset($_GET["add-book"])) {
    $dynamicPanel = "
        <form method='post'>
            Title: <input type='text' name='add-book-title'>
            Description: <input type='text' name='add-book-description'>
            Price: <input type='number' name='add-book-price'>
            Category: <input type='number' name='add-book-category-id'>
            <input type='submit' value='Submit'>
        </form>
    ";
} else if (isset($_GET["edit-book"])) {
    // todo: сделать редактирование книжки
//    $dynamicPanel = "
//        <form method='post'>
//            Title: <input type='text' name='add-book-title'>
//            Description: <input type='text' name='add-book-description'>
//            Price: <input type='number' name='add-book-price'>
//            Category: <input type='number' name='add-book-category-id'>
//            <input type='submit' value='Submit'>
//        </form>
//    ";
} else if (isset($_GET["delete-book"])) {
    $dynamicPanel = "
        <form method='post'>
            Title: <input type='text' name='delete-book-title'>
        </form>
    ";
} else if (isset($_GET["edit-order"])) {
    // check for security that item in user order
    $bookId = $_GET["edit-order-book-id"];
    $booksWithUsernameAndBookIdSQL = "
        select b.isbn_10, b.title, b.price, b.description, c.category from amazon.orders o
        inner join amazon.users u on o.user_id = u.user_id
        inner join amazon.books b on o.book_id = b.book_id
        inner join amazon.categories c on b.category_id = c.category_id
        where u.username = '$username'
    ";

    $totalGoods = 0;
    $totalPrice = 0;
    $result = queryMySql($booksWithUsernameAndBookIdSQL);
    if ($result->num_rows == 0) {
        $dynamicPanel = "<div>Your order is empty</div>";
    } else {
        $bookDivs = array();
        while ($row = $result->fetch_assoc()) {
            $isbn = $row["isbn_10"];
            $title = $row["title"];
            $price = $row["price"];
            $description = $row["description"];
            $category = $row["category"];
            $bookDiv = "
                <div>
                    Title: $title; Price: $price; Category: $category; Description: $description
                    <form method='post'>
                        <input type='hidden' name='edit-order-delete-position' value='$isbn'>
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
} else {
    $ordersForUserSQL = "
        select b.book_id, b.isbn_10, b.title, b.description, b.price, c.category, o.creation_ts from amazon.orders o
        inner join amazon.books b on o.book_id = b.book_id
        inner join amazon.users u on o.user_id = u.user_id
        inner join amazon.categories c on b.category_id = c.category_id
        where u.username = '$username'
        order by o.creation_ts desc
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
            $creationTs = $row["creation_ts"];

            $book = new Book($isbn, $title, $price, $description, $category);
            if (isset($creationTsToBooks[$creationTs]) && !in_array($creationTs, $creationTsToBooks)) {
                $creationTsToBooks[$creationTs][] = $book;
            } elseif (!isset($creationTsToBooks[$creationTs])) {
                $creationTsToBooks[$creationTs] = array($book);
            }
        }

        $totalGoods = 0;
        $totalPrice = 0;
        $orderIdx = 1;
        $orderDivs = array();
        foreach ($creationTsToBooks as $creationTs => $books) {
            $bookDivs = array();
            $orderTotalGoods = 0;
            $orderTotalPrice = 0;
            foreach ($books as $book) {
                $bookDiv = "
                    <div>
                        ISBN: $book->isbn; Title: $book->title; Price: $book->price; Category: $book->category; Description: $book->description
                    </div>
                ";
                $bookDivs[] = $bookDiv;
                $orderTotalGoods++;
                $orderTotalPrice += $book->price;
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
                            <input type='submit' name='edit-order' value='Edit Order'
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