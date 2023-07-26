<?php

session_start();

$host = $_SERVER["DOCUMENT_ROOT"];
require_once $host . "/log/log.php";
require_once $host . "/util/functions.php";
require_once $host . "/db/db.php";

global $connection;

if (isset($_SESSION["username"])) {
    $loggedIn = true;
    $nameSession = $_SESSION["name"];
    $usernameSession = $_SESSION["username"];
    $userIdSession = $_SESSION["user_id"];
    $roleTypeSession = $_SESSION["role_type"];
} else {
    $loggedIn = false;
}

if (!$loggedIn) {
    error_log("Not logged in");
    http_response_code(401);
    return;
}

if ($roleTypeSession != "admin") {
    error_log('Only admin has access');
    http_response_code(403);
    return;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["isbn"])) {
        $isbnPOST = $_POST["isbn"];

        $deleteUserByUsernameSQL = "
            delete from amazon.books
            where isbn_10 = '$isbnPOST'";

        $result = $connection->query($deleteUserByUsernameSQL);
        if ($result) {
            if ($connection->affected_rows == 1) {
                http_response_code(200);
            } else if ($connection->affected_rows == 0) {
                http_response_code(404);
            } else {
                http_response_code(500);
            }
        } else {
            http_response_code(500);
        }
    } else {
        http_response_code(400);
    }
}

