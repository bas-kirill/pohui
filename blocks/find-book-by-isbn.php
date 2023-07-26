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
    error_log('Only admin has access');
    http_response_code(403);
    return;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["isbn"])) {
        $isbnPOST = $_POST["isbn"];

        $findBookByISBNSQL = "
            select title, description, price, creation_timestamp, isbn_10, category from amazon.books b
            inner join amazon.categories c on b.category_id = c.category_id
            where isbn_10 = '$isbnPOST'";

        $result = $connection->query($findBookByISBNSQL);
        if ($result) {
            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                $title = $row["title"];
                $description = $row["description"];
                $price = $row["price"];
                $creationTs = $row["creation_timestamp"];
                $isbn = $row["isbn_10"];
                $category = $row["category"];

                http_response_code(200);
                $data = array(
                    "title" => $title,
                    "description" => $description,
                    "price" => $price,
                    "creation-ts" => $creationTs,
                    "isbn" => $isbn,
                    "category" => $category
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
    } else {
        http_response_code(400);
    }
}

