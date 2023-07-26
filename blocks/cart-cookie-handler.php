<?php

$host = $_SERVER["DOCUMENT_ROOT"];
require_once $host . "/util/functions.php";

if (isset($_POST["checkout-book-ids"])) {
    destroyCookie("cart");
}

?>