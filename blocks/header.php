<?php

echo <<<_END
    <style>
        body > header {
            /*position: fixed;    !* fixed header *!*/
            /*top: 0;*/
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
    </style>

        <div id="logo-panel" class="text-center"><a href="/web/index.php">Logo</a></div>
        <div id="catalogue-panel"><button type="button" class="btn">Catalogue</button></div>
    
        <div id="search-panel" class="input-group">
            <form action="/web/search.php" method="post">
                <input type="search" placeholder="Search" name="search-query">
            </form>
        </div>
        <div id="cart-panel">
            <button type="button" class="btn" onClick="redirectToCartPage()">Cart</button>
        </div>
        <script>
            function redirectToCartPage() {
                cartURL = "http://localhost:8888/web/cart.php";
                window.location.href = cartURL;
            }
        </script>
_END;

if (isset($_SESSION["username"])) {
    $loggedIn = true;
    $user = $_SESSION["username"];
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
                cartURL = "http://localhost:8888/web/account.php";
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
                indexURL = "http://localhost:8888/web/login.php";
                window.location.href = indexURL;
            }
            console.log("Please sign and/or log in to join")
        </script>
_END;
}
?>
