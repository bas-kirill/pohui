<?php

$host = $_SERVER["DOCUMENT_ROOT"];
require_once $host . "/util/functions.php";
require_once $host . "/db/db.php";

global $connection;

if (isset($_POST["login-username"])) {
    $username = sanitizeString($_POST["login-username"]);
    $password = sanitizeString($_POST["login-password"]);

    if ($username == "" || $password == "") {
        echo "<div>Not all required fields entered</div>";
        return;
    }

    $usersSQL = "
        select user_id, name, username, password, role_name from amazon.users u
        inner join amazon.roles r on u.role_id = r.role_id
        where username = '$username' and password = '$password'";

    $result = $connection->query($usersSQL);
    if ($result->num_rows == 0) {
        echo "<div>Username / Password invalid</div>";
        return;
    }

    $row = $result->fetch_assoc();
    $userId = $row["user_id"];
    $name = $row["name"];
    $username = $row["username"];
    $password = $row["password"];
    $roleName = $row["role_name"];

    $_SESSION["user_id"] = $userId;
    $_SESSION["name"] = $name;
    $_SESSION["username"] = $username;
    $_SESSION["password"] = $password;
    $_SESSION["role_type"] = $roleName;
    echo "<span>You are logged in</span>";
    return;
}

echo <<<_END
<style>
    #login-panel {
        background-color: darkgray;
    }
</style>

<div id="login-panel">
    <div>Login</div>
    <div id="username-password-panel">
        <form id="login-form" action="" method="post">
            <span class="fieldname">Username:</span><input type="text" name="login-username">
            <br>
            <span class="fieldname">Password:</span><input type="password" name="login-password">
        </form>
    </div>
    <div id="login-signup-panel">
        <button onclick="redirect('http://localhost:8888/web/signup.php')">Sign Up</button>
        <input type="submit" form="login-form" value="Login">
    </div>
</div>

<script>
    function redirect(url) {
        window.location.href = url;
    }
</script>
_END;

?>