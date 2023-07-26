<?php

if (isset($_POST["users"])) {
    header("Location: users/users.php");
} elseif (isset($_POST["add-users"])) {
    header("Location: users/add.php");
} elseif (isset($_POST["edit-users"])) {
    header("Location: users/edit.php");
} elseif (isset($_POST["delete-users"])) {
    header("Location: users/delete.php");
} elseif (isset($_POST["books"])) {
    header("Location: books/books.php");
} elseif (isset($_POST["add-book"])) {
    header("Location: books/add.php");
} elseif (isset($_POST["edit-book"])) {
    header("Location: books/edit.php");
} elseif (isset($_POST["delete-book"])) {
    header("Location: books/delete.php");
} elseif (isset($_POST["orders"])) {
    header("Location: orders/orders.php");
} elseif (isset($_POST["add-order"])) {
    header("Location: orders/add.php");
} elseif (isset($_POST["edit-order"])) {
    header("Location: orders/edit.php");
} elseif (isset($_POST["delete-order"])) {
    header("Location: orders/delete.php");
} else {
    header("Location: default.php");
}

?>