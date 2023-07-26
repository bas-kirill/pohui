<?php

$host = $_SERVER["DOCUMENT_ROOT"];
require_once $host . "/util/functions.php";

if (isset($_POST["book-id-to-cart-cookie"])) {
    $bookIdToCartCookie = $_POST["book-id-to-cart-cookie"];
    addValueToCookie("cart", $bookIdToCartCookie);
}

?>