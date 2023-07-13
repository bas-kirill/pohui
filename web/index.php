<html>
<head>
    <?php
        require_once "../blocks/head.php";
    ?>
</head>
<body>
<header>
    <?php
        require_once "../blocks/header.php";
    ?>
</header>
<main>
    <?php
        echo <<<_END
        <style>
            body > main {
                width: 100%;
                height: 80%;
            }
        </style>
_END;

        require_once "../blocks/recent-posts-table.php";
        require_once "../blocks/promo-category-panel.php";
    ?>
</main>
<footer>
    <?php require_once "../blocks/footer.php"; ?>
</footer>
</body>
</html>
