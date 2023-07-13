<?php

require_once "../util/functions.php";
require_once "../db/db.php";

$searchQuery = $_POST["search-query"];
debug_to_console("search query $searchQuery");

$sql = "
    select title, description, price, category from amazon.books b
    inner join amazon.categories c on b.category_id = c.category_id
    where description like '%$searchQuery%'
    order by creation_timestamp desc;
";

$result = queryMySql($sql);

if (!$result) {
    debug_to_console("can not execute query for searching books");
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
    $title = $row["title"];
    $description = $row["description"];
    $price = $row["price"];
    $category = $row["category"];
    debug_to_console("$title, $description, $price, $category");
    $serp_div = "<div>Title: $title; Description: $description; Price: $price; Category: $category</div>";
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