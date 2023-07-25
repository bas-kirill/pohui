<?php

require_once "../util/functions.php";
require_once "../db/db.php";



if (isset($_GET["description"]) && isset($_GET["category"])) {
    $descriptionQueryParam = $_GET["description"];
    $categoryQueryParam = $_GET["category"];
    debugToConsole(sprintf("searchQueryParam=%s; categoryQueryParam=%s", $descriptionQueryParam, $categoryQueryParam));
    $sql = "
        select book_id, title, description, price, category from amazon.books b
        inner join amazon.categories c on b.category_id = c.category_id
        where (category = '' or category = '$categoryQueryParam') and description like '%$descriptionQueryParam%'
        order by creation_timestamp desc;
    ";
} else if (isset($_GET["description"])) {
    $descriptionQueryParam = $_GET["description"];
    debugToConsole(sprintf("searchQueryParam=%s", $descriptionQueryParam));
    $sql = "
        select book_id, title, description, price, category from amazon.books b
        inner join amazon.categories c on b.category_id = c.category_id
        where description like '%$descriptionQueryParam%'
        order by creation_timestamp desc;
    ";
} else if (isset($_GET["category"])) {
    $categoryQueryParam = $_GET["category"];
    debugToConsole(sprintf("categoryQueryParam=%s", $categoryQueryParam));
    $sql = "
        select book_id, title, description, price, category from amazon.books b
        inner join amazon.categories c on b.category_id = c.category_id
        where category = '$categoryQueryParam'
        order by creation_timestamp desc;
    ";
} else {
    $sql = "
        select book_id, title, description, price, category from amazon.books b
        inner join amazon.categories c on b.category_id = c.category_id
        order by creation_timestamp desc;
    ";
}

$result = queryMySql($sql);

if (!$result) {
    debugToConsole("can not execute query for searching books");
    return;
}

if ($result->num_rows == 0) {
    echo <<<_END
    <div>Not found</div>
_END;
    return;
}

$serp_divs = array();
while ($row = $result->fetch_assoc()) {
    $book_id = $row["book_id"];
    $title = $row["title"];
    $description = $row["description"];
    $price = $row["price"];
    $category = $row["category"];
    debugToConsole("$title, $description, $price, $category");
    $bookUrl = sprintf("/web/book.php?id=%s", $book_id);
    $serp_div = "
        <div>
            <a href=$bookUrl>Id: $book_id; Title: $title; Description: $description; Price: $price; Category: $category</a>
        </div>";
    $serp_divs[] = $serp_div;
}

$serp_divs_html = implode(" ", $serp_divs);

echo <<<_END
<style>
    #serp-panel {
        background-color: cornflowerblue;
    }
</style>

<div id="serp-panel">
   $serp_divs_html
</div>
_END;



?>