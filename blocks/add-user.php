<?php

session_start();

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
    debugToConsole("Not logged in");
    http_response_code(401);
    return;
}

if ($roleType != "admin") {
    debugToConsole("Only admin has access");
    http_response_code(403);
    return;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["name"]) &&
        isset($_POST["username"]) &&
        isset($_POST["password"]) &&
        isset($_POST["role"]) &&
        isset($_POST["address"])) {

        $name = $_POST["name"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $role = $_POST["role"];
        $address = $_POST["address"];

        debugToConsole(sprintf("name=%s;username=%s;password=%s;role=%s;address=%s", $name, $username, $password, $role, $address));

        $insertNewUserSQL = "
            insert ignore into amazon.users (name, username, password, delivery_address, role_id)
            select '$name', '$username', '$password', '$address', role_id 
            from amazon.roles 
            where role_name = '$role'
            on conflict nothing";
        $result = $connection->query($insertNewUserSQL);
        if ($result === true) {
            if ($connection->affected_rows > 0) {
                http_response_code(200);
            } else {
                http_response_code(409);
            }
        } else {
            http_response_code(500);
        }
    }
}

