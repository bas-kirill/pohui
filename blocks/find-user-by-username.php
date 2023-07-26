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
    error_log("Not logged in");
    http_response_code(401);
    return;
}

if ($roleType != "admin") {
    error_log('Only admin has access');
    http_response_code(403);
    return;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["username"])) {
        $usernamePOST = $_POST["username"];

        $findUserByUsernameSQL = "
            select name, username, password, delivery_address, role_name
            from amazon.users u
            inner join amazon.roles r on u.role_id = r.role_id
            where username = '$usernamePOST'";

        $result = $connection->query($findUserByUsernameSQL);
        if ($result) {
            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                $name = $row["name"];
                $username = $row["username"];
                $password = $row["password"];
                $address = $row["delivery_address"];
                $roleName = $row["role_name"];

                http_response_code(200);
                $data = array(
                    "name" => $name,
                    "username" => $username,
                    "password" => $password,
                    "address" => $address,
                    "role-name" => $roleName
                );
                header("Content-Type: application/json");
                echo json_encode($data);
            } else if ($result->num_rows === 0) {
                http_response_code(404);
            } else {
                http_response_code(500);
            }
        } else {
            http_response_code(500);
        }
    }
}

