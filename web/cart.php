<?php
$host = $_SERVER["DOCUMENT_ROOT"];
require_once $host . "/blocks/cart-cookie-handler.php";
echo "<html>";
echo "<head>";
require_once $host . "/blocks/head.php";
echo "</head>";
echo "<body>";
echo "<header>";
require_once $host . "/blocks/header.php";
echo "</header>";
echo "<main>";
require_once $host . "/blocks/cart-panel.php";
echo "</main>";
echo "<footer>";
require_once $host . "/blocks/footer.php";
echo "</footer>";
echo "</body>";
echo "</html>";
?>