<?php
require_once "../util/functions.php";
require_once "../db/db.php";

$bookId = $_GET["id"];

$sql = "
    select book_id, title, description, price, category from amazon.books b
    inner join amazon.categories c on b.category_id = c.category_id
    where b.book_id = $bookId";

$result = queryMySql($sql);
if (!$result) {
    debugToConsole("can not execute $sql");
    return;
}

if ($result->num_rows > 1) {
    $book_div_html = "<span>Got several books by id=$bookId</span>";
    return;
} else if ($result->num_rows == 0) {
    $book_div_html = "<span>Got zero books by id=$bookId</span>";
    return;
}

$row = $result->fetch_assoc();

$bookId = $row["book_id"];
$title = $row["title"];
$description = $row["description"];
$price = $row["price"];
$category = $row["category"];

$book_div_html = "
<div>
    <a href='/web/book.php?id=$bookId'>Id: $bookId; Title: $title; Description: $description; Price: $price; Category: $category</a>
    <form method='post'>
        <input type='hidden' name='book-id-to-cart-cookie' value='$bookId'>
        <button type='submit' class='button'>Add To Cart</button>
    </form>
</div>


<script type='text/javascript'>
    function addToCart() {
        console.log('added to cart');
    }
</script>";

echo <<<_END
<style>
    #book-profile {
        background-color: bisque;
    }
</style>

<div id="book-profile">
   $book_div_html
</div>
_END;


?>