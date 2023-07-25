<?php

if (isset($_POST["users"])) {
    header("Location: users/users.php");
} elseif (isset($_POST["add-users"])) {
    header("Location: users/add.php");
} elseif (isset($_POST["edit-users"])) {
    header("Location: users/edit.php");
} elseif (isset($_POST["delete-users"])) {
    header("Location: users/delete.php");
} elseif (isset($_POST["add-book"])) {
    header("Location: books/add.php");
} elseif (isset($_POST["edit-book"])) {
    header("Location: books/edit.php");
} elseif (isset($_POST["delete-book"])) {
    header("Location: books/delete.php");
} else {
    header("Location: default.php");
}

?>