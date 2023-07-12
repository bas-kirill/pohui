<?php

require_once "../util/functions.php";

$db_host = "localhost";
$db_name = "amazon";
$db_user = "root";
$db_password = "root";

$connection = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($connection->connect_error) {
    debug_to_console("can not connect to db");
    die($connection->connect_error);
}

debug_to_console("connected to db successfully");

function createTable($name, $query)
{
    queryMySql("create table if not exists $name($query)");
}

function queryMySql($query)
{
    global $connection;
    $result = $connection->query($query);
    if (!$result) {
        debug_to_console("can not execute query " . $query);
        die($connection->error);
    }
    return $result;
}

?>