<?php

require_once "../util/functions.php";

if (isset($_POST["book-id-to-cart-cookie"])) {
    $bookIdToCartCookie = $_POST["book-id-to-cart-cookie"];
    addValueToCookie("cart", $bookIdToCartCookie);
}

?>