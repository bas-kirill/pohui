<?php

require_once "../util/functions.php";

if (isset($_POST["checkout-book-ids"])) {
    destroyCookie("cart");
}

?>