<?php

require_once "../db/db.php";

$result = queryMySql("
    select book_id, title, description, price, category from amazon.books b
    inner join amazon.categories c on b.category_id = c.category_id
    where category = 'Fiction'
    order by creation_timestamp desc
    limit 6
");

$books_div = array();
$cnt = 0;
while ($row = $result->fetch_assoc()) {
    $book_id = $row["book_id"];
    $title = $row["title"];
    $description = $row["description"];
    $price = $row["price"];
    $category = $row["category"];
    $book_div = "
        <div class=\"col-md-4\">
            <a href='/web/book.php?id=$book_id'>Id: $book_id; Title: $title; Description: $description; Price: $price; Category: $category</a>
        </div>";
    $books_divs[] = $book_div;
    $cnt++;
}

debutToConsole("found $cnt rows from MySQL");

$books_part_one = implode(" ", array_slice($books_divs, 0, 3));
$books_part_two = implode(" ", array_slice($books_divs, 3, 3));

echo <<<_END
    <style>
        #promo-category-panel {
            height: 50%;
            background-color: cornflowerblue;
        }

        #promo-category-table {
            background-color: darkgray;
        }
    </style>
    
    <div id="promo-category-panel">
        <span id="promo-category-title-text">10% discount on the promo code:</span>
        <div id="promo-category-table" class="text-center">
            <div id="promo-category-row-one" class="row">
                $books_part_one
            </div>
            <div id="promo-category-row-two" class="row">
                $books_part_two
            </div>
        </div>
    </div>
_END;
?>

