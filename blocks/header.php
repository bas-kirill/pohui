<?php

echo <<<_END
    <style>
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
    </style>

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
_END;

if (isset($_SESSION["user"])) {
    $loggedIn = true;
    $user = $_SESSION["user"];
} else {
    $loggedIn = false;
}

if ($loggedIn) {
    echo <<<_END
        <style>
            #account-panel {
                grid-column: 6;
                background-color: cornsilk;
            }
        </style>
        
        <div id="account-panel">
            <button type="button" class="btn" onClick="redirectToAccountPage()">Account</button>
        </div>
        
        <script type="text/javascript">
            function redirectToAccountPage() {
                cartURL = "http://localhost:8888/account.php";
                window.location.href = cartURL;
            }
            console.log("Logged in as $user");
        </script>
_END;
} else {
    echo <<<_END
        <style>
            #login-panel {
                grid-column: 6;
                background-color: cornsilk;
            }
        </style>
        
        <div id="login-panel">
            <button type="button" class="btn" onClick="redirectToLoginPage()">Login</button>
        </div>
        
        <script type="text/javascript">
            function redirectToLoginPage() {
                indexURL = "http://localhost:8888/login.php";
                window.location.href = indexURL;
            }
            console.log("Please sign and/or log in to join")
        </script>
_END;
}
?>
