<?php

session_start();

$host = $_SERVER["DOCUMENT_ROOT"];
require_once $host . "/log/log.php";
require_once $host . "/util/functions.php";
require_once $host . "/db/db.php";

global $connection;

if (isset($_SESSION["username"])) {
    $loggedIn = true;
    $sessionName = $_SESSION["name"];
    $sessionUsername = $_SESSION["username"];
    $sessionUserId = $_SESSION["user_id"];
    $sessionRoleType = $_SESSION["role_type"];
} else {
    $loggedIn = false;
}

if (!$loggedIn) {
    error_log("Not logged in");
    http_response_code(401);
    return;
}

if ($sessionRoleType != "admin") {
    error_log("Only admin has access");
    http_response_code(403);
    return;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["title"]) &&
        isset($_POST["description"]) &&
        isset($_POST["price"]) &&
        isset($_POST["isbn"]) &&
        isset($_POST["category"])) {

        $titlePOST = $_POST["title"];
        $descriptionPOST = $_POST["description"];
        $pricePOST = $_POST["price"];
        $isbnPOST = $_POST["isbn"];
        $categoryPOST = $_POST["category"];

        $insertNewBookSQL = "
            insert ignore into amazon.books (title, description, price, isbn_10, category_id)
            select '$titlePOST', '$descriptionPOST', '$pricePOST', '$isbnPOST', category_id 
            from amazon.categories 
            where category = '$categoryPOST'";
        $result = $connection->query($insertNewBookSQL);
        if ($result === true) {
            if ($connection->affected_rows > 0) {
                http_response_code(200);
                $data = array(
                    "title" => $titlePOST,
                    "description" => $descriptionPOST,
                    "price" => $pricePOST,
                    "isbn" => $isbnPOST,
                    "category" => $categoryPOST
                );
                header("Content-Type: application/json");
                echo json_encode($data);
            } else {
                http_response_code(409);
            }
        } else {
            http_response_code(500);
        }

        header("Content-Type: application/json");
        $data = array("rows" => $connection->affected_rows);
        echo json_encode($data);
    } else {
        http_response_code(400);
    }
}

