<?php

echo <<<_END
    <style>
        #cart-panel {
            display: grid;
            background-color: bisque;
        }
    </style>

    <div id="cart-panel">
_END;
require_once "books-to-buy.php";
require_once "checkout-panel.php";
echo "</div>"
?>