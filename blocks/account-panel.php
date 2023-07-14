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

if (isset($_POST["add-user"])) {
    return;
}

if (isset($_POST["edit-another-user"])) {
    return;
}

if (isset($_POST["delete-another-user"])) {
    return;
}

if (isset($_POST["add-book"])) {
    return;
}

if (isset($_POST["edit-book"])) {
    return;
}

if (isset($_POST["delete-book"])) {
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
} else {
    $dynamicPanel = "
        <div>Orders:</div>
        <div>
            Todo: Get Orders
        </div>
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