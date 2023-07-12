<html>
<head>
    <meta charset="utf-8">
    <title>Book Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="styles.css">
    <style>
        body > header {
            width: 100%;
            height: 10%;
            background-color: aqua;
            display: grid;
            border: 1px solid black;
        }

        #logo-panel {
            grid-column: 1;
            background-color: azure;
        }

        #catalogue-panel {
            grid-column: 2;
            background-color: blueviolet;
        }

        #search-panel {
            grid-column: 3 / span 2;
            background-color: deepskyblue;
        }

        #cart-panel {
            grid-column: 5;
            background-color: ghostwhite;
        }

        #account-panel {
            grid-column: 6;
            background-color: cornsilk;
        }

        #login-panel {
            grid-column: 6;
            background-color: cornsilk;
        }

        body > main {
            width: 100%;
            height: 80%;
        }

        #recent-books-panel {
            width: 100%;
            height: 50%;
            background-color: aqua;
        }

        #recent-posts-table {
            background-color: cornsilk;
        }

        #promo-category-panel {
            height: 50%;
            background-color: cornflowerblue;
        }

        #promo-category-table {
            background-color: darkgray;
        }

        body > footer {
            width: 100%;
            height: 10%;
            background-color: gold;
        }
    </style>

    <?php
        require_once "setup.php";
    ?>

</head>
<body>
<header>
    <div id="logo-panel" class="text-center">Logo</div>
    <div id="catalogue-panel"><button type="button" class="btn">Catalogue</button></div>

    <div id="search-panel" class="input-group">
        <form action="/search.php" method="post">
            <input type="text" placeholder="Look for amazing world of books..." aria-label="Search books...">
            <button id="search-button" class="btn btn-outline-secondary" type="submit">Search</button>
        </form>
    </div>
    <div id="cart-panel">
        <button type="button" class="btn" onClick="redirectToCartPage()">Cart</button>
    </div>
    <?php
        if (isset($_SESSION["user"])) {
            $loggedIn = true;
            $user = $_SESSION["user"];
        } else {
            $loggedIn = false;
        }

        if ($loggedIn) {
            echo <<<_END
              <div id="account-panel">
                <button type="button" class="btn" onClick="redirectToAccountPage()">Account</button>
              </div>
              <script>
                function redirectToAccountPage() {
                    cartURL = "http://localhost:8888/account.php";
                    window.location.href = cartURL;
                }
                console.log("Logged in as $user");
              </script>
_END;
        } else {
            echo <<<_END
              <div id="login-panel">
                <button type="button" class="btn" onClick="redirectToLoginPage()">Login</button>
              </div>
              <script>
                function redirectToLoginPage() {
                    indexURL = "http://localhost:8888/login.php";
                    window.location.href = indexURL;
                }
                console.log("Please sign and/or log in to join")
              </script>
_END;
        }
    ?>
</header>

<main>
    <?php
        $db_servername = "localhost";
        $db_username = "root"; // default MAMP MySQL username
        $db_password = "root"; // default MAMP MySQL password
        $conn = new mysqli($db_servername, $db_username, $db_password);

        if ($conn->connect_error) {
            die("Connection to MySQL Failed" . $conn->connect_error);
        }
        echo "<script>console.log(\"Connected Successfully\");</script>";

        echo <<<_END
    <div id="recent-books-panel">
        <span>Recent Posts:</span>
        <div id="recent-posts-table" class="container-fluid text-center">
            <div id="recent-posts-row-one" class="row">
_END;

        $select_recent_books_sql = "select title, price, description from books fetch first 6 rows";
        $result = $conn->query($select_recent_books_sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $book_title = $row["title"];
                $book_description = $row["description"];
                echo "<div class=\"col-md-4\">Title: $book_title; Description: $book_description</div>";
            }
        }

        echo "            </div>";

    ?>
            <div id="recent-posts-row-two" class="row">
                <div class="col-md-4">[1, 0]</div>
                <div class="col-md-4">[1, 1]</div>
                <div class="col-md-4">[1, 2]</div>
            </div>
        </div>
    </div>

    <div id="promo-category-panel">
        <span id="promo-category-title-text">10% discount on the promo code:</span>
        <div id="promo-category-table" class="text-center">
            <div id="promo-category-row-one" class="row">
                <div class="col-md-4">0, 0</div>
                <div class="col-md-4">0, 1</div>
                <div class="col-md-4">0, 2</div>
            </div>
            <div id="promo-category-row-two" class="row">
                <div class="col-md-4">1, 0</div>
                <div class="col-md-4">1, 1</div>
                <div class="col-md-4">1, 2</div>
            </div>
        </div>
    </div>
</main>

<footer>
    Ku ku Epta!
</footer>
</body>

<script type="text/javascript">
    function redirectToCartPage() {
        cart_page = "http://localhost:8888/cart.php";
        window.location.href = cart_page;
    }
</script>
</html>