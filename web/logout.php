<?php
$host = $_SERVER["DOCUMENT_ROOT"];
require_once $host . "/util/functions.php";

destroySession();
header("Location: login.php");

?>