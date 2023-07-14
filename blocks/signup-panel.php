<?php

require_once "../util/functions.php";
require_once "../db/db.php";
require_once "../log/log.php";

function addUserToCookie() {

}

if (isset($_POST["signup-name"])) {
    $name = sanitizeString($_POST["signup-name"]);
    $address = sanitizeString($_POST["signup-address"]);
    $username = sanitizeString($_POST["signup-username"]);
    $password = sanitizeString($_POST["signup-password"]);

    if ($name == "" || $address == "" || $username == "" || $password == "") {
        echo "<div>Not all fields were entered</div>";
        return;
    }

    $selectAllUsersSQL = "select username from amazon.users where username = '$username'";
    $usersResultSet = queryMySql($selectAllUsersSQL);

    if ($usersResultSet->num_rows > 0) {
        echo "<div>User with username '$username' already exists</div>";
        return;
    }

    $insertNewUserSQL = "
        insert into amazon.users (name, username, password, delivery_address, order_id)
        values ('$name', '$username', '$password', '$address', null)
    ";

    log_debug($insertNewUserSQL);

    $result = queryMySql($insertNewUserSQL);

    if ($result) {
        log_info("Registered new user with nickname=$username");
        echo "<div>You are registered successfully</div>";
    } else {
        log_error("User $username was not signed up");
        echo "<div>Required fields are mission</div>";
        return;
    }
} else {
echo <<<_END
<style>
    #signup-panel {
        background-color: darkgray;
    }
</style>

<div id="signup-panel">
    <div>Sign Up</div>
    <div id="user-data-input-panel">
        <form id="signup-form" action="" method="post"> <!-- <?php echo \$_SERVER['PHP_SELF'];?> -->
            Name: <input type="text" name="signup-name" required>
            <br>
            Address: <input type="text" name="signup-address" required>
            <br>
            Username: <input type="text" name="signup-username" required>
            <br>
            Password: <input type="text" name="signup-password" required>
        </form>
    </div>
    <input type="submit" form="signup-form" value="Sign Up">
</div>
_END;
}



?>