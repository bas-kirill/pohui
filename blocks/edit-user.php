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

// пыха не вычитывает данные через $_POST, если отправляем при помощи ajax
$data = file_get_contents('php://input');
$requestBody = json_decode($data, true);
error_log(serialize($requestBody));

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $usernamePOST = $requestBody["username"];
    $namePOST = $requestBody["name"];
    $passwordPOST = $requestBody["password"];
    $rolePOST = $requestBody["role"];
    $addressPOST = $requestBody["address"];

    $updateUserByUsernameSQL = "
        update amazon.users
        set name = '$namePOST', 
            password = '$passwordPOST', 
            delivery_address = '$addressPOST', 
            role_id = (select role_id from amazon.roles where role_name = '$rolePOST')
        where username = '$usernamePOST'";

    $result = $connection->query($updateUserByUsernameSQL);
    if ($result) {
        http_response_code(200);
        $data = array(
            "name" => $namePOST,
            "username" => $usernamePOST,
            "password" => $passwordPOST,
            "address" => $addressPOST,
            "role-name" => $rolePOST
        );
        error_log(serialize($data));
        header("Content-Type: application/json");
        echo json_encode($data);
    } else {
        http_response_code(500);
    }
}

