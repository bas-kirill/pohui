<?php
session_start();
require_once "../blocks/cookie-handler.php";
echo "<html>";
echo "<head>";
require_once "../blocks/head.php";
echo "</head>";
echo "<body>";
echo "<header>";
require_once "../blocks/header.php";
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
    require_once "../blocks/book-profile.php";
echo "</main>";
echo "<footer>";
require_once "../blocks/footer.php";
echo "</footer>";
echo "</body>";
echo "</html>";
?>

