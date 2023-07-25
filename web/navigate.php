<?php

if (isset($_POST["users"])) {
    header("Location: users.php");
} elseif (isset($_POST["add-users"])) {
    header("Location: users/add.php");
} elseif (isset($_POST["edit-users"])) {
    header("Location: users/edit.php");
} elseif (isset($_POST["delete-users"])) {
    header("Location: users/delete.php");
} elseif (isset($_POST["add-book"])) {
    header("Location: add-book.php");
} elseif (isset($_POST["edit-book"])) {
    header("Location: edit-book.php");
} elseif (isset($_POST["delete-book"])) {
    header("Location: delete-book.php");
} else {
    header("Location: default.php");
}

?>