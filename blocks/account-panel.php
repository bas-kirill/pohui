<?php

require_once "../log/log.php";
require_once "../util/functions.php";
require_once "../db/db.php";

if (isset($_SESSION["username"])) {
    $loggedIn = true;
    $username = $_SESSION["username"];
} else {
    $loggedIn = false;
}

if (!$loggedIn) {
    debutToConsole("not logged in");
    die();
}

if (isset($_POST["edit-address"]) && isset($_POST["edit-name"])) {
    $address = $_POST["edit-address"];
    $name = $_POST["edit-name"];

    debutToConsole($address);
    debutToConsole($name);
    debutToConsole($username);

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

    debutToConsole($name);

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
} else {
    $selectOrdersSQL = "
        select b.title, b.description, b.price, c.category from amazon.orders o
        inner join amazon.books b on o.book_id = b.book_id
        inner join amazon.users u on o.user_id = u.user_id
        inner join amazon.categories c on b.category_id = c.category_id
        where u.username = '$username'
    ";
    $result = queryMySql($selectOrdersSQL);
    if ($result->num_rows == 0) {
        $orderDivsHtml = "<div>Orders was not found</div>";
    } else {
        $orderDivs = array();
        while ($row = $result->fetch_assoc()) {
            $title = $row["title"];
            $description = $row["description"];
            $price = $row["price"];
            $category = $row["category"];

            $orderDivHtml = "
                <div>
                    Title: $title; Price: $price; Category: $category; Description: $description
                    <br>
                    <button>Edit Order</button>
                </div>";

            $orderDivs[] = $orderDivHtml;
        }

        $orderDivsHtml = implode(" ", $orderDivs);
    }

    $dynamicPanel = "
        <div>Orders:</div>
        $orderDivsHtml
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
        $dynamicPanel
    </div>
</div>

_END;

?>