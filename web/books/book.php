<?php
session_start();
$host = $_SERVER["DOCUMENT_ROOT"];
require_once $host . "/blocks/book-cookie-handler.php";
echo "<html>";
echo "<head>";
require_once $host . "/blocks/head.php";
echo "</head>";
echo "<body>";
echo "<header>";
require_once $host . "/blocks/header.php";
echo "</header>";
echo "<main>";
echo <<<_END
    <style>
        body > main {
            width: 100%;
            height: 80%;
        }
    </style>
_END;
require_once $host . "/blocks/books-panel.php";
echo "</main>";
echo "<footer>";
require_once $host . "/blocks/footer.php";
echo "</footer>";
echo "</body>";
echo "</html>";
?>

