<?php

require_once "../util/functions.php";
require_once "../db/db.php";

$searchQuery = $_POST["search-query"];
debutToConsole("search query $searchQuery");

$sql = "
    select book_id, title, description, price, category from amazon.books b
    inner join amazon.categories c on b.category_id = c.category_id
    where description like '%$searchQuery%'
    order by creation_timestamp desc;
";

$result = queryMySql($sql);

if (!$result) {
    debutToConsole("can not execute query for searching books");
    return;
}

if ($result->num_rows == 0) {
    echo <<<_END
    <div>Not found books by query '$searchQuery'
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
    debutToConsole("$title, $description, $price, $category");
    $serp_div = "
        <div>
            <a href='/web/book.php?id=$book_id'>Id: $book_id; Title: $title; Description: $description; Price: $price; Category: $category</a>
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