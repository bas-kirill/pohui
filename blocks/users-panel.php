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
               <input type='submit' name='delete-order' value='Delete Order'>
               <br>
            </form>
        </div>";
}

$allUsersWithRoleSQL = "
    select user_id, name, username, password, delivery_address, role_name
    from users u
    inner join roles r on u.role_id = r.role_id";
$result = $connection->query($allUsersWithRoleSQL);

$userDivs = array();
while ($row = $result->fetch_assoc()) {
    $userId = $row["user_id"];
    $name = $row["name"];
    $username = $row["username"];
    $password = $row["password"];
    $roleName = $row["role_name"];
    $deliveryAddress = $row["delivery_address"];
    $userDiv = "
        <div>
            Used Id: $userId; Name: $name; Username: $username; Password: $password; Role Type: $roleName; Delivery Address: $deliveryAddress
        </div>
    ";
    $userDivs[] = $userDiv;
}

$userDivsHtml = implode(" ", $userDivs);

$dynamicPanel = "
    <div>
        $userDivsHtml
    </div>";

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
            $dynamicPanel
        </div>
    </div>
_END;
