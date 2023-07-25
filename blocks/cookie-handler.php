<?php

if (isset($_POST["book-id-to-cart-cookie"])) {
    $bookIdToCartCookie = $_POST["book-id-to-cart-cookie"];
    addBookToCartCookie($bookIdToCartCookie);
}

function addBookToCartCookie($bookId) {
    $cartCookieName = "cart";
    $existingCart = isset($_COOKIE[$cartCookieName]) ? $_COOKIE[$cartCookieName] : "";
    if ($existingCart == "") {
        $newCart = $bookId;
    } else {
        $newCart = $existingCart . "~" . $bookId;
    }

    $expirationTime = time() + 3600;
    $path = "/";
    $domain = "";
    $secure = false;
    $httpOnly = true;
    setcookie($cartCookieName, $newCart, $expirationTime, $path, $domain, $secure, $httpOnly);
}

?>