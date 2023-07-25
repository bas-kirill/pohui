<?php

require_once "../util/functions.php";
require_once "../db/db.php";

if (isset($_POST["checkout-book-ids"])) {
    if (isset($_SESSION["username"])) {
        $loggedIn = true;
        $username = $_SESSION["username"];
        $userId = $_SESSION["user_id"];
    } else {
        $loggedIn = false;
    }

    if (!$loggedIn) {
        echo "<div>You need to log in to create the order</div>";
        return;
    }

    $cartBookIds = $_POST["checkout-book-ids"];
    $bookIds = explode("~", $cartBookIds);
    debugToConsole($bookIds);
    $creationTs = date('Y-m-d H:i:s');
    foreach ($bookIds as $bookId) {
        // TODO: rewrite on one insert statemenet
        $createNewOrderSQL = "insert into amazon.orders (book_id, user_id, creation_ts) value ($bookId, $userId, '$creationTs')";
        $result = queryMySql($createNewOrderSQL);
        debugToConsole($bookId);
    }
    echo "<div>Successfully check out cart!</div>";
    return;
}

$booksIds = parseFromCookie("cart");
if (count($booksIds) == 0) {
    $books_div_html = "<span>Your Cart is Empty</span>";
} else {
    $totalPrice = 0;
    $book_divs = array();
    foreach ($booksIds as $bookId) {
        // todo: переписать на один запрос
        $get_book_by_id_sql = "
        select book_id, title, price, category from amazon.books b
        inner join amazon.categories c on b.category_id = c.category_id
        where book_id = $bookId
    ";

        $result = queryMySql($get_book_by_id_sql);

        if (!$result) {
            debugToConsole("can not get book id from database: $bookId");
            continue;
        }

        $row = $result->fetch_assoc();
        $bookId = $row["book_id"];
        $title = $row["title"];
        $price = $row["price"];
        $category = $row["category"];

        $book_div = "<div>Id: $bookId Title: $title; Price: $price; Category: $category</div>";
        $book_divs[] = $book_div;

        $totalPrice += $price;
    }
}

$books_div_html = implode(" ", $book_divs);
$goods = count($book_divs);
$cartBookIds = implode("~", $booksIds);

echo <<<_END
    <style>
        #cart-panel {
            display: grid;
            background-color: bisque;
        }
        #books-to-buy {
            grid-column: 1;
            background-color: deepskyblue;
        }
        #checkout-panel {
            grid-column: 2;
            background-color: aquamarine;
        }
    </style>

    <div id="cart-panel">
        <div id="books-to-buy">$books_div_html</div>
        <div id="checkout-panel">
            <div>Items: $goods</div>
            <div>Total price: $totalPrice</div>   
            <form method="post">
                <input type="hidden" name="checkout-book-ids" value="$cartBookIds">
                <input type="submit">
            </form>
        </div>
    </div>
_END;
?>