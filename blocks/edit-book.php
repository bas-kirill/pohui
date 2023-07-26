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
    $isbnPOST = $requestBody["isbn"];
    $titlePOST = $requestBody["title"];
    $descriptionPOST = $requestBody["description"];
    $pricePOST = $requestBody["price"];
    $creationTsPOST = $requestBody["creation-ts"];
    $categoryPOST = $requestBody["category"];

    error_log(sprintf("isbn=%s;title=%s;description=%s;price=%s;creationTs=%s;category=%s", $isbnPOST, $titlePOST, $descriptionPOST, $pricePOST, $creationTsPOST, $categoryPOST));

    $updateBookByISBNSQL = "
        update amazon.books
        set title = '$titlePOST', 
            description = '$descriptionPOST', 
            price = '$pricePOST', 
            creation_timestamp = '$creationTsPOST',
            category_id = (select category_id from amazon.categories where category = '$categoryPOST')
        where isbn_10 = '$isbnPOST'";

    $result = $connection->query($updateBookByISBNSQL);
    if ($result) {
        if ($connection->affected_rows == 1) {
            http_response_code(200);
            $data = array(
                "isbn" => $isbnPOST,
                "title" => $titlePOST,
                "description" => $descriptionPOST,
                "price" => $pricePOST,
                "creation-ts" => $creationTsPOST,
                "category" => $categoryPOST
            );
            error_log(serialize($data));
            header("Content-Type: application/json");
            echo json_encode($data);
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
