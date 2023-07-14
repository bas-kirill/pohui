<?php

require_once "../log/log.php";
require_once "../util/functions.php";
require_once "../db/db.php";

if ($_GET["edit"]) {
    // todo: добавить механизм редактирования данных об аккаунте
    return;
}

if ($_GET["delete"]) {
    // todo: сделать механизм удаления аккаунта
    return;
}

if (isset($_SESSION["username"])) {
    $loggedIn = true;
    $username = $_SESSION["username"];
} else {
    $loggedIn = false;
}

if (!$loggedIn) {
    die();
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
            <div>Add User</div>
            <div>Edit User</div>
            <div>Delete User</div>
            <div>Add Book</div>
            <div>Edit Book</div>
            <div>Delete Book</div>
        </div>";
}

$order_divs_html = "";

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
        <div>Orders:</div>
        $order_divs_html
    </div>
</div>

_END;

?>