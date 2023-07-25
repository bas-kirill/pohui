<?php

$host = $_SERVER["DOCUMENT_ROOT"];
require_once $host . "/util/functions.php";

$db_host = "localhost";
$db_name = "amazon";
$db_user = "root";
$db_password = "root";

$connection = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($connection->connect_error) {
    debugToConsole("can not connect to db");
    die($connection->connect_error);
}

debugToConsole("connected to db successfully");

function createTable($name, $query)
{
    queryMySql("create table if not exists $name($query)");
}

function queryMySql($query)
{
    global $connection;
    $result = $connection->query($query);
    if (!$result) {
        debugToConsole("can not execute query " . $query);
        die($connection->error);
    }
    debugToConsole($result);
    return $result;
}

?>