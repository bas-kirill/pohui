<?php
$db_servername = "localhost";
$db_username = "root"; // default MAMP MySQL username
$db_password = "root"; // default MAMP MySQL password
$conn = new mysqli($db_servername, $db_username, $db_password);

if ($conn->connect_error) {
    die("Connection to MySQL Failed" . $conn->connect_error);
}
echo "<script type=\"text/javascript\">console.log(\"Connected Successfully\");</script>";

echo <<<_END
    <style>
        #recent-books-panel {
            width: 100%;
            height: 50%;
            background-color: aqua;
        }

        #recent-posts-table {
            background-color: cornsilk;
        }
    </style>
    
    <div id="recent-books-panel">
        <span>Recent Posts:</span>
        <div id="recent-posts-table" class="container-fluid text-center">
            <div id="recent-posts-row-one" class="row">
_END;

$select_recent_books_sql = "select title, price, description from books fetch first 6 rows";
$result = $conn->query($select_recent_books_sql);
if ($result->num_rows > 0) {
    $cnt = 0;
    while ($row = $result->fetch_assoc()) {
        if ($cnt % 3 == 0) {
            echo "</div>";
            echo "<div id=\"recent-posts-row-two\" class=\"row\">";
        }
        $book_title = $row["title"];
        $book_description = $row["description"];
        echo "<div class=\"col-md-4\">Title: $book_title; Description: $book_description</div>";
        $cnt += 1;
    }
}

echo "</div></div></div>";
?>
